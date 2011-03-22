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
   Original Author of file: David Durieux
   Co-authors of file:
   Purpose of file:
   ----------------------------------------------------------------------
 */

define('GLPI_ROOT', '../../..');

include (GLPI_ROOT . "/inc/includes.php");


$ptud = new PluginFusioninventoryUnknownDevice();
$ptt  = new PluginFusioninventoryTask();

commonHeader($LANG['plugin_fusioninventory']['title'][0], $_SERVER["PHP_SELF"], "plugins", "fusioninventory","unknown");

PluginFusioninventoryProfile::checkRight("fusioninventory", "unknowndevice","r");

PluginFusioninventoryMenu::displayMenu("mini");

$id = "";
if (isset($_GET["id"])) {
	$id = $_GET["id"];
}

if (isset($_POST["delete"])) {
   PluginFusioninventoryProfile::checkRight("fusioninventory", "unknowndevice","w");

	$ptud->check($_POST['id'],'w');

	$ptud->delete($_POST,1);

//	logEvent($_POST["id"], "computers", 4, "inventory", $_SESSION["glpiname"]." ".$LANG['log'][22]);
	glpi_header(GLPI_ROOT."/plugins/fusioninventory/front/unknowndevice.php");
} else if (isset($_POST["restore"])) {


} else if (isset($_POST["purge"]) || isset($_GET["purge"])) {


} else if (isset($_POST["update"])) {
	$ptud->check($_POST['id'],'w');
	$ptud->update($_POST);
	glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset($_POST["import"])) {
   $Import = 0;
   $NoImport = 0;
   list($Import, $NoImport) = $ptud->import($_POST['id'],$Import,$NoImport);
   addMessageAfterRedirect($LANG['plugin_fusioninventory']['discovery'][5]." : ".$Import);
   addMessageAfterRedirect($LANG['plugin_fusioninventory']['discovery'][9]." : ".$NoImport);
   if ($Import == "0") {
      glpi_header($_SERVER['HTTP_REFERER']);
   } else {
      glpi_header(GLPI_ROOT."/plugins/fusioninventory/front/unknowndevice.php");
   }
}

$ptud->showForm($id);

commonFooter();

?>