<?php

/*
 * @version $Id$
 -------------------------------------------------------------------------
 FusionInventory
 Copyright (C) 2003-2010 by the INDEPNET Development Team.

 http://www.fusioninventory.org/   http://forge.fusioninventory.org/
 -------------------------------------------------------------------------

 LICENSE

 This file is part of FusionInventory plugins.

 FusionInventory is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 FusionInventory is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with FusionInventory; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 --------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: DURIEUX David
// Purpose of file:
// ----------------------------------------------------------------------

//include_once ("includes.php");

// Init the hooks of fusinvdeploy
function plugin_init_fusinvdeploy() {
   global $PLUGIN_HOOKS,$CFG_GLPI,$LANG;

   // ##### 1. Stop if fusioninventory not activated #####

   $plugin = new Plugin;
   if (!$plugin->isActivated("fusioninventory")) {
      if (isset($_GET['id'])
         && isset($_GET['action'])
            && strstr($_SERVER['HTTP_REFERER'], "front/plugin.php")) {
         switch ($_GET['action']) {
            case 'activate':
               addMessageAfterRedirect($LANG['plugin_fusinvdeploy']["setup"][17]);
               break;
            case 'uninstall':
               addMessageAfterRedirect($LANG['plugin_fusinvdeploy']["setup"][18]);
               glpi_header($CFG_GLPI["root_doc"]."/front/plugin.php");
               break;
         }
      }
      return false;
   }

   // ##### 2. register classes #####

/*
   Plugin::registerClass('PluginFusinvDeployConfig');
   Plugin::registerClass('PluginFusinvdeployJob');
   Plugin::registerClass('PluginFusinvdeployPackage');
   Plugin::registerClass('PluginFusinvdeployPackage');
   Plugin::registerClass('PluginFusinvdeployInstall');
   Plugin::registerClass('PluginFusinvdeployUninstall');
*/
   // ##### 3. get informations of the plugin #####

   $a_plugin = plugin_version_fusinvdeploy();
   $moduleId = PluginFusioninventoryModule::getModuleId($a_plugin['shortname']);

   // ##### 4. Set in session module_id #####

   $_SESSION["plugin_".$a_plugin['shortname']."_moduleid"] = $moduleId;


   if (!isset($_SESSION['glpi_plugin_fusioninventory']['configuration']['moduletabforms']['fusinvdeploy'][$LANG['plugin_fusinvdeploy']["title"][0]])) {
      $_SESSION['glpi_plugin_fusioninventory']['configuration']['moduletabforms']['fusinvdeploy'][$LANG['plugin_fusinvdeploy']["title"][0]] =
                                             array('class'        => 'PluginFusinvDeployConfig',
                                                   'submitbutton' => 'plugin_fusinvdeploy_config_set',
                                                   'submitmethod' => 'putForm');
   }

   $PLUGIN_HOOKS['change_profile']['fusinvdeploy'] =
      PluginFusioninventoryProfile::changeprofile($moduleId,$a_plugin['shortname']);

   $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['add']['packages'] =
      '../fusinvdeploy/front/package.form.php?add=1';
   $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['search']['packages'] =
      '../fusinvdeploy/front/package.php';
   $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['add']['mirror'] =
      '../fusinvdeploy/front/mirror.form.php?add=1';
   $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['search']['mirror'] =
      '../fusinvdeploy/front/mirror.php';
   $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['add']['task'] =
      '../fusinvdeploy/front/task.form.php?add=1';
   $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['search']['task'] =
      '../fusinvdeploy/front/task.php';
   $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['add']['group'] =
      '../fusinvdeploy/front/group.form.php?add=1';
   $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['search']['group'] =
      '../fusinvdeploy/front/group.php';

   $PLUGIN_HOOKS['add_css']['fusinvdeploy'] = "css/style.css";
}

// Name and Version of the plugin
function plugin_version_fusinvdeploy() {
   return array('name'           => 'FusionInventory Deployment',
                'shortname'      => 'fusinvdeploy',
                'version'        => '0.0.1',
                'author'         => "<a href='http://www.teclib.com'>TECLIB'</a>",
                'homepage'       => 'http://forge.fusioninventory.org/projects/pluginfusinvdeploy',
                'minGlpiVersion' => '0.78' // For compatibility / no install in version < 0.78
   );
}

// Optional : check prerequisites before install : may print errors or add to message after redirect
function plugin_fusinvdeploy_check_prerequisites() {
   global $LANG;
   if (version_compare('0.78',GLPI_VERSION) < 0) {
      $plugin = new Plugin;
      if (!$plugin->isActivated("fusioninventory")) {
         return false;
      }
      return true;
   } else {
      echo $LANG['plugin_fusinvdeploy']["errors"][50];
   }
}

function plugin_fusinvdeploy_check_config() {
   return true;
}
?>
