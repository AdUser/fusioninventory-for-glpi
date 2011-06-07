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
   Co-authors of file:
   Purpose of file:
   ----------------------------------------------------------------------
 */

$title="FusionInventory";

$LANG['plugin_fusioninventory']['title'][0]="$title";
$LANG['plugin_fusioninventory']['title'][1]="FusInv";
$LANG['plugin_fusioninventory']['title'][5]="Bloqueios";

$LANG['plugin_fusioninventory']['config'][0] = "Frequência do inventário (em horas)";

$LANG['plugin_fusioninventory']['profile'][0]="Gerenciamento de permissões";
$LANG['plugin_fusioninventory']['profile'][2]="Agents";
$LANG['plugin_fusioninventory']['profile'][3]="Agent remote controle";
$LANG['plugin_fusioninventory']['profile'][4]="Configuration";
$LANG['plugin_fusioninventory']['profile'][5]="WakeOnLan";
$LANG['plugin_fusioninventory']['profile'][6]="Unknown devices";
$LANG['plugin_fusioninventory']['profile'][7]="Tasks";

$LANG['plugin_fusioninventory']['setup'][16]="Documentação";
$LANG['plugin_fusioninventory']['setup'][17]="Outros plugins do FusionInventory (fusinv...) deve ser desinstalado antes de desinstalar o plugin FusionInventory";

$LANG['plugin_fusioninventory']['functionalities'][0]="Funções";
$LANG['plugin_fusioninventory']['functionalities'][2]="Configuração geral";
$LANG['plugin_fusioninventory']['functionalities'][6]="Legenda";
$LANG['plugin_fusioninventory']['functionalities'][8]="Agent port";
$LANG['plugin_fusioninventory']['functionalities'][9]="Retenção em dias";
$LANG['plugin_fusioninventory']['functionalities'][16]="Autenticação SNMP";
$LANG['plugin_fusioninventory']['functionalities'][17]="Banco de dados";
$LANG['plugin_fusioninventory']['functionalities'][18]="Arquivos";
$LANG['plugin_fusioninventory']['functionalities'][19]="Por favor, configure a autenticação SNMP na configuração do plugin";
$LANG['plugin_fusioninventory']['functionalities'][27]="Somente SSL para o agente";
$LANG['plugin_fusioninventory']['functionalities'][29]="Lista de campos para o histórico";
$LANG['plugin_fusioninventory']['functionalities'][32]="Apagar tarefas depois";
$LANG['plugin_fusioninventory']['functionalities'][60]="Limpar histórico";
$LANG['plugin_fusioninventory']['functionalities'][73]="Campos";
$LANG['plugin_fusioninventory']['functionalities'][74]="Valores";
$LANG['plugin_fusioninventory']['functionalities'][75]="Bloqueios";
$LANG['plugin_fusioninventory']['functionalities'][76]="Extra-debug";

$LANG['plugin_fusioninventory']['errors'][22]="Elemento autônomo em";
$LANG['plugin_fusioninventory']['errors'][50]="Versão do GLPI não compatível. Necessário versão 0.80";
$LANG['plugin_fusioninventory']['errors'][1] = "PHP allow_url_fopen is off, can't wake agent to do inventory";
$LANG['plugin_fusioninventory']['errors'][2] = "PHP allow_url_fopen is off, push mode can't work";

$LANG['plugin_fusioninventory']['rules'][2]="Equipment import and link rules";
$LANG['plugin_fusioninventory']['rules'][3]="Search GLPI equipment with the status";
$LANG['plugin_fusioninventory']['rules'][4]="Destination of equipment entity";
$LANG['plugin_fusioninventory']['rules'][5]="FusionInventory link";
$LANG['plugin_fusioninventory']['rules'][6] = "Link if possible, else import denied";
$LANG['plugin_fusioninventory']['rules'][7] = "Link if possible, else create device";
$LANG['plugin_fusioninventory']['rules'][8] = "Send";
$LANG['plugin_fusioninventory']['rules'][9]  = "exist";
$LANG['plugin_fusioninventory']['rules'][10]  = "not exist";
$LANG['plugin_fusioninventory']['rules'][11] = "is present in GLPI";
$LANG['plugin_fusioninventory']['rules'][12] = "is empty";
$LANG['plugin_fusioninventory']['rules'][13] = "Hard disk serial number";
$LANG['plugin_fusioninventory']['rules'][14] = "Partition serial number";
$LANG['plugin_fusioninventory']['rules'][15] = "uuid";
$LANG['plugin_fusioninventory']['rules'][16] = "FusionInventory tag";
$LANG['plugin_fusioninventory']['rules'][17] = "Import denied";
$LANG['plugin_fusioninventory']['rules'][18] = "Device created";

