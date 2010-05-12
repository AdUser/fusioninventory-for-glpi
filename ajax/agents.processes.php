<?php
/*
 * @version $Id: computer.tabs.php 8003 2009-02-26 11:03:19Z moyo $
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2009 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org
 -------------------------------------------------------------------------

 LICENSE

 This file is part of GLPI.

 GLPI is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 GLPI is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with GLPI; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 --------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: David DURIEUX
// Purpose of file:
// ----------------------------------------------------------------------

define('GLPI_ROOT', '../../..');
include (GLPI_ROOT . "/inc/includes.php");
header("Content-Type: text/html; charset=UTF-8");
header_nocache();

if(!isset($_POST["id"])) {
	exit();
}
if(!isset($_POST["sort"])) $_POST["sort"] = "";
if(!isset($_POST["order"])) $_POST["order"] = "";
if(!isset($_POST["withtemplate"])) $_POST["withtemplate"] = "";

switch($_POST['glpi_tab']) {
	case -1 :
      $ptap = new PluginFusioninventoryAgentsProcesses;
      $ptap->ShowProcesses();
      $ptac = new PluginFusioninventorySnmphistoryconnection;
      $ptac->showForm($_GET);
      $ptae = new PluginFusioninventoryAgentsErrors;
      $ptae->ShowErrors($_GET);
		break;

	case 1 :
      $ptap = new PluginFusioninventoryAgentsProcesses;
      $ptap->ShowProcesses();
		break;

   case 2 :
      $ptac = new PluginFusioninventorySnmphistoryconnection;
      $ptac->showForm($_POST);
		break;

   case 3 :
      $ptae = new PluginFusioninventoryAgentsErrors;
      $ptae->ShowErrors($_POST);
		break;

   default :
      $ptap = new PluginFusioninventoryAgentsProcesses;
      $ptap->ShowProcesses();
		break;
}

ajaxFooter();

?>