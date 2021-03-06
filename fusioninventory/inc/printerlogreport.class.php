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

class PluginFusioninventoryPrinterLogReport extends CommonDBTM {

   function __construct() {
      global $CFG_GLPI;
      $this->table = "glpi_plugin_fusioninventory_printers";
      $CFG_GLPI['glpitablesitemtype']["PluginFusioninventoryPrinterLogReport"] = $this->table;
   }


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
      $tab[6]['linkfield'] = 'printers_id';
      $tab[6]['name'] = __('Total number of printed pages', 'fusioninventory');

      $tab[6]['forcegroupby']='1';

      $tab[7]['table'] = 'glpi_plugin_fusioninventory_printerlogs';
      $tab[7]['field'] = 'pages_n_b';
      $tab[7]['linkfield'] = 'printers_id';
      $tab[7]['name'] = __('Number of printed black and white pages', 'fusioninventory');

      $tab[7]['forcegroupby']='1';

      $tab[8]['table'] = 'glpi_plugin_fusioninventory_printerlogs';
      $tab[8]['field'] = 'pages_color';
      $tab[8]['linkfield'] = 'printers_id';
      $tab[8]['name'] = __('Number of printed color pages', 'fusioninventory');

      $tab[8]['forcegroupby']='1';

      $tab[9]['table'] = 'glpi_plugin_fusioninventory_printerlogs';
      $tab[9]['field'] = 'pages_recto_verso';
      $tab[9]['linkfield'] = 'printers_id';
      $tab[9]['name'] = __('Number of pages printed duplex', 'fusioninventory');

      $tab[9]['forcegroupby']='1';

      $tab[10]['table'] = 'glpi_plugin_fusioninventory_printerlogs';
      $tab[10]['field'] = 'scanned';
      $tab[10]['linkfield'] = 'printers_id';
      $tab[10]['name'] = __('Number of scanned pages', 'fusioninventory');

      $tab[10]['forcegroupby']='1';

      $tab[11]['table'] = 'glpi_plugin_fusioninventory_printerlogs';
      $tab[11]['field'] = 'pages_total_print';
      $tab[11]['linkfield'] = 'printers_id';
      $tab[11]['name'] = __('Total number of printed pages (print)', 'fusioninventory');

      $tab[11]['forcegroupby']='1';

      $tab[12]['table'] = 'glpi_plugin_fusioninventory_printerlogs';
      $tab[12]['field'] = 'pages_n_b_print';
      $tab[12]['linkfield'] = 'printers_id';
      $tab[12]['name'] = __('Number of printed black and white pages (print)', 'fusioninventory');

      $tab[12]['forcegroupby']='1';

      $tab[13]['table'] = 'glpi_plugin_fusioninventory_printerlogs';
      $tab[13]['field'] = 'pages_color_print';
      $tab[13]['linkfield'] = 'printers_id';
      $tab[13]['name'] = __('Number of printed color pages (print)', 'fusioninventory');

      $tab[13]['forcegroupby']='1';

      $tab[14]['table'] = 'glpi_plugin_fusioninventory_printerlogs';
      $tab[14]['field'] = 'pages_total_copy';
      $tab[14]['linkfield'] = 'printers_id';
      $tab[14]['name'] = __('Total number of printed pages (copy)', 'fusioninventory');

      $tab[14]['forcegroupby']='1';

      $tab[15]['table'] = 'glpi_plugin_fusioninventory_printerlogs';
      $tab[15]['field'] = 'pages_n_b_copy';
      $tab[15]['linkfield'] = 'printers_id';
      $tab[15]['name'] = __('Number of printed black and white pages (copy)', 'fusioninventory');

      $tab[15]['forcegroupby']='1';

      $tab[16]['table'] = 'glpi_plugin_fusioninventory_printerlogs';
      $tab[16]['field'] = 'pages_color_copy';
      $tab[16]['linkfield'] = 'printers_id';
      $tab[16]['name'] = __('Number of printed color pages (copy)', 'fusioninventory');

      $tab[16]['forcegroupby']='1';

      $tab[17]['table'] = 'glpi_plugin_fusioninventory_printerlogs';
      $tab[17]['field'] = 'pages_total_fax';
      $tab[17]['linkfield'] = 'printers_id';
      $tab[17]['name'] = __('Total number of printed pages (fax)', 'fusioninventory');

      $tab[17]['forcegroupby']='1';

      return $tab;
   }
}

?>
