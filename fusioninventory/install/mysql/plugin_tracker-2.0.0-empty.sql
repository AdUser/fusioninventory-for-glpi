DROP TABLE IF EXISTS `glpi_dropdown_plugin_tracker_mib_label`;

CREATE TABLE `glpi_dropdown_plugin_tracker_mib_label` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comments` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_dropdown_plugin_tracker_mib_object`;

CREATE TABLE `glpi_dropdown_plugin_tracker_mib_object` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comments` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_dropdown_plugin_tracker_mib_oid`;

CREATE TABLE `glpi_dropdown_plugin_tracker_mib_oid` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comments` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;




DROP TABLE IF EXISTS `glpi_dropdown_plugin_tracker_snmp_auth_auth_protocol`;

CREATE TABLE `glpi_dropdown_plugin_tracker_snmp_auth_auth_protocol` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `comments` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_dropdown_plugin_tracker_snmp_auth_priv_protocol`;

CREATE TABLE `glpi_dropdown_plugin_tracker_snmp_auth_priv_protocol` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `comments` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_dropdown_plugin_tracker_snmp_auth_sec_level`;

CREATE TABLE `glpi_dropdown_plugin_tracker_snmp_auth_sec_level` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `comments` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_dropdown_plugin_tracker_snmp_version`;

CREATE TABLE `glpi_dropdown_plugin_tracker_snmp_version` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comments` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_tracker_agents`;