$LANG['plugin_fusioninventory']['rulesengine'][152] = "Equipment to import";

$LANG['plugin_fusioninventory']['choice'][0] = "No";
$LANG['plugin_fusioninventory']['choice'][1] = "Yes";
$LANG['plugin_fusioninventory']['choice'][2] = "or";
$LANG['plugin_fusioninventory']['choice'][3] = "and";

$LANG['plugin_fusioninventory']['processes'][1]="PID";
$LANG['plugin_fusioninventory']['processes'][38]="Process number";

$LANG['plugin_fusioninventory']['menu'][1]="Configuração do agente";
$LANG['plugin_fusioninventory']['menu'][2]="IP range configuration";
$LANG['plugin_fusioninventory']['menu'][3]="Menu";
$LANG['plugin_fusioninventory']['menu'][4]="Dispositivo desconhecido";
$LANG['plugin_fusioninventory']['menu'][5] = "Credentials";
$LANG['plugin_fusioninventory']['menu'][6]="Remote devices to inventory";
$LANG['plugin_fusioninventory']['menu'][7]="Trabalhos em execução";

$LANG['plugin_fusioninventory']['discovery'][5]="Número de dispositivos importados";
$LANG['plugin_fusioninventory']['discovery'][9]="Número de dispositivos não importados devido ao tipo não definido";

$LANG['plugin_fusioninventory']['agents'][4]="Last contact";
$LANG['plugin_fusioninventory']['agents'][6]="desabilitar";
$LANG['plugin_fusioninventory']['agents'][15]="Estado do agente";
$LANG['plugin_fusioninventory']['agents'][17]="Agente está executado";
$LANG['plugin_fusioninventory']['agents'][22]="Espera";
$LANG['plugin_fusioninventory']['agents'][23]="Ligação de computador";
$LANG['plugin_fusioninventory']['agents'][24]="Token";
$LANG['plugin_fusioninventory']['agents'][25]="Versão";
$LANG['plugin_fusioninventory']['agents'][27]="Módulos dos agentes";
$LANG['plugin_fusioninventory']['agents'][28]="Agente";
$LANG['plugin_fusioninventory']['agents'][30]="Impossible to communicate with agent!";
$LANG['plugin_fusioninventory']['agents'][31]="Force inventory";
$LANG['plugin_fusioninventory']['agents'][32]="Auto managenement dynamic of agents";
$LANG['plugin_fusioninventory']['agents'][33]="Auto managenement dynamic of agents (same subnet)";
$LANG['plugin_fusioninventory']['agents'][34]="Activation (by default)";
$LANG['plugin_fusioninventory']['agents'][35]="Device_id";
$LANG['plugin_fusioninventory']['agents'][36]="Agent modules";
$LANG['plugin_fusioninventory']['agents'][37]="locked";
$LANG['plugin_fusioninventory']['agents'][38]="Available";
$LANG['plugin_fusioninventory']['agents'][39]="Running";
$LANG['plugin_fusioninventory']['agents'][40]="Computer without known IP";
$LANG['plugin_fusioninventory']['agents'][41] = "Service URL";

$LANG['plugin_fusioninventory']['unknown'][2]="Dispositivos aprovados";
$LANG['plugin_fusioninventory']['unknown'][4]="Hub de rede";
$LANG['plugin_fusioninventory']['unknown'][5]="Import unknown device into asset";

$LANG['plugin_fusioninventory']['task'][0]="Tarefa";
$LANG['plugin_fusioninventory']['task'][1]="Gerenciamento de tarefa";
$LANG['plugin_fusioninventory']['task'][2]="Ação";
$LANG['plugin_fusioninventory']['task'][14]="Data da cobrança";
$LANG['plugin_fusioninventory']['task'][15]="Nova ação";
$LANG['plugin_fusioninventory']['task'][17]="Frequência";
$LANG['plugin_fusioninventory']['task'][18]="Tarefas";
$LANG['plugin_fusioninventory']['task'][19]="Tarefas em execução";
$LANG['plugin_fusioninventory']['task'][20]="Tarefas finalizadas";
$LANG['plugin_fusioninventory']['task'][21]="Ação sobre este material";
$LANG['plugin_fusioninventory']['task'][22]="Somente tarefas planejadas";
$LANG['plugin_fusioninventory']['task'][24]="Number of trials";
$LANG['plugin_fusioninventory']['task'][25]="Time between 2 trials (in minutes)";
$LANG['plugin_fusioninventory']['task'][26]="Module";
$LANG['plugin_fusioninventory']['task'][27]="Definition";
$LANG['plugin_fusioninventory']['task'][28]="Action";
$LANG['plugin_fusioninventory']['task'][29]="Type";
$LANG['plugin_fusioninventory']['task'][30]="Selection";
$LANG['plugin_fusioninventory']['task'][31]="Time between task start and start this action";
$LANG['plugin_fusioninventory']['task'][32]="Force the end";
$LANG['plugin_fusioninventory']['task'][33]="Communication type";
$LANG['plugin_fusioninventory']['task'][34]="Permanent";
$LANG['plugin_fusioninventory']['task'][35]="minutes";
$LANG['plugin_fusioninventory']['task'][36]="hours";
$LANG['plugin_fusioninventory']['task'][37]="days";
$LANG['plugin_fusioninventory']['task'][38]="months";
$LANG['plugin_fusioninventory']['task'][39]="Unable to run task because some jobs is running yet!";
$LANG['plugin_fusioninventory']['task'][40]="Force running";
$LANG['plugin_fusioninventory']['task'][41]="Server contacts the agent (push)";
$LANG['plugin_fusioninventory']['task'][42]="Agent contacts the server (pull)";
$LANG['plugin_fusioninventory']['task'][43]="Communication mode";
$LANG['plugin_fusioninventory']['task'][44]="See all informations of task";
$LANG['plugin_fusioninventory']['task'][45]="Advanced options";

