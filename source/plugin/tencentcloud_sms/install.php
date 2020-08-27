<?php
/*
 * Copyright (C) 2020 Tencent Cloud.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
if (!defined('IN_DISCUZ') || !defined('IN_ADMINCP')){
    exit('Access Denied');
}
defined('TENCENT_DISCUZX_SMS_DIR')||define( 'TENCENT_DISCUZX_SMS_DIR', __DIR__.DIRECTORY_SEPARATOR);
if (!is_file(TENCENT_DISCUZX_SMS_DIR.'vendor/autoload.php')) {
    exit(lang('plugin/tencentcloud_sms','require_sdk'));
}
require_once 'vendor/autoload.php';
use TencentDiscuzSMS\SMSActions;
global $_G;
$careatesql = "CREATE TABLE IF NOT EXISTS cdb_tencentcloud_pluginInfo (
       `plugin_name` varchar(150) NOT NULL DEFAULT '',
       `version` varchar(32) NOT NULL DEFAULT '',
       `href` varchar(255) NOT NULL  DEFAULT '',
       `plugin_id` varchar(255) NOT NULL DEFAULT '',
       `activation` varchar(32) NOT NULL DEFAULT '',
       `status` varchar(32) NOT NULL DEFAULT '',
       `install_datetime` bigint NOT NULL DEFAULT 0,
       `last_modify_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP(),
       PRIMARY KEY (`plugin_name`)
) ENGINE=InnoDB;
";
runquery($careatesql);
$href = ADMINSCRIPT.'?action=plugins&operation=config&do=' . $pluginid;
$time = time();
$inserSQL=<<<EOF
REPLACE INTO pre_tencentcloud_pluginInfo (`plugin_name`, `version`, `href`, `plugin_id`, `activation`, `status`, `install_datetime`)
 VALUES ( 'tencentcloud_sms', '1.0.0', '$href', '$pluginid', 'true', 'false', '$time');
EOF;
runquery($inserSQL);

$sql = <<<SQL
CREATE TABLE IF NOT EXISTS `cdb_tencent_discuzx_sms_sent_records` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT 0,
  `verify_code` varchar(16) NOT NULL DEFAULT '' ,
  `phone` varchar(32) NOT NULL DEFAULT '' ,
  `type` int(10) unsigned NOT NULL  DEFAULT 1 ,
  `template_id` varchar(32) NOT NULL DEFAULT '',
  `template_params` text NOT NULL ,
  `response` text NOT NULL ,
  `status` int(10) unsigned  NOT NULL DEFAULT 0 ,
  `send_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;
SQL;
runquery($sql);

$sql = <<<SQL
CREATE TABLE IF NOT EXISTS `cdb_tencent_discuzx_sms_user_bind` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT 0 ,
  `phone` varchar(32) NOT NULL DEFAULT '' ,
  `valid` int(10) unsigned NOT NULL  DEFAULT 1 ,
  `bind_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;
SQL;
runquery($sql);
SMSActions::uploadDzxStatisticsData('activate');
$finish = true;
