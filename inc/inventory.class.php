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
   Original Author of file: David DURIEUX
   Co-authors of file:
   Purpose of file:
   ----------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}

class PluginFusinvinventoryInventory {

   
   /**
   * Import data
   *
   * @param $p_DEVICEID XML code to import
   * @param $p_CONTENT XML code of the Computer
   * @param $p_CONTENT XML code of all agent have sent
   *
   * @return nothing (import ok) / error string (import ko)
   **/
   function import($p_DEVICEID, $p_CONTENT, $p_xml) {
      global $LANG;

      $errors = '';

      $this->sendCriteria($p_DEVICEID, $p_CONTENT, $p_xml);

      return $errors;
   }



   /**
   * Send Computer to ruleimportequipment
   *
   * @param $p_DEVICEID XML code to import
   * @param $p_CONTENT XML code of the Computer
   * @param $p_CONTENT XML code of all agent have sent
   *
   * @return nothing
   *
   **/
   function sendCriteria($p_DEVICEID, $p_CONTENT, $p_xml) {

      $PluginFusinvinventoryBlacklist = new PluginFusinvinventoryBlacklist();
      $p_xml = $PluginFusinvinventoryBlacklist->cleanBlacklist($p_xml);

      $_SESSION['SOURCEXML'] = $p_xml;

      $xml = simplexml_load_string($p_xml,'SimpleXMLElement', LIBXML_NOCDATA);
      $input = array();

      // Global criterias

         if ((isset($xml->CONTENT->BIOS->SSN)) AND (!empty($xml->CONTENT->BIOS->SSN))) {
            $input['serial'] = addslashes_deep((string)$xml->CONTENT->BIOS->SSN);
         }
         if ((isset($xml->CONTENT->HARDWARE->UUID)) AND (!empty($xml->CONTENT->HARDWARE->UUID))) {
            $input['uuid'] = addslashes_deep((string)$xml->CONTENT->HARDWARE->UUID);
         }
         if (isset($xml->CONTENT->NETWORKS)) {
            foreach($xml->CONTENT->NETWORKS as $network) {
               if (((isset($network->VIRTUALDEV)) AND ($network->VIRTUALDEV != '1'))
                       OR (!isset($network->VIRTUALDEV))){
                  if ((isset($network->MACADDR)) AND (!empty($network->MACADDR))) {
                     $input['mac'][] = addslashes_deep((string)$network->MACADDR);
                  }
               }
            }
         }
         if ((isset($xml->CONTENT->HARDWARE->WINPRODKEY)) 
               AND (!empty($xml->CONTENT->HARDWARE->WINPRODKEY))) {
            $input['mskey'] = addslashes_deep((string)$xml->CONTENT->HARDWARE->WINPRODKEY);
         }
         if ((isset($xml->CONTENT->HARDWARE->OSNAME)) 
               AND (!empty($xml->CONTENT->HARDWARE->OSNAME))) {
            $input['osname'] = addslashes_deep((string)$xml->CONTENT->HARDWARE->OSNAME);

         }
         if ((isset($xml->CONTENT->BIOS->SMODEL)) AND (!empty($xml->CONTENT->BIOS->SMODEL))) {
            $input['model'] = addslashes_deep((string)$xml->CONTENT->BIOS->SMODEL);
         }
         if (isset($xml->CONTENT->STORAGES)) {
            foreach($xml->CONTENT->STORAGES as $storage) {
               if ((isset($storage->SERIALNUMBER)) AND (!empty($storage->SERIALNUMBER))) {
                  $input['partitionserial'][] = addslashes_deep((string)$storage->SERIALNUMBER);
               }
            }
         }
         if (isset($xml->CONTENT->DRIVES)) {
            foreach($xml->CONTENT->DRIVES as $drive) {
               if ((isset($drive->SERIAL)) AND (!empty($drive->SERIAL))) {
                  $input['hdserial'][] = addslashes_deep((string)$drive->SERIAL);
               }
            }
         }
         if ((isset($xml->CONTENT->ACCOUNTINFO->KEYNAME)) AND ($xml->CONTENT->ACCOUNTINFO->KEYNAME == 'TAG')) {
            if (isset($xml->CONTENT->ACCOUNTINFO->KEYVALUE)) {
               $input['tag'] = addslashes_deep((string)$xml->CONTENT->ACCOUNTINFO->KEYVALUE);
            }
         }
         if ((isset($xml->CONTENT->HARDWARE->NAME)) AND (!empty($xml->CONTENT->HARDWARE->NAME))) {
            $input['name'] = addslashes_deep((string)$xml->CONTENT->HARDWARE->NAME);
         }
         $input['itemtype'] = "Computer";
      $_SESSION['plugin_fusioninventory_classrulepassed'] = "PluginFusinvinventoryInventory";
      $rule = new PluginFusioninventoryRuleImportEquipmentCollection();
      $data = array();
      $data = $rule->processAllRules($input, array());
      if (isset($data['_no_rule_matches']) AND ($data['_no_rule_matches'] == '1')) {
         $this->rulepassed(0, "Computer");
      }
      PluginFusioninventoryConfig::logIfExtradebug("pluginFusioninventory-rules", 
                                                   print_r($data, true));
   }
   


