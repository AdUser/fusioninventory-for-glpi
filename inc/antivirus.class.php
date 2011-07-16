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

class PluginFusinvinventoryAntivirus extends CommonDBTM {
   
   static function getTypeName() {
      global $LANG;

      return $LANG['plugin_fusinvinventory']['antivirus'][0];
   }

   function canCreate() {
      return true;
   }

   function canView() {
      return true;
   }

   function canCancel() {
      return true;
   }

   function canUndo() {
      return true;
   }

   function canValidate() {
      return true;
   }

   
   
   function getSearchOptions() {
      global $LANG;

      $tab = array();
      $tab['common'] = $LANG['common'][32];
      
      $tab[1]['table']         = $this->getTable();
      $tab[1]['field']         = 'version';
      $tab[1]['name']          = "Version";
      $tab[1]['type']          = 'text';
      
      return $tab;
   }
      
   
   
   function addHistory($item){
      global $LANG;
      
      foreach ($item->oldvalues as $field=>$old_value) {
         $changes = array();
         $changes[0] = 0;
         $changes[1] = '';
         $changes[2] = "Antivirus.".$field." : ".$old_value." --> ".$item->fields[$field];
         Log::history($item->fields['computers_id'], 
                     "Computer", 
                     $changes, 
                     'PluginFusinvinventoryAntivirus', 
                     HISTORY_LOG_SIMPLE_MESSAGE);         
      }
   }
   
   
      
   /**
   * Display form for antivirus
   *
   * @param $items_id integer ID of the antivirus
   * @param $options array
   *
   *@return bool true if form is ok
   *
   **/
   function showForm($items_id, $options=array()) {
      global $LANG;

      $a_antivirus = $this->find("`computers_id`='".$items_id."'");
      $antivirusData = array();
      foreach ($a_antivirus as $antivirusData) {

      }

      echo "<table class='tab_cadre_fixe' cellpadding='1'>";
 
      if (count($antivirusData) == '0') {
         echo "<tr>";
         echo "<th>".$LANG['plugin_fusinvinventory']['antivirus'][0];
         echo "</th>";
         echo "</tr>";

         echo "<tr class='tab_bg_1'>";
         echo "<td align='center'><br/><strong>";
         echo $LANG['plugin_fusinvinventory']['antivirus'][1]."<br/>";
         echo "</strong><br/></td>";
         echo "</tr>";
      } else {
         echo "<tr>";
         echo "<th colspan='4'>".$LANG['plugin_fusinvinventory']['antivirus'][0];
         echo "</th>";
         echo "</tr>";

         echo "<tr class='tab_bg_1'>";
         echo "<td>";
         echo $LANG['common'][16]."&nbsp;:";
         echo "</td>";
         echo "<td>";
         echo $antivirusData['name'];
         echo "</td>";
         echo "<td>";
         echo $LANG['common'][60]."&nbsp;:";
         echo "</td>";
         echo "<td>";
         echo Dropdown::getYesNo($antivirusData['is_active']);
         echo "</td>";
         echo "</tr>";

         echo "<tr class='tab_bg_1'>";
         echo "<td>";
         echo $LANG['common'][5]."&nbsp;:";
         echo "</td>";
         echo "<td>";
         echo Dropdown::getDropdownName('glpi_manufacturers', $antivirusData["manufacturers_id"]);
         echo "</td>";
         echo "<td>";
         echo $LANG['plugin_fusinvinventory']['antivirus'][3]."&nbsp;:";
         echo "</td>";
         echo "<td>";
         echo Dropdown::getYesNo($antivirusData['uptodate']);
         echo "</td>";
         echo "</tr>";

         echo "<tr class='tab_bg_1'>";
         echo "<td>";
         echo $LANG['plugin_fusinvinventory']['antivirus'][2]."&nbsp;:";
         echo "</td>";
         echo "<td>";
         echo $antivirusData['version'];
         echo "</td>";
         echo "<td colspan='2'>";
         echo "</td>";
         echo "</tr>";

      }
      echo "</table>";
      return true;
   }



   /**
   * Delete antivirus on computer
   *
   * @param $items_id integer id of the computer
   *
   *@return nothing
   *
   **/
   static function cleanComputer($items_id) {
      $PluginFusinvinventoryAntivirus = new PluginFusinvinventoryAntivirus();
      $a_antivirus = $PluginFusinvinventoryAntivirus->find("`computers_id`='".$items_id."'");
      if (count($a_antivirus) > 0) {
         $input = current($a_antivirus);
         $PluginFusinvinventoryAntivirus->delete($input);
      }
   }
}

?>