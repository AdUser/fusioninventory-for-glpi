<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2011 by the FusionInventory Development Team.

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
   @copyright Copyright (c) 2010-2011 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010
 
   ------------------------------------------------------------------------
 */

class PluginFusinvsnmpDiscovery extends CommonDBTM {

   static function criteria($p_criteria, $type=0) {
      global $DB;

      $ptc = new PluginFusioninventoryConfig;

      $a_criteria = array();

      $CountCriteria1 = 0;
      $CountCriteria2 = 0;
      $arrayc = array();
      if ($type == '0') {
         $arrayc = array('ip', 'name', 'serial', 'macaddr');
         $CountCriteria1 = $ptc->getValue('criteria1_ip');
         $CountCriteria2 = $ptc->getValue('criteria2_ip');
      } else {
         $arrayc = array('name', 'serial', 'macaddr');
      }
      $CountCriteria1 +=  $ptc->getValue('criteria1_name')
                        + $ptc->getValue('criteria1_serial')
                        + $ptc->getValue('criteria1_macaddr');

      $CountCriteria2 +=  $ptc->getValue('criteria2_name')
                        + $ptc->getValue('criteria2_serial')
                        + $ptc->getValue('criteria2_macaddr');

      foreach ($arrayc as $criteria) {
         if (!isset($p_criteria[$criteria])) {
            $p_criteria[$criteria] = '';
         }
      }

      switch ($CountCriteria1) {
         case 0:
            return false;
            break;

         case 1:
            foreach ($arrayc as $criteria) {
               if ($ptc->getValue('criteria1_'.$criteria) == "1"){
                  if ($p_criteria[$criteria] == "") {
                     // Go to criteria2
                  } else {
                     unset($a_criteria);
                     $a_criteria[$criteria] = $p_criteria[$criteria];
                     $r_find = PluginFusinvsnmpDiscovery::find_device($a_criteria, $type);
                     if ($r_find) {
                        return $r_find;
                     } else {
                        return false;
                     }
                  }
               }
            }
            break;

         default: // > 1
            $i = 0;
            unset($a_criteria);
            foreach ($arrayc as $criteria) {
               if ($ptc->getValue('criteria1_'.$criteria) == "1"){
                  $a_criteria[$criteria] = $p_criteria[$criteria];
                  if ($p_criteria[$criteria] != "") {
                     $i++;
                  }
               }
            }
            if ($i == 0) {
               // Go to criteria2
            } else {
               $r_find = PluginFusinvsnmpDiscovery::find_device($a_criteria, $type);
               if ($r_find) {
                  return $r_find;
               } else {
                  unset($a_criteria);
                  foreach ($arrayc as $criteria) {
                     if ($ptc->getValue('criteria1_'.$criteria) == "1"){
                        if ($p_criteria[$criteria] != "") {
                           $a_criteria[$criteria] = $p_criteria[$criteria];
                        }
                     }
                  }
                  $r_find = PluginFusinvsnmpDiscovery::find_device($a_criteria, $type);
                  if ($r_find) {
                     return $r_find;
                  }
               }
            }
            break;
      }

      switch ($CountCriteria2) {
         case 0:
            return false;
            break;

         case 1:
            foreach ($arrayc as $criteria) {
               if ($ptc->getValue('criteria2_'.$criteria) == "1"){
                  if ($p_criteria[$criteria] == "") {
                     return false;
                  } else {
                     unset($a_criteria);
                     $a_criteria[$criteria] = $p_criteria[$criteria];
                     $r_find = PluginFusinvsnmpDiscovery::find_device($a_criteria, $type);
                     if ($r_find) {
                        return $r_find;
                     } else {
                        return false;
                     }
                  }
               }
            }
            break;

         default: // > 1
            $i = 0;
            unset($a_criteria);
            foreach ($arrayc as $criteria) {
               if ($ptc->getValue('criteria2_'.$criteria) == "1"){
                  $a_criteria[$criteria] = $p_criteria[$criteria];
                  if ($p_criteria[$criteria] != "") {
                     $i++;
                  }
               }
            }
            if ($i == 0) {
               return false;
            } else {
               $r_find = PluginFusinvsnmpDiscovery::find_device($a_criteria, $type);
               if ($r_find) {
                  return $r_find;
               } else {
                  unset($a_criteria);
                  foreach ($arrayc as $criteria) {
                     if ($ptc->getValue('criteria2_'.$criteria) == "1"){
                        if ($p_criteria[$criteria] != "") {
                           $a_criteria[$criteria] = $p_criteria[$criteria];
                        }
                     }
                  }
                  $r_find = PluginFusinvsnmpDiscovery::find_device($a_criteria, $type);
                  if ($r_find) {
                     return $r_find;
                  } else {
                     return false;
                  }
               }
            }
            break;
      }
      return false;
   }


   
   static function find_device($a_criteria, $p_type=0) {
      global $DB,$CFG_GLPI;

      $ci = new commonitem;

      $a_types = array(COMPUTER_TYPE, NETWORKING_TYPE, PRINTER_TYPE, PERIPHERAL_TYPE,
                  PHONE_TYPE, 'PluginFusioninventoryUnknownDevice');
      if ($p_type != '0') {
         $a_types = array($p_type);
      }
      $condition = "";
      $select = "";
      $condition_unknown = "";
      $select_unknown = "";
      foreach ($a_criteria as $criteria=>$value) {
         switch ($criteria) {

            case 'ip':
               $condition .= "AND `ip`='".$value."' ";
               $select .= ", ip";
               $condition_unknown .= "AND `glpi_networkports`.`ip`='".$value."' ";
               $select_unknown .= ", `glpi_networkports`.`ip`";
               break;

            case 'macaddr':
               $condition .= "AND `mac`='".$value."' ";
               $select .= ", mac";
               $condition_unknown .= "AND `glpi_networkports`.`mac`='".$value."' ";
               $select_unknown .= ", `glpi_networkports`.`mac`";
               break;

            case 'name':
               $condition .= "AND `name`='".$value."' ";
               $select .= ", name";
               $condition_unknown .= "AND `name`='".$value."' ";
               $select_unknown .= ", name";
               break;

            case 'serial':
               $condition .= "AND `serial`='".$value."' ";
               $select .= ", serial";
               $condition_unknown .= "AND `serial`='".$value."' ";
               $select_unknown .= ", serial";
               break;
         }
      }

      foreach ($a_types as $type) {
         $ci->setType($type,true);
         $query = "";
         if ($type == 'PluginFusioninventoryUnknownDevice') {
            $query = "SELECT ".$ci->obj->getTable().".id ".$select_unknown." FROM ".$ci->obj->getTable();
         } else {
            $query = "SELECT ".$ci->obj->getTable().".id ".$select." FROM ".$ci->obj->getTable();
         }
         if ($ci->obj->getTable() != "glpi_networkequipments") {
            $query .= " LEFT JOIN glpi_networkports on items_id=".$ci->obj->getTable().".id AND itemtype=".$type;
         }
         if ($type == 'PluginFusioninventoryUnknownDevice') {
            $query .= " WHERE is_deleted=0 ".$condition_unknown;
         } else {
            $query .= " WHERE is_deleted=0 ".$condition;
         }
         $result = $DB->query($query);
         if($DB->numrows($result) > 0) {
            $data = $DB->fetch_assoc($result);
            if ($p_type == '0') {
               return $data['id'].'||'.$type;
            } else {
               return $data['id'];
            }
         }
      }

      // Search in 'PluginFusioninventoryUnknownDevice' when ip in not empty (so when it's a switch)
      $ci->setType('PluginFusioninventoryUnknownDevice',true);
      $query = "SELECT ".$ci->obj->getTable().".id ".$select." FROM ".$ci->obj->getTable();
      $query .= " WHERE is_deleted=0 ".$condition;
      $result = $DB->query($query);
      if($DB->numrows($result) > 0) {
         $data = $DB->fetch_assoc($result);
         if ($p_type == '0') {
            return $data['id'].'||'.$type;
         } else {
            return $data['id'];
         }
      }
      return false;
   }
}

?>