   /**
   * If rule have found computer or rule give to create computer
   *
   * @param $items_id integer id of the computer found (or 0 if must be created)
   * @param $itemtype value Computer type here
   *
   * @return nothing
   *
   **/
   function rulepassed($items_id, $itemtype) {
      PluginFusioninventoryConfig::logIfExtradebug("pluginFusioninventory-rules", 
                                                   "Rule passed : ".$items_id.", ".$itemtype."\n");
      $xml = simplexml_load_string($_SESSION['SOURCEXML'],'SimpleXMLElement', LIBXML_NOCDATA);

      if ($itemtype == 'Computer') {
         $PluginFusinvinventoryLib = new PluginFusinvinventoryLib();
         $Computer = new Computer();

         // ** Get entity with rules
            $input_rules = array();
            if ((isset($xml->CONTENT->BIOS->SSN)) AND (!empty($xml->CONTENT->BIOS->SSN))) {
               $input_rules['serialnumber'] = addslashes_deep((string)$xml->CONTENT->BIOS->SSN);
            }
            if ((isset($xml->CONTENT->HARDWARE->NAME)) AND (!empty($xml->CONTENT->HARDWARE->NAME))) {
               $input_rules['name'] = addslashes_deep((string)$xml->CONTENT->HARDWARE->NAME);
            }
            if (isset($xml->CONTENT->NETWORKS)) {
               foreach($xml->CONTENT->NETWORKS as $network) {
                  if ((isset($network->IPADDRESS)) AND (!empty($network->IPADDRESS))) {
                     if ((string)$network->IPADDRESS != '127.0.0.1') {
                        $input_rules['ip'][] = addslashes_deep((string)$network->IPADDRESS);
                     }
                  }
                  if ((isset($network->IPSUBNET)) AND (!empty($network->IPSUBNET))) {
                     $input_rules['subnet'][] = addslashes_deep((string)$network->IPSUBNET);
                  }
               }
            }
            if ((isset($xml->CONTENT->HARDWARE->WORKGROUP)) AND (!empty($xml->CONTENT->HARDWARE->WORKGROUP))) {
               $input_rules['domain'] = addslashes_deep((string)$xml->CONTENT->HARDWARE->WORKGROUP);
            }
            if ((isset($xml->CONTENT->ACCOUNTINFO->KEYNAME)) 
                  AND ($xml->CONTENT->ACCOUNTINFO->KEYNAME == 'TAG')) {
               if (isset($xml->CONTENT->ACCOUNTINFO->KEYVALUE)) {
                  $input_rules['tag'] = addslashes_deep((string)$xml->CONTENT->ACCOUNTINFO->KEYVALUE);
               }
            }

            $ruleEntity = new PluginFusinvinventoryRuleEntityCollection();
            $dataEntity = array ();
            $dataEntity = $ruleEntity->processAllRules($input_rules, array());
            
            if (isset($dataEntity['entities_id'])) {
               $_SESSION["plugin_fusinvinventory_entity"] = $dataEntity['entities_id'];
            } else {
               $_SESSION["plugin_fusinvinventory_entity"] = "N/A";
            }
            

          PluginFusioninventoryConfig::logIfExtradebug("pluginFusinvinventory-entityrules", 
                                                   print_r($dataEntity, true));

         if ($items_id == '0') {
            if ($_SESSION["plugin_fusinvinventory_entity"] == NOT_AVAILABLE) {
               $_SESSION["plugin_fusinvinventory_entity"] = 0;
            }
            $input = array();
            $input['date_mod'] = date("Y-m-d H:i:s");
            $input['entities_id'] = $_SESSION["plugin_fusinvinventory_entity"];
            if (isset($dataEntity['locations_id'])) {
               $input["locations_id"] = $dataEntity['locations_id'];
            }

            self::addDefaultStateIfNeeded($input, false);
            $items_id = $Computer->add($input);
            $PluginFusinvinventoryLib->startAction($xml, $items_id, '1');
         } else {
            $PluginFusinvinventoryLib->startAction($xml, $items_id, '0');
         }
      } else if ($itemtype == 'PluginFusioninventoryUnknownDevice') {
         // Write XML file
         if (isset($_SESSION['SOURCEXML'])) {
            PluginFusioninventoryUnknownDevice::writeXML($items_id, $_SESSION['SOURCEXML']);
         }
      }
   }

