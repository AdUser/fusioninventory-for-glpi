<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2012 by the FusionInventory Development Team.

   http://www.fusioninventory.org/   http://forge.fusioninventory.org/
   ------------------------------------------------------------------------

   LICENSE

   This file is part of FusionInventory project.

   FusionInventory is free software: you can redistribute it and/or modify
   it under the terms of the GNU Affero General Public License as published by
   the Free Software Foundation, either version 3 of the License, or
   (at your option) any later version.

   FusionInventory is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
   GNU Affero General Public License for more details.

   You should have received a copy of the GNU Affero General Public License
   along with Behaviors. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   FusionInventory
   @author    David Durieux
   @co-author
   @copyright Copyright (c) 2010-2012 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010

   ------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusioninventoryInventoryNetworkEquipmentLib extends CommonDBTM {


   /**
    * Function to update NetworkEquipment
    * 
    * @param array $a_inventory data fron agent inventory
    * @param id $items_id id of the networkequipment
    * 
    * @return nothing
    */
   function updateNetworkEquipment($a_inventory, $items_id) {
      global $DB;
      
      $networkEquipment = new NetworkEquipment();
      $pfNetworkEquipment = new PluginFusioninventoryNetworkEquipment();
      
      $networkEquipment->getFromDB($items_id);
      
      
      // * NetworkEquipment
      $db_networkequipment = array();
      $a_field = array('name', 'networkequipmentfirmwares_id', 
                       'networkequipmentmodels_id', 'locations_id', 'ram', 'serial',
                       'manufacturers_id');
      foreach ($a_field as $field) {
         $db_networkequipment[$field] = $networkEquipment->fields[$field];
      }
      $a_lockable = PluginFusioninventoryLock::getLockFields('glpi_networkequipments', $items_id);
      
      $a_ret = PluginFusioninventoryToolbox::checkLock($a_inventory['NetworkEquipment'], 
                                                       $db_networkequipment, $a_lockable);
      $a_inventory['NetworkEquipment'] = $a_ret[0];
      $db_networkequipment = $a_ret[1];
         
      $input = PluginFusioninventoryToolbox::diffArray($a_inventory['NetworkEquipment'], 
                                                       $db_networkequipment);
      $input['id'] = $items_id;         
      $networkEquipment->update($input);

      $this->internalPorts($a_inventory['internalport'], 
                           $items_id, 
                           $a_inventory['NetworkEquipment']['mac'],
                           $a_inventory['name']);      
      
      
      // * NetworkEquipment fusion (ext)
         $db_networkequipment = array();
         $query = "SELECT * 
            FROM `".  getTableForItemType("PluginFusioninventoryNetworkEquipment")."`
            WHERE `networkequipments_id` = '$items_id'";
         $result = $DB->query($query);         
         while ($data = $DB->fetch_assoc($result)) {            
            foreach($data as $key=>$value) {
               $data[$key] = Toolbox::addslashes_deep($value);
            }
            $db_networkequipment = $data;
         }
         if (count($db_networkequipment) == '0') { // Add
            $a_inventory['PluginFusioninventoryNetworkEquipment']['networkequipments_id'] = 
               $items_id;
            $pfNetworkEquipment->add($a_inventory['PluginFusioninventoryNetworkEquipment']);
         } else { // Update
            $idtmp = $db_networkequipment['id'];
            unset($db_networkequipment['id']);
            unset($db_networkequipment['networkequipments_id']);
            unset($db_networkequipment['plugin_fusioninventory_snmpmodels_id']);
            unset($db_networkequipment['plugin_fusioninventory_configsecurities_id']);
            
            $a_ret = PluginFusioninventoryToolbox::checkLock(
                        $a_inventory['PluginFusioninventoryNetworkEquipment'], 
                        $db_networkequipment);
            $a_inventory['PluginFusioninventoryNetworkEquipment'] = $a_ret[0];
            $db_networkequipment = $a_ret[1];
            $input = PluginFusioninventoryToolbox::diffArray(
                        $a_inventory['PluginFusioninventoryNetworkEquipment'], 
                        $db_networkequipment);
            $input['id'] = $idtmp;
            $pfNetworkEquipment->update($input);
         }
        
      // * Ports
         $this->importPorts($a_inventory, $items_id);
      
   }
   
   
   
   
   function addNewNetworkEquipment($a_inventory) {
      
   }

   
   
   /**
    * Import IPs
    *
    * @param $p_ips IPs code to import
    * @param $networkequipments_id id of network equipment
    *
    * @return errors string to be alimented if import ko / '' if ok
    **/
   function internalPorts($a_ips, $networkequipments_id, $mac, $networkname_name) {

      $networkPort = new NetworkPort();
      $iPAddress = new IPAddress();
      $pfUnknownDevice = new PluginFusioninventoryUnknownDevice();
      $networkName = new NetworkName();

      // Get agregated ports
      $a_networkPortAggregates = current($networkPort->find(
                    "`itemtype`='NetworkEquipment' 
                       AND `items_id`='".$networkequipments_id."'
                       AND `instantiation_type`='NetworkPortAggregate'
                       AND `logical_number` = '0'", '', 1));
      $a_ips_DB = array();
      $networkports_id = 0;
      if (isset($a_networkPortAggregates['id'])) {
         $a_networkPortAggregates['mac'] = $mac;
         $networkPort->update($a_networkPortAggregates);
         
         $networkports_id = $a_networkPortAggregates['id'];         
      } else {
         $input = array();
         $input['itemtype'] = 'NetworkEquipment';
         $input['items_id'] = $networkequipments_id;
         $input['instantiation_type'] = 'NetworkPortAggregate';
         $input['name'] = 'general';
         $input['mac'] = $mac;
         $networkports_id = $networkPort->add($input);
      }
      // Get networkname
      $a_networknames_find = current($networkName->find("`items_id`='".$networkports_id."'
                                                         AND `itemtype`='NetworkPort'", "", 1));
      $networknames_id = 0;
      if (isset($a_networknames_find['id'])) {
         $networknames_id = $a_networknames_find['id'];
         $a_networknames_find['name'] = $networkname_name;
         $networkName->update($a_networknames_find);
      } else {
         $input = array();
         $input['items_id'] = $networkports_id;
         $input['itemtype'] = 'NetworkPort';
         $input['name']     = $networkname_name;
         $networknames_id   = $networkName->add($input);
      }
      $a_ips_fromDB = $iPAddress->find("`itemtype`='networkName'
                                    AND `items_id`='".$networknames_id."'");
      foreach ($a_ips_fromDB as $data) {
         $a_ips_DB[$data['id']] = $data['name'];
      }
      
      foreach ($a_ips as $key => $ip) {
         foreach ($a_ips_DB as $keydb => $ipdb) {
            if ($ip == $ipdb) {
               unset($a_ips[$key]);
               unset($a_ips_DB[$keydb]);
               break;
            }
         }
      }
      if (count($a_ips) == 0
         AND count($a_ips_DB) == 0) {
         // Nothing to do
      } else {
         if (count($a_ips_DB) != 0) {
            // Delete processor in DB
            foreach ($a_ips_DB as $idtmp => $ip) {
               $iPAddress->delete(array('id'=>$idtmp));
            }
         }
         if (count($a_ips) != 0) {
            foreach($a_ips as $ip) {
               if ($ip != '127.0.0.1') {
                  $input = array();
                  $input['entities_id'] = 0;
                  $input['itemtype'] = 'NetworkName';
                  $input['items_id'] = $networknames_id;
                  $input['name'] = $ip;
                  $id = $iPAddress->add($input);
                  
                  // Search in unknown device if device with IP (LLDP) is yet added, in this case,
                  // we get id of this unknown device
                  $a_unknown = $pfUnknownDevice->find("`ip`='".$ip."'", "", 1);
                  if (count($a_unknown) > 0) {
                     $datas= current($a_unknown);
                     $this->unknownDeviceCDP = $datas['id'];
                  }
               }
            }
         }
      }
   }
   
   
   
   function importPorts($a_inventory, $items_id) {
      
      $pfNetworkporttype = new PluginFusioninventoryNetworkporttype();
      $networkPort = new NetworkPort();
      $pfNetworkPort = new PluginFusioninventoryNetworkPort();

      $networkports_id = 0;
      foreach ($a_inventory['networkport'] as $a_port) {
         $ifType = $a_port['iftype'];
         if ($pfNetworkporttype->isImportType($ifType)) {
            $a_ports_DB = current($networkPort->find(
                       "`itemtype`='NetworkEquipment' 
                          AND `items_id`='".$items_id."'
                          AND `instantiation_type`='NetworkPortEthernet'
                          AND `logical_number` = '".$a_port['logical_number']."'", '', 1));
            if (!isset($a_ports_DB['id'])) {
               // Add port
               $a_port['instantiation_type'] = 'NetworkPortEthernet';
               $a_port['items_id'] = $items_id;
               $a_port['itemtype'] = 'NetworkEquipment';
               $networkports_id = $networkPort->add($a_port);
               $a_port['networkports_id'] = $networkports_id;
               $pfNetworkPort->add($a_port);

            } else {
               // Update port
               $networkports_id = $a_ports_DB['id'];
               $a_port['id'] = $a_ports_DB['id'];
               $networkPort->update($a_port);
               unset($a_port['id']);
               
               // Check if pfnetworkport exist.
               $a_pfnetworkport_DB = current($pfNetworkPort->find(
                       "`networkports_id`='".$networkports_id."'", '', 1));
               $a_port['networkports_id'] = $networkports_id;
               if (isset($a_pfnetworkport_DB['id'])) {
                  $a_port['id'] = $a_pfnetworkport_DB['id'];
                  $pfNetworkPort->update($a_port);
               } else {
                  $a_port['networkports_id'] = $networkports_id;
                  $pfNetworkPort->add($a_port);
               }
            }

            // Connections
            if (isset($a_inventory['connection-lldp'][$a_port['logical_number']])) {
               $this->importConnectionLLDP($a_inventory['connection-lldp'][$a_port['logical_number']],
                       $networkports_id);
            } else if (isset($a_inventory['connection-mac'][$a_port['logical_number']])) {
               $this->importConnectionMac();
            }

            // Vlan
            $this->importPortVlan($a_inventory['vlans'][$a_port['logical_number']],
                                  $networkports_id);
         }
      }
      
   }

   

   function importConnectionLLDP($a_lldp, $networkports_id) {
      
      $pfNetworkPort = new PluginFusioninventoryNetworkPort();
      
      if ($a_lldp['ip'] != '') {
         $portID = $pfNetworkPort->getPortIDfromDeviceIP($a_lldp['ip'],
                                                         $a_lldp['ifdescr'],
                                                         $a_lldp['sysdescr'],
                                                         $a_lldp['name'],
                                                         $a_lldp['model']);
      } else {
         if ($a_lldp['mac'] != '') {
            $portID = $pfNetworkPort->getPortIDfromSysmacandPortnumber($a_lldp['sysmac'],
                                                                       $a_lldp['logical_number'],
                                                                       $a_lldp);
         }
      }
      
      if ($portID
              AND $portID > 0) {
         $wire = new NetworkPort_NetworkPort();
         $contact_id = $wire->getOppositeContact($networkports_id);
         if (!($contact_id
                 AND $contact_id == $portID)) {
            $pfNetworkPort->disconnectDB($networkports_id);
            $pfNetworkPort->disconnectDB($portID);
            $wire->add(array('networkports_id_1'=> $networkports_id,
                             'networkports_id_2' => $portID));
         }
      }
   }
   
   
   
   function importConnectionMac() {
      
   }
   
   
   
   function importPortVlan() {
      
   }
}

?>