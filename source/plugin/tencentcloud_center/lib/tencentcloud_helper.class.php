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

require_once DISCUZ_ROOT . 'source/plugin/tencentcloud_center/vendor/autoload.php';

use GuzzleHttp\Client;
use TencentCloud\Common\Credential;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;
use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Ms\V20180408\MsClient;
use TencentCloud\Ms\V20180408\Models\DescribeUserBaseInfoInstanceRequest;

class TencentCloudHelper {

    //开启数据上报标志
    const SITE_REPORT_OPEN = '1';

    //开启自定义密钥标志
    const SITE_SECKEY_OPEN = '1';

    /*
     * 获取站点URL
     */
    public static function siteUrl($url = ''){
        global $_G;
        return rtrim($_G['siteurl'], '/').$url;
    }

    /**
     * 获取插件中心相关参数
     * @return mixed|string|string[]
     */
    public static function config() {
        global $_G;
        if (isset($_G['setting']['tencentcloud_center'])) {
            $plugin = C::t('common_plugin')->fetch_by_identifier('tencentcloud_center');
            C::t('common_pluginvar')->delete_by_pluginid($plugin['pluginid']);
            $params = unserialize($_G['setting']['tencentcloud_center']);
        } else {
            $params = array (
                'secretid' => '',
                'secretkey' => '',
                'site_sec_on' => self::SITE_SECKEY_OPEN,
                'site_report_on' => self::SITE_REPORT_OPEN,
                'site_id'=>'',
                );

        }
        return $params;
    }

    /**
     * 获取站点静态URL
     * @param string $path
     * @return string
     */
    public static function staticUrl($path = '') {
        return TencentCloudHelper::siteUrl().'/source/plugin/tencentcloud_center/static'.$path;
    }

    /**
     * @return mixed查询插件中心设置信息
     */
    public static function tencent_discuz_plugincenter_queryPluginInfo(){
        return C::t('#tencentcloud_center#tencentcloud_center') -> findAll();
    }


    /**
     * 获取唯一站点ID
     */
    public static function getDiscuzSiteID(){
        global $_G;
        if ($_G['setting']['tencentcloud_center']){
            $params = unserialize($_G['setting']['tencentcloud_center']);
            return $params['site_id'];
        } else {
            $data = array (
                'secretid' => '',
                'secretkey' => '',
                'site_sec_on' => self::SITE_SECKEY_OPEN,
                'site_report_on' => self::SITE_REPORT_OPEN,
                'site_id'=>uniqid('discuzx_'),
            );
            C::t('common_setting')->update_batch(array("tencentcloud_center" => $data));
            //更新缓存信息
            updatecache('setting');
            return $data['site_id'];
        }
    }

    /**
     * 获取站点的平台名称
     *
     */
    public static function getDiscuzSiteApp(){
        return "Discuz! X";
    }

    /**
     * 获取用户基础信息 UserUin
     * @param $option string 腾讯云账号的密钥信息 SecretId 和SecretKey
     * @return bool|mixed UserUin的值
     */
    public static function getUserUinBySecret($secret_id, $secret_key){
        if ( empty($secret_id) || empty($secret_key)) {
            return '';
        }
        try {
            $cred = new Credential($secret_id, $secret_key);
            $httpProfile = new HttpProfile();
            $httpProfile->setEndpoint("ms.tencentcloudapi.com");
            $clientProfile = new ClientProfile();
            $clientProfile->setHttpProfile($httpProfile);
            $client = new MsClient($cred, "", $clientProfile);
            $req = new DescribeUserBaseInfoInstanceRequest();
            $params = "{}";
            $req->fromJsonString($params);

            $resp = $client->DescribeUserBaseInfoInstance($req);
            if (is_object($resp)) {
                $result = json_decode($resp->toJsonString(), true);
                return isset($result['UserUin']) ? $result['UserUin'] : '';
            } else {
                return '';
            }
        } catch (TencentCloudSDKException $e) {
            echo '';
        }
    }

    /**
     * 发送用户体验计划相关数据
     * @param $data array 插件使用的公共数据 非私密数据
     * @return bool|void
     */
    public static function sendUserExperienceInfo($data){
        if (empty($data) || !is_array($data)) {
            return ;
        }
        global $_G;

        $params = unserialize($_G['setting']['tencentcloud_center']);
        if (($data['action'] == 'save_common_config' || $data['action'] == 'save_config')){
            $url = self::getLogServerUrl();
            self::sendPostRequest($url, $data);
            return true;
        }
        if (isset($params['site_report_on']) && $params['site_report_on'] != self::SITE_REPORT_OPEN) {
            return false;
        }

        $url = self::getLogServerUrl();
        self::sendPostRequest($url, $data);
        return true;
    }

    /**
     * 获取腾讯云插件日志服务器地址
     * @return string
     */
    public static function getLogServerUrl(){
        $common_path =  DISCUZ_ROOT.'./source/plugin/tencentcloud_center/lib/config.json';
        if (file_exists($common_path)) {
            $common_info_json = file_get_contents($common_path);
            $common_info_arr = json_decode($common_info_json, true);
            if (isset($common_info_arr['log_server_url'])) {
                return $common_info_arr['log_server_url'];
            }
        }
        return '';

    }

    /**
     * 发送post请求
     * @param $url
     * @param $data
     */
    public static function sendPostRequest($url, $data){
        ob_start();
        if (function_exists('curl_init')) {
            $json_data = json_encode($data);
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $json_data);
            curl_exec($curl);
            curl_close($curl);
        } else {
            $client = new Client();
            $client->post($url, [
                GuzzleHttp\RequestOptions::JSON => $data
            ]);
        }
        ob_end_clean();
    }

    /**
     * 参数过滤
     * @param $key
     * @param string $default
     * @return string|void
     */
    public static function filterParam($key, $default = '')
    {
        return isset($key) ? dhtmlspecialchars($key) : $default;
    }



}
