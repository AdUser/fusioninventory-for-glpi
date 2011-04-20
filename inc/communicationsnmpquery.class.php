<?php

/*
   ----------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2011 by the FusionInventory Development Team.

   http://www.fusioninventory.org/   http://forge.fusioninventory.org/
   ----------------------------------------------------------------------

   LICENSE

   This file is part of FusionInventory.

   FusionInventory is free software: you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation, either version 2 of the License, or
   any later version.

   FusionInventory is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with FusionInventory.  If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------
   Original Author of file: Vincent MAZZONI
   Co-authors of file: David DURIEUX
   Purpose of file:
   ----------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}

class PluginFusinvsnmpCommunicationSNMPQuery {
//   private $sxml, $deviceId, $ptd, $type='', $logFile;
   private $sxml, $ptd, $logFile, $agent, $unknownDeviceCDP;

   function __construct() {
      if (PluginFusioninventoryConfig::getValue($_SESSION["plugin_fusioninventory_moduleid"], 'extradebug')) {
         $this->logFile = GLPI_LOG_DIR.'/fusioninventorycommunication.log';
      }
   }


   
   /**
    * Import data
    *
    *@param $p_DEVICEID XML code to import
    *@param $p_CONTENT XML code to import
    *@return "" (import ok) / error string (import ko)
    **/
   function import($p_DEVICEID, $p_CONTENT, $p_xml) {
      global $LANG;

      $_SESSION['SOURCEXML'] = $p_xml;

      $result = false;

      PluginFusioninventoryCommunication::addLog(
              'Function PluginFusinvsnmpCommunicationSNMPQuery->import().');

      $PluginFusioninventoryAgent = new PluginFusioninventoryAgent();
      $PluginFusioninventoryTaskjobstatus = new PluginFusioninventoryTaskjobstatus();

      $this->agent = $PluginFusioninventoryAgent->InfosByKey($p_DEVICEID);

      $this->sxml = simplexml_load_string($p_xml,'SimpleXMLElement', LIBXML_NOCDATA);
      $errors = '';

      $_SESSION['glpi_plugin_fusioninventory_processnumber'] = $p_CONTENT->PROCESSNUMBER;
      $PluginFusioninventoryTaskjobstatus->getFromDB($p_CONTENT->PROCESSNUMBER);
      if ($PluginFusioninventoryTaskjobstatus->fields['state'] != "3") {
         $PluginFusioninventoryTaskjobstatus->changeStatus($p_CONTENT->PROCESSNUMBER, 2);
         if ((!isset($p_CONTENT->AGENT->START)) AND (!isset($p_CONTENT->AGENT->END))) {
            $nb_devices = 0;
            foreach($p_CONTENT->DEVICE as $child) {
               $nb_devices++;
            }
            $_SESSION['plugin_fusinvsnmp_taskjoblog']['taskjobs_id'] = $p_CONTENT->PROCESSNUMBER;
            $_SESSION['plugin_fusinvsnmp_taskjoblog']['items_id'] = $this->agent['id'];
            $_SESSION['plugin_fusinvsnmp_taskjoblog']['itemtype'] = 'PluginFusioninventoryAgent';
            $_SESSION['plugin_fusinvsnmp_taskjoblog']['state'] = '6';
            $_SESSION['plugin_fusinvsnmp_taskjoblog']['comment'] = $nb_devices.' ==fusinvsnmp::1==';
            $this->addtaskjoblog();

         }
         $errors.=$this->importContent($p_CONTENT);
         $result=true;
         if ($errors != '') {
            if (isset($_SESSION['glpi_plugin_fusioninventory_processnumber'])) {
               $result=true;
   //            $PluginFusioninventoryAgentp = new PluginFusioninventoryAgentProcess();
   //            $PluginFusioninventoryAgentp->updateProcess($_SESSION['glpi_plugin_fusioninventory_processnumber'],
   //                                 array('comment' => $errors));

            } else {
               // It's PROLOG
               $result=false;
            }
         }
         if (isset($p_CONTENT->AGENT->END)) {
            $PluginFusioninventoryTaskjobstatus->changeStatusFinish($p_CONTENT->PROCESSNUMBER,
                                                      $this->agent['id'],
                                                      'PluginFusioninventoryAgent');
         }
         if (isset($p_CONTENT->AGENT->START)) {
            $_SESSION['plugin_fusinvsnmp_taskjoblog']['taskjobs_id'] = $p_CONTENT->PROCESSNUMBER;
            $_SESSION['plugin_fusinvsnmp_taskjoblog']['items_id'] = $this->agent['id'];
            $_SESSION['plugin_fusinvsnmp_taskjoblog']['itemtype'] = 'PluginFusioninventoryAgent';
            $_SESSION['plugin_fusinvsnmp_taskjoblog']['state'] = '6';
            $_SESSION['plugin_fusinvsnmp_taskjoblog']['comment'] = '==fusinvsnmp::6==';
            $this->addtaskjoblog();
         }
      }
      return $result;
   }


   
   /**
    * Import CONTENT
    *@param $p_content CONTENT code to import
    *
    *@return errors string to be alimented if import ko / '' if ok
    **/
   function importContent($p_content) {
      global $LANG;

      PluginFusioninventoryCommunication::addLog(
              'Function PluginFusinvsnmpCommunicationSNMPQuery->importContent().');
      //$PluginFusioninventoryAgentp = new PluginFusioninventoryAgentProcess;
      $PluginFusioninventoryAgent = new PluginFusioninventoryAgent();
      
      $errors='';
      $nbDevices = 0;

      foreach ($p_content->children() as $child) {
         PluginFusioninventoryCommunication::addLog($child->getName());
         switch ($child->getName()) {
            case 'DEVICE' :
               if (isset($child->ERROR)) {
                  $itemtype = "";
                  if ((string)$child->ERROR->TYPE == "NETWORKING") {
                     $itemtype = "NetworkEquipment";
                  } else if ((string)$child->ERROR->TYPE == "PRINTER") {
                     $itemtype = "Printer";
                  }
                  $_SESSION['plugin_fusinvsnmp_taskjoblog']['comment'] = '[detail] '.(string)$child->ERROR->MESSAGE.' [['.$itemtype.'::'.$child->ERROR->ID.']]';
                  $this->addtaskjoblog();
               } else if (!isset($child->INFO->COMMENTS)
                       AND !isset($child->INFO->NAME)
                       AND !isset($child->INFO->SERIAL)) {
                  $itemtype = "";
                  if ((string)$child->TYPE == "NETWORKING") {
                     $itemtype = "NetworkEquipment";
                  } else if ((string)$child->TYPE == "PRINTER") {
                     $itemtype = "Printer";
                  }
                  $_SESSION['plugin_fusinvsnmp_taskjoblog']['comment'] = '[detail] No informations [['.$itemtype.'::'.$child->ID.']]';
                  $this->addtaskjoblog();
               } else {
//               $errors.=$this->importDevice($child);
                  if (count($child) > 0) {
                     $this->sendCriteria($this->sxml->DEVICEID, $child);
                     $nbDevices++;
                  }
               }
               break;

            case 'AGENT' :
               if (isset($this->sxml->CONTENT->AGENT->START)) {
//                  $PluginFusioninventoryAgentp->updateProcess($_SESSION['glpi_plugin_fusioninventory_processnumber'],
//                                       array('start_time_query' => date("Y-m-d H:i:s")));
               } else if (isset($this->sxml->CONTENT->AGENT->END)) {
//                  $PluginFusioninventoryAgentp->updateProcess($_SESSION['glpi_plugin_fusioninventory_processnumber'],
//                                       array('end_time_query' => date("Y-m-d H:i:s")));
               } else if (isset($this->sxml->CONTENT->AGENT->EXIT)) {
//                  $PluginFusioninventoryAgentp->endProcess($_SESSION['glpi_plugin_fusioninventory_processnumber'],
//                                       date("Y-m-d H:i:s"));
               }
               if (isset($this->sxml->CONTENT->AGENT->AGENTVERSION)) {
                  $agent = $PluginFusioninventoryAgent->InfosByKey($this->sxml->DEVICEID);
                  $agent['fusioninventory_agent_version'] = $this->sxml->CONTENT->AGENT->AGENTVERSION;
                  $agent['last_agent_update'] = date("Y-m-d H:i:s");
                  //$p_xml = gzuncompress($GLOBALS["HTTP_RAW_POST_DATA"]);
                  $PluginFusioninventoryAgent->update($agent);
               }
               break;

            case 'PROCESSNUMBER' :
               break;

            case 'MODULEVERSION' :
               break;
            
            default :
               $_SESSION['plugin_fusinvsnmp_taskjoblog']['comment'] = '[detail] '.$LANG['plugin_fusioninventory']['errors'][22].' CONTENT : '.$child->getName();
               $this->addtaskjoblog();
         }
      }
      return $errors;
   }



   /**
    * Import DEVICE
    *@param $p_device DEVICE code to import
    *
    *@return errors string to be alimented if import ko / '' if ok
    **/
   function importDevice($itemtype, $items_id) {
      global $LANG;

      PluginFusioninventoryCommunication::addLog(
              'Function PluginFusinvsnmpCommunicationSNMPQuery->importDevice().');
      //$PluginFusioninventoryAgente = new PluginFusioninventoryAgentProcessError;

      $p_xml = simplexml_load_string($_SESSION['SOURCE_XMLDEVICE'],'SimpleXMLElement', LIBXML_NOCDATA);

      // Write XML file
      if (isset($p_xml)) {
         $folder = substr($items_id,0,-1);
         if (empty($folder)) {
            $folder = '0';
         }
         if (!file_exists(GLPI_PLUGIN_DOC_DIR."/fusinvsnmp/".$itemtype."/".$folder)) {
            mkdir(GLPI_PLUGIN_DOC_DIR."/fusinvsnmp/".$itemtype."/".$folder, 0777, true);
         }
         $fileopen = fopen(GLPI_PLUGIN_DOC_DIR."/fusinvsnmp/".$itemtype."/".$folder."/".$items_id, 'w');
         fwrite($fileopen, $p_xml->asXML());
         fclose($fileopen);
       }

      $errors='';
      $this->deviceId=$items_id;
      switch ($itemtype) {
         case 'Printer':
            $this->type = 'Printer';
            break;
         case 'NetworkEquipment':
            $this->type = 'NetworkEquipment';
            break;
         default:
            $errors.=$LANG['plugin_fusioninventory']['errors'][22].' TYPE : '
                              .$p_xml->INFO->TYPE."\n";
      }
      if (isset($p_xml->ERROR)) {
//         $PluginFusioninventoryAgentp->updateProcess($_SESSION['glpi_plugin_fusioninventory_processnumber'],
//                              array('query_nb_error' => '1'));
         $a_input = array();
         $a_input['id'] = $p_xml->ERROR->ID;
         if ($p_xml->ERROR->TYPE=='NETWORKING') {
            $a_input['TYPE'] = 'NetworkEquipment';
         } elseif ($p_xml->ERROR->TYPE=='PRINTER') {
            $a_input['TYPE'] = 'Printer';
         }
         $a_input['MESSAGE'] = $p_xml->ERROR->MESSAGE;
         $a_input['agent_type'] = 'SNMPQUERY';
         //$PluginFusioninventoryAgente->addError($a_input);
      } else {
//         $PluginFusioninventoryAgentp->updateProcess($this->sxml->CONTENT->PROCESSNUMBER, array('query_nb_query' => '1'));

         $errors.=$this->importInfo($itemtype, $items_id);
         if ($this->deviceId!='') {
            foreach ($p_xml->children() as $child) {
               switch ($child->getName()) {
                  case 'INFO' : // already managed
                     break;
                  case 'PORTS' :
                     $errors.=$this->importPorts($child);
                     break;
                  case 'CARTRIDGES' :
                     if ($this->type == 'Printer') {
                        $errors.=$this->importCartridges($child);
                        break;
                     }
                  case 'PAGECOUNTERS' :
                     if ($this->type == 'Printer') {
                        $errors.=$this->importPageCounters($child);
                        break;
                     }
                  default :
                     $errors.=$LANG['plugin_fusioninventory']['errors'][22].' DEVICE : '
                              .$child->getName()."\n";
               }
            }
            if ($errors=='') {
               $this->ptd->updateDB();
            } else {
               //$PluginFusioninventoryAgentp->updateProcess($_SESSION['glpi_plugin_fusioninventory_processnumber'],
               //      array('query_nb_error' => '1'));
               $a_input = array();
               $a_input['id'] = $p_xml->ERROR->ID;
               if ($p_xml->ERROR->TYPE=='NETWORKING') {
                  $a_input['TYPE'] = 'NetworkEquipment';
               } elseif ($p_xml->ERROR->TYPE=='PRINTER') {
                  $a_input['TYPE'] = 'Printer';
               }
               $a_input['MESSAGE'] = $errors;
               $a_input['agent_type'] = 'SNMPQUERY';
               //$PluginFusioninventoryAgente->addError($a_input);
            }
         } else {
            //$PluginFusioninventoryAgentp->updateProcess($_SESSION['glpi_plugin_fusioninventory_processnumber'],
            //      array('query_nb_error' => '1'));
            $a_input = array();
            $a_input['id'] = $p_xml->ERROR->ID;
            if ($p_xml->ERROR->TYPE=='NETWORKING') {
               $a_input['TYPE'] = 'NetworkEquipment';
            } elseif ($p_xml->ERROR->TYPE=='PRINTER') {
               $a_input['TYPE'] = 'Printer';
            }
            $a_input['MESSAGE'] = $errors;
            $a_input['agent_type'] = 'SNMPQUERY';
            //$PluginFusioninventoryAgente->addError($a_input);
         }
      }
      return $errors;
   }



   /**
    * Import INFO
    *@param $p_info INFO code to import
    *
    *@return errors string to be alimented if import ko / '' if ok
    **/
   function importInfo($itemtype, $items_id) {
      global $LANG;

      PluginFusioninventoryCommunication::addLog(
              'Function PluginFusinvsnmpCommunicationSNMPQuery->importInfo().');
      $errors='';
      $xml = simplexml_load_string($_SESSION['SOURCE_XMLDEVICE'],'SimpleXMLElement', LIBXML_NOCDATA);
      if ($itemtype == 'NetworkEquipment') {
         $errors.=$this->importInfoNetworking($xml->INFO);
      } elseif ($itemtype == 'Printer') {
         $errors.=$this->importInfoPrinter($xml->INFO);
      }
      if (!empty($errors)) {
         //$pfiae = new PluginFusioninventoryAgentProcessError;

         $a_input = array();
         $a_input['id'] = $xml->INFO->ID[0];
         if ($xml->INFO->TYPE=='NetworkEquipment') {
            $a_input['TYPE'] = 'NetworkEquipment';
         } elseif ($xml->INFO->TYPE=='Printer') {
            $a_input['TYPE'] = 'Printer';
         }
         $a_input['MESSAGE'] = $errors;
         $a_input['agent_type'] = 'SNMPQUERY';
         //$pfiae->addError($a_input);
      }

      return $errors;
   }


   
   /**
    * Import INFO:Networking
    *@param $p_info INFO code to import
    *
    *@return errors string to be alimented if import ko / '' if ok
    **/
   function importInfoNetworking($p_info) {
      global $LANG;
      
      $errors='';
      $this->ptd = new PluginFusinvsnmpNetworkEquipment();
      $this->ptd->load($this->deviceId);

      $_SESSION["plugin_fusinvinventory_entity"] = $this->ptd->getValue('entities_id');

      $a_lockable = PluginFusioninventoryLock::getLockFields('glpi_networkequipments', $this->ptd->getValue('id'));

      foreach ($p_info->children() as $child) {
         switch ($child->getName()) {
            case 'ID' : // already managed
               break;
            case 'TYPE' : // already managed
               break;
            case 'COMMENTS' :
               $this->ptd->setValue('sysdescr', $p_info->COMMENTS[0]);
               break;
            case 'CPU' :
               $this->ptd->setValue('cpu', $p_info->CPU[0]);
               break;
            case 'FIRMWARE' :
               if (!in_array('networkequipmentfirmwares_id', $a_lockable)) {
                  $NetworkEquipmentFirmware = new NetworkEquipmentFirmware();
                  $this->ptd->setValue('networkequipmentfirmwares_id', $NetworkEquipmentFirmware->import(array('name' => (string)$p_info->FIRMWARE)));
               }
               break;
            case 'MAC' :
               if (!in_array('mac', $a_lockable)) {
                  $this->ptd->setValue('mac', $p_info->MAC[0]);
               }
               break;
            case 'MEMORY' :
               if (!in_array('memory', $a_lockable)) {
                  $this->ptd->setValue('memory', $p_info->MEMORY[0]);
               }
               break;
            case 'MODEL' :
               $NetworkEquipmentModel = new NetworkEquipmentModel();
               $networkequipmentmodels_id = $NetworkEquipmentModel->import(array('name'=>(string)$p_info->MODEL));
               $this->ptd->setValue('networkequipmentmodels_id', $networkequipmentmodels_id);
               break;
            case 'LOCATION' :
               if (!in_array('locations_id', $a_lockable)) {
                  $Location = new Location();
                  $this->ptd->setValue('locations_id', $Location->import(array('name' => (string)$p_info->LOCATION,
                                                                    'entities_id' => $this->ptd->getValue('entities_id'))));
               }
               break;
            case 'NAME' :
               if (!in_array('name', $a_lockable)) {
                  $this->ptd->setValue('name', $p_info->NAME[0]);
               }
               break;
            case 'RAM' :
               $this->ptd->setValue('ram', $p_info->RAM[0]);
               break;
            case 'SERIAL' :
               if (!in_array('serial', $a_lockable)) {
                  $this->ptd->setValue('serial', $p_info->SERIAL[0]);
               }
               break;
            case 'UPTIME' :
               $this->ptd->setValue('uptime', $p_info->UPTIME[0]);
               break;
            case 'IPS' :
               $errors.=$this->importIps($child);
               break;
            default :
               $errors.=$LANG['plugin_fusioninventory']['errors'][22].' INFO : '.$child->getName()."\n";
         }
      }
      return $errors;
      
   }


   
   /**
    * Import INFO:Printer
    *@param $p_info INFO code to import
    *
    *@return errors string to be alimented if import ko / '' if ok
    **/
   function importInfoPrinter($p_info) {
      global $LANG;

      PluginFusioninventoryCommunication::addLog(
              'Function PluginFusinvsnmpCommunicationSNMPQuery->importInfoPrinter().');

      $errors='';
      $this->ptd = new PluginFusinvsnmpPrinter();
      $this->ptd->load($this->deviceId);

      $_SESSION["plugin_fusinvinventory_entity"] = $this->ptd->getValue('entities_id');

      $a_lockable = PluginFusioninventoryLock::getLockFields('glpi_printers', $this->ptd->getValue('id'));

      foreach ($p_info->children() as $child) {
         switch ($child->getName()) {
            case 'ID' : // already managed
               break;
            case 'TYPE' : // already managed
               break;
            case 'COMMENTS' :
               $this->ptd->setValue('sysdescr', (string)$p_info->COMMENTS);
               break;
            case 'MEMORY' :
               $this->ptd->setValue('memory_size', (string)$p_info->MEMORY);
               break;
            case 'MODEL' :
               if (!in_array('printermodels_id', $a_lockable)) {
                  $PrinterModel = new PrinterModel();
                  $printermodels_id = $PrinterModel->import(array('name'=>(string)$p_info->MODEL));
                  $this->ptd->setValue('printermodels_id', $printermodels_id);
               }
               break;
            case 'NAME' :
               if (!in_array('name', $a_lockable)) {
                  $this->ptd->setValue('name', (string)$p_info->NAME);
               }
               break;
            case 'SERIAL' :
               if (!in_array('serial', $a_lockable)) {
                  $this->ptd->setValue('serial', (string)$p_info->SERIAL);
               }
               break;
            case 'OTHERSERIAL' :
               if (!in_array('otherserial', $a_lockable)) {
                  $this->ptd->setValue('otherserial', (string)$p_info->OTHERSERIAL);
               }
               break;
            case 'LOCATION' :
               if (!in_array('locations_id', $a_lockable)) {
                  $Location = new Location();
                  $this->ptd->setValue('locations_id', $Location->import(array('name' => (string)$p_info->LOCATION,
                                                                           'entities_id' => $this->ptd->getValue('entities_id'))));
               }
               break;
            case 'CONTACT' :
               if (!in_array('contact', $a_lockable)) {
                  $this->ptd->setValue('contact', (string)$p_info->CONTACT);
               }
               break;
            case 'MANUFACTURER' :
               if (!in_array('manufacturers_id', $a_lockable)) {
                  $Manufacturer = new Manufacturer();
                  $this->ptd->setValue('manufacturers_id', $Manufacturer->import(array('name' => (string)$p_info->MANUFACTURER)));
               }
               break;
            default :
               $errors.=$LANG['plugin_fusioninventory']['errors'][22].' INFO : '.$child->getName()."\n";
         }
      }
      return $errors;
   }



   /**
    * Import IPS
    *@param $p_ips IPS code to import
    *
    *@return errors string to be alimented if import ko / '' if ok
    **/
   function importIps($p_ips) {
      global $LANG;

      $errors='';
      $PluginFusinvsnmpNetworkEquipmentIP = new PluginFusinvsnmpNetworkEquipmentIP();
      $PluginFusioninventoryUnknownDevice = new PluginFusioninventoryUnknownDevice();
      foreach ($p_ips->children() as $name=>$child) {
         switch ($child->getName()) {
            case 'IP' :
               if ((string)$child != "127.0.0.1") {
                  $ifaddrIndex = $this->ptd->getIfaddrIndex((string)$child);
                  if (is_int($ifaddrIndex)) {
                     $oldIfaddr = $this->ptd->getIfaddr($ifaddrIndex);
                     $PluginFusinvsnmpNetworkEquipmentIP->load($oldIfaddr->getValue('id'));
                  } else {
                     $PluginFusinvsnmpNetworkEquipmentIP->load();
                  }
                  $PluginFusinvsnmpNetworkEquipmentIP->setValue('ip', (string)$child);
                  $this->ptd->addIfaddr(clone $PluginFusinvsnmpNetworkEquipmentIP, $ifaddrIndex);
                  // Search in unknown device if device with IP (CDP) is yet added, in this case,
                  // we get id of this unknown device
                  $a_unknown = $PluginFusioninventoryUnknownDevice->find("`ip`='".(string)$child."'");
                  if (count($a_unknown) > 0) {
                     foreach ($a_unknown as $datas) {
                     }
                     $this->unknownDeviceCDP = $datas['id'];
                  }
               }
               break;
            default :
               $errors.=$LANG['plugin_fusioninventory']['errors'][22].' IPS : '.$child->getName()."\n";
         }
      }
      $this->ptd->saveIfaddrs();
      return $errors;
   }


   
   /**
    * Import PORTS
    *@param $p_ports PORTS code to import
    *
    *@return errors string to be alimented if import ko / '' if ok
    **/
   function importPorts($p_ports) {
      global $LANG;

      PluginFusioninventoryCommunication::addLog(
              'Function PluginFusinvsnmpCommunicationSNMPQuery->importPorts().');
      $errors='';
      foreach ($p_ports->children() as $child) {
         switch ($child->getName()) {
            case 'PORT' :
               if ($this->type == "Printer") {
                  $errors.=$this->importPortPrinter($child);
               } elseif ($this->type == "NetworkEquipment") {
                  $errors.=$this->importPortNetworking($child);
               }
               break;
            default :
               $errors.=$LANG['plugin_fusioninventory']['errors'][22].' PORTS : '.$child->getName()."\n";
         }
      }
      return $errors;
   }


   
   /**
    * Import PORT Networking
    *@param $p_port PORT code to import
    *
    *@return errors string to be alimented if import ko / '' if ok
    **/
   function importPortNetworking($p_port) {
      global $LANG;
      PluginFusioninventoryCommunication::addLog(
              'Function PluginFusinvsnmpCommunicationSNMPQuery->importPortNetworking().');
      $errors='';
//      $ptp = new PluginFusioninventoryNetworkPort(NETWORKING_TYPE);
      $ptp = new PluginFusinvsnmpNetworkPort("NetworkEquipment", $this->logFile);
      $ifType = $p_port->IFTYPE;
      if ( $ptp->isReal($ifType) ) { // not virtual port
         // Get port of unknown device CDP if exist
         $portloaded = 0;
         if (!empty($this->unknownDeviceCDP)) {
            $NetworkPort = new NetworkPort();
            $a_unknownPorts = $NetworkPort->find("`itemtype`='PluginFusioninventoryUnknownDevice'
                     AND `items_id`='".$this->unknownDeviceCDP."'");
            foreach($a_unknownPorts as $dataport) {
               if ((isset($p_port->IFNAME))
                       AND ($p_port->IFNAME == $dataport['name'])
                       AND ($portloaded != '1')) {

                  // get this port and put in this switch
                  $dataport['itemtype'] = 'NetworkEquipment';
                  $dataport['items_id'] = $this->ptd->getValue('id');
                  $NetworkPort->update($dataport);
                  $ptp->load($dataport['id']);
                  $portloaded = 1;
                  $portIndex = $p_port->IFNUMBER;
               }
            }
            $a_unknownPorts = $NetworkPort->find("`itemtype`='PluginFusioninventoryUnknownDevice'
                     AND `items_id`='".$this->unknownDeviceCDP."'");
            if (count($a_unknownPorts) == '0') {
               $PluginFusioninventoryUnknownDevice = new PluginFusioninventoryUnknownDevice();
               $PluginFusioninventoryUnknownDevice->getFromDB($this->unknownDeviceCDP);
               $PluginFusioninventoryUnknownDevice->delete($PluginFusioninventoryUnknownDevice->fields, 1);
               $this->unknownDeviceCDP = 0;
            }
         }
         if ($portloaded == '0') {
            $portIndex = $this->ptd->getPortIndex($p_port->IFNUMBER, $this->getConnectionIP($p_port));
            if (is_int($portIndex)) {
               $oldPort = $this->ptd->getPort($portIndex);
               $ptp->load($oldPort->getValue('id'));
            } else {
               $ptp->addDB($this->deviceId, TRUE);
            }
         }

         foreach ($p_port->children() as $name=>$child) {
            $trunk = 0;
            switch ($name) {
               case 'CONNECTIONS' :
                  $errors.=$this->importConnections($child, $ptp);
                  break;
               case 'VLANS' :
                  $errors.=$this->importVlans($child, $ptp);
                  break;
               case 'IFNAME' :
                  PluginFusinvsnmpNetworkPortLog::networkport_addLog($ptp->getValue('id'), $child, strtolower($name));
                  $ptp->setValue('name', $child);
                  break;
               case 'MAC' :
                  PluginFusinvsnmpNetworkPortLog::networkport_addLog($ptp->getValue('id'), $child, strtolower($name));
                  if (!strstr($child, '00:00:00')) {
                     $ptp->setValue('mac', $child);
                  }
                  break;
               case 'IFNUMBER' :
                  PluginFusinvsnmpNetworkPortLog::networkport_addLog($ptp->getValue('id'), $child, strtolower($name));
                  $ptp->setValue('logical_number', $child);
                  break;
               case 'IFTYPE' : // already managed
                  break;
               case 'TRUNK' :
                  if ((string)$child == '1') {
                     PluginFusinvsnmpNetworkPortLog::networkport_addLog($ptp->getValue('id'), $child, strtolower($name));
                     $ptp->setValue('trunk', 1);
                     $trunk = 1;
                  }
                  break;

               case 'IFDESCR' :
                  if (!isset($p_port->IFNAME)) {
                     $ptp->setValue('name', $p_port->IFDESCR);
                  }
                  $ptp->setValue(strtolower($name), $p_port->$name);
                  break;
                  
               case 'IFINERRORS' :
               case 'IFINOCTETS' :
               case 'IFINTERNALSTATUS' :
               case 'IFLASTCHANGE' :
               case 'IFMTU' :
               case 'IFOUTERRORS' :
               case 'IFOUTOCTETS' :
               case 'IFSPEED' :
               case 'IFSTATUS' :
                  PluginFusinvsnmpNetworkPortLog::networkport_addLog($ptp->getValue('id'), $child, strtolower($name));
                  $ptp->setValue(strtolower($name), $p_port->$name);
                  break;
               default :
                  $errors.=$LANG['plugin_fusioninventory']['errors'][22].' PORT : '.$name."\n";
            }
         }
         if ($trunk == "0") {
            if ($ptp->getValue('trunk') == '1') {
               PluginFusinvsnmpNetworkPortLog::networkport_addLog($ptp->getValue('id'), '0', 'trunk');
               $ptp->setValue('trunk', 0);
            }
         }
         $this->ptd->addPort($ptp, $portIndex);
      } else { // virtual port : do not import but delete if exists
         if ( is_numeric($ptp->getValue('id')) ) $ptp->deleteDB();
      }
      return $errors;
   }


   
   /**
    * Import PORT Printer
    *@param $p_port PORT code to import
    *
    *@return errors string to be alimented if import ko / '' if ok
    **/
   function importPortPrinter($p_port) {
      global $LANG;

      $errors='';
      $ptp = new PluginFusinvsnmpNetworkPort('Printer');
      $ifType = $p_port->IFTYPE;
      if ( $ptp->isReal($ifType) ) { // not virtual port
         $portIndex = $this->ptd->getPortIndex($p_port->MAC, $p_port->IP);
         if (is_int($portIndex)) {
            $oldPort = $this->ptd->getPort($portIndex);
            $ptp->load($oldPort->getValue('id'));
         } else {
            $ptp->addDB($this->deviceId, TRUE);
         }
         foreach ($p_port->children() as $name=>$child) {
            switch ($name) {
               case 'IFNAME' :
                  PluginFusinvsnmpNetworkPortLog::networkport_addLog($ptp->getValue('id'), $child, strtolower($name));
                  $ptp->setValue('name', $child);
                  break;
               case 'MAC' :
                  PluginFusinvsnmpNetworkPortLog::networkport_addLog($ptp->getValue('id'), $child, strtolower($name));
                  $ptp->setValue('mac', $child);
                  break;
               case 'IP' :
                  PluginFusinvsnmpNetworkPortLog::networkport_addLog($ptp->getValue('id'), $child, strtolower($name));
                  $ptp->setValue('ip', $child);
                  break;
               case 'IFNUMBER' :
                  PluginFusinvsnmpNetworkPortLog::networkport_addLog($ptp->getValue('id'), $child, strtolower($name));
                  $ptp->setValue('logical_number', $child);
                  break;
               case 'IFTYPE' : // already managed
                  break;
               default :
                  $errors.=$LANG['plugin_fusioninventory']['errors'][22].' PORT : '.$name."\n";
            }
         }
         $this->ptd->addPort($ptp, $portIndex);
      }
      return $errors;
   }



   /**
    * Import CARTRIDGES
    *@param $p_cartridges CARTRIDGES code to import
    *
    *@return errors string to be alimented if import ko / '' if ok
    **/
   function importCartridges($p_cartridges) {
      global $LANG;

      $PluginFusioninventoryMapping = new PluginFusioninventoryMapping();
      $errors='';
      foreach ($p_cartridges->children() as $name=>$child) {
         if ($plugin_fusioninventory_mappings = $PluginFusioninventoryMapping->get("Printer", strtolower($name)) ) {
            $ptc = new PluginFusinvsnmpPrinterCartridge('glpi_plugin_fusinvsnmp_printercartridges');
            $cartridgeIndex = $this->ptd->getCartridgeIndex($name);
            if (is_int($cartridgeIndex)) {
               $oldCartridge = $this->ptd->getCartridge($cartridgeIndex); //TODO ???
               $ptc->load($oldCartridge->getValue('id'));
            } else {
               $ptc->addCommon(TRUE); //TODO ???
               $ptc->setValue('printers_id', $this->deviceId);
            }
            $ptc->setValue('plugin_fusioninventory_mappings_id', $plugin_fusioninventory_mappings['id']);
            $ptc->setValue('state', $child, $ptc, 0);
            $this->ptd->addCartridge($ptc, $cartridgeIndex);
         } else {
            $errors.=$LANG['plugin_fusioninventory']['errors'][22].' CARTRIDGES : '.$name."\n";
         }
      }
      return $errors;
   }


   
   /**
    * Import PAGECOUNTERS
    *@param $p_pagecounters PAGECOUNTERS code to import
    *
    *@return errors string to be alimented if import ko / '' if ok
    **/
   function importPageCounters($p_pagecounters) {
      global $LANG;

      $errors='';
      foreach ($p_pagecounters->children() as $name=>$child)
      {
         switch ($child->getName()) {
            case 'TOTAL' :
               $errors.=$this->ptd->addPageCounter('pages_total', $child);
               break;
            case 'BLACK' :
               $errors.=$this->ptd->addPageCounter('pages_n_b', $child);
               break;
            case 'COLOR' :
               $errors.=$this->ptd->addPageCounter('pages_color', $child);
               break;
            case 'RECTOVERSO' :
               $errors.=$this->ptd->addPageCounter('pages_recto_verso', $child);
               break;
            case 'SCANNED' :
               $errors.=$this->ptd->addPageCounter('scanned', $child);
               break;
            case 'PRINTTOTAL' :
               $errors.=$this->ptd->addPageCounter('pages_total_print', $child);
               break;
            case 'PRINTBLACK' :
               $errors.=$this->ptd->addPageCounter('pages_n_b_print', $child);
               break;
            case 'PRINTCOLOR' :
               $errors.=$this->ptd->addPageCounter('pages_color_print', $child);
               break;
            case 'COPYTOTAL' :
               $errors.=$this->ptd->addPageCounter('pages_total_copy', $child);
               break;
            case 'COPYBLACK' :
               $errors.=$this->ptd->addPageCounter('pages_n_b_copy', $child);
               break;
            case 'COPYCOLOR' :
               $errors.=$this->ptd->addPageCounter('pages_color_copy', $child);
               break;
            case 'FAXTOTAL' :
               $errors.=$this->ptd->addPageCounter('pages_total_fax', $child);
               break;
            default :
               $errors.=$LANG['plugin_fusioninventory']['errors'][22].' PAGECOUNTERS : '.$name."\n";
         }
      }
      return $errors;
   }


   
   /**
    * Import CONNECTIONS
    *@param $p_connections CONNECTIONS code to import
    *@param $p_oPort Port object to connect
    *
    *@return errors string to be alimented if import ko / '' if ok
    **/
   function importConnections($p_connections, $p_oPort) {
      global $LANG;

      PluginFusioninventoryCommunication::addLog(
              'Function PluginFusinvsnmpCommunicationSNMPQuery->importConnections().');
      $errors='';
      $cdp = 0;
      if (isset($p_connections->CDP)) {
         $cdp = $p_connections->CDP;
         if ($cdp==1) {
            $p_oPort->setCDP();
         } else {
            $errors.=$LANG['plugin_fusioninventory']['errors'][22].' CONNECTIONS : CDP='.$cdp."\n";
         }
      }
      $count = 0;
      $a_macsFound = array();
      foreach ($p_connections->children() as $child) {
         switch ($child->getName()) {
            case 'CDP' : // already managed
               break;
            case 'CONNECTION' :
               $continue = 1;
               if (isset($child->MAC)) {
                  if (isset($a_macsFound[(string)$child->MAC])) {
                     $continue = 0;
                  } else {
                     $a_macsFound[(string)$child->MAC] = 1;
                  }
               }
               if ($continue == '1') {
                  $count++;
                  $errors.=$this->importConnection($child, $p_oPort, $cdp);
               }
               break;
            default :
               $errors.=$LANG['plugin_fusioninventory']['errors'][22].' CONNECTIONS : '
                        .$child->getName()."\n";
         }
      }
      if ($p_oPort->getValue('trunk')!=1) {
         if ($count > 1) { // MultipleMac
            $p_oPort->setNoTrunk();
            $pfiud = new PluginFusioninventoryUnknownDevice;
            $pfiud->hubNetwork($p_oPort, $this->agent);
         } else {
            if (!$p_oPort->getNoTrunk()) {
               $p_oPort->setValue('trunk', 0);
            }
         }
//      } else {
//         if ($p_oPort->getValue('trunk') == '-1') {
//            $p_oPort->setValue('trunk', '0');
//         }
      }
      return $errors;
   }

   

   /**
    * Import CONNECTION
    *@param $p_connection CONNECTION code to import
    *@param $p_oPort Port object to connect
    *@param $p_cdp CDP value (1 or <>1)
    *@return errors string to be alimented if import ko / '' if ok
    **/
   function  importConnection($p_connection, $p_oPort, $p_cdp) {
      global $LANG;

      PluginFusioninventoryCommunication::addLog(
              'Function PluginFusinvsnmpCommunicationSNMPQuery->importConnection().');
      $errors='';
      $portID=''; $mac=''; $ip=''; $sysmac=''; $ifnumber='';
      $PluginFusinvsnmpSNMP = new PluginFusinvsnmpSNMP();
      if ($p_cdp==1) {
         $ifdescr='';
         foreach ($p_connection->children() as $child) {
            switch ($child->getName()) {
               
               case 'IP' :
                  $ip=(string)$child;
                  $p_oPort->addIp($ip);
                  break;

               case 'IFDESCR' :
                  $ifdescr=(string)$child;
                  break;

               case 'SYSMAC': // LLDP Nortel
                  $sysmac=(string)$child;
                  break;

               case 'IFNUMBER': // LLDP Nortel
                  $ifnumber=(string)$child;
                  break;
               
               default :
                  $errors.=$LANG['plugin_fusioninventory']['errors'][22].' CONNECTION (CDP='.$p_cdp.') : '
                           .$child->getName()."\n";
            }
         }
         if ($ip != '' AND $ifdescr!='') {
            $portID=$PluginFusinvsnmpSNMP->getPortIDfromDeviceIP($ip, $ifdescr);
         } else if($sysmac != '' AND $ifnumber!='') {
            $portID=$PluginFusinvsnmpSNMP->getPortIDfromSysmacandPortnumber($sysmac, $ifnumber);
         }
      } else {
         foreach ($p_connection->children() as $child) {
            switch ($child->getName()) {
               case 'MAC' :
                  $mac=strval($child);
                  $portID=$PluginFusinvsnmpSNMP->getPortIDfromDeviceMAC($child, $p_oPort->getValue('id'));
                  $p_oPort->addMac($mac);
                  break;
               case 'IP' ://TODO : si ip ajouter une tache de decouverte sur l'ip pour recup autre info // utile seulement si mac inconnu dans glpi
                  $ip=strval($child);
                  $p_oPort->addIp($ip);
                  break;
               default :
                  $errors.=$LANG['plugin_fusioninventory']['errors'][22].' CONNECTION (CDP='.$p_cdp.') : '
                           .$child->getName()."\n";
            }

         }
      }
      if ($portID != '') {
         $p_oPort->addConnection($portID);
         if ($ip != '') $p_oPort->setValue('ip', $ip);
      } else {
         $p_oPort->addUnknownConnection($mac, $ip);
         //TODO : si ip ajouter une tache de decouverte sur l'ip pour recup autre info
      }
      return $errors;
   }


   
   /**
    * Import VLANS
    *@param $p_vlans VLANS code to import
    *@param $p_oPort Port object to connect
    *
    *@return errors string to be alimented if import ko / '' if ok
    **/
   function importVlans($p_vlans, $p_oPort) {
      global $LANG;

      $errors='';
      foreach ($p_vlans->children() as $child) {
         switch ($child->getName()) {
            case 'VLAN' :
               $errors.=$this->importVlan($child, $p_oPort);
               break;
            default :
               $errors.=$LANG['plugin_fusioninventory']['errors'][22].' VLANS : '.$child->getName()."\n";
         }
      }
      return $errors;
   }


   
   /**
    * Import VLAN
    *@param $p_vlan VLAN code to import
    *@param $p_oPort Port object to connect
    *@return errors string to be alimented if import ko / '' if ok
    **/
   function importVlan($p_vlan, $p_oPort) {
      global $LANG;

      $errors='';
      $number=''; $name='';
      foreach ($p_vlan->children() as $child) {
         switch ($child->getName()) {
            case 'NUMBER' :
               $number=$child;
               break;
            case 'NAME' :
               $name=$child;
               break;
            default :
               $errors.=$LANG['plugin_fusioninventory']['errors'][22].' VLAN : '.$child->getName()."\n";
         }
      }
      $p_oPort->addVlan($number, $name);
      return $errors;
   }


   
   /**
    * Get connection IP
    *
    *@param $p_port PORT code to import
    *@return first connection IP or ''
    **/
   function getConnectionIP($p_port) {
      foreach ($p_port->children() as $connectionsName=>$connectionsChild) {
         switch ($connectionsName) {
            
            case 'CONNECTIONS' :
               foreach ($connectionsChild->children() as $connectionName=>$connectionChild) {
                  
                  switch ($connectionName) {

                     case 'CONNECTION' :
                        foreach ($connectionChild->children() as $ipName=>$ipChild) {

                           switch ($ipName) {

                              case 'IP' :
                                 if ($ipChild != '') return $ipChild;
                                 break;
                                 
                           }
                        }
                        break;
                        
                  }
               }
               break;
               
         }
      }
      return '';
   }

   

   function sendCriteria($p_DEVICEID, $p_CONTENT) {

      PluginFusioninventoryCommunication::addLog(
              'Function PluginFusinvsnmpCommunicationSNMPQuery->sendCriteria().');

      // Manual blacklist
       if ((isset($p_CONTENT->INFO->SERIAL)) AND ($p_CONTENT->INFO->SERIAL == 'null')) {
          unset($p_CONTENT->INFO->SERIAL);
       }
       // End manual blacklist

       $_SESSION['SOURCE_XMLDEVICE'] = $p_CONTENT->asXML();

       $input = array();

      // Global criterias

         if ((isset($p_CONTENT->INFO->SERIAL)) AND (!empty($p_CONTENT->INFO->SERIAL))) {
            $input['serial'] = (string)$p_CONTENT->INFO->SERIAL;
         }
         if ($p_CONTENT->INFO->TYPE=='NETWORKING') {
            $input['itemtype'] = "NetworkEquipment";
            if ((isset($p_CONTENT->INFO->MAC)) AND (!empty($p_CONTENT->INFO->MAC))) {
               $input['mac'][] = (string)$p_CONTENT->INFO->MAC;
            }
         } else if ($p_CONTENT->INFO->TYPE=='PRINTER') {
            $input['itemtype'] = "Printer";
            if (isset($p_CONTENT->CONTENT->PORTS)) {
               foreach($p_CONTENT->CONTENT->PORTS as $port) {
                  if ((isset($port->MAC)) AND (!empty($port->MAC))) {
                     $input['mac'][] = (string)$port->MAC;
                  }
                  if ((isset($port->MAC)) AND (!empty($port->IP))) {
                     $input['ip'][] = (string)$port->IP;
                  }
               }
            }
         }
         if ((isset($p_CONTENT->INFO->MODEL)) AND (!empty($p_CONTENT->INFO->MODEL))) {
            $input['model'] = (string)$p_CONTENT->INFO->MODEL;
         }
         if ((isset($p_CONTENT->INFO->NAME)) AND (!empty($p_CONTENT->INFO->NAME))) {
            $input['name'] = (string)$p_CONTENT->INFO->NAME;
         }
      $_SESSION['plugin_fusinvsnmp_datacriteria'] = serialize($input);
      $_SESSION['plugin_fusioninventory_classrulepassed'] = "PluginFusinvsnmpCommunicationSNMPQuery";
      $rule = new PluginFusioninventoryRuleImportEquipmentCollection();
      $data = array();
      $data = $rule->processAllRules($input, array());
      if (isset($data['action'])
              AND ($data['action'] == PluginFusioninventoryRuleImportEquipment::LINK_RESULT_NO_IMPORT)) {

         $_SESSION['plugin_fusinvsnmp_taskjoblog']['comment'] = '==fusioninventory::3== '.implode(",", $input);
         $this->addtaskjoblog();
      }
      if (isset($data['_no_rule_matches']) AND ($data['_no_rule_matches'] == '1')) {
         if (PluginFusioninventoryConfig::getValue($_SESSION["plugin_fusioninventory_moduleid"], 'extradebug')) {
            logInFile("pluginFusioninventory-rules", "norulematch = 1");
         }
         if (isset($input['itemtype'])
              AND isset($data['action'])
              AND ($data['action'] == PluginFusioninventoryRuleImportEquipment::LINK_RESULT_IMPORT)) {

            $this->rulepassed(0, $input['itemtype']);
         } else {
            $this->rulepassed(0, "PluginFusioninventoryUnknownDevice");
         }
      }
   }



   function rulepassed($items_id, $itemtype) {
      global $DB;

      PluginFusioninventoryCommunication::addLog(
              'Function PluginFusinvsnmpCommunicationSNMPQuery->rulepassed().');

      $xml = simplexml_load_string($_SESSION['SOURCE_XMLDEVICE'],'SimpleXMLElement', LIBXML_NOCDATA);

      $class = new $itemtype();
      if ($items_id == "0") {
         $input = array();
         $input['date_mod'] = date("Y-m-d H:i:s");
         $items_id = $class->add($input);
      }
      if ($itemtype == "PluginFusioninventoryUnknownDevice") {
         $class->getFromDB($items_id);
         if ((isset($xml->INFO->NAME)) AND (!empty($xml->INFO->NAME))) {
            $class->fields['name'] = (string)$xml->INFO->NAME;
         }
         if ((isset($xml->INFO->SERIAL)) AND (!empty($xml->INFO->SERIAL))) {
            $class->fields['serial'] = (string)$xml->INFO->SERIAL;
         }
         if ((isset($xml->INFO->OTHERSERIAL)) AND (!empty($xml->INFO->OTHERSERIAL))) {
            $class->fields['otherserial'] = (string)$xml->INFO->OTHERSERIAL;
         }
         if ($xml->INFO->TYPE=='NETWORKING') {
            $class->fields['itemtype'] = "NetworkEquipment";
         } else if ($xml->INFO->TYPE=='PRINTER') {
            $class->fields['itemtype'] = "Printer";
         }
         // TODO : add import ports 

         $class->update($class->fields);
         $_SESSION['plugin_fusinvsnmp_taskjoblog']['comment'] =
            '[detail] ==fusinvsnmp::5== Update '.PluginFusioninventoryUnknownDevice::getTypeName().' [[PluginFusioninventoryUnknownDevice::'.$items_id.']]';
         $this->addtaskjoblog();

      } else {
         $_SESSION['plugin_fusinvsnmp_taskjoblog']['comment'] =
               '[detail] Update '.$class->getTypeName().' [['.$itemtype.'::'.$items_id.']]';
         $this->addtaskjoblog();
         $this->importDevice($itemtype, $items_id);
      }
   }



   function addtaskjoblog() {

      $PluginFusioninventoryTaskjoblog = new PluginFusioninventoryTaskjoblog();
      $PluginFusioninventoryTaskjoblog->addTaskjoblog(
                     $_SESSION['plugin_fusinvsnmp_taskjoblog']['taskjobs_id'],
                     $_SESSION['plugin_fusinvsnmp_taskjoblog']['items_id'],
                     $_SESSION['plugin_fusinvsnmp_taskjoblog']['itemtype'],
                     $_SESSION['plugin_fusinvsnmp_taskjoblog']['state'],
                     $_SESSION['plugin_fusinvsnmp_taskjoblog']['comment']);
   }

}

?>