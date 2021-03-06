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
   @author    Alexandre Delaunay
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
   die("Sorry. You can't access directly to this file");
}

class PluginFusinvdeployAction_Commandstatus extends CommonDBTM {

   static function getTypeName($nb=0) {

      return __('Execute a command');

   }

   static function canCreate() {
      return true;
   }

   static function canView() {
      return true;
   }

   static function getForCommand($commands_id) {
      $response = array();

      $codeMatrice['RETURNCODE_OK'] = 'okCode';
      $codeMatrice['RETURNCODE_KO'] = 'errorCode';
      $codeMatrice['REGEX_OK'] = 'okPattern';
      $codeMatrice['REGEX_KO'] = 'errorPattern';

      $commands = getAllDatasFromTable('glpi_plugin_fusinvdeploy_actions_commandstatus',
                                       "`plugin_fusinvdeploy_commands_id`='$commands_id'");
      foreach ($commands as $command) {
         $response[] = array( 'type' => $codeMatrice[$command['type']],
                              'values' => array ( $command['value'] ) );
      }
      return $response;
   }
}

?>
