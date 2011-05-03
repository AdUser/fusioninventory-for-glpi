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
   Original Author of file: David DURIEUX
   Co-authors of file: Mathieu SIMON, Alexander Lüthy
   Purpose of file:
   ----------------------------------------------------------------------
 */

$title="FusionInventory";
$version="2.4.0";

$LANG['plugin_fusioninventory']['title'][0]="$title";
$LANG['plugin_fusioninventory']['title'][1]="FusInv";
$LANG['plugin_fusioninventory']['title'][5]="Sperrung";

$LANG['plugin_fusioninventory']['config'][0] = "Regelmässigkeit der Inventarisierung (in Stunden)";

$LANG['plugin_fusioninventory']['profile'][0]="Rechteverwaltung";
$LANG['plugin_fusioninventory']['profile'][2]="Agents";
$LANG['plugin_fusioninventory']['profile'][3]="Fernverwaltung der Agents";
$LANG['plugin_fusioninventory']['profile'][4]="Konfiguration";
$LANG['plugin_fusioninventory']['profile'][5]="Wake On LAN";
$LANG['plugin_fusioninventory']['profile'][6]="Unbekannte Geräte";
$LANG['plugin_fusioninventory']['profile'][7]="Aufgaben";

$LANG['plugin_fusioninventory']['setup'][16]="Dokumentation";
$LANG['plugin_fusioninventory']['setup'][17]="Andere FusionInventory Plugins (fusinv...) vorher deinstalliert werden, um das FusionInventory Plugin zu deinstallieren";

$LANG['plugin_fusioninventory']['functionalities'][0]="Funktionen";
$LANG['plugin_fusioninventory']['functionalities'][2]="Grundlegende Konfiguration";
$LANG['plugin_fusioninventory']['functionalities'][6]="Legende";
$LANG['plugin_fusioninventory']['functionalities'][8]="Netzwerkports Agents";
$LANG['plugin_fusioninventory']['functionalities'][9]="Speicherung (in Tagen)";
$LANG['plugin_fusioninventory']['functionalities'][16]="SNMP-Authentifizierung ";
$LANG['plugin_fusioninventory']['functionalities'][17]="Datenbank";
$LANG['plugin_fusioninventory']['functionalities'][18]="Dateien";
$LANG['plugin_fusioninventory']['functionalities'][19]="Bitte Konfigurieren Sie die SNMP-Authentifizierung im Setup des Plugins";
$LANG['plugin_fusioninventory']['functionalities'][27]="Nur SSL für die Kommunikation mit dem Agent verwenden";
$LANG['plugin_fusioninventory']['functionalities'][29]="Feldliste, die in den Verlauf verschoben werden";
$LANG['plugin_fusioninventory']['functionalities'][32]="Agent-Aufgabe am Ende löschen";
$LANG['plugin_fusioninventory']['functionalities'][60]="Verlauf löschen";
$LANG['plugin_fusioninventory']['functionalities'][73]="Felder";
$LANG['plugin_fusioninventory']['functionalities'][74]="Werte";
$LANG['plugin_fusioninventory']['functionalities'][75]="Sperrungen";
$LANG['plugin_fusioninventory']['functionalities'][76]="Extra-debug";

$LANG['plugin_fusioninventory']['errors'][22]="Nicht erkannte Elemente in";
$LANG['plugin_fusioninventory']['errors'][50]="Ihre GLPI-Version ist nicht kompatibel, Voraussetzung ist Version 0.78";
$LANG['plugin_fusioninventory']['errors'][1] = "PHP allow_url_fopen is off, can't wake agent to do inventory";
$LANG['plugin_fusioninventory']['errors'][2] = "PHP allow_url_fopen is off, push mode can't work";

$LANG['plugin_fusioninventory']['rules'][2]="Importregeln für Geräte";
$LANG['plugin_fusioninventory']['rules'][3]="Durchsuche GLPI nach Material mit dem Status";
$LANG['plugin_fusioninventory']['rules'][4]="Ziel-Entität des Materials";
$LANG['plugin_fusioninventory']['rules'][5]="FusionInventory-Verbindung";
$LANG['plugin_fusioninventory']['rules'][6] = "Verbinde wenn möglich, andernfalls verbiete den Import";
$LANG['plugin_fusioninventory']['rules'][7] = "Verbind wenn möglich";
$LANG['plugin_fusioninventory']['rules'][8] = "Sende";
$LANG['plugin_fusioninventory']['rules'][9] = "existiert";
$LANG['plugin_fusioninventory']['rules'][10] = "existiert nicht";
$LANG['plugin_fusioninventory']['rules'][11] = "existiert in GLPI";
$LANG['plugin_fusioninventory']['rules'][12] = "ist leer";
$LANG['plugin_fusioninventory']['rules'][13] = "Seriennummer Festplatte";
$LANG['plugin_fusioninventory']['rules'][14] = "Seriennummer Partition";
$LANG['plugin_fusioninventory']['rules'][15] = "UUID";
$LANG['plugin_fusioninventory']['rules'][16] = "FusionInventory Tag";

