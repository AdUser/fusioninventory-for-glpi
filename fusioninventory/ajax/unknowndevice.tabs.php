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
header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

if(!isset($_POST["id"])) {
   exit();
}

if(!isset($_POST["sort"])) $_POST["sort"] = "";
if(!isset($_POST["order"])) $_POST["order"] = "";
if(!isset($_POST["withtemplate"])) $_POST["withtemplate"] = "";

$pfia = new PluginFusioninventoryAgent;
$pfit = new PluginFusioninventoryTask;
$pfUnknownDevice = new PluginFusioninventoryUnknownDevice;
$pfUnknownDevice->getFromDB($_POST['id']);

switch($_POST['glpi_tab']) {

   case -1 :
      NetworkPort::showForItem($pfUnknownDevice);
      $pfUnknownDevice->importForm($CFG_GLPI['root_doc'] . '/plugins/fusioninventory/front/unknowndevice.form.php?id='.$_POST["id"],$_POST["id"]);
      Log::showForItem($pfUnknownDevice);
      CommonGLPI::displayStandardTab($pfUnknownDevice, $_REQUEST['glpi_tab']);
      break;

   case 1 :
      NetworkPort::showForItem($pfUnknownDevice);
      break;

   case 2 :
      $pfUnknownDevice->importForm($CFG_GLPI['root_doc'] . '/plugins/fusioninventory/front/unknowndevice.form.php?id='.$_POST["id"],$_POST["id"]);
      break;

   case 3:
      break;

   case 4 :
      Log::showForItem($pfUnknownDevice);
      break;

   default :
      CommonGLPI::displayStandardTab($pfUnknownDevice, $_REQUEST['glpi_tab']);
      break;

}

Html::ajaxFooter();

?>