$LANG['plugin_fusioninventory']['taskjoblog'][1]="Iniciado";
$LANG['plugin_fusioninventory']['taskjoblog'][2]="Ok";
$LANG['plugin_fusioninventory']['taskjoblog'][3]="Erro / replanejado";
$LANG['plugin_fusioninventory']['taskjoblog'][4]="Erro";
$LANG['plugin_fusioninventory']['taskjoblog'][5]="desconhecido";
$LANG['plugin_fusioninventory']['taskjoblog'][6]="Running";
$LANG['plugin_fusioninventory']['taskjoblog'][7]="Prepared";

$LANG['plugin_fusioninventory']['update'][0]="sua tabela de histórico tem mais de 300.000 entradas, você deve executar este comando para finalizar a atualização : ";

$LANG['plugin_fusioninventory']['xml'][0]="XML";

$LANG['plugin_fusioninventory']['codetasklog'][1]="Bad token, impossible to start agent";
$LANG['plugin_fusioninventory']['codetasklog'][2]="Agent stopped/crashed";
$LANG['plugin_fusioninventory']['codetasklog'][3]=$LANG['plugin_fusioninventory']['rules'][17];

$LANG['plugin_fusioninventory']['credential'][1] = "Credential for remote inventory";
$LANG['plugin_fusioninventory']['credential'][2] = "Remote device inventory";
$LANG['plugin_fusioninventory']['credential'][3] = "Credentials";

$LANG['plugin_fusioninventory']['locks'][0]="Delete locks";
$LANG['plugin_fusioninventory']['locks'][1]="Add locks";

$LANG['plugin_fusioninventory']['iprange'][0]="Start of IP range";
$LANG['plugin_fusioninventory']['iprange'][1]="End of IP range";
$LANG['plugin_fusioninventory']['iprange'][2]="IP Ranges";
$LANG['plugin_fusioninventory']['iprange'][3]="Query";
$LANG['plugin_fusioninventory']['iprange'][7]="Bad IP";

$LANG['plugin_fusioninventory']['buttons'][0]="Finish";

$LANG['plugin_fusioninventory']['wizard'][0]="Action choice";
$LANG['plugin_fusioninventory']['wizard'][1]="Type of device to inventory";
$LANG['plugin_fusioninventory']['wizard'][2]="Import options";
$LANG['plugin_fusioninventory']['wizard'][3]="Entity rules";
$LANG['plugin_fusioninventory']['wizard'][4]="Agent configuration";
$LANG['plugin_fusioninventory']['wizard'][5]="Credential management";
$LANG['plugin_fusioninventory']['wizard'][6]="ESX servers management";
$LANG['plugin_fusioninventory']['wizard'][7]="Run tasks";
$LANG['plugin_fusioninventory']['wizard'][8]="Tasks running result";
$LANG['plugin_fusioninventory']['wizard'][9]="SNMP authentication";
$LANG['plugin_fusioninventory']['wizard'][10]="Choice (netdiscovery or inventory)";
$LANG['plugin_fusioninventory']['wizard'][11]="Breadcrumb";
$LANG['plugin_fusioninventory']['wizard'][12]="Discover the network";
$LANG['plugin_fusioninventory']['wizard'][13]="Inventory devices";
$LANG['plugin_fusioninventory']['wizard'][14]="Welcome in FusionInventory. Begin configuration?";
$LANG['plugin_fusioninventory']['wizard'][15]="Computers and peripherals";
$LANG['plugin_fusioninventory']['wizard'][16]="ESX servers";
$LANG['plugin_fusioninventory']['wizard'][17]="Network devices and printers";

?>