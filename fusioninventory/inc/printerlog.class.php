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
   die("Sorry. You can't access this file directly");
}

class PluginFusioninventoryPrinterLog extends CommonDBTM {

   function getSearchOptions() {

      $tab = array();

      $tab['common'] = __('History meter printer', 'fusioninventory');


      $tab[1]['table'] = $this->getTable();
      $tab[1]['field'] = 'id';
      $tab[1]['name'] = 'id';

      $tab[2]['table'] = "glpi_printers";
      $tab[2]['field'] = 'name';
      $tab[2]['linkfield'] = 'printers_id';
      $tab[2]['name'] = __('Name');

      $tab[2]['datatype'] = 'itemlink';
      $tab[2]['itemlink_type']  = 'Printer';
//      $tab[2]['forcegroupby'] = true;


//      $tab[1]['table'] = "glpi_printers";
//      $tab[1]['field'] = 'name';
//      $tab[1]['linkfield'] = 'printers_id';
//      $tab[1]['name'] = __('Name');

//      $tab[1]['datatype'] = 'itemlink';
//      $tab[1]['itemlink_type']  = 'Printer';
//      $tab[1]['forcegroupby'] = true;

      $tab[24]['table'] = 'glpi_locations';
      $tab[24]['field'] = 'name';
      $tab[24]['linkfield'] = 'locations_id';
      $tab[24]['name'] = __('Location');

      $tab[24]['datatype'] = 'itemlink';
      $tab[24]['itemlink_type'] = 'Location';

      $tab[19]['table'] = 'glpi_printertypes';
      $tab[19]['field'] = 'name';
      $tab[19]['linkfield'] = 'printertypes_id';
      $tab[19]['name'] = __('Type');

      $tab[19]['datatype'] = 'itemlink';
      $tab[19]['itemlink_type'] = 'PrinterType';

//      $tab[2]['table'] = 'glpi_printermodels';
//      $tab[2]['field'] = 'name';
//      $tab[2]['linkfield'] = 'printermodels_id';
//      $tab[2]['name'] = __('Model');

//      $tab[2]['datatype']='itemptype';
//
      $tab[18]['table'] = 'glpi_states';
      $tab[18]['field'] = 'name';
      $tab[18]['linkfield'] = 'states_id';
      $tab[18]['name'] = __('Status');

      $tab[18]['datatype']='itemptype';

      $tab[20]['table'] = 'glpi_printers';
      $tab[20]['field'] = 'serial';
      $tab[20]['linkfield'] = 'printers_id';
      $tab[20]['name'] = __('Serial Number');


      $tab[23]['table'] = 'glpi_printers';
      $tab[23]['field'] = 'otherserial';
      $tab[23]['linkfield'] = 'printers_id';
      $tab[23]['name'] = __('Inventory number');


      $tab[21]['table'] = 'glpi_users';
      $tab[21]['field'] = 'name';
      $tab[21]['linkfield'] = 'users_id';
      $tab[21]['name'] = __('User');


      $tab[3]['table'] = 'glpi_manufacturers';
      $tab[3]['field'] = 'name';
      $tab[3]['linkfield'] = 'manufacturers_id';
      $tab[3]['name'] = __('Manufacturer');


      $tab[5]['table'] = 'glpi_networkports';
      $tab[5]['field'] = 'ip';
      $tab[5]['linkfield'] = 'printers_id';
      $tab[5]['name'] = __('IP');


//      $tab[4]['table'] = 'glpi_infocoms';
//      $tab[4]['field'] = 'budget';
//      $tab[4]['linkfield'] = '';
//      $tab[4]['name'] = __('Budget');


      $tab[6]['table'] = 'glpi_plugin_fusioninventory_printerlogs';
      $tab[6]['field'] = 'pages_total';
      $tab[6]['linkfield'] = 'id';
      $tab[6]['name'] = __('Total number of printed pages', 'fusioninventory');


      $tab[7]['table'] = 'glpi_plugin_fusioninventory_printerlogs';
      $tab[7]['field'] = 'pages_n_b';
      $tab[7]['linkfield'] = 'id';
      $tab[7]['name'] = __('Number of printed black and white pages', 'fusioninventory');


      $tab[8]['table'] = 'glpi_plugin_fusioninventory_printerlogs';
      $tab[8]['field'] = 'pages_color';
      $tab[8]['linkfield'] = 'id';
      $tab[8]['name'] = __('Number of printed color pages', 'fusioninventory');


      $tab[9]['table'] = $this->getTable();
      $tab[9]['field'] = 'pages_recto_verso';
      $tab[9]['linkfield'] = 'id';
      $tab[9]['name'] = __('Number of pages printed duplex', 'fusioninventory');


      $tab[10]['table'] = $this->getTable();
      $tab[10]['field'] = 'scanned';
      $tab[10]['linkfield'] = 'id';
      $tab[10]['name'] = __('Number of scanned pages', 'fusioninventory');


      $tab[11]['table'] = $this->getTable();
      $tab[11]['field'] = 'pages_total_print';
      $tab[11]['linkfield'] = 'id';
      $tab[11]['name'] = __('Total number of printed pages (print)', 'fusioninventory');


      $tab[12]['table'] = $this->getTable();
      $tab[12]['field'] = 'pages_n_b_print';
      $tab[12]['linkfield'] = 'id';
      $tab[12]['name'] = __('Number of printed black and white pages (print)', 'fusioninventory');


      $tab[13]['table'] = $this->getTable();
      $tab[13]['field'] = 'pages_color_print';
      $tab[13]['linkfield'] = 'id';
      $tab[13]['name'] = __('Number of printed color pages (print)', 'fusioninventory');


      $tab[14]['table'] = $this->getTable();
      $tab[14]['field'] = 'pages_total_copy';
      $tab[14]['linkfield'] = 'id';
      $tab[14]['name'] = __('Total number of printed pages (copy)', 'fusioninventory');


      $tab[15]['table'] = $this->getTable();
      $tab[15]['field'] = 'pages_n_b_copy';
      $tab[15]['linkfield'] = 'id';
      $tab[15]['name'] = __('Number of printed black and white pages (copy)', 'fusioninventory');


      $tab[16]['table'] = $this->getTable();
      $tab[16]['field'] = 'pages_color_copy';
      $tab[16]['linkfield'] = 'id';
      $tab[16]['name'] = __('Number of printed color pages (copy)', 'fusioninventory');


      $tab[17]['table'] = $this->getTable();
      $tab[17]['field'] = 'pages_total_fax';
      $tab[17]['linkfield'] = 'id';
      $tab[17]['name'] = __('Total number of printed pages (fax)', 'fusioninventory');


      return $tab;
   }



