<?php

/*
 * @version $Id$
 ----------------------------------------------------------------------
 FusionInventory
 Coded by the FusionInventory Development Team.

 http://www.fusioninventory.org/   http://forge.fusioninventory.org//
 ----------------------------------------------------------------------

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
 ------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: DURIEUX David
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusinvdeployUninstall extends CommonDBTM {

   static function getTypeName() {
      global $LANG;

      return $LANG['plugin_fusinvdeploy']['package'][15];
   }

   static function showForm($id){
      global $CFG_GLPI;

      if(isset($_POST["glpi_tab"])) {
         switch($_POST["glpi_tab"]){
            case -1 :
               $render = "alluninstall";
               break;
            default:
               $render = "uninstall";
               break;
         }
      }

      // Display front JS
      echo "<table class='deploy_extjs'>
            <thead>
               <tr>
                  <th colspan='2'>
                     ".PluginFusinvdeployCheck::getTypeName()."
                     <a href=\"javascript:showHideDiv('".$render."Check','".$render."checkimg',
                        '".$CFG_GLPI["root_doc"]."/pics/deplier_down.png',
                        '".$CFG_GLPI["root_doc"]."/pics/deplier_up.png')\">
                     <img alt='' name='".$render."checkimg'
                        src='".$CFG_GLPI["root_doc"]."/pics/deplier_up.png'>

                  </th>
               </tr>
            </thead>
            <tbody>
               <tr>
                  <td id='".$render."Check'></td>
               </tr>
            </tbody>
         </table>";

      // Display front JS
      echo "<table class='deploy_extjs'>
            <thead>
               <tr>
                  <th colspan='2'>
                     ".PluginFusinvdeployFile::getTypeName()."
                     <a href=\"javascript:showHideDiv('".$render."File','".$render."fileimg',
                        '".$CFG_GLPI["root_doc"]."/pics/deplier_down.png',
                        '".$CFG_GLPI["root_doc"]."/pics/deplier_up.png')\">
                     <img alt='' name='".$render."fileimg'
                        src='".$CFG_GLPI["root_doc"]."/pics/deplier_up.png'>
                  </th>
               </tr>
            </thead>
            <tbody>
               <tr>
                  <td id='".$render."File'></td>
               </tr>
            </tbody>
         </table>";

      // Display front JS
      echo "<table class='deploy_extjs'>
            <thead>
               <tr>
                  <th colspan='2'>
                     ".PluginFusinvdeployAction::getTypeName()."
                     <a href=\"javascript:showHideDiv('".$render."Action','".$render."actionimg',
                        '".$CFG_GLPI["root_doc"]."/pics/deplier_down.png',
                        '".$CFG_GLPI["root_doc"]."/pics/deplier_up.png')\">
                     <img alt='' name='".$render."actionimg'
                        src='".$CFG_GLPI["root_doc"]."/pics/deplier_up.png'>
                     </a>
                  </th>
               </tr>
            </thead>
            <tbody>
               <tr>
                  <td id='".$render."Action'></td>
               </tr>
            </tbody>
         </table>";

      // Include JS
      require GLPI_ROOT."/plugins/fusinvdeploy/js/package_check.front.php";
      require GLPI_ROOT."/plugins/fusinvdeploy/js/package_file.front.php";
      require GLPI_ROOT."/plugins/fusinvdeploy/js/package_action.front.php";
   }

}

?>