CREATE TABLE `glpi_plugin_tracker_agents` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `core_discovery` int(11) NOT NULL DEFAULT '1',
  `threads_discovery` int(11) NOT NULL DEFAULT '1',
  `core_query` int(11) NOT NULL DEFAULT '1',
  `threads_query` int(11) NOT NULL DEFAULT '1',
  `last_agent_update` datetime DEFAULT NULL,
  `tracker_agent_version` varchar(255) DEFAULT NULL,
  `lock` int(1) NOT NULL DEFAULT '0',
  `logs` int(1) NOT NULL DEFAULT '0',
  `key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fragment` int(11) NOT NULL DEFAULT '50',
  PRIMARY KEY (`ID`),
  KEY `name` (`name`),
  KEY `key` (`key`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_tracker_agents_processes`;

CREATE TABLE `glpi_plugin_tracker_agents_processes` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `process_number` varchar(255) DEFAULT NULL,
  `FK_agent` int(11) NOT NULL DEFAULT '0',
  `status` int(1) NOT NULL DEFAULT '0',
  `start_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `start_time_discovery` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end_time_discovery` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `start_time_query` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end_time_query` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `errors` int(11) NOT NULL DEFAULT '0',
  `error_msg` varchar(255) NOT NULL DEFAULT '0',
  `networking_queries` int(11) NOT NULL DEFAULT '0',
  `printers_queries` int(11) NOT NULL DEFAULT '0',
  `discovery_queries` int(11) NOT NULL DEFAULT '0',
  `discovery_queries_total` int(11) NOT NULL DEFAULT '0',
  `networking_ports_queries` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `process_number` (`process_number`,`FK_agent`),
  KEY `process_number_2` (`process_number`,`FK_agent`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_tracker_connection_history`;

CREATE TABLE `glpi_plugin_tracker_connection_history` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `FK_computers` int(11) NOT NULL DEFAULT '0',
  `date` datetime DEFAULT NULL,
  `state` int(1) NOT NULL DEFAULT '0',
  `username` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `FK_users` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_tracker_computers`;

CREATE TABLE `glpi_plugin_tracker_computers` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `FK_computers` int(11) NOT NULL,
  `FK_model_infos` int(8) NOT NULL DEFAULT '0',
  `FK_snmp_connection` int(8) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_tracker_config`;

CREATE TABLE `glpi_plugin_tracker_config` (
  `ID` int(1) NOT NULL AUTO_INCREMENT,
  `activation_history` int(1) DEFAULT NULL,
  `activation_connection` int(1) DEFAULT NULL,
  `activation_snmp_computer` int(1) NOT NULL DEFAULT '0',
  `activation_snmp_networking` int(1) DEFAULT NULL,
  `activation_snmp_peripheral` int(1) DEFAULT NULL,
  `activation_snmp_phone` int(1) DEFAULT NULL,
  `activation_snmp_printer` int(1) DEFAULT NULL,
  `authsnmp` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `URL_agent_conf` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ssl_only` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_tracker_config_discovery`;

CREATE TABLE `glpi_plugin_tracker_config_discovery` (
  `ID` int(1) NOT NULL AUTO_INCREMENT,
  `link_ip` int(1) NOT NULL DEFAULT '0',
  `link_name` int(1) NOT NULL DEFAULT '0',
  `link_serial` int(1) NOT NULL DEFAULT '0',
  `link2_ip` int(1) NOT NULL DEFAULT '0',
  `link2_name` int(1) NOT NULL DEFAULT '0',
  `link2_serial` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_tracker_config_snmp_networking`;

CREATE TABLE `glpi_plugin_tracker_config_snmp_networking` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `active_device_state` int(11) NOT NULL DEFAULT '0',
  `history_wire` int(11) NOT NULL DEFAULT '0',
  `history_ports_state` int(11) NOT NULL DEFAULT '0',
  `history_unknown_mac` int(11) NOT NULL DEFAULT '0',
  `history_snmp_errors` int(11) NOT NULL DEFAULT '0',
  `history_process` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_tracker_config_snmp_printer`;

CREATE TABLE `glpi_plugin_tracker_config_snmp_printer` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `active_device_state` int(1) NOT NULL DEFAULT '0',
  `manage_cartridges` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_tracker_config_snmp_script`;

CREATE TABLE `glpi_plugin_tracker_config_snmp_script` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `nb_process` int(11) NOT NULL DEFAULT '1',
  `logs` int(1) NOT NULL DEFAULT '0',
  `lock` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_tracker_discovery`;

CREATE TABLE `glpi_plugin_tracker_discovery` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `FK_processes` int(11) NOT NULL DEFAULT '0',
  `FK_agents` int(11) NOT NULL DEFAULT '0',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ifaddr` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `descr` text COLLATE utf8_unicode_ci,
  `serialnumber` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` int(11) NOT NULL DEFAULT '0',
  `FK_model_infos` int(11) NOT NULL DEFAULT '0',
  `FK_snmp_connection` int(11) NOT NULL DEFAULT '0',
  `FK_entities` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_tracker_errors`;

CREATE TABLE `glpi_plugin_tracker_errors` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ifaddr` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `device_type` smallint(6) NOT NULL,
  `device_id` int(11) NOT NULL DEFAULT '0',
  `FK_entities` int(11) NOT NULL DEFAULT '0',
  `first_pb_date` datetime DEFAULT NULL,
  `last_pb_date` datetime DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ifaddr` (`ifaddr`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_tracker_mib_networking`;

CREATE TABLE `glpi_plugin_tracker_mib_networking` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `FK_model_infos` int(8) DEFAULT NULL,
  `FK_mib_label` int(8) DEFAULT NULL,
  `FK_mib_oid` int(8) DEFAULT NULL,
  `FK_mib_object` int(8) DEFAULT NULL,
  `oid_port_counter` int(1) DEFAULT NULL,
  `oid_port_dyn` int(1) DEFAULT NULL,
  `mapping_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `mapping_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `activation` int(1) NOT NULL DEFAULT '1',
  `vlan` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `FK_model_infos` (`FK_model_infos`),
  KEY `FK_model_infos_2` (`FK_model_infos`,`oid_port_dyn`),
  KEY `FK_model_infos_3` (`FK_model_infos`,`oid_port_counter`,`mapping_name`),
  KEY `FK_model_infos_4` (`FK_model_infos`,`mapping_name`),
  KEY `oid_port_dyn` (`oid_port_dyn`),
  KEY `activation` (`activation`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_tracker_model_infos`;

CREATE TABLE `glpi_plugin_tracker_model_infos` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `device_type` int(8) NOT NULL DEFAULT '0',
  `deleted` int(1) DEFAULT NULL,
  `FK_entities` int(11) NOT NULL DEFAULT '0',
  `activation` int(1) NOT NULL DEFAULT '1',
  `discovery_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_tracker_networking`;

CREATE TABLE `glpi_plugin_tracker_networking` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `FK_networking` int(8) NOT NULL,
  `FK_model_infos` int(8) NOT NULL DEFAULT '0',
  `FK_snmp_connection` int(8) NOT NULL DEFAULT '0',
  `uptime` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `cpu` int(3) NOT NULL DEFAULT '0',
  `memory` int(8) NOT NULL DEFAULT '0',
  `last_tracker_update` datetime DEFAULT NULL,
  `last_PID_update` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `FK_networking` (`FK_networking`),
  KEY `FK_model_infos` (`FK_model_infos`,`FK_snmp_connection`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_tracker_networking_ifaddr`;

CREATE TABLE `glpi_plugin_tracker_networking_ifaddr` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `FK_networking` int(11) NOT NULL,
  `ifaddr` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `ifaddr` (`ifaddr`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_tracker_networking_ports`;

CREATE TABLE `glpi_plugin_tracker_networking_ports` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `FK_networking_ports` int(8) NOT NULL,
  `ifmtu` int(8) NOT NULL DEFAULT '0',
  `ifspeed` int(12) NOT NULL DEFAULT '0',
  `ifinternalstatus` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ifconnectionstatus` int(8) NOT NULL DEFAULT '0',
  `iflastchange` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ifinoctets` bigint(50) NOT NULL DEFAULT '0',
  `ifinerrors` bigint(50) NOT NULL DEFAULT '0',
  `ifoutoctets` bigint(50) NOT NULL DEFAULT '0',
  `ifouterrors` bigint(50) NOT NULL DEFAULT '0',
  `ifstatus` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ifmac` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ifdescr` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `portduplex` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `trunk` int(1) NOT NULL DEFAULT '0',
  `lastup` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`),
  KEY `FK_networking_ports` (`FK_networking_ports`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_tracker_printers_history`;

CREATE TABLE `glpi_plugin_tracker_printers_history` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `FK_printers` int(11) NOT NULL DEFAULT '0',
  `date` datetime DEFAULT '0000-00-00 00:00:00',
  `pages_total` int(11) NOT NULL DEFAULT '0',
  `pages_n_b` int(11) NOT NULL DEFAULT '0',
  `pages_color` int(11) NOT NULL DEFAULT '0',
  `pages_recto_verso` int(11) NOT NULL DEFAULT '0',
  `scanned` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_tracker_printers`;

CREATE TABLE `glpi_plugin_tracker_printers` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `FK_printers` int(11) NOT NULL,
  `FK_model_infos` int(8) NOT NULL DEFAULT '0',
  `FK_snmp_connection` int(8) NOT NULL DEFAULT '0',
  `frequence_days` int(5) NOT NULL DEFAULT '1',
  `last_tracker_update` datetime DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `FK_printers` (`FK_printers`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_tracker_printers_cartridges`;

CREATE TABLE `glpi_plugin_tracker_printers_cartridges` (
  `ID` int(100) NOT NULL AUTO_INCREMENT,
  `FK_printers` int(11) NOT NULL,
  `object_name` varchar(255) NOT NULL,
  `FK_cartridges` int(11) NOT NULL DEFAULT '0',
  `state` int(3) NOT NULL DEFAULT '100',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_tracker_processes`;

CREATE TABLE `glpi_plugin_tracker_processes` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `thread_id` int(4) NOT NULL DEFAULT '0',
  `start_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` int(4) NOT NULL DEFAULT '0',
  `error_msg` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `process_id` int(11) NOT NULL DEFAULT '0',
  `network_queries` int(8) NOT NULL DEFAULT '0',
  `printer_queries` int(8) NOT NULL DEFAULT '0',
  `ports_queries` int(8) NOT NULL DEFAULT '0',
  `discovery_queries` int(8) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `end_time` (`end_time`),
  KEY `process_id` (`process_id`),
  KEY `network_queries` (`network_queries`,`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_tracker_processes_values`;

CREATE TABLE `glpi_plugin_tracker_processes_values` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `FK_processes` int(8) NOT NULL,
  `device_ID` int(8) NOT NULL DEFAULT '0',
  `device_type` int(8) NOT NULL DEFAULT '0',
  `port` int(8) NOT NULL DEFAULT '0',
  `unknow_mac` varchar(255) DEFAULT NULL,
  `snmp_errors` varchar(255) DEFAULT NULL,
  `dropdown_add` varchar(255) DEFAULT NULL,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`),
  KEY `unknow_mac` (`unknow_mac`,`FK_processes`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_tracker_profiles`;

CREATE TABLE IF NOT EXISTS `glpi_plugin_tracker_profiles` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `interface` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'tracker',
  `is_default` enum('0','1') COLLATE utf8_unicode_ci DEFAULT NULL,
  `snmp_networking` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `snmp_printers` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `snmp_models` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `snmp_authentification` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `snmp_scripts_infos` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `snmp_discovery` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `general_config` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `snmp_iprange` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `snmp_agent` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `snmp_agent_infos` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `snmp_report` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_tracker_rangeip`;

CREATE TABLE `glpi_plugin_tracker_rangeip` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `FK_tracker_agents` int(11) NOT NULL DEFAULT '0',
  `ifaddr_start` varchar(255) DEFAULT NULL,
  `ifaddr_end` varchar(255) DEFAULT NULL,
  `discover` int(1) NOT NULL DEFAULT '0',
  `query` int(1) NOT NULL DEFAULT '0',
  `FK_entities` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `FK_tracker_agents` (`FK_tracker_agents`,`discover`),
  KEY `FK_tracker_agents_2` (`FK_tracker_agents`,`query`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_tracker_snmp_connection`;

CREATE TABLE `glpi_plugin_tracker_snmp_connection` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `FK_snmp_version` int(8) NOT NULL,
  `community` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sec_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sec_level` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_protocol` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_passphrase` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `priv_protocol` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `priv_passphrase` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `glpi_plugin_tracker_snmp_history`;

CREATE TABLE `glpi_plugin_tracker_snmp_history` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `FK_ports` int(11) NOT NULL,
  `Field` varchar(255) NOT NULL DEFAULT '0',
  `date_mod` datetime DEFAULT NULL,
  `old_value` varchar(255) DEFAULT NULL,
  `old_device_type` int(11) NOT NULL DEFAULT '0',
  `old_device_ID` int(11) NOT NULL DEFAULT '0',
  `new_value` varchar(255) DEFAULT NULL,
  `new_device_type` int(11) NOT NULL DEFAULT '0',
  `new_device_ID` int(11) NOT NULL DEFAULT '0',
  `FK_process` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `FK_ports` (`FK_ports`,`date_mod`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `glpi_plugin_tracker_unknown_mac`;

CREATE TABLE `glpi_plugin_tracker_unknown_mac` (
   `ID` INT( 100 ) NOT NULL AUTO_INCREMENT,
   `start_FK_processes` INT( 8 ) NOT NULL,
   `end_FK_processes` INT( 8 ) NOT NULL,
   `start_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
   `end_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
   `port` INT( 8 ) NOT NULL,
   `unknow_mac` VARCHAR( 255 ) NOT NULL,
   `unknown_ip` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   PRIMARY KEY (`ID`),
   UNIQUE KEY `start_FK_processes` (`start_FK_processes`,`end_FK_processes`,`port`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `glpi_plugin_tracker_connection_stats`;

CREATE TABLE `glpi_plugin_tracker_connection_stats` (
  `ID` int(11) NOT NULL auto_increment,
  `device_type` int(11) NOT NULL default '0',
  `item_id` int(11) NOT NULL,
  `checksum` timestamp NOT NULL default '0000-00-00 00:00:00' on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `glpi_plugin_tracker_walks`;

CREATE TABLE `glpi_plugin_tracker_walks` (
  `ID` int(30) NOT NULL AUTO_INCREMENT,
  `on_device` int(11) NOT NULL DEFAULT '0',
  `device_type` int(11) NOT NULL DEFAULT '0',
  `FK_agents_processes` int(11) NOT NULL DEFAULT '0',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `vlan` varchar(255) DEFAULT NULL,
  `oid` varchar(255) DEFAULT NULL,
  `value` text,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


INSERT INTO `glpi_dropdown_plugin_tracker_snmp_auth_auth_protocol` VALUES (1,'MD5','');
INSERT INTO `glpi_dropdown_plugin_tracker_snmp_auth_auth_protocol` VALUES (2,'SHA','');
INSERT INTO `glpi_dropdown_plugin_tracker_snmp_auth_priv_protocol` VALUES (3,'DES','');
INSERT INTO `glpi_dropdown_plugin_tracker_snmp_auth_priv_protocol` VALUES (4,'AES128','');
INSERT INTO `glpi_dropdown_plugin_tracker_snmp_auth_priv_protocol` VALUES (5,'AES192','');
INSERT INTO `glpi_dropdown_plugin_tracker_snmp_auth_priv_protocol` VALUES (6,'AES256','');

INSERT INTO `glpi_dropdown_plugin_tracker_snmp_auth_sec_level` VALUES (1,'noAuthNoPriv','');
INSERT INTO `glpi_dropdown_plugin_tracker_snmp_auth_sec_level` VALUES (2,'authNoPriv','');
INSERT INTO `glpi_dropdown_plugin_tracker_snmp_auth_sec_level` VALUES (3,'authPriv','');

INSERT INTO `glpi_dropdown_plugin_tracker_snmp_version` VALUES (1,'1','');
INSERT INTO `glpi_dropdown_plugin_tracker_snmp_version` VALUES (2,'2c','');
INSERT INTO `glpi_dropdown_plugin_tracker_snmp_version` VALUES (3,'3','');

INSERT INTO `glpi_plugin_tracker_snmp_connection` VALUES (1, 'Communauté Public v1', '1', 'public', '', '0', '0', '', '0', '', '0');
INSERT INTO `glpi_plugin_tracker_snmp_connection` VALUES (2, 'Communauté Public v2c', '2', 'public', '', '0', '0', '', '0', '', '0');

INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5150','3','1','0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5150','4','2','0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5150','6','3','0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5150','7','4','0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5150','8','5','0');

INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL, '5151', '3', '1', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL, '5151', '5', '2', '0');

INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL, '5152', '3', '1', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL, '5152', '4', '2', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL, '5152', '5', '3', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL, '5152', '6', '4', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL, '5152', '7', '5', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL, '5152', '8', '6', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL, '5152', '9', '7', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL, '5152', '10', '8', '0');

INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL, '5153', '2', '1', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL, '5153', '4', '2', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL, '5153', '5', '3', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL, '5153', '6', '4', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL, '5153', '7', '5', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL, '5153', '8', '6', '0');

INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5157', '2', '1', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5157', '3', '2', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5157', '4', '3', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5157', '5', '4', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5157', '6', '5', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5157', '7', '6', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5157', '8', '7', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5157', '9', '8', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5157', '10', '9', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5157', '11', '10', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5157', '12', '11', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5157', '13', '12', '0');

INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5158', '8', '1', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5158', '9', '2', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5158', '10', '3', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5158', '11', '4', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5158', '12', '5', '0');

INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5159', '2', '1', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5159', '3', '2', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5159', '5', '3', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5159', '6', '4', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5159', '7', '5', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5159', '8', '6', '0');

INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5160', '3', '1', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5160', '4', '2', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5160', '5', '3', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5160', '6', '4', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5160', '7', '5', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5160', '8', '6', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5160', '9', '7', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5160', '10', '8', '0');

INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5161', '2', '1', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5161', '3', '2', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5161', '4', '3', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5161', '5', '4', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5161', '6', '5', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5161', '7', '6', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5161', '8', '7', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5161', '9', '8', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5161', '10', '9', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5161', '11', '10', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5161', '12', '11', '0');

INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5162', '2', '1', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5162', '3', '2', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5162', '4', '3', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5162', '5', '4', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5162', '6', '5', '0');

INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5163', '2', '1', '0');
INSERT INTO `glpi_display` (`ID`, `type`, `num`, `rank`, `FK_users`) VALUES (NULL,'5163', '3', '2', '0');