   function countAllEntries($id) {
      global $DB;

      $num = 0;
      $query = "SELECT count(DISTINCT `id`)
                FROM ".$this->getTable()."
                WHERE `printers_id` = '".$id."';";
      $result_num=$DB->query($query);
      if ($result_num) {
         $field = $DB->result($result_num,0,0);
         if ($field) {
            $num += $field;
         }
      }
      return $num;
   }



   /* Gets history (and the number of entries) of one printer */
   function getEntries($id, $begin, $limit) {
      global $DB;

      $datas=array();
      $query = "SELECT *
                FROM ".$this->getTable()."
                WHERE `printers_id` = '".$id."'
                LIMIT ".$begin.", ".$limit.";";
      $result=$DB->query($query);
      if ($result) {
         $i = 0;
         while ($data=$DB->fetch_assoc($result)) {
            $data['date'] = Html::convDateTime($data['date']);
            $datas["$i"] = $data;
            $i++;
         }
         return $datas;
      }
      return false;
   }



   function stats($id) {
      global $DB;

      $query = "SELECT MIN(`date`) AS `min_date`, MIN(`pages`) AS `min_pages`, ".
                  "MAX(`date`) AS `max_date`, MAX(`pages`) AS `max_pages`
                FROM ".$this->getTable()."
                WHERE `printers_id` = '".$id."';";
      $result = $DB->query($query);
      if ($result) {
         $fields = $DB->fetch_assoc($result);
         if ($fields) {
            $output = array();
            $output['num_days'] =
               ceil((strtotime($fields['max_date']) - strtotime($fields['min_date']))/(60*60*24));
            $output['num_pages'] = $fields['max_pages'] - $fields['min_pages'];
            $output['pages_per_day'] = round($output['num_pages'] / $output['num_days']);
            return $output;
         }
      }
      return false;
   }



