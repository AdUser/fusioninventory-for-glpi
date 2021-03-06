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

include (GLPI_ROOT . "/inc/includes.php");

PluginFusioninventoryProfile::checkRight("fusioninventory", "configsecurity","r");

$pfConfigSecurity = new PluginFusioninventoryConfigSecurity();
$config = new PluginFusioninventoryConfig();

Html::header(__('FusionInventory', 'fusioninventory'),$_SERVER["PHP_SELF"],"plugins","fusioninventory","configsecurity");

PluginFusioninventoryMenu::displayMenu("mini");


if (isset ($_POST["add"])) {
   PluginFusioninventoryProfile::checkRight("fusioninventory", "configsecurity","w");
   $new_ID = 0;
   $new_ID = $pfConfigSecurity->add($_POST);
   Html::back();
} else if (isset ($_POST["update"])) {
   PluginFusioninventoryProfile::checkRight("fusioninventory", "configsecurity","w");
   $pfConfigSecurity->update($_POST);
   Html::back();
} else if (isset ($_POST["delete"])) {
   PluginFusioninventoryProfile::checkRight("fusioninventory", "configsecurity","w");
   $pfConfigSecurity->delete($_POST);
   Html::redirect("configsecurity.php");
}

$id = "";
if (isset($_GET["id"])) {
   $id = $_GET["id"];
}

if (strstr($_SERVER['HTTP_REFERER'], "wizard.php")) {
   Html::redirect($_SERVER['HTTP_REFERER']."&id=".$id);
}

$pfConfigSecurity->showForm($id);

Html::footer();

?>
