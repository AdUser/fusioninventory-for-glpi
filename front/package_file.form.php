<?php

/*
   ----------------------------------------------------------------------
   GLPI - Gestionnaire Libre de Parc Informatique
   Copyright (C) 2003-2008 by the INDEPNET Development Team.

   http://indepnet.net/   http://glpi-project.org/
   ----------------------------------------------------------------------

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
   ------------------------------------------------------------------------
 */

// Original Author of file: David DURIEUX
// Purpose of file:
// ----------------------------------------------------------------------

define('GLPI_ROOT', '../../..');

include (GLPI_ROOT . "/inc/includes.php");

//PluginFusioninventoryProfile::checkRight("Fusioninventory", "agents","r");

$PluginFusinvdeployPackage_File = new PluginFusinvdeployPackage_File();

if (isset ($_POST["add"])) {
//	PluginFusioninventoryProfile::checkRight("Fusinvdeloy", "package","w");
	$PluginFusinvdeployPackage_File->add($_POST);
	glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset ($_POST["update"])) {
//	PluginFusioninventoryProfile::checkRight("Fusinvdeloy", "package","w");
	$PluginFusinvdeployPackage_File->update($_POST);
	glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset ($_POST["delete"])) {
//	PluginFusioninventoryProfile::checkRight("Fusinvdeloy", "package","w");
	$PluginFusinvdeployPackage_File->delete($_POST);
	glpi_header("agent.php");
}

?>