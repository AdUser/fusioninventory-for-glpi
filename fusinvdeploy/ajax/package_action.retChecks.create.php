<?php
/*
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2008 by the INDEPNET Development Team.

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
// Original Author of file: Alexandre delaunay
// Purpose of file:
// ----------------------------------------------------------------------

define('GLPI_ROOT', '../../..');
include (GLPI_ROOT."/inc/includes.php");
checkLoginUser();

global $DB,$LANG;

$render = $_GET['render'];
$commandIdName = $render."CommandId";

if(isset($HTTP_RAW_POST_DATA)){
   $retcheck = get_object_vars(json_decode($HTTP_RAW_POST_DATA));
   $retcheck = $retcheck[$render.'retChecks'];

   $commandId = $retcheck->$commandIdName;
   $type = $retcheck->type;
   $value = $retcheck->value;
} else {
   exit;
}

$commandstatus = new PluginFusinvdeployAction_Commandstatus();

$data = array( 'type'   => $type,
               'value'  => $value,
               'plugin_fusinvdeploy_commands_id'     => $commandId);

$newId = $commandstatus->add($data);

$sql = "SELECT plugin_fusinvdeploy_commands_id as $commandIdName, id, type, value
         FROM `glpi_plugin_fusinvdeploy_actions_commandstatus`
         WHERE id = $newId";
$qry  = $DB->query($sql);

$res  = array();
while($row = $DB->fetch_array($qry)) {
   $res[$render.'retChecks'][] = $row;
}
echo "{success:true, ".substr(json_encode($res),1, -1)."}";

?>
