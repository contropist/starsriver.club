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
if (!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
    exit('Access Denied');
}

$create_sql = "
CREATE TABLE IF NOT EXISTS cdb_tencentcloud_pluginInfo (
       `plugin_name` varchar(255) NOT NULL DEFAULT '',
       `version` varchar(32) NOT NULL DEFAULT '',
       `href` varchar(255) NOT NULL  DEFAULT '',
       `plugin_id` varchar(255) NOT NULL DEFAULT '',
       `activation` varchar(32) NOT NULL DEFAULT '',
       `status` varchar(32) NOT NULL DEFAULT '',
       `install_datetime` timestamp NOT NULL DEFAULT  CURRENT_TIMESTAMP(),
       `last_modify_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP(),
       PRIMARY KEY (`plugin_name`)
) ENGINE=InnoDB;
";
runquery($create_sql);

$create_sql = "
CREATE TABLE IF NOT EXISTS cdb_tencentcloud_center (
       `id` int(20) unsigned NOT NULL AUTO_INCREMENT,
       `plugin_name` varchar(255) NOT NULL DEFAULT '',
       `nick_name` varchar(255) NOT NULL DEFAULT '',
       `plugin_desc` varchar(255) NOT NULL DEFAULT '',
       PRIMARY KEY (`id`)
) ENGINE=InnoDB ;
";
runquery($create_sql);

$scriptlang = lang('plugin/tencentcloud_center');

$insert_sql = "
INSERT INTO cdb_tencentcloud_center (`plugin_name`, `nick_name`, `plugin_desc`) 
VALUES ( 'tencentcloud_captcha', '" . $scriptlang["tencentcloud_captcha_nickname"] . "', '" . $scriptlang["tencentcloud_captcha_desc"] . "');
";
runquery($insert_sql);

$insert_sql = "
INSERT INTO cdb_tencentcloud_center (`plugin_name`, `nick_name`, `plugin_desc`) 
VALUES ( 'tencentcloud_cos', '" . $scriptlang["tencentcloud_cos_nickname"] . "', '" . $scriptlang["tencentcloud_cos_nickname"] . "');
";
runquery($insert_sql);

$insert_sql = "
INSERT INTO cdb_tencentcloud_center (`plugin_name`, `nick_name`, `plugin_desc`) 
VALUES ( 'tencentcloud_ims', '" . $scriptlang["tencentcloud_ims_nickname"] . "', '" . $scriptlang["tencentcloud_ims_nickname"] . "');
";
runquery($insert_sql);

$insert_sql = "
INSERT INTO cdb_tencentcloud_center (`plugin_name`, `nick_name`, `plugin_desc`) 
VALUES ( 'tencentcloud_sms', '" . $scriptlang["tencentcloud_sms_nickname"] . "', '" . $scriptlang["tencentcloud_sms_nickname"] . "');
";
runquery($insert_sql);

$insert_sql = "
INSERT INTO cdb_tencentcloud_center (`plugin_name`, `nick_name`, `plugin_desc`) 
VALUES ( 'tencentcloud_tms', '" . $scriptlang["tencentcloud_tms_nickname"] . "', '" . $scriptlang["tencentcloud_tms_nickname"] . "');
";
runquery($insert_sql);

$insert_sql = "
INSERT INTO cdb_tencentcloud_center (`plugin_name`, `nick_name`, `plugin_desc`) 
VALUES ( 'tencentcloud_vod', '" . $scriptlang["tencentcloud_vod_nickname"] . "', '" . $scriptlang["tencentcloud_vod_nickname"] . "');
";
runquery($insert_sql);

$finish = TRUE;


