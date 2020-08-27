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
if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

class table_tencentcloud_center extends discuz_table{
    /**
     * table_tencentcloud_center constructor.
     */
    public function __construct() {

        $this->_table = 'tencentcloud_center';
        $this->_pk    = 'id';
        parent::__construct();
    }

    /**
     * 查询所有插件信息
     * @return array
     */
    public function findAll() {
        $sql = "SELECT m.plugin_name plugin_identifier, m.nick_name, m.plugin_desc, s.*
            FROM " . DB::table('tencentcloud_center') . " m
            LEFT JOIN " . DB::table('tencentcloud_pluginInfo') . " s ON m.plugin_name=s.plugin_name
            WHERE m.plugin_name<>'tencentcloud_center'";
        return DB::fetch_all($sql);
    }




}

