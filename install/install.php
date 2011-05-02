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

function pluginFusinvsnmpInstall() {
   global $DB,$LANG;

   ini_set("max_execution_time", "0");

   // Get informations of plugin
   $a_plugin = plugin_version_fusinvsnmp();

   include (GLPI_ROOT . "/plugins/fusinvsnmp/install/update.php");
   $version_detected = pluginfusinvsnmpGetCurrentVersion($a_plugin['version']);
   if ((isset($version_detected)) AND ($version_detected != $a_plugin['version'])) {
      // Update
      pluginFusinvsnmpUpdate($version_detected);
   } else {
      // Installation

      // Create database
      $DB_file = GLPI_ROOT ."/plugins/fusinvsnmp/install/mysql/plugin_fusinvsnmp-".$a_plugin['version']."-empty.sql";
      $DBf_handle = fopen($DB_file, "rt");
      $sql_query = fread($DBf_handle, filesize($DB_file));
      fclose($DBf_handle);
      foreach ( explode(";\n", $sql_query) as $sql_line) {
         if (get_magic_quotes_runtime())
            $sql_line=stripslashes_deep($sql_line);
         if (!empty($sql_line))
            $DB->query($sql_line)/* or die($DB->error())*/;
      }

      // Create folder in GLPI_PLUGIN_DOC_DIR
      if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/'.$a_plugin['shortname'])) {
         mkdir(GLPI_PLUGIN_DOC_DIR.'/'.$a_plugin['shortname']);
         mkdir(GLPI_PLUGIN_DOC_DIR.'/'.$a_plugin['shortname'].'/tmp');
      }

      $configLogField = new PluginFusinvsnmpConfigLogField();
      $configLogField->initConfig();

      // Import models
      $importexport = new PluginFusinvsnmpImportExport();

      $nb = 0;
      foreach (glob(GLPI_ROOT.'/plugins/fusinvsnmp/models/*.xml') as $file) {
         $nb++;
      }
      $i = 0;
      echo "<table class='tab_cadre_fixe'>";
      echo "<tr class='tab_bg_1'>";
      echo "<th align='center'>";
      echo "Importing SNMP models, please wait...";
      echo "</th>";
      echo "</tr>";
      echo "<tr class='tab_bg_1'>";
      echo "<td align='center'>";
      createProgressBar("Importing SNMP models, please wait...");
      foreach (glob(GLPI_ROOT.'/plugins/fusinvsnmp/models/*.xml') as $file) {
         $importexport->import($file,0,1);
         $i++;
         changeProgressBarPosition($i,$nb,"$i / $nb");
      }
      echo "</td>";
      echo "</table>";

      $plugins_id = PluginFusioninventoryModule::getModuleId($a_plugin['shortname']);
      PluginFusioninventoryProfile::initProfile($a_plugin['shortname'], $plugins_id);

      $configSNMP = new PluginFusinvSNMPConfig;
      $configSNMP->initConfigModule();
      // Creation config values
   //      PluginFusioninventoryConfig::add($modules_id, type, value);

      PluginFusioninventoryProfile::changeProfile($plugins_id);
      $PluginFusioninventoryAgentmodule = new PluginFusioninventoryAgentmodule;
      $input = array();
      $input['plugins_id'] = $plugins_id;
      $input['modulename'] = "SNMPQUERY";
      $input['is_active']  = 0;
      $input['exceptions'] = exportArrayToDB(array());
      $PluginFusioninventoryAgentmodule->add($input);

      $input = array();
      $input['plugins_id'] = $plugins_id;
      $input['modulename'] = "NETDISCOVERY";
      $input['is_active']  = 0;
      $input['exceptions'] = exportArrayToDB(array());
      $PluginFusioninventoryAgentmodule->add($input);

      Crontask::Register('PluginFusinvsnmpNetworkPortLog', 'cleannetworkportlogs', (3600 * 24), array('mode'=>2, 'allowmode'=>3, 'logs_lifetime'=>30));
   }
}


function pluginFusinvsnmpUninstall() {
   global $DB;

   // Get informations of plugin
   $a_plugin = plugin_version_fusinvsnmp();

   $PluginFusioninventorySetup = new PluginFusioninventorySetup();

   if (file_exists(GLPI_PLUGIN_DOC_DIR.'/'.$a_plugin['shortname'])) {
      $PluginFusioninventorySetup->rrmdir(GLPI_PLUGIN_DOC_DIR.'/'.$a_plugin['shortname']);
   }

   PluginFusioninventoryProfile::cleanProfile($a_plugin['shortname']);

   $query = "SHOW TABLES;";
   $result=$DB->query($query);
   while ($data=$DB->fetch_array($result)) {
      if (strstr($data[0],"glpi_plugin_".$a_plugin['shortname']."_")){
         $query_delete = "DROP TABLE `".$data[0]."`;";
         $DB->query($query_delete) or die($DB->error());
      }
   }

   $query="DELETE FROM `glpi_displaypreferences`
           WHERE `itemtype` LIKE 'PluginFusinvsnmp%';";
   $DB->query($query) or die($DB->error());
//   $a_netports = $np->find("`itemtype`='PluginFusioninventoryUnknownDevice' ");
//   foreach ($a_netports as $NetworkPort){
//      $np->cleanDBonPurge($NetworkPort['id']);
//      $np->deleteFromDB($NetworkPort['id']);
//   }

   PluginFusioninventoryTask::cleanTasksbyMethod('netdiscovery');
   PluginFusioninventoryTask::cleanTasksbyMethod('snmpquery');

   $plugins_id = PluginFusioninventoryModule::getModuleId('fusinvsnmp');

   $PluginFusioninventoryAgentmodule = new PluginFusioninventoryAgentmodule;
   $PluginFusioninventoryAgentmodule->deleteModule($plugins_id);


   $config = new PluginFusioninventoryConfig;
   $config->cleanConfig($plugins_id);
   return true;
}

?>