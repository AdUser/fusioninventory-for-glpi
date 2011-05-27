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
   Original Author of file: Vincent Mazzoni
   Co-authors of file: David Durieux
   Purpose of file:
   ----------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusioninventorySetup {

   // Uninstallation function
   static function uninstall() {
      global $DB;

      CronTask::Unregister('fusioninventory');

      $PluginFusioninventorySetup = new PluginFusioninventorySetup();

      if (file_exists(GLPI_PLUGIN_DOC_DIR.'/fusioninventory')) {
         $PluginFusioninventorySetup->rrmdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory');
      }

      $query = "SHOW TABLES;";
      $result=$DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         if ((strstr($data[0],"glpi_plugin_fusioninventory_"))
                OR (strstr($data[0], "glpi_dropdown_plugin_fusioninventory"))
                OR (strstr($data[0], "glpi_plugin_tracker"))
                OR (strstr($data[0], "glpi_dropdown_plugin_tracker"))) {

            $query_delete = "DROP TABLE `".$data[0]."`;";
            $DB->query($query_delete) or die($DB->error());
         }
      }

      $query="DELETE FROM `glpi_displaypreferences`
              WHERE `itemtype` LIKE 'PluginFusioninventory%';";
      $DB->query($query) or die($DB->error());

      // Delete rules
      $Rule = new Rule();
      $a_rules = $Rule->find("`sub_type`='PluginFusioninventoryRuleImportEquipment'");
      foreach ($a_rules as $id => $data) {
         $Rule->delete($data);
      }

      return true;
   }

   function rrmdir($dir) {
      $PluginFusioninventorySetup = new PluginFusioninventorySetup();

      if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
          if ($object != "." && $object != "..") {
            if (filetype($dir."/".$object) == "dir") {
               $PluginFusioninventorySetup->rrmdir($dir."/".$object);
            } else {
               unlink($dir."/".$object);
            }
          }
        }
        reset($objects);
        rmdir($dir);
      }
   }


   function initRules() {

      $ranking = 0;
      
     // Create rule for : Computer + serial + uuid
      $rulecollection = new PluginFusioninventoryRuleImportEquipmentCollection();
      $input = array();
      $input['is_active']=1;
      $input['name']='Computer serial + uuid';
      $input['match']='AND';
      $input['sub_type'] = 'PluginFusioninventoryRuleImportEquipment';
      $input['ranking'] = $ranking;
      $rule_id = $rulecollection->add($input);

         // Add criteria
         $rule = $rulecollection->getRuleClass();
         $rulecriteria = new RuleCriteria(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "serial";
         $input['pattern']= 1;
         $input['condition']=10;
         $rulecriteria->add($input);

         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "serial";
         $input['pattern']= 1;
         $input['condition']=8;
         $rulecriteria->add($input);

         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "uuid";
         $input['pattern']= 1;
         $input['condition']=10;
         $rulecriteria->add($input);

         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "uuid";
         $input['pattern']= 1;
         $input['condition']=8;
         $rulecriteria->add($input);

         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "itemtype";
         $input['pattern']= 'Computer';
         $input['condition']=0;
         $rulecriteria->add($input);

         // Add action
         $ruleaction = new RuleAction(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['action_type'] = 'assign';
         $input['field'] = '_fusion';
         $input['value'] = '0';
         $ruleaction->add($input);


     $ranking++;
     // Create rule for : Computer + serial
      $rulecollection = new PluginFusioninventoryRuleImportEquipmentCollection();
      $input = array();
      $input['is_active']=1;
      $input['name']='Computer serial';
      $input['match']='AND';
      $input['sub_type'] = 'PluginFusioninventoryRuleImportEquipment';
      $input['ranking'] = $ranking;
      $rule_id = $rulecollection->add($input);

         // Add criteria
         $rule = $rulecollection->getRuleClass();
         $rulecriteria = new RuleCriteria(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "serial";
         $input['pattern']= 1;
         $input['condition']=10;
         $rulecriteria->add($input);

         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "serial";
         $input['pattern']= 1;
         $input['condition']=8;
         $rulecriteria->add($input);

         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "itemtype";
         $input['pattern']= 'Computer';
         $input['condition']=0;
         $rulecriteria->add($input);

         // Add action
         $ruleaction = new RuleAction(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['action_type'] = 'assign';
         $input['field'] = '_fusion';
         $input['value'] = '0';
         $ruleaction->add($input);

      $ranking++;
      // Create rule for : Computer + uuid
      $rulecollection = new PluginFusioninventoryRuleImportEquipmentCollection();
      $input = array();
      $input['is_active']=1;
      $input['name']='Computer uuid';
      $input['match']='AND';
      $input['sub_type'] = 'PluginFusioninventoryRuleImportEquipment';
      $input['ranking'] = $ranking;
      $rule_id = $rulecollection->add($input);

         // Add criteria
         $rule = $rulecollection->getRuleClass();
         $rulecriteria = new RuleCriteria(get_class($rule));

         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "uuid";
         $input['pattern']= 1;
         $input['condition']=10;
         $rulecriteria->add($input);

         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "uuid";
         $input['pattern']= 1;
         $input['condition']=8;
         $rulecriteria->add($input);

         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "itemtype";
         $input['pattern']= 'Computer';
         $input['condition']=0;
         $rulecriteria->add($input);

         // Add action
         $ruleaction = new RuleAction(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['action_type'] = 'assign';
         $input['field'] = '_fusion';
         $input['value'] = '0';
         $ruleaction->add($input);

     $ranking++;
     // Create rule for : Computer + mac
      $rulecollection = new PluginFusioninventoryRuleImportEquipmentCollection();
      $input = array();
      $input['is_active']=1;
      $input['name']='Computer mac';
      $input['match']='AND';
      $input['sub_type'] = 'PluginFusioninventoryRuleImportEquipment';
      $input['ranking'] = $ranking;
      $rule_id = $rulecollection->add($input);

         // Add criteria
         $rule = $rulecollection->getRuleClass();
         $rulecriteria = new RuleCriteria(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "mac";
         $input['pattern']= 1;
         $input['condition']=10;
         $rulecriteria->add($input);

         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "mac";
         $input['pattern']= 1;
         $input['condition']=8;
         $rulecriteria->add($input);

         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "itemtype";
         $input['pattern']= 'Computer';
         $input['condition']=0;
         $rulecriteria->add($input);

         // Add action
         $ruleaction = new RuleAction(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['action_type'] = 'assign';
         $input['field'] = '_fusion';
         $input['value'] = '0';
         $ruleaction->add($input);


      $ranking++;
      // Create rule for : Computer import
      $rulecollection = new PluginFusioninventoryRuleImportEquipmentCollection();
      $input = array();
      $input['is_active']=1;
      $input['name']='Computer import';
      $input['match']='AND';
      $input['sub_type'] = 'PluginFusioninventoryRuleImportEquipment';
      $input['ranking'] = $ranking;
      $rule_id = $rulecollection->add($input);

         // Add criteria
         $rule = $rulecollection->getRuleClass();
         $rulecriteria = new RuleCriteria(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "itemtype";
         $input['pattern']= 'Computer';
         $input['condition']=0;
         $rulecriteria->add($input);
         
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "name";
         $input['pattern']= 1;
         $input['condition']=8;
         $rulecriteria->add($input);

         // Add action
         $ruleaction = new RuleAction(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['action_type'] = 'assign';
         $input['field'] = '_fusion';
         $input['value'] = '0';
         $ruleaction->add($input);



     $ranking++;
     // Create rule for : Printer + serial
      $rulecollection = new PluginFusioninventoryRuleImportEquipmentCollection();
      $input = array();
      $input['is_active']=1;
      $input['name']='Printer serial';
      $input['match']='AND';
      $input['sub_type'] = 'PluginFusioninventoryRuleImportEquipment';
      $input['ranking'] = $ranking;
      $rule_id = $rulecollection->add($input);

         // Add criteria
         $rule = $rulecollection->getRuleClass();
         $rulecriteria = new RuleCriteria(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "serial";
         $input['pattern']= 1;
         $input['condition']=10;
         $rulecriteria->add($input);

         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "serial";
         $input['pattern']= 1;
         $input['condition']=8;
         $rulecriteria->add($input);

         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "itemtype";
         $input['pattern']= 'Printer';
         $input['condition']=0;
         $rulecriteria->add($input);

         // Add action
         $ruleaction = new RuleAction(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['action_type'] = 'assign';
         $input['field'] = '_fusion';
         $input['value'] = '0';
         $ruleaction->add($input);

      $ranking++;
      // Create rule for : Printer + mac
      $rulecollection = new PluginFusioninventoryRuleImportEquipmentCollection();
      $input = array();
      $input['is_active']=1;
      $input['name']='Printer mac';
      $input['match']='AND';
      $input['sub_type'] = 'PluginFusioninventoryRuleImportEquipment';
      $input['ranking'] = $ranking;
      $rule_id = $rulecollection->add($input);

         // Add criteria
         $rule = $rulecollection->getRuleClass();
         $rulecriteria = new RuleCriteria(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "mac";
         $input['pattern']= 1;
         $input['condition']=10;
         $rulecriteria->add($input);

         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "mac";
         $input['pattern']= 1;
         $input['condition']=8;
         $rulecriteria->add($input);

         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "itemtype";
         $input['pattern']= 'Printer';
         $input['condition']=0;
         $rulecriteria->add($input);

         // Add action
         $ruleaction = new RuleAction(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['action_type'] = 'assign';
         $input['field'] = '_fusion';
         $input['value'] = '0';
         $ruleaction->add($input);

      $ranking++;
      // Create rule for : Printer + name
      $rulecollection = new PluginFusioninventoryRuleImportEquipmentCollection();
      $input = array();
      $input['is_active']=1;
      $input['name']='Printer name';
      $input['match']='AND';
      $input['sub_type'] = 'PluginFusioninventoryRuleImportEquipment';
      $input['ranking'] = $ranking;
      $rule_id = $rulecollection->add($input);

         // Add criteria
         $rule = $rulecollection->getRuleClass();
         $rulecriteria = new RuleCriteria(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "name";
         $input['pattern']= 1;
         $input['condition']=10;
         $rulecriteria->add($input);

         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "name";
         $input['pattern']= 1;
         $input['condition']=8;
         $rulecriteria->add($input);

         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "itemtype";
         $input['pattern']= 'Printer';
         $input['condition']=0;
         $rulecriteria->add($input);

         // Add action
         $ruleaction = new RuleAction(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['action_type'] = 'assign';
         $input['field'] = '_fusion';
         $input['value'] = '0';
         $ruleaction->add($input);

      $ranking++;
      // Create rule for : NetworkEquipment + serial
      $rulecollection = new PluginFusioninventoryRuleImportEquipmentCollection();
      $input = array();
      $input['is_active']=1;
      $input['name']='NetworkEquipment serial';
      $input['match']='AND';
      $input['sub_type'] = 'PluginFusioninventoryRuleImportEquipment';
      $input['ranking'] = $ranking;
      $rule_id = $rulecollection->add($input);

         // Add criteria
         $rule = $rulecollection->getRuleClass();
         $rulecriteria = new RuleCriteria(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "serial";
         $input['pattern']= 1;
         $input['condition']=10;
         $rulecriteria->add($input);

         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "serial";
         $input['pattern']= 1;
         $input['condition']=8;
         $rulecriteria->add($input);

         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "itemtype";
         $input['pattern']= 'NetworkEquipment';
         $input['condition']=0;
         $rulecriteria->add($input);

         // Add action
         $ruleaction = new RuleAction(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['action_type'] = 'assign';
         $input['field'] = '_fusion';
         $input['value'] = '0';
         $ruleaction->add($input);

      $ranking++;
      // Create rule for : NetworkEquipment + mac
      $rulecollection = new PluginFusioninventoryRuleImportEquipmentCollection();
      $input = array();
      $input['is_active']=1;
      $input['name']='NetworkEquipment mac';
      $input['match']='AND';
      $input['sub_type'] = 'PluginFusioninventoryRuleImportEquipment';
      $input['ranking'] = $ranking;
      $rule_id = $rulecollection->add($input);

         // Add criteria
         $rule = $rulecollection->getRuleClass();
         $rulecriteria = new RuleCriteria(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "mac";
         $input['pattern']= 1;
         $input['condition']=10;
         $rulecriteria->add($input);

         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "mac";
         $input['pattern']= 1;
         $input['condition']=8;
         $rulecriteria->add($input);

         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "itemtype";
         $input['pattern']= 'NetworkEquipment';
         $input['condition']=0;
         $rulecriteria->add($input);

         // Add action
         $ruleaction = new RuleAction(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['action_type'] = 'assign';
         $input['field'] = '_fusion';
         $input['value'] = '0';
         $ruleaction->add($input);

      $ranking++;
      // Create rule for : NetworkEquipment import
      $rulecollection = new PluginFusioninventoryRuleImportEquipmentCollection();
      $input = array();
      $input['is_active']=1;
      $input['name']='NetworkEquipment import';
      $input['match']='AND';
      $input['sub_type'] = 'PluginFusioninventoryRuleImportEquipment';
      $input['ranking'] = $ranking;
      $rule_id = $rulecollection->add($input);

         // Add criteria
         $rule = $rulecollection->getRuleClass();
         $rulecriteria = new RuleCriteria(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "itemtype";
         $input['pattern']= 'NetworkEquipment';
         $input['condition']=0;
         $rulecriteria->add($input);

         // Add action
         $ruleaction = new RuleAction(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['action_type'] = 'assign';
         $input['field'] = '_fusion';
         $input['value'] = '0';
         $ruleaction->add($input);

      $ranking++;
      // Create rule for search serial in all DB
      $rulecollection = new PluginFusioninventoryRuleImportEquipmentCollection();
      $input = array();
      $input['is_active']=1;
      $input['name']='Find serial in all GLPI';
      $input['match']='AND';
      $input['sub_type'] = 'PluginFusioninventoryRuleImportEquipment';
      $input['ranking'] = $ranking;
      $rule_id = $rulecollection->add($input);

         // Add criteria
         $rule = $rulecollection->getRuleClass();
         $rulecriteria = new RuleCriteria(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "serial";
         $input['pattern']= 1;
         $input['condition']=10;
         $rulecriteria->add($input);

         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "serial";
         $input['pattern']= 1;
         $input['condition']=8;
         $rulecriteria->add($input);

         // Add action
         $ruleaction = new RuleAction(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['action_type'] = 'assign';
         $input['field'] = '_fusion';
         $input['value'] = '0';
         $ruleaction->add($input);


     $ranking++;
     // Create rule for search mac in all DB
      $rulecollection = new PluginFusioninventoryRuleImportEquipmentCollection();
      $input = array();
      $input['is_active']=1;
      $input['name']='Find mac in all GLPI';
      $input['match']='AND';
      $input['sub_type'] = 'PluginFusioninventoryRuleImportEquipment';
      $input['ranking'] = $ranking;
      $rule_id = $rulecollection->add($input);

         // Add criteria
         $rule = $rulecollection->getRuleClass();
         $rulecriteria = new RuleCriteria(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "mac";
         $input['pattern']= 1;
         $input['condition']=10;
         $rulecriteria->add($input);

         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "mac";
         $input['pattern']= 1;
         $input['condition']=8;
         $rulecriteria->add($input);

         // Add action
         $ruleaction = new RuleAction(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['action_type'] = 'assign';
         $input['field'] = '_fusion';
         $input['value'] = '0';
         $ruleaction->add($input);


     $ranking++;
     // Create rule for search name in all DB
      $rulecollection = new PluginFusioninventoryRuleImportEquipmentCollection();
      $input = array();
      $input['is_active']=1;
      $input['name']='Find name in all GLPI';
      $input['match']='AND';
      $input['sub_type'] = 'PluginFusioninventoryRuleImportEquipment';
      $input['ranking'] = $ranking;
      $rule_id = $rulecollection->add($input);

         // Add criteria
         $rule = $rulecollection->getRuleClass();
         $rulecriteria = new RuleCriteria(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "name";
         $input['pattern']= 1;
         $input['condition']=10;
         $rulecriteria->add($input);

         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "name";
         $input['pattern']= 1;
         $input['condition']=8;
         $rulecriteria->add($input);

         // Add action
         $ruleaction = new RuleAction(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['action_type'] = 'assign';
         $input['field'] = '_fusion';
         $input['value'] = '0';
         $ruleaction->add($input);


      $ranking++;
      // Create rule for import into unknown devices
      $rulecollection = new PluginFusioninventoryRuleImportEquipmentCollection();
      $input = array();
      $input['is_active']=1;
      $input['name']='Unknown device import';
      $input['match']='AND';
      $input['sub_type'] = 'PluginFusioninventoryRuleImportEquipment';
      $input['ranking'] = $ranking;
      $rule_id = $rulecollection->add($input);

         // Add criteria
         $rule = $rulecollection->getRuleClass();
         $rulecriteria = new RuleCriteria(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "name";
         $input['pattern']= '*';
         $input['condition']=0;
         $rulecriteria->add($input);

         // Add action
         $ruleaction = new RuleAction(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['action_type'] = 'assign';
         $input['field'] = '_fusion';
         $input['value'] = '0';
         $ruleaction->add($input);
         
   }

}

?>