   function showForm($id, $options=array()) {

      if (!PluginFusioninventoryProfile::haveRight("fusinvsnmp", "printer","r")) {
         return false;
      }

      // display stats
      $stats = $this->stats($id);
      if ($stats) {
         $this->showTabs($options);
         $this->showFormHeader($options);

         echo "<tr class='tab_bg_1'>";
         echo "<td>".__('Total printed pages', 'fusioninventory')." : </td>";
         echo "<td>".$stats["num_pages"]."</td></tr>";

         echo "<tr class='tab_bg_1'>";
         echo "<td>".__('Pages / day', 'fusioninventory')." : </td>";
         echo "<td>".$stats["pages_per_day"]."</td></tr>";

         echo "</table></div>";

      }

      // preparing to display history
      if (!isset($_GET['start'])) {
         $_GET['start'] = 0;
      }

      $numrows = $this->countAllEntries($id);
      $parameters = "id=".$_GET["id"]."&onglet=".$_SESSION["glpi_onglet"];

      echo "<br>";
      Html::printPager($_GET['start'], $numrows, $_SERVER['PHP_SELF'], $parameters);

      $limit = $numrows;
      if ($_SESSION["glpilist_limit"] < $numrows) {
         $limit = $_SESSION["glpilist_limit"];
      }
      // Get history
      $data = $this->getEntries($id, $_GET['start'], $limit);
      if (!($data)) {
         return false;
      }

      echo "<div align='center'><form method='post' name='printer_history_form'
                 id='printer_history_form'  action=\"".Toolbox::getItemTypeFormURL(__CLASS__)."\">";

      echo "<table class='tab_cadre' cellpadding='5'><tr><th colspan='3'>";
      echo __('History meter printer', 'fusioninventory')." :</th></tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<th></th>";
      echo "<th>".__('Date')." :</th>";
      echo "<th>".__('Meter', 'fusioninventory')." :</th></tr>";

      for ($i=0 ; $i<$limit ; $i++) {
         echo "<tr class='tab_bg_1'>";
         echo "<td align='center'>";
         echo "<input type='checkbox' name='checked_$i' value='1'>";
         echo "</td>";
         echo "<td align='center'>".$data["$i"]['date']."</td>";
         echo "<td align='center'>".$data["$i"]['pages']."</td>";
         echo "</td></tr>";
         echo "<input type='hidden' name='ID_$i' value='".$data["$i"]['id']."'>";
      }

      if (!PluginFusioninventoryProfile::haveRight("fusinvsnmp", "printer","w")) {
         return false;
      }

      echo "<input type='hidden' name='limit' value='".$limit."'>";
      echo "<tr class='tab_bg_1'><td colspan='3'>";
      echo "<div align='center'><a onclick= \"if (markAllRows('printer_history_form'))
                 return false;\"
                 href='".$_SERVER['PHP_SELF']."?select=all'>".__('Check All', 'fusioninventory')."</a>";
      echo " - <a onclick= \"if ( unMarkAllRows('printer_history_form') ) return false;\"
                  href='".$_SERVER['PHP_SELF']."?select=none'>".__('Uncheck All', 'fusioninventory')."</a> ";
      echo "<input type='submit' name='delete' value=\"".__('Delete', 'fusioninventory')."\" class='submit' >
            </div></td></tr>";
      echo "</table>";
      Html::closeForm();
      echo "</div>";
   }