   static function addDefaultStateIfNeeded(&$input, $check_management = false, $management_value = 0) {
      $config = new PluginFusioninventoryConfig();
      $state = $config->getValue($_SESSION["plugin_fusinvinventory_moduleid"], "states_id_default");
      if ($state) {
         if (!$check_management || ($check_management && !$management_value)) {
            $input['states_id'] = $state;
         
         }
      
      }
   }
   

   /**
   * Create GLPI existant computer (never in lib) in Lib FusionInventory
   *
   * @param $items_id integer id of the computer
   * @param $internal_id value uniq id in internal lib
   *
   * @return nothing
   *
   **/
   function createMachineInLib($items_id, $internal_id) {

      $NetworkPort = new NetworkPort();
      $Computer = new Computer();
      $a_sectionsinfos = array();

      $Computer->getFromDB($items_id);
      $datas = $Computer->fields;

      $xml = new SimpleXMLElement("<?xml version='1.0' encoding='UTF-8'?><REQUEST></REQUEST>");
      $xml_content = $xml->addChild('IMPORT', 'GLPI');
      $xml_content = $xml->addChild('CONTENT');

      // ** NETWORKS
      $a_networkport = $NetworkPort->find("`items_id`='".$items_id."' AND `itemtype`='Computer' ");
      foreach ($a_networkport as $networkport_id => $networkport_data) {
         $a_sectionsinfos[] = "NETWORKS/".$networkport_id;
         $xml_networks = $xml_content->addChild("NETWORKS");
         $xml_networks->addChild("MACADDR", $networkport_data['mac']);
         $xml_networks->addChild("IPADDRESS", $networkport_data['ip']);
         $xml_networks->addChild("IPMASK", $networkport_data['netmask']);
         $xml_networks->addChild("IPSUBNET", $networkport_data['subnet']);
         $xml_networks->addChild("IPGATEWAY", $networkport_data['gateway']);
         $xml_networks->addChild("DESCRIPTION", $networkport_data['name']);
         $network_type = Dropdown::getDropdownName('glpi_networkinterfaces', 
                                                   $networkport_data['networkinterfaces_id']);
         if ($network_type != "&nbsp;") {
            $xml_networks->addChild("TYPE", $network_type);
         }
      }

      // ** BIOS
      $xml_bios = $xml_content->addChild("BIOS");
      $a_sectionsinfos[] = "BIOS/".$items_id;
      $xml_bios->addChild("SSN", $datas['serial']);
      $manufacturer = Dropdown::getDropdownName(getTableForItemType('Manufacturer'), 
                                                $datas['manufacturers_id']);
      if ($manufacturer != "&nbsp;") {
         $xml_bios->addChild("SMANUFACTURER", $manufacturer);
      }
      $model = Dropdown::getDropdownName(getTableForItemType('ComputerModel'), 
                                         $datas['computermodels_id']);
      if ($model != "&nbsp;") {
         $xml_bios->addChild("SMODEL", $model);
      }
      $type = Dropdown::getDropdownName(getTableForItemType('ComputerType'), 
                                        $datas['computertypes_id']);
      if ($type != "&nbsp;") {
         $xml_bios->addChild("TYPE", $type);
      }

      // ** HARDWARE
      $xml_hardware = $xml_content->addChild("HARDWARE");
      $a_sectionsinfos[] = "HARDWARE/".$items_id;
      $xml_hardware->addChild("NAME", $datas['name']);
      $osname = Dropdown::getDropdownName(getTableForItemType('OperatingSystem'), 
                                          $datas['operatingsystems_id']);
      if ($osname != "&nbsp;") {
         $xml_bios->addChild("OSNAME", $osname);
      }
      $osversion = Dropdown::getDropdownName(getTableForItemType('OperatingSystemVersion'), 
                                             $datas['operatingsystemversions_id']);
      if ($osversion != "&nbsp;") {
         $xml_bios->addChild("OSVERSION", $osversion);
      }
      $xml_hardware->addChild("WINPRODID", $datas['os_licenseid']);
      $xml_hardware->addChild("WINPRODKEY", $datas['os_license_number']);
      $workgroup = Dropdown::getDropdownName(getTableForItemType('Domain'), $datas['domains_id']);
      if ($workgroup != "&nbsp;") {
         $xml_bios->addChild("WORKGROUP", $workgroup);
      }
      $xml_hardware->addChild("DESCRIPTION", $datas['comment']);

      // ** CONTROLLERS
      $CompDeviceControl = new Computer_Device('DeviceControl');
      $DeviceControl = new DeviceControl();
      $a_deviceControl = $CompDeviceControl->find("`computers_id`='".$items_id."' ");
      foreach ($a_deviceControl as $deviceControl_id => $deviceControl_data) {
         $a_sectionsinfos[] = "CONTROLLERS/".$deviceControl_id;
         $xml_controller = $xml_content->addChild("CONTROLLERS");
         $DeviceControl->getFromDB($deviceControl_data['devicecontrols_id']);
         $xml_controller->addChild("CAPTION", $DeviceControl->fields['designation']);
         $manufacturer = Dropdown::getDropdownName(getTableForItemType('Manufacturer'), 
                                                   $DeviceControl->fields['manufacturers_id']);
         if ($manufacturer != "&nbsp;") {
            $xml_controller->addChild("MANUFACTURER", $manufacturer);
         }
         $xml_controller->addChild("NAME", $DeviceControl->fields['designation']);
      }


      // ** CPUS
      $CompDeviceProcessor = new Computer_Device('DeviceProcessor');
      $DeviceProcessor = new DeviceProcessor();
      $a_deviceProcessor = $CompDeviceProcessor->find("`computers_id`='".$items_id."' ");
      foreach ($a_deviceProcessor as $deviceProcessor_id => $deviceProcessor_data) {
         $a_sectionsinfos[] = "CPUS/".$deviceProcessor_id;
         $xml_cpu = $xml_content->addChild("CPUS");
         $DeviceProcessor->getFromDB($deviceProcessor_data['deviceprocessors_id']);
         $xml_cpu->addChild("NAME", $DeviceProcessor->fields['designation']);
         $xml_cpu->addChild("SPEED", $deviceProcessor_data['specificity']);
         $manufacturer = Dropdown::getDropdownName(getTableForItemType('Manufacturer'), 
                                                   $DeviceProcessor->fields['manufacturers_id']);
         if ($manufacturer != "&nbsp;") {
            $xml_cpu->addChild("MANUFACTURER", $manufacturer);
         }
      }
      
      // ** STORAGE
      $CompDeviceDrive = new Computer_Device('DeviceDrive');
      $DeviceDrive = new DeviceDrive();
      $a_deviceDrive = $CompDeviceDrive->find("`computers_id`='".$items_id."' ");
      foreach ($a_deviceDrive as $deviceDrive_id => $deviceDrive_data) {
         $a_sectionsinfos[] = "STORAGES/d".$deviceDrive_id;
         $xml_storage = $xml_content->addChild("STORAGES");
         $DeviceDrive->getFromDB($deviceDrive_data['devicedrives_id']);
         $xml_storage->addChild("NAME", $DeviceDrive->fields['designation']);
         $xml_storage->addChild("MODEL", $DeviceDrive->fields['designation']);
         $manufacturer = Dropdown::getDropdownName(getTableForItemType('Manufacturer'), 
                                                   $DeviceDrive->fields['manufacturers_id']);
         if ($manufacturer != "&nbsp;") {
            $xml_storage->addChild("MANUFACTURER", $manufacturer);
         }
         $interface = Dropdown::getDropdownName(getTableForItemType('InterfaceType'), 
                                                $DeviceDrive->fields['interfacetypes_id']);
         if ($interface != "&nbsp;") {
            $xml_storage->addChild("INTERFACE", $interface);
         }
      }
      $CompDeviceHardDrive = new Computer_Device('DeviceHardDrive');
      $DeviceHardDrive = new DeviceHardDrive();
      $a_DeviceHardDrive = $CompDeviceHardDrive->find("`computers_id`='".$items_id."' ");
      foreach ($a_DeviceHardDrive as $DeviceHardDrive_id => $DeviceHardDrive_data) {
         $a_sectionsinfos[] = "STORAGES/".$DeviceHardDrive_id;
         $xml_storage = $xml_content->addChild("STORAGES");
         $DeviceHardDrive->getFromDB($DeviceHardDrive_data['deviceharddrives_id']);
         $xml_storage->addChild("NAME", $DeviceHardDrive->fields['designation']);
         $xml_storage->addChild("MODEL", $DeviceHardDrive->fields['designation']);
         $manufacturer = Dropdown::getDropdownName(getTableForItemType('Manufacturer'), 
                                                   $DeviceHardDrive->fields['manufacturers_id']);
         if ($manufacturer != "&nbsp;") {
            $xml_storage->addChild("MANUFACTURER", $manufacturer);
         }
         $interface = Dropdown::getDropdownName(getTableForItemType('InterfaceType'), 
                                                $DeviceHardDrive->fields['interfacetypes_id']);
         if ($interface != "&nbsp;") {
            $xml_storage->addChild("INTERFACE", $interface);
         }
      }

      // ** DRIVES
      $ComputerDisk = new ComputerDisk;
      $a_disk = $ComputerDisk->find("`computers_id`='".$items_id."' ");
      foreach ($a_disk as $disk_id => $disk_data) {
         $a_sectionsinfos[] = "DRIVES/".$disk_id;
         $xml_drive = $xml_content->addChild("DRIVES");
         $xml_drive->addChild("LABEL", $disk_data['name']);
         $xml_drive->addChild("VOLUMN", $disk_data['device']);
         $xml_drive->addChild("MOUNTPOINT", $disk_data['mountpoint']);
         $filesystem = Dropdown::importExternal('Filesystem', $disk_data['filesystems_id']);
         if ($filesystem != "&nbsp;") {
            $xml_drive->addChild("FILESYSTEM", $filesystem);
         }
         $xml_drive->addChild("TOTAL", $disk_data['totalsize']);
         $xml_drive->addChild("FREE", $disk_data['freesize']);
      }


      // ** MEMORIES
      $CompDeviceMemory = new Computer_Device('DeviceMemory');
      $DeviceMemory = new DeviceMemory();
      $a_deviceMemory = $CompDeviceMemory->find("`computers_id`='".$items_id."' ");
      foreach ($a_deviceMemory as $deviceMemory_id => $deviceMemory_data) {
         $a_sectionsinfos[] = "MEMORIES/".$deviceMemory_id;
         $xml_memory = $xml_content->addChild("MEMORIES");
         $DeviceMemory->getFromDB($deviceMemory_data['devicememories_id']);
         $xml_memory->addChild("DESCRIPTION", $DeviceMemory->fields['designation']);
         $xml_memory->addChild("CAPACITY", $deviceMemory_data['specificity']);
         $xml_memory->addChild("SPEED", $DeviceMemory->fields['frequence']);
         $type = Dropdown::getDropdownName(getTableForItemType('DeviceMemoryType'), 
                                           $DeviceMemory->fields['devicememorytypes_id']);
         if ($type != "&nbsp;") {
            $xml_memory->addChild("TYPE", $type);
         }
      }


      // ** MONITORS
      $Monitor = new Monitor();
      $Computer_Item = new Computer_Item();
      $a_ComputerMonitor = $Computer_Item->find("`computers_id`='".$items_id.
                                                "' AND 'itemtype' = 'Monitor'");
      foreach ($a_ComputerMonitor as $ComputerMonitor_id => $ComputerMonitor_data) {
         $a_sectionsinfos[] = "MONITORS/".$ComputerMonitor_id;
         $xml_monitor = $xml_content->addChild("MONITORS");
         $Monitor->getFromDB($ComputerMonitor_data['items_id']);
         $xml_monitor->addChild("CAPTION", $Monitor->fields['name']);
         $manufacturer = Dropdown::getDropdownName(getTableForItemType('Manufacturer'), 
                                                   $Monitor->fields['manufacturers_id']);
         if ($manufacturer != "&nbsp;") {
            $xml_monitor->addChild("MANUFACTURER", $manufacturer);
         }
         $xml_monitor->addChild("SERIAL", $Monitor->fields['serial']);
         $xml_monitor->addChild("DESCRIPTION", $Monitor->fields['comment']);
      }


      // ** PRINTERS
      $Printer = new Printer();
      $Computer_Item = new Computer_Item();
      $a_ComputerPrinter = $Computer_Item->find("`computers_id`='".$items_id.
                                                "' AND 'itemtype' = 'Printer'");
      foreach ($a_ComputerPrinter as $ComputerPrinter_id => $ComputerPrinter_data) {
         $a_sectionsinfos[] = "PRINTERS/".$ComputerPrinter_id;
         $xml_printer = $xml_content->addChild("PRINTERS");
         $Printer->getFromDB($ComputerPrinter_data['items_id']);
         $xml_printer->addChild("NAME", $Printer->fields['name']);
         $xml_printer->addChild("SERIAL", $Printer->fields['serial']);
         if ($Printer->fields['have_usb'] == "1") {
            $xml_printer->addChild("PORT", 'USB');
         }
      }
      

      // ** SOFTWARE
      $Computer_SoftwareVersion = new Computer_SoftwareVersion();
      $SoftwareVersion = new SoftwareVersion();
      $Software = new Software();
      $a_softwareVersion = $Computer_SoftwareVersion->find("`computers_id`='".$items_id."' ");
      foreach ($a_softwareVersion as $softwareversion_id => $softwareversion_data) {
         $SoftwareVersion->getFromDB($softwareversion_data['softwareversions_id']);
         $Software->getFromDB($SoftwareVersion->fields['softwares_id']);
         $a_sectionsinfos[] = "SOFTWARES/".$softwareversion_id;
         $xml_software = $xml_content->addChild("SOFTWARES");
         $xml_software->addChild("NAME", $Software->fields['name']);
         $xml_software->addChild("VERSION", $SoftwareVersion->fields['name']);
         $manufacturer = Dropdown::getDropdownName(getTableForItemType('Manufacturer'), 
                                                   $Software->fields['manufacturers_id']);
         if ($manufacturer != "&nbsp;") {
            $xml_software->addChild("PUBLISHER", $manufacturer);
         }
      }

      
      // ** SOUNDS
      $CompDeviceSoundCard = new Computer_Device('DeviceSoundCard');
      $DeviceSoundCard = new DeviceSoundCard();
      $a_deviceSoundCard = $CompDeviceSoundCard->find("`computers_id`='".$items_id."' ");
      foreach ($a_deviceSoundCard as $deviceSoundCard_id => $deviceSoundCard_data) {
         $a_sectionsinfos[] = "SOUNDS/".$deviceSoundCard_id;
         $xml_sound = $xml_content->addChild("SOUNDS");
         $DeviceSoundCard->getFromDB($deviceSoundCard_data['devicesoundcards_id']);
         $xml_sound->addChild("NAME", $DeviceSoundCard->fields['designation']);
         $xml_sound->addChild("DESCRIPTION", $DeviceSoundCard->fields['comment']);
         $manufacturer = Dropdown::getDropdownName(getTableForItemType('Manufacturer'), 
                                                   $DeviceSoundCard->fields['manufacturers_id']);
         if ($manufacturer != "&nbsp;") {
            $xml_sound->addChild("MANUFACTURER", $manufacturer);
         }
      }
     

      // ** VIDEOS
      $CompDeviceGraphicCard = new Computer_Device('DeviceGraphicCard');
      $DeviceGraphicCard = new DeviceGraphicCard();
      $a_deviceGraphicCard = $CompDeviceGraphicCard->find("`computers_id`='".$items_id."' ");
      foreach ($a_deviceGraphicCard as $deviceGraphicCard_id => $deviceGraphicCard_data) {
         $a_sectionsinfos[] = "VIDEOS/".$deviceGraphicCard_id;
         $xml_video = $xml_content->addChild("VIDEOS");
         $DeviceGraphicCard->getFromDB($deviceGraphicCard_data['devicegraphiccards_id']);
         $xml_video->addChild("NAME", $DeviceGraphicCard->fields['designation']);
         $xml_video->addChild("MEMORY", $deviceGraphicCard_data['specificity']);
      }

      // ** VIRTUALMACHINES
      $ComputerVirtualMachine = new ComputerVirtualMachine();
      $a_VirtualMachines = $ComputerVirtualMachine->find("`computers_id`='".$items_id."' ");
      foreach ($a_VirtualMachines as $VirtualMachines_id=>$VirtualMachines_data) {
         $a_sectionsinfos[] = "VIRTUALMACHINES/".$VirtualMachines_id;
         $xml_virtualmachine = $xml_content->addChild("VIRTUALMACHINES");
         $xml_virtualmachine->addChild("MEMORY", $VirtualMachines_data['ram']."MB");
         $xml_virtualmachine->addChild("NAME", $VirtualMachines_data['name']);

         $xml_virtualmachine->addChild("STATUS", 
                                       Dropdown::getDropdownName("glpi_virtualmachinestates",
                                                                 $VirtualMachines_data['virtualmachinestates_id']));
         $xml_virtualmachine->addChild("SUBSYSTEM", 
                                       Dropdown::getDropdownName("glpi_virtualmachinesystems",
                                                                 $VirtualMachines_data['virtualmachinesystems_id']));
         $xml_virtualmachine->addChild("UUID", $VirtualMachines_data['uuid']);
         $xml_virtualmachine->addChild("VCPU", $VirtualMachines_data['vcpu']);
         $xml_virtualmachine->addChild("VMTYPE", 
                                       Dropdown::getDropdownName("glpi_virtualmachinetypes",
                                                                 $VirtualMachines_data['virtualmachinetypes_id']));
      }
      
      // ** USBDEVICES (PERIPHERALS)
      $Peripheral = new Peripheral();
      $Computer_Item = new Computer_Item();
      $a_ComputerPeripheral = $Computer_Item->find("`computers_id`='".$items_id."' AND `itemtype`='Peripheral'");
      foreach ($a_ComputerPeripheral as $ComputerPeripheral_id => $ComputerPeripheral_data) {
         $a_sectionsinfos[] = "USBDEVICES/".$ComputerPeripheral_id;
         $xml_peripheral = $xml_content->addChild("USBDEVICES");
         $Peripheral->getFromDB($ComputerPeripheral_data['items_id']);
         $xml_peripheral->addChild("NAME", $Peripheral->fields['name']);
         if ($Peripheral->fields['serial'] != "") {
            $xml_peripheral->addChild("SERIAL", $Peripheral->fields['serial']);
         }
      }
      
      $PluginFusinvinventoryLib = new PluginFusinvinventoryLib();
      $PluginFusinvinventoryLib->addLibMachineFromGLPI($items_id, $internal_id, $xml, $a_sectionsinfos);
   }
}

?>