$LANG['plugin_fusioninventory']['rulesengine'][152] = "Material zum importieren";

$LANG['plugin_fusioninventory']['choice'][0] = "Nein";
$LANG['plugin_fusioninventory']['choice'][1] = "Ja";
$LANG['plugin_fusioninventory']['choice'][2] = "oder";
$LANG['plugin_fusioninventory']['choice'][3] = "und";

$LANG['plugin_fusioninventory']['processes'][1]="PID";
$LANG['plugin_fusioninventory']['processes'][38]="Prozessnummer";

$LANG['plugin_fusioninventory']['menu'][1]="Agent-Verwaltung";
$LANG['plugin_fusioninventory']['menu'][3]="Menü";
$LANG['plugin_fusioninventory']['menu'][4]="Ubekanntes Gerät";
$LANG['plugin_fusioninventory']['menu'][5] = "Credentials";
$LANG['plugin_fusioninventory']['menu'][6]="Remote devices to inventory";
$LANG['plugin_fusioninventory']['menu'][7]="Aktive / Laufende Aufgaben";

$LANG['plugin_fusioninventory']['discovery'][5]="Anzahl importierter Geräte";
$LANG['plugin_fusioninventory']['discovery'][9]="Number of devices not imported because type non defined";

$LANG['plugin_fusioninventory']['agents'][4]="Letzter Kontakt";
$LANG['plugin_fusioninventory']['agents'][6]="Deaktiviert";
$LANG['plugin_fusioninventory']['agents'][15]="Agent-Status";
$LANG['plugin_fusioninventory']['agents'][17]="Agent läuft zur Zeit";
$LANG['plugin_fusioninventory']['agents'][22]="Wartend";
$LANG['plugin_fusioninventory']['agents'][23]="Computerverbindung";
$LANG['plugin_fusioninventory']['agents'][24]="Token";
$LANG['plugin_fusioninventory']['agents'][25]="Version";
$LANG['plugin_fusioninventory']['agents'][27]="Agent-Module";
$LANG['plugin_fusioninventory']['agents'][28]="Agent";
$LANG['plugin_fusioninventory']['agents'][30]="Kann nicht mit dem Agent kommunizieren!";
$LANG['plugin_fusioninventory']['agents'][31]="Inventarisierung erzwingen";
$LANG['plugin_fusioninventory']['agents'][32]="Dynamische Auto-Verwaltung des Agents";
$LANG['plugin_fusioninventory']['agents'][33]="Dynamische Auto-Verwaltung des Agents (gleiches Subnetz)";
$LANG['plugin_fusioninventory']['agents'][34]="Aktivierung (Standard)";
$LANG['plugin_fusioninventory']['agents'][35]="Device_id";
$LANG['plugin_fusioninventory']['agents'][36]="Agenten-Module";
$LANG['plugin_fusioninventory']['agents'][37]="gesperrt";
$LANG['plugin_fusioninventory']['agents'][38]="Available";
$LANG['plugin_fusioninventory']['agents'][39]="Running";
$LANG['plugin_fusioninventory']['agents'][40]="Computer without known IP";
$LANG['plugin_fusioninventory']['agents'][41] = "Service URL";

$LANG['plugin_fusioninventory']['unknown'][2]="Genehmigte Geräte";
$LANG['plugin_fusioninventory']['unknown'][4]="Netzwerk-Hub";
$LANG['plugin_fusioninventory']['unknown'][5]="Import unknown device into asset";