   /**
    * Show printer graph form
    **/
   function showGraph($id, $options=array()) {
      global $DB,$CFG_GLPI;

      $where=''; $begin=''; $end=''; $timeUnit='day'; $graphField='pages_total'; $pagecounters = array();$graphType='day';
      if (isset($_SESSION['glpi_plugin_fusioninventory_graph_begin'])) {
         $begin=$_SESSION['glpi_plugin_fusioninventory_graph_begin'];
      }
      if ( $begin == 'NULL' OR $begin == '' ) {
         $begin=date("Y-m-01"); // first day of current month
      }
      if (isset($_SESSION['glpi_plugin_fusioninventory_graph_end'])) {
         $end=$_SESSION['glpi_plugin_fusioninventory_graph_end'];
      }
      if (isset($_SESSION['glpi_plugin_fusioninventory_graph_type'])) {
         $graphType = $_SESSION['glpi_plugin_fusioninventory_graph_type'];
      }
      if ( $end == 'NULL' OR $end == '' ) {
         $end=date("Y-m-d"); // today
      }
      if (isset($_SESSION['glpi_plugin_fusioninventory_graph_timeUnit'])) {
         $timeUnit=$_SESSION['glpi_plugin_fusioninventory_graph_timeUnit'];
      }
      if (!isset($_SESSION['glpi_plugin_fusioninventory_graph_printersComp'])) {
         $_SESSION['glpi_plugin_fusioninventory_graph_printersComp']=array();
      }
      if (isset($_SESSION['glpi_plugin_fusioninventory_graph_printerCompAdd'])) {
         $printerCompAdd=$_SESSION['glpi_plugin_fusioninventory_graph_printerCompAdd'];
         if (!key_exists($printerCompAdd, $_SESSION['glpi_plugin_fusioninventory_graph_printersComp'])) {
            $oPrinter = new Printer();
            if ($oPrinter->getFromDB($printerCompAdd)){
               $_SESSION['glpi_plugin_fusioninventory_graph_printersComp'][$printerCompAdd] = $oPrinter->getField('name');
            }
         }
      } elseif (isset($_SESSION['glpi_plugin_fusioninventory_graph_printerCompRemove'])) {
         unset($_SESSION['glpi_plugin_fusioninventory_graph_printersComp'][$_SESSION['glpi_plugin_fusioninventory_graph_printerCompRemove']]);
      }
      $oPrinter = new Printer();
      $printers = $_SESSION['glpi_plugin_fusioninventory_graph_printersComp'];
      $printersView = $printers; // printers without the current printer
      if (isset($printersView[$id])) {
         unset($printersView[$id]);
      } else {
         if ($oPrinter->getFromDB($id)){
            $printers[$id] = $oPrinter->getField('name');
         }
      }

      $printersList = '';
      foreach ($printers as $printers_id=>$printername) {
         if ($printersList != '') {
            $printersList .= '<br/>';
         }
         if ($printers_id == $id) {
            $printersList .= $printername;
         } else {
            $oPrinter->getFromDB($printers_id);
            $printersList .= $oPrinter->getLink(1);
         }
      }
      $printersIds = "";
      foreach (array_keys($printers) as $printerId) {
         if ($printersIds != '') {
            $printersIds.=', ';
         }
         $printersIds .= $printerId;
      }

      $where = " WHERE `printers_id` IN(".$printersIds.")";
      if ($begin!='' || $end!='') {
         $where .= " AND " .getDateRequest("`date`",$begin,$end);
      }
      $group = '';
      switch ($timeUnit) {
         case 'day':
            $group = "GROUP BY `printers_id`, `year`, `month`, `day`";
            break;
         case 'week':
            $group = "GROUP BY `printers_id`, `year`, `month`, `week`";
            break;
         case 'month':
            $group = "GROUP BY `printers_id`, `year`, `month`";
            break;
         case 'year':
            $group = "GROUP BY `printers_id`, `year`";
            break;
      }

      echo "<form method='post' name='snmp_form' id='snmp_form' action='".$CFG_GLPI['root_doc']."/plugins/fusinvsnmp/front/printer_info.form.php'>";
      echo "<table class='tab_cadre' cellpadding='5' width='950'>";
      $mapping = new PluginFusioninventoryMapping();
      $maps = $mapping->find("`itemtype`='Printer'");
      foreach ($maps as $mapfields) {
         if (!isset($mapfields["shortlocale"])) {
            $mapfields["shortlocale"] = $mapfields["locale"];
         }
         $pagecounters[$mapfields['name']] = $mapping->getTranslation($mapfields);
      }

      echo "<tr class='tab_bg_1'>";
      echo "<th colspan='4'>";
      echo __('Printed page counter', 'fusioninventory');

      echo "</th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td class='left'>".__('Start date')."&nbsp;:</td>";
      echo "<td class='left'>";
      Html::showDateFormItem("graph_begin", $begin);
      echo "</td>";
      echo "<td class='left'>".__('Time unit', 'fusioninventory')."&nbsp;:</td>";
      echo "<td class='left'>";
      $elementsTime=array('day'=>__('day'),

                          'week'=>__('week'),

                          'month'=>__('month'),

                          'year'=>__('year', 'fusioninventory'));

      Dropdown::showFromArray('graph_timeUnit', $elementsTime,
                              array('value'=>$timeUnit));
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td class='left'>".__('End date')."&nbsp;:</td>";
      echo "<td class='left'>";
      Html::showDateFormItem("graph_end", $end);
      echo "</td>";
      echo "<td class='left'>".__('Display', 'fusioninventory')."&nbsp;:</td>";
      echo "<td class='left'>";
      $elements=array('total'=>__('Total counter', 'fusioninventory'),

                    'day'=>__('pages per day', 'fusioninventory'));

      Dropdown::showFromArray('graph_type', $elements,
                              array('value'=>$graphType));
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_2'>";
      echo "<td class='center' colspan='4'>
               <input type='submit' class='submit' name='graph_plugin_fusioninventory_printer_period'
                      value='" . __('Update') . "'/>";
      echo "</td>";
      echo "</tr>\n";

      echo "<tr>";
      echo "<th colspan='4'>".__('Printers to compare', 'fusioninventory')."</th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td class='left' rowspan='3'>".__('Printers')."&nbsp;:</td>";
      echo "<td class='left' rowspan='3'>";
      echo $printersList;
      echo "</td>";
      echo "<td class='left'>".__('Add a printer', 'fusioninventory')."&nbsp;:</td>";
      echo "<td class='left'>";
      $printersused = array();
      foreach($printersView as $printer_id=>$name) {
         $printersused[] = $printer_id;
      }
      Dropdown::show('Printer', array('name'    =>'graph_printerCompAdd',
                                      'entiry'  => $_SESSION['glpiactive_entity'],
                                      'used'    => $printersused));
      echo "&nbsp;<input type='submit' value=\"".__('Add')."\" class='submit' name='graph_plugin_fusioninventory_printer_add'>";
      echo "</td>";
      echo "</tr>\n";

      echo "<tr class='tab_bg_1'>";
      echo "<td class='left'>".__('Remove a printer', 'fusioninventory')."&nbsp;:</td>";
      echo "<td class='left'>";
      $printersTmp = $printersView;
      $printersTmp[0] = "-----";
      asort($printersTmp);
      Dropdown::showFromArray('graph_printerCompRemove', $printersTmp);
      echo "&nbsp;<input type='submit' value=\"".__('Delete', 'fusioninventory')."\" class='submit' name='graph_plugin_fusioninventory_printer_remove'>";
      echo "</td>";
      echo "</tr>\n";

      echo "<tr class='tab_bg_1'>";
      echo "<td colspan='2'></td>";
      echo "</tr>";

      echo "</table>";
      Html::closeForm();

      $elementsField=array('pages_total'=>$pagecounters['pagecountertotalpages'],
                      'pages_n_b'=>$pagecounters['pagecounterblackpages'],
                      'pages_color'=>$pagecounters['pagecountercolorpages'],
                      'pages_recto_verso'=>$pagecounters['pagecounterrectoversopages'],
                      'scanned'=>$pagecounters['pagecounterscannedpages'],
                      'pages_total_print'=>$pagecounters['pagecountertotalpages_print'],
                      'pages_n_b_print'=>$pagecounters['pagecounterblackpages_print'],
                      'pages_color_print'=>$pagecounters['pagecountercolorpages_print'],
                      'pages_total_copy'=>$pagecounters['pagecountertotalpages_copy'],
                      'pages_n_b_copy'=>$pagecounters['pagecounterblackpages_copy'],
                      'pages_color_copy'=>$pagecounters['pagecountercolorpages_copy'],
                      'pages_total_fax'=>$pagecounters['pagecountertotalpages_fax']);

      echo "<br/>";
      foreach($elementsField as $graphField=>$name) {
         $query = "SELECT `printers_id`, DAY(`date`)-1 AS `day`, WEEK(`date`) AS `week`,
                    MONTH(`date`) AS `month`, YEAR(`date`) AS `year`,
                    `$graphField`
             FROM `glpi_plugin_fusinvsnmp_printerlogs`"
             .$where
             .$group."
             ORDER BY `year`, `month`, `day`, `printers_id`";

         $input = array();
         $result = $DB->query($query);

         $calendarDay = array (
               __('Monday'),
               __('Tuesday'),
               __('Wednesday'),
               __('Thursday'),
               __('Friday'),
               __('Saturday'),
               __('Sunday'),
               __('Sunday')
         );

         $calendarMonth = array (
               __('January'),
               __('February'),
               __('March'),
               __('April'),
               __('May'),
               __('June'),
               __('July'),
               __('August'),
               __('September'),
               __('October'),
               __('November'),
               __('December')
         );

         if ($result) {
            if ($DB->numrows($result) != 0) {
               $pages = array();
               $data = array();
               $date = '';
               while ($data = $DB->fetch_assoc($result)) {
                  switch($timeUnit) {

                     case 'day':
                        $time=mktime(0,0,0,$data['month'],$data['day'],$data['year']);
                        $dayofweek=date("w",$time);
                        if ($dayofweek==0) {
                           $dayofweek=7;
                        }

                        $date= $calendarDay[$dayofweek%7]." ".$data['day']." ".$calendarMonth[$data['month']];
                        break;

                     case 'week':
                        $date= $data['day']."/".$data['month'];
                        break;

                     case 'month':
                        $date= $data['month']."/".$data['year'];
                        break;

                     case 'year':
                        $date = $data['year'];
                        break;

                  }

                  if ($graphType == 'day') {
                     if (!isset($pages[$data['printers_id']])) {
                        $pages[$data['printers_id']] = 0;
                     }
                     $oPrinter->getFromDB($data['printers_id']);

                     $input[$oPrinter->getName()][$date] = $data[$graphField] - $pages[$data['printers_id']];
                     $pages[$data['printers_id']] = $data[$graphField];
                  } else {
                     $oPrinter->getFromDB($data['printers_id']);
                     $input[$oPrinter->getName()][$date] = $data[$graphField];
                  }
               }
            }
         }
// TODO : correct title (not total of printed)
         $type = 'line';
         if ($graphType == 'day') {
            $type = 'bar';
         }

         $continue = 1;
         foreach($input as $num=>$datas) {
            if (array_sum($datas) == '0') {
               $continue = '-1';
            } else if (count($datas) > 60) {
               $continue = 0;
            } else if (count($datas) == '1') {
               $input[$num] = array_merge(array('' => "0"),$input[$num]);
            } else if (count($datas) == '0') {
               $continue = '-1';
            } else {
               array_shift($datas);
               $input[$num] = $datas;
            }
         }

         if (($continue == '0') OR ($continue == '-1')) {
            echo "<table class='tab_cadre' cellpadding='5' width='900'>";
            echo "<tr class='tab_bg_1'>";
            echo "<th>";
            echo $name;
            echo "</th>";
            echo "</tr>";

            echo "<tr class='tab_bg_1'>";
            echo "<td align='center'>";
            if ($continue == '0') {
               echo __('Too datas to display', 'fusioninventory');

            }
            echo "</td>";
            echo "</tr>";

            echo "</table><br/>";
         } else {
            Stat::showGraph($input,
                     array('title'  => $name,
                        'unit'      => '',
                        'type'      => $type,
                        'height'    => 400,
                        'showtotal' => false));
         }
      }
   }
}

?>
