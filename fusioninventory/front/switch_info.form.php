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

define('GLPI_ROOT', '../../..');

include (GLPI_ROOT."/inc/includes.php");

Session::checkRight("networking","r");
PluginFusioninventoryProfile::checkRight("fusioninventory", "networkequipment","w");

if ((isset($_POST['update'])) && (isset($_POST['id']))) {
   $pfNetworkEquipment = new PluginFusioninventoryNetworkEquipment();
   $pfNetworkEquipment->update_network_infos($_POST['id'], $_POST['model_infos'], $_POST['plugin_fusinvsnmp_configsecurities_id'], $_POST['sysdescr']);
} else if ((isset($_POST["GetRightModel"])) && (isset($_POST['id']))) {
   $pfModel = new PluginFusioninventorySnmpmodel();
   $pfModel->getrightmodel($_POST['id'], NETWORKING_TYPE);
}

Html::back();

?>