$LANG['plugin_fusioninventory']['task'][0]="Aufgabe";
$LANG['plugin_fusioninventory']['task'][1]="Aufgabenverwaltung";
$LANG['plugin_fusioninventory']['task'][2]="Aufgabe / Befehl";
$LANG['plugin_fusioninventory']['task'][14]="Geplanter Zeitpunkt";
$LANG['plugin_fusioninventory']['task'][16]="Neue Aufgabe";
$LANG['plugin_fusioninventory']['task'][17]="Regelmässigkeit";
$LANG['plugin_fusioninventory']['task'][18]="Aufgaben";
$LANG['plugin_fusioninventory']['task'][19]="Laufende Augfaben";
$LANG['plugin_fusioninventory']['task'][20]="Beendete Aufgaben";
$LANG['plugin_fusioninventory']['task'][21]="Aufgabe zu diesem Gerät";
$LANG['plugin_fusioninventory']['task'][22]="Nur geplante Aufgaben";
$LANG['plugin_fusioninventory']['task'][24]="Anzahl Versuche";
$LANG['plugin_fusioninventory']['task'][25]="Zeitabstand zwischen 2 Versuchen (in Minuten)";
$LANG['plugin_fusioninventory']['task'][26]="Modul";
$LANG['plugin_fusioninventory']['task'][27]="Definition";
$LANG['plugin_fusioninventory']['task'][28]="Aktion";
$LANG['plugin_fusioninventory']['task'][29]="Typ";
$LANG['plugin_fusioninventory']['task'][30]="Auswahl";
$LANG['plugin_fusioninventory']['task'][31]="Zeitverzögerung zwischen Aufgabe und gewählter Aufgabe";
$LANG['plugin_fusioninventory']['task'][32]="Beendigung erzwingen";
$LANG['plugin_fusioninventory']['task'][33]="Kommunikationstyp";
$LANG['plugin_fusioninventory']['task'][34]="dauerhaft";
$LANG['plugin_fusioninventory']['task'][35]="Minuten";
$LANG['plugin_fusioninventory']['task'][36]="Stunden";
$LANG['plugin_fusioninventory']['task'][37]="Tage";
$LANG['plugin_fusioninventory']['task'][38]="Monate";
$LANG['plugin_fusioninventory']['task'][39]="Unable to run task because some jobs is running yet!";
$LANG['plugin_fusioninventory']['task'][40]="Force running";
$LANG['plugin_fusioninventory']['task'][41]="Server contacts the agent (push)";
$LANG['plugin_fusioninventory']['task'][42]="Agent contacts the server (pull)";

$LANG['plugin_fusioninventory']['taskjoblog'][1]="gestartet";
$LANG['plugin_fusioninventory']['taskjoblog'][2]="OK";
$LANG['plugin_fusioninventory']['taskjoblog'][3]="Fehler / Erneuter Versuch geplant";
$LANG['plugin_fusioninventory']['taskjoblog'][4]="Fehler";
$LANG['plugin_fusioninventory']['taskjoblog'][5]="Unbekannt";
$LANG['plugin_fusioninventory']['taskjoblog'][6]="Aktiv";
$LANG['plugin_fusioninventory']['taskjoblog'][7]="Vorbereitet";

$LANG['plugin_fusioninventory']['update'][0]="Ihre Verlaufstabelle hat mehr als 300'000 Einträge, Sie müssen dieses Kommando ausführen um das Update abzuschliessen: ";

$LANG['plugin_fusioninventory']['xml'][0]="XML";

$LANG['plugin_fusioninventory']['codetasklog'][1]="Falcher Token, kann den Agent nicht starten";
$LANG['plugin_fusioninventory']['codetasklog'][2]="Agent gestoppt/abgestürzt";
$LANG['plugin_fusioninventory']['codetasklog'][3]=$LANG['ocsconfig'][11];

$LANG['plugin_fusioninventory']['credential'][1] = "Credential for remote inventory";
$LANG['plugin_fusioninventory']['credential'][2] = "Remote device inventory";
$LANG['plugin_fusioninventory']['credential'][3] = "Credentials";

$LANG['plugin_fusioninventory']['locks'][0]="Delete locks";
$LANG['plugin_fusioninventory']['locks'][1]="Add locks";

$LANG['plugin_fusioninventory']['iprange'][0]="Start des IP-Adressbereichs";
$LANG['plugin_fusioninventory']['iprange'][1]="Ende des IP-Adressbereichs";
$LANG['plugin_fusioninventory']['iprange'][2]="IP-Adressbereich";
$LANG['plugin_fusioninventory']['iprange'][3]="Abfrage";
$LANG['plugin_fusioninventory']['iprange'][7]="IP-Adresse inkorrekt";

?>