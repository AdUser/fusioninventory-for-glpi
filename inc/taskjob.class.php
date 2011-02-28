<?php

/*
   ----------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2003-2008 by the INDEPNET Development Team.

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

// Original Author of file: David DURIEUX
// Purpose of file:
// ----------------------------------------------------------------------

class PluginFusioninventoryTaskjob extends CommonDBTM {


   static function getTypeName() {
      global $LANG;
      
      return $LANG['plugin_fusioninventory']['task'][2];
   }


   function getSearchOptions() {
      global $LANG;

      $tab = array();

      $tab['common'] = $LANG['plugin_fusioninventory']['task'][0];

      $tab[1]['table']          = $this->getTable();
      $tab[1]['field']          = 'name';
      $tab[1]['linkfield']      = '';
      $tab[1]['name']           = $LANG['common'][16];
      $tab[1]['datatype']       = 'itemlink';

      $tab[2]['table']           = 'glpi_entities';
      $tab[2]['field']           = 'completename';
      $tab[2]['linkfield']       = 'entities_id';
      $tab[2]['name']            = $LANG['entity'][0];

//      $tab[3]['table']          = $this->getTable();
//      $tab[3]['field']          = 'date_scheduled';
//      $tab[3]['linkfield']      = '';
//      $tab[3]['name']           = $LANG['common'][27];
//      $tab[3]['datatype']       = 'datetime';

      $tab[4]['table']          = 'glpi_plugin_fusioninventory_tasks';
      $tab[4]['field']          = 'name';
      $tab[4]['linkfield']      = 'plugin_fusioninventory_tasks_id';
      $tab[4]['name']           = $LANG['plugin_fusioninventory']['task'][0];
      $tab[4]['datatype']       = 'itemlink';
      $tab[4]['itemlink_type']  = 'PluginFusioninventoryTask';
      
      $tab[5]['table']          = $this->getTable();
      $tab[5]['field']          = 'status';
      $tab[5]['linkfield']      = '';
      $tab[5]['name']           = 'status';

      $tab[6]['table']          = $this->getTable();
      $tab[6]['field']          = 'id';
      $tab[6]['linkfield']      = '';
      $tab[6]['name']           = 'id';

      return $tab;
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

   function canUpdate() {
      return true;
   }


   function  showForm($id, $options=array()) {
      global $DB,$CFG_GLPI,$LANG;

      $PluginFusioninventoryTaskjobstatus = new PluginFusioninventoryTaskjobstatus;
      $PluginFusioninventoryTaskjoblog = new PluginFusioninventoryTaskjoblog;

      if ($id!='') {
         $this->getFromDB($id);
      } else {
         $this->getEmpty();
      }

      $this->showFormHeader($options);
      
      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['common'][16]."&nbsp;:</td>";
      echo "<td align='center'>";
      echo "<input type='text' name='name' size='40' value='".$this->fields["name"]."'/>";
      echo "</td>";
      echo "<td rowspan='5'>".$LANG['common'][25]."&nbsp;:</td>";
      echo "<td align='center' rowspan='5'>";
      echo "<textarea cols='40' rows='5' name='comment' >".$this->fields["comment"]."</textarea>";
      echo "<input type='hidden' name='plugin_fusioninventory_tasks_id' value='".$_POST['id']."' />";
      $a_methods = array();
      $a_methods = PluginFusioninventoryStaticmisc::getmethods();
      foreach ($a_methods as $datas) {
         echo "<input type='hidden' name='method-".$datas['method']."' value='".PluginFusioninventoryModule::getModuleId($datas['module'])."' />";
      }
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_fusioninventory']['task'][31]."&nbsp;:</td>";
      echo "<td align='center'>";
      Dropdown::showInteger("periodicity_count", $this->fields['periodicity_count'], 0, 300);
      $a_time = array();
      $a_time[] = "------";
      $a_time['minutes'] = $LANG['plugin_fusioninventory']['task'][35];
      $a_time['hours'] = $LANG['plugin_fusioninventory']['task'][36];
      $a_time['days'] = $LANG['plugin_fusioninventory']['task'][37];
      $a_time['months'] = $LANG['plugin_fusioninventory']['task'][38];
      Dropdown::showFromArray("periodicity_type", $a_time, array('value'=>$this->fields['periodicity_type']));
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_fusioninventory']['task'][24]."&nbsp;:</td>";
      echo "<td align='center'>";
      Dropdown::showInteger("retry_nb", $this->fields["retry_nb"], 0, 30);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_fusioninventory']['task'][25]."&nbsp;:</td>";
      echo "<td align='center'>";
      Dropdown::showInteger("retry_time", $this->fields["retry_time"], 0, 360);
      echo "</td>";
      echo "</tr>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_fusioninventory']['task'][26]."&nbsp;:</td>";
      echo "<td align='center'>";
      $this->dropdownMethod("method_id", $this->fields['method'], $this->fields['method']);
      echo "</td>";
      echo "</tr>";

      // Definition   *   Action
      echo "<tr>";
      echo "<th colspan='2'>".$LANG['plugin_fusioninventory']['task'][27]."&nbsp;:</th>";
      echo "<th colspan='2'>".$LANG['plugin_fusioninventory']['task'][28]."&nbsp;:</th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_fusioninventory']['task'][29]."&nbsp;:</td>";
      echo "<td align='center' height='20'>";
      echo "<span id='show_DefinitionType_id'>";
      echo "</span>";
      echo "</td>";
      echo "<td>".$LANG['plugin_fusioninventory']['task'][29]."&nbsp;:</td>";
      echo "<td align='center'>";
      echo "<span id='show_ActionType_id'>";
      echo "</span>";
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td rowspan='2'>".$LANG['plugin_fusioninventory']['task'][30]."&nbsp;:</td>";
      echo "<td align='center' height='20'>";
      echo "<span id='show_DefinitionList'>";
      echo "</span>";
      echo "</td>";
      echo "<td rowspan='2'>".$LANG['plugin_fusioninventory']['task'][30]."&nbsp;:</td>";
      echo "<td align='center'>";
      echo "<span id='show_ActionList'>";
      echo "</span>";
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td align='center'>";
      $a_list = importArrayFromDB($this->fields['definition']);
      $deflist = "";
      $deflisthidden = "";
      foreach ($a_list as $data) {
         $item_type = key($data);
         $class = new $item_type();
         $itemname = $class->getTypeName();
         $class->getFromDB(current($data));
         $name = $class->fields['name'];
         $deflist .= '<br>'.$itemname.' -> '.$name.' <img src="'.GLPI_ROOT.'/pics/delete2.png" onclick=\'deldef("'.$itemname.'->'.$name.'->'.$class->getType().'->'.$class->fields['id'].'")\'>';
         $deflisthidden .= ','.key($data).'->'.current($data);
      }
      echo "<span id='definitionselection'>";
      echo $deflist;
      echo "</span>";
      echo "<div style='visibility:hidden'>";
      echo "<textarea name='definitionlist' id='definitionlist'>".$deflisthidden."</textarea>";
      echo "<span id='show_DefinitionListEmpty'>";
      echo "</span>";
      echo "</div>";
      echo "</td>";
      echo "<td align='center'>";
      $a_list = importArrayFromDB($this->fields['action']);
      $actionlist = "";
      $actionlisthidden = "";
      foreach ($a_list as $data) {
         $item_type = key($data);
         $class = new $item_type();
         $itemname = $class->getTypeName();
         $class->getFromDB(current($data));
         if (current($data) == '.1') {
            $name = $LANG['plugin_fusioninventory']['agents'][32];
            $idTmp = '.1';
         } else if (current($data) == '.2') {
            $name = $LANG['plugin_fusioninventory']['agents'][33];
            $idTmp = '.2';
         } else {
            $class->getFromDB(current($data));
            $name = $class->fields['name'];
            $idTmp = $class->fields['id'];
         }         
         $actionlist .= '<br>'.$itemname.' -> '.$name.' <img src="'.GLPI_ROOT.'/pics/delete2.png" onclick=\'delaction("'.$itemname.'->'.$name.'->'.$class->getType().'->'.$idTmp.'")\'>';
         $actionlisthidden .= ','.key($data).'->'.current($data);
      }
      echo "<span id='actionselection'>";
      echo $actionlist;
      echo "</span>";
      echo "<div style='visibility:hidden'>";
      echo "<textarea name='actionlist' id='actionlist'>".$actionlisthidden."</textarea>";
      echo "<span id='show_ActionListEmpty'>";
      echo "</span>";
      echo "</div>";
      echo "</td>";
      echo "</tr>";

      
      echo "<script type='text/javascript'>
         function deldef(data) {
            var elem = data.split('->');
            document.getElementById('definitionlist').value = document.getElementById('definitionlist').value.replace(',' + elem[2] + '->' + elem[3], '');
            document.getElementById('definitionselection').innerHTML = document.getElementById('definitionselection').innerHTML.replace('<br>' + elem[0] + ' -&gt; ' + elem[1] +
            ' <img src=\"".GLPI_ROOT."/pics/delete2.png\" onclick=\'deldef(\"' + elem[0] + '->' + elem[1] + '->' + elem[2] + '->' + elem[3] + '\")\'>', '');
         }

         function delaction(data) {
            var elem = data.split('->');
            document.getElementById('actionlist').value = document.getElementById('actionlist').value.replace(',' + elem[2] + '->' + elem[3], '');
            document.getElementById('actionselection').innerHTML = document.getElementById('actionselection').innerHTML.replace('<br>' + elem[0] + ' -&gt; ' + elem[1] +
            ' <img src=\"".GLPI_ROOT."/pics/delete2.png\" onclick=\'delaction(\"' + elem[0] + '->' + elem[1] + '->' + elem[2] + '->' + elem[3] + '\")\'>', '');
         }
      </script>";




      if ($id) {
         if (count($PluginFusioninventoryTaskjobstatus->find("`plugin_fusioninventory_taskjobs_id`='".$id."' AND `state` < 3")) == 0) {
            $this->showFormButtons($options);
         }
         if (count($PluginFusioninventoryTaskjobstatus->find("`plugin_fusioninventory_taskjobs_id`='".$id."'")) > 0) {
 
            $PluginFusioninventoryTaskjobstatus->stateTaskjob($id);

            // Display graph finish
            $PluginFusioninventoryTaskjoblog->graphFinish($id);
            echo "<br/>";
         } 
      } else  {
         $this->showFormButtons($options);
      }

      return true;
   }



   function dropdownMethod($myname,$value=0,$valueType=0,$entity_restrict='') {
      global $DB,$CFG_GLPI;

      $a_methods = PluginFusioninventoryStaticmisc::getmethods();

      $a_methods2 = array();
      $a_methods2[''] = "------";
      foreach ($a_methods as $datas) {
         $a_methods2[$datas['method']] = $datas['method'];
      }
      $rand = Dropdown::showFromArray($myname, $a_methods2, array('value'=>$value));

      // ** List methods available
      $params=array('method_id'=>'__VALUE__',
                     'entity_restrict'=>$entity_restrict,
                     'rand'=>$rand,
                     'myname'=>$myname
                     );
      ajaxUpdateItemOnSelectEvent("dropdown_method_id".$rand,"show_DefinitionType_id",$CFG_GLPI["root_doc"]."/plugins/fusioninventory/ajax/dropdowndefinitiontype.php",$params);

      if ($value != "0") {
         echo "<script type='text/javascript'>";
         ajaxUpdateItemJsCode("show_DefinitionType_id",$CFG_GLPI["root_doc"]."/plugins/fusioninventory/ajax/dropdowndefinitiontype.php",$params,true,"dropdown_method_id".$rand);
         echo "</script>";
      }

      $params=array('method_id'=>'__VALUE__',
                     'entity_restrict'=>$entity_restrict,
                     'rand'=>$rand,
                     'myname'=>$myname
                     );
      ajaxUpdateItemOnSelectEvent("dropdown_method_id".$rand,"show_ActionType_id",$CFG_GLPI["root_doc"]."/plugins/fusioninventory/ajax/dropdownactiontype.php",$params);

      if ($value != "0") {
         echo "<script type='text/javascript'>";
         ajaxUpdateItemJsCode("show_ActionType_id",$CFG_GLPI["root_doc"]."/plugins/fusioninventory/ajax/dropdownactiontype.php",$params,true,"dropdown_method_id".$rand);
         echo "</script>";
      }

      return $rand;
   }



   function dropdownDefinitionType($myname,$method,$value=0,$entity_restrict='', $title = 0) {
      global $DB,$CFG_GLPI;

      $a_methods = PluginFusioninventoryStaticmisc::getmethods();
      $a_definitiontype = array();
      $a_definitiontype[''] = '------';
      foreach ($a_methods as $datas) {
         if ($method == $datas['method']) {
            $module = $datas['module'];
            if (is_callable(array("Plugin".$module."Staticmisc", "task_definitiontype_".$method))) {
               $a_definitiontype = call_user_func(array("Plugin".$module."Staticmisc", "task_definitiontype_".$method), $a_definitiontype);
            }

         }
      }

      $rand = Dropdown::showFromArray($myname, $a_definitiontype);

      $params=array('DefinitionType'=>'__VALUE__',
            'entity_restrict'=>$entity_restrict,
            'rand'=>$rand,
            'myname'=>$myname,
            'method'=>$method,
            'deftypeid'=>'dropdown_'.$myname.$rand
            );
      ajaxUpdateItemOnSelectEvent('dropdown_DefinitionType'.$rand,"show_DefinitionList",$CFG_GLPI["root_doc"]."/plugins/fusioninventory/ajax/dropdowndefinitionlist.php",$params);

      return $rand;
   }


   function dropdownDefinition($myname,$definitiontype,$method,$deftypeid,$value=0,$entity_restrict='', $title = 0) {
      global $DB,$CFG_GLPI, $LANG;

      $a_methods = PluginFusioninventoryStaticmisc::getmethods();
      $module = '';
      foreach ($a_methods as $datas) {
         if ($method == $datas['method']) {
            $module = $datas['module'];
         }
      }


      if (is_callable(array("Plugin".$module."Staticmisc", "task_definitionselection_".$definitiontype."_".$method))) {
         $rand = call_user_func(array("Plugin".$module."Staticmisc", "task_definitionselection_".$definitiontype."_".$method), $title);
      }

      echo "&nbsp;<input type='button' name='addObject' id='addObject' value='".$LANG['buttons'][8]."' class='submit'/>";

            $params=array('selection'=>'__VALUE__',
                     'entity_restrict'=>$entity_restrict,
                     'myname'=>$myname,
                     'defselectadd' => 'dropdown_definitionselectiontoadd'.$rand,
                     'deftypeid'=>$deftypeid
                     );


      ajaxUpdateItemOnEvent('addObject','show_DefinitionListEmpty',$CFG_GLPI["root_doc"]."/plugins/fusioninventory/ajax/dropdowndefinitionselection.php",$params,array("click"));

   }



   function dropdownActionType($myname,$method,$value=0,$entity_restrict='', $title = 0) {
      global $DB,$CFG_GLPI,$LANG;

      $a_methods = PluginFusioninventoryStaticmisc::getmethods();
      $a_actioninitiontype = array();
      $a_actioninitiontype[''] = '------';
      $a_actioninitiontype['PluginFusioninventoryAgent'] = PluginFusioninventoryAgent::getTypeName();
      foreach ($a_methods as $datas) {
         if ($method == $datas['method']) {
            $module = $datas['module'];
            if (is_callable(array("Plugin".$module."Staticmisc", "task_actiontype_".$method))) {
               $a_actioninitiontype = call_user_func(array("Plugin".$module."Staticmisc", "task_actiontype_".$method), $a_actioninitiontype);
            }

         }
      }

      $rand = Dropdown::showFromArray($myname, $a_actioninitiontype);

      $params=array('ActionType'=>'__VALUE__',
            'entity_restrict'=>$entity_restrict,
            'rand'=>$rand,
            'myname'=>$myname,
            'method'=>$method,
            'actiontypeid'=>'dropdown_'.$myname.$rand
            );
      ajaxUpdateItemOnSelectEvent('dropdown_ActionType'.$rand,"show_ActionList",$CFG_GLPI["root_doc"]."/plugins/fusioninventory/ajax/dropdownactionlist.php",$params);

      return $rand;
   }


   function dropdownAction($myname,$actiontype,$method,$actiontypeid,$value=0,$entity_restrict='', $title = 0) {
      global $DB,$CFG_GLPI, $LANG;

      $a_methods = PluginFusioninventoryStaticmisc::getmethods();
      $module = '';
      foreach ($a_methods as $datas) {
         if ($method == $datas['method']) {
            $module = $datas['module'];
         }
      }

//
//      if (is_callable(array("Plugin".$module."Staticmisc", "task_actionselection_".$actiontype."_".$method))) {
//         $rand = call_user_func(array("Plugin".$module."Staticmisc", "task_actionselection_".$actiontype."_".$method), $title);
//      }

      $a_data = $this->get_agents($method);

      $rand = Dropdown::showFromArray('actionselectiontoadd', $a_data);

      echo "&nbsp;<input type='button' name='addAObject' id='addAObject' value='".$LANG['buttons'][8]."' class='submit'/>";

            $params=array('selection'=>'__VALUE__',
                     'entity_restrict'=>$entity_restrict,
                     'myname'=>$myname,
                     'actionselectadd' => 'dropdown_actionselectiontoadd'.$rand,
                     'actiontypeid'=>$actiontypeid
                     );


      ajaxUpdateItemOnEvent('addAObject','show_ActionListEmpty',$CFG_GLPI["root_doc"]."/plugins/fusioninventory/ajax/dropdownactionselection.php",$params,array("click"));

   }
   


   function get_agents($module) {
      global $LANG;

      $array = array();
      $array[".1"] = $LANG['plugin_fusioninventory']['agents'][32];
      $array[".2"] = $LANG['plugin_fusioninventory']['agents'][33];
      $PluginFusioninventoryAgentmodule = new PluginFusioninventoryAgentmodule();
      $array1 = $PluginFusioninventoryAgentmodule->getAgentsCanDo(strtoupper($module));
      foreach ($array1 as $id => $data) {
         $array[$id] = $data['name'];
      }
      return $array;
   }

   

   static function cronTaskscheduler() {
      global $DB;

      $PluginFusioninventoryTask = new PluginFusioninventoryTask();
      $PluginFusioninventoryTaskjoblog = new PluginFusioninventoryTaskjoblog;
      $PluginFusioninventoryTaskjobstatus = new PluginFusioninventoryTaskjobstatus;
      $PluginFusioninventoryTaskjob = new PluginFusioninventoryTaskjob();

      // Search for task with periodicity and must be ok (so reinit state of job to 0)
      $query = "SELECT *, UNIX_TIMESTAMP(date_scheduled) as date_scheduled_timestamp FROM `".$PluginFusioninventoryTask->getTable()."`
         WHERE `is_active`='1'
            AND `periodicity_count` != '0'";
      
      $result = $DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         $period = $PluginFusioninventoryTaskjob->periodicityToTimestamp($data['periodicity_type'], $data['periodicity_count']);
         
         // Calculate next execution from last
         $queryJob = "SELECT * FROM `".$PluginFusioninventoryTaskjob->getTable()."`
            WHERE `plugin_fusioninventory_tasks_id`='".$data['id']."'
            ORDER BY `id` DESC
            LIMIT 1";

         $finished = 2;
         $resultJob = $DB->query($queryJob);
         while ($dataJob=$DB->fetch_array($resultJob)) {
            $a_taskjobstatus = $PluginFusioninventoryTaskjobstatus->find("`plugin_fusioninventory_taskjobs_id`='".$dataJob['id']."'", "id DESC", 1);
            $taskjobstatusfinished = 0;
            foreach ($a_taskjobstatus as $statusdata) {
               $a_joblog = $PluginFusioninventoryTaskjoblog->find("`plugin_fusioninventory_taskjobstatus_id`='".$statusdata['id']."'");
               foreach($a_joblog as $joblogdata) {
                  switch ($joblogdata['state']) {

                     case '2':
                     case '3':
                     case '4':
                     case '5':
                        // finished
                        $taskjobstatusfinished++;
                        break;

                  }
               }
            }

            if ((count($a_taskjobstatus) == $taskjobstatusfinished)
                    AND ($finished != "0")
                    AND (($data['date_scheduled_timestamp'] + $period) < date('U')) ) {

               $finished = 1;
            } else {
               $finished = 0;
            }
         }
         // if all jobs are finished, we calculate if we reinitialize all jobs
         if ($finished == "1") {
            $data['execution_id']++;
            $queryUpdate = "UPDATE `".$PluginFusioninventoryTaskjob->getTable()."`
               SET `status`='0', `execution_id`='".$data['execution_id']."'
               WHERE `plugin_fusioninventory_tasks_id`='".$data['id']."'";
            $DB->query($queryUpdate);

            if (($data['date_scheduled_timestamp'] + $period) <= date('U')) {
               $data['date_scheduled'] = date("Y-m-d H:i:s", date('U'));
            } else {
               $data['date_scheduled'] = date("Y-m-d H:i:s", $data['date_scheduled_timestamp'] + $period);
            }
            $PluginFusioninventoryTask->update($data);
         }
      }

      // *** Search task ready

      $remoteStartAgents = array();
      $dateNow = date("Y-m-d H:i:s");
      $query = "SELECT `".$PluginFusioninventoryTaskjob->getTable()."`.*,`glpi_plugin_fusioninventory_tasks`.`communication`, UNIX_TIMESTAMP(date_scheduled) as date_scheduled_timestamp
         FROM ".$PluginFusioninventoryTaskjob->getTable()."
         LEFT JOIN `glpi_plugin_fusioninventory_tasks` ON `plugin_fusioninventory_tasks_id`=`glpi_plugin_fusioninventory_tasks`.`id`
         WHERE `is_active`='1'
            AND `status` = '0'
            AND `date_scheduled` <= '".$dateNow."' ";
      $result = $DB->query($query);
      $return = 0;
      while ($data=$DB->fetch_array($result)) {
         $period = $PluginFusioninventoryTaskjob->periodicityToTimestamp($data['periodicity_type'], $data['periodicity_count']);
         if (($data['date_scheduled_timestamp'] + $period) <= date('U')) {
            // Get module name
            $pluginName = PluginFusioninventoryModule::getModuleName($data['plugins_id']);
            $className = "Plugin".ucfirst($pluginName).ucfirst($data['method']);
            $class = new $className;
            $class->prepareRun($data['id']);
            $return = 1;
         }
      }
      if ($return == '1') {
         return 1;
      }
      return 0;
   }



   function periodicityToTimestamp($periodicity_type, $periodicity_count) {
      $period = 0;
      switch($periodicity_type) {

         case 'minutes':
            $period = $periodicity_count * 60;
            break;

         case 'hours':
            $period = $periodicity_count * 60 * 60;
            break;

         case 'days':
            $period = $periodicity_count * 60 * 60 * 24;
            break;

         case 'months':
            $period = $periodicity_count * 60 * 60 * 24 * 30; //month
            break;

         default:
            $period = 0;
      }
      return $period;
   }



   function getStateAgent($ip, $agentid, $type="") {
      global $LANG;

      $this->disableDebug();
      //PluginFusioninventoryDisplay::disableDebug();
      $PluginFusioninventoryConfig = new PluginFusioninventoryConfig;

      $plugins_id = PluginFusioninventoryModule::getModuleId('fusioninventory');

      $state = false;
      if (empty($ip)) {
         return $state;
      }

      $ctx = stream_context_create(array(
          'http' => array(
              'timeout' => 2
              )
          )
      );

      $url = "http://".$ip.":".$PluginFusioninventoryConfig->getValue($plugins_id, 'agent_port')."/status";

      $str = @file_get_contents($url, 0, $ctx);
      $this->reenableusemode();
      if (strstr($str, "waiting")) {
         return true;
      }
      return $state;
   }
   


   function RemoteStartAgent($ip, $token) {

      $this->disableDebug();
      $PluginFusioninventoryConfig = new PluginFusioninventoryConfig;
      $plugins_id = PluginFusioninventoryModule::getModuleId('fusioninventory');

      $input = '';
//      ini_set('default_socket_timeout', 2);
      $ctx = stream_context_create(array(
          'http' => array(
              'timeout' => 2
              )
          )
      );
      $data = @file_get_contents("http://".$ip.":".$PluginFusioninventoryConfig->getValue($plugins_id, 'agent_port')."/status", 0, $ctx);
      if (isset($data) && !empty($data)) {
         @file_get_contents("http://".$ip.":".$PluginFusioninventoryConfig->getValue($plugins_id, 'agent_port')."/now/".$token, 0, $ctx);
         $input = 'Agent run Now';
         $this->reenableusemode();
         return true;
      } else {
         $input = 'Agent don\'t respond';
         $this->reenableusemode();
         return false;
      }
   }


   function disableDebug() {
      error_reporting(0);
      set_error_handler(array(new PluginFusioninventoryTaskjob(),'errorempty'));
   }

   function reenableusemode() {
      if ($_SESSION['glpi_use_mode']==DEBUG_MODE){
         ini_set('display_errors','On');
         error_reporting(E_ALL | E_STRICT);
         set_error_handler("userErrorHandler");
      }

   }

   function errorempty() {
      
   }




   function showRunning() {
      global $DB;

      $PluginFusioninventoryTaskjobstatus = new PluginFusioninventoryTaskjobstatus;

      $query = "SELECT * FROM ".$this->getTable()."
         WHERE status='1' ";

      if ($result = $DB->query($query)) {
         while ($data=$DB->fetch_array($result)) {
            if ($PluginFusioninventoryTaskjobstatus->stateTaskjob($data['id'], '', 'get') < 100) {
               $PluginFusioninventoryTaskjobstatus->stateTaskjob($data['id'], '200');
            }
         }
      }
   }
   
   
   /*
    * Display actions possible in device
    *
    */
   function showActions($items_id, $itemtype) {
      global $LANG;

      // load all plugin and get method possible
      /*
       * Example :
       * * inventory
       * * snmpquery
       * * wakeonlan
       * * deploy => software
       * 
       *
       */

      echo "<div align='center'>";
      echo "<form method='post' name='' id=''  action=\"".GLPI_ROOT . "/plugins/fusioninventory/front/taskjob.form.php\">";

      echo "<table  class='tab_cadre_fixe'>";

      echo "<tr>";
      echo "<th colspan='4'>";
      echo $LANG['plugin_fusioninventory']['task'][21];
      echo " : </th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td align='center'>";
      echo $LANG['plugin_fusioninventory']['task'][2]."&nbsp;:";
      echo "</td>";

      echo "<td align='center'>";
      $a_methods = array();
      $a_methods = PluginFusioninventoryStaticmisc::getmethods();
      $a_parseMethods = array();
      $a_parseMethods[''] = "------";
      foreach($a_methods as $num=>$data) {
         if (is_callable(array('Plugin'.$data['module'].'Staticmisc', 'task_action_'.$data['method']))) {
            $a_itemtype = call_user_func(array('Plugin'.$data['module'].'Staticmisc', 'task_action_'.$data['method']));
            if (in_array($itemtype, $a_itemtype)) {
               $a_parseMethods[$data['module']."||".$data['method']] = $data['method'];
            }
         }
      }
      Dropdown::showFromArray('methodaction', $a_parseMethods);
      echo "</td>";

      echo "<td align='center'>";
      echo $LANG['plugin_fusioninventory']['task'][14]."&nbsp;:";
      echo "</td>";
      echo "<td align='center'>";
      showDateTimeFormItem("date_scheduled",date("Y-m-d H:i:s"),1);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td align='center' colspan='4'>";
      echo "<input type='hidden' name='items_id' value='".$items_id."'/>";
      echo "<input type='hidden' name='itemtype' value='".$itemtype."'/>";
      echo "<input type='submit' name='itemaddaction' value=\"".$LANG['buttons'][8]."\" class='submit' >";
      echo "</td>";
      echo "</tr>";

      echo "</table>";
      echo "</form>";
      echo "</div>";
      
   }


   function showMiniAction($items_id, $width="950") {
      global $LANG;
      
      echo "<center><table class='tab_cadrehov' style='width: ".$width."px'>";

      echo "<tr>";
      echo "<th>";
      echo "Date";
      echo "</th>";
      echo "<th>";
      echo "Comment";
      echo "</th>";
      echo "</tr>";

      $a_taskjob = $this->find('`id`="'.$items_id.'" ', 'date_scheduled DESC');
      foreach ($a_taskjob as $is=>$data) {
         echo "<tr class='tab_bg_1'>";
         echo "<td align='center'>";
         echo convDateTime($data['date_scheduled']);
         echo "</td>";
         echo "<td align='center'>";
         echo $data['comment'];
         echo "</td>";
         echo "</tr>";
      }

      echo "</table></center>";
   }


   function redirectTask($taskjobs_id) {

      $this->getFromDB($taskjobs_id);

      $a_taskjob = $this->find("`plugin_fusioninventory_tasks_id`='".$this->fields['plugin_fusioninventory_tasks_id']."'
            AND `rescheduled_taskjob_id`='0' ", "id");
      $i = 1;
      foreach($a_taskjob as $id=>$datas) {
         $i++;
         if ($id == $taskjobs_id) {
            $tab = $i;
         }
      }
      glpi_header(GLPI_ROOT."/plugins/fusioninventory/front/task.form.php?"
              ."itemtype=PluginFusioninventoryTask&id=".$this->fields['plugin_fusioninventory_tasks_id']."&glpi_tab=".$tab);

   }


   function manageTasksByObject($itemtype='', $items_id=0) {
      // Create task
      $PluginFusioninventoryTaskjob = new PluginFusioninventoryTaskjob;
      $PluginFusioninventoryTaskjob->showActions($items_id, $itemtype);
      // See task runing
      $PluginFusioninventoryTaskjobstatus = new PluginFusioninventoryTaskjobstatus;
      $PluginFusioninventoryTaskjobstatus->stateTaskjobItem($items_id, $itemtype, 'running');
      // see tasks finished
      $PluginFusioninventoryTaskjobstatus->stateTaskjobItem($items_id, $itemtype, 'nostarted');
      // see tasks finished
      $PluginFusioninventoryTaskjobstatus->stateTaskjobItem($items_id, $itemtype, 'finished');
   }


}

?>