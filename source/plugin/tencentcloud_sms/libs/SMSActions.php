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
namespace TencentDiscuzSMS;

use C;
use DB;
use TencentCloud\Sms\V20190711\SmsClient;
use TencentCloud\Sms\V20190711\Models\SendSmsRequest;
use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Common\Credential;
defined('TENCENT_DISCUZX_SMS_PLUGIN_NAME')||define( 'TENCENT_DISCUZX_SMS_PLUGIN_NAME', 'tencentcloud_sms');
class SMSActions
{
    const PLUGIN_TYPE = 'sms';
    const CODE_SUCCESS = 0;
    const CODE_EXCEPTION = 10000;
    const CODE_INVALID_PHONE = 10001;
    const CODE_INVALID_VERIFY_CODE = 10002;
    const CODE_NEED_LOGIN = 10003;
    const CODE_PHONE_UNBIND = 10004;
    const CODE_TWO_PWD_UNEQUAL = 10005;
    const CODE_NEW_PWD_TOO_SHORT = 10006;
    const CODE_PHONE_USED = 10007;

    //短信验证码发送成功
    const VERIFY_CODE_SUCCESS = 0;
    //短信验证码发送失败
    const VERIFY_CODE_FAIL = 1;
    //短信验证码失效
    const VERIFY_CODE_INVALID = 2;
    //绑定是否有效
    const BIND_VALID = 1;
    //绑定无效（用户换绑）
    const BIND_INVALID = 0;
    //用于登录
    const TYPE_LOGIN = 1;
    //用于绑定手机号
    const TYPE_BIND = 2;
    //用于重置密码
    const TYPE_RESET_PWD = 3;
    //用于注册
    const TYPE_REGISTER = 4;
    //用于后台发送测试接口
    const TYPE_TEST = 99;

    /**
     * 返回json
     * @param int $code
     * @param array $data
     * @param string $msg
     */
    public function jsonReturn($code = self::CODE_SUCCESS,$data = array(),$msg = '')
    {
        $lang = lang('plugin/tencentcloud_sms','error_msg');
        if (empty($msg) && isset($lang[$code])) {
            $msg = $lang[$code];
        }
        echo json_encode(array(
            'code'=>$code,
            'msg'=>$msg,
            'data'=>$data,
        ));
        exit;
    }

    /**
     * post参数过滤
     * @param $key
     * @param string $default
     * @return string|void
     */
    public function filterPostParam($key, $default = '')
    {
        return isset($_POST[$key]) ? dhtmlspecialchars($_POST[$key]) : $default;
    }
    /**
     * get参数过滤
     * @param $key
     * @param string $default
     * @return string|void
     */
    public function filterGetParam($key, $default = '')
    {
        return isset($_GET[$key]) ? dhtmlspecialchars($_GET[$key]) : $default;
    }

    /**
     * 验证是否为手机号
     * @param $phone
     *
     * @return bool
     */
    public static function isPhoneNumber($phone)
    {
        return preg_match("/^1[3-9]\d{9}$/", $phone) === 1;
    }

    /**
     * 查询该手机号最近一次发送成功的验证码
     * @param $phone
     * @return mixed
     * @throws \Exception
     */
    public function getVerifyCodeByPhone($phone)
    {
        $SMSOptions = self::getSMSOptionsObject();
        $expired = $SMSOptions->getCodeExpired();
        $dateStart = date('Y-m-d H:i:s', TIMESTAMP - $expired*60);
        $dateEnd = date('Y-m-d H:i:s');
        $sql = "SELECT `id`,`verify_code` FROM %t WHERE `status`=%d AND `phone`= %s AND `send_date` BETWEEN %s AND %s ORDER BY `id` DESC";
        return  DB::fetch_first($sql,array(TENCENT_DISCUZX_SMS_SENT_TABLE,self::VERIFY_CODE_SUCCESS,$phone,$dateStart,$dateEnd));
    }

    /**
     * 用户绑定手机号
     * @param $phone
     * @param int $uid
     * @return int
     * @throws \Exception
     */
    public function userBindPhone($phone, $uid)
    {
        $id = DB::insert(TENCENT_DISCUZX_USER_BIND_TABLE,array('uid'=>$uid,'phone'=>$phone,'valid'=>self::BIND_VALID,'bind_date'=>date('Y-m-d H:i:s')),true);
        if (!is_numeric($id)) {
            throw new \Exception('bind fail');
        }
        return $id;
    }

    /**
     * 验证码失效
     * @param $id
     * @return int|bool
     */
    public function loseCodeEfficacy($id)
    {
        return DB::update(TENCENT_DISCUZX_SMS_SENT_TABLE,array('status'=>self::VERIFY_CODE_INVALID),"`id`={$id}");
    }


    /**
     * 获取配置对象
     * @return SMSOptions
     * @throws \Exception
     */
    public static function getSMSOptionsObject()
    {
        global $_G;
        $SMSOptions = new SMSOptions();
        $options = $_G['setting'][TENCENT_DISCUZX_SMS_PLUGIN_NAME];
        if ( empty($options) ) {
            $options = C::t('common_setting')->fetch(TENCENT_DISCUZX_SMS_PLUGIN_NAME);
        }
        if ( empty($options) ) {
            return $SMSOptions;
        }
        $options = unserialize($options);
        $SMSOptions->setCustomKey($options['customKey']);
        $SMSOptions->setSecretID($options['secretId']);
        $SMSOptions->setSecretKey($options['secretKey']);
        $SMSOptions->setSDKAppID($options['SDKAppID']);
        $SMSOptions->setSign($options['sign']);
        $SMSOptions->setTemplateID($options['templateId']);
        $SMSOptions->setPostNeedPhone($options['postNeedPhone']);
        $SMSOptions->setCommentNeedPhone($options['commentNeedPhone']);
        $SMSOptions->setCodeExpired($options['codeExpired']);
        $SMSOptions->setHasExpiredTime($options['hasExpireTime']);
        return $SMSOptions;
    }

    /**
     * 发送验证码短信
     * @param $phone
     * @param int $uid
     * @param int $type
     * @return bool
     * @throws \Exception
     */
    public function sendVerifyCodeSMS($phone,$type,$uid = 0)
    {
        if (!self::isPhoneNumber($phone)) {
            throw new \Exception(lang('plugin/tencentcloud_sms','phone_error'));
        }
        if (!in_array($type,array(self::TYPE_LOGIN,self::TYPE_BIND,self::TYPE_RESET_PWD,self::TYPE_REGISTER,self::TYPE_TEST),true)) {
            throw new \Exception(lang('plugin/tencentcloud_sms','type_error'));
        }
        //绑定时uid不能为空
        if (empty($uid) && in_array($type,array(self::TYPE_BIND),true)) {
            throw new \Exception(lang('plugin/tencentcloud_sms','type_error'));
        }
        $SMSOptions = self::getSMSOptionsObject();
        $verifyCode = self::verifyCodeGenerator();
        $templateParams = array($verifyCode);

        if ( $SMSOptions->getHasExpiredTime() === SMSOptions::HAS_EXPIRED_TIME ) {
            $templateParams[] = $SMSOptions->getCodeExpired();
        }
        $response = $this->sendSMS(array($phone),$SMSOptions,$templateParams);
        $status = self::VERIFY_CODE_SUCCESS;
        if ( $response['SendStatusSet'][0]['Fee'] !== 1 || $response['SendStatusSet'][0]['Code'] !== 'Ok' ) {
            $status = self::VERIFY_CODE_FAIL;
        }
        //发送测试不计入数据库
        if (!in_array($type,array(self::TYPE_TEST),true)) {
            $saveData['verify_code'] = $verifyCode;
            $saveData['phone'] = $phone;
            $saveData['type'] = $type;
            $saveData['uid'] = $uid;
            $saveData['response'] = json_encode($response,JSON_UNESCAPED_UNICODE);
            $saveData['template_params'] = json_encode($templateParams,JSON_UNESCAPED_UNICODE);
            $saveData['template_id'] = $SMSOptions->getTemplateID();
            $saveData['status'] = $status;
            $this->saveSMSSentRecord($saveData);
        }
        if ($status !== self::VERIFY_CODE_SUCCESS) {
            $errorCode = $response['errorCode'] ?: $response['SendStatusSet'][0]['Code'];
            $lang = lang('plugin/tencentcloud_sms','error_msg');
            throw new \Exception('failure：'.$lang[$errorCode]);
        }
        return true;
    }

    /**
     * 发送短信
     * @param $phones
     * @param SMSOptions $SMSOptions
     * @param array $templateParams
     * @return array|mixed
     */
    private function sendSMS($phones, $SMSOptions, $templateParams = array())
    {
        try {
            $cred = new Credential($SMSOptions->getSecretID(), $SMSOptions->getSecretKey());
            $client = new SmsClient($cred, "ap-shanghai");
            $req = new SendSmsRequest();
            $req->SmsSdkAppid = $SMSOptions->getSDKAppID();
            $req->Sign = $SMSOptions->getSign();
            $req->ExtendCode = "0";
            foreach ($phones as &$phone) {
                $preFix = substr($phone, 0, 3);
                if ( !in_array($preFix, array('+86')) ) {
                    $phone = '+86' . $phone;
                }
            }
            /*最多不要超过200个手机号*/
            $req->PhoneNumberSet = $phones;
            /* 国际/港澳台短信 senderid: 国内短信填空 */
            $req->SenderId = "";
            $req->TemplateID = $SMSOptions->getTemplateID();
            $req->TemplateParamSet = $templateParams;
            $resp = $client->SendSms($req);
            return json_decode($resp->toJsonString(), JSON_OBJECT_AS_ARRAY);
        } catch (TencentCloudSDKException $e) {
            return array('requestId' => $e->getRequestId(), 'errorCode' => $e->getErrorCode(), 'errorMessage' => $e->getMessage());
        }
    }

    /**
     * 保存短信发送记录
     * @param $data
     * @return int|bool
     * @throws \Exception
     */
    private function saveSMSSentRecord($data)
    {
        $id = DB::insert(TENCENT_DISCUZX_SMS_SENT_TABLE,$data,true);
        if (!is_numeric($id)) {
            throw new \Exception('发送失败,请联系管理员。');
        }
        return $id;
    }

    /**
     * 通过uid获取用户绑定的手机号
     * @param int $uid
     * @return mixed|string
     */
    public static function getPhoneByUid($uid = 0)
    {
        if (empty($uid) || !is_numeric($uid)) {
            return '';
        }
        $sql = "SELECT `phone` FROM %t WHERE `valid`=%d AND `uid`= %d ORDER BY `id` DESC";
        $result = DB::fetch_first($sql,array(TENCENT_DISCUZX_USER_BIND_TABLE,self::BIND_VALID,$uid));
        return isset($result['phone'])?$result['phone']:'';
    }
    /**
     * 通过手机号获取uid
     * @param string $phone
     * @return int
     */
    public static function getUidByPhone($phone)
    {
        if (!self::isPhoneNumber($phone)) {
            return 0;
        }
        $sql = "SELECT `uid` FROM %t WHERE `valid`=%d AND `phone`= %s ORDER BY `id` DESC";
        $result = DB::fetch_first($sql,array(TENCENT_DISCUZX_USER_BIND_TABLE,self::BIND_VALID,$phone));
        return isset($result['uid'])?intval($result['uid']):0;
    }

    /**
     * 生成随机验证码
     * @param int $length
     *
     * @return string
     */
    public static function verifyCodeGenerator($length = 4)
    {
        if (!is_numeric($length) || $length < 4) {
            $length = 4;
        }
        if ($length > 8) {
            $length = 8;
        }
        $nums = range(0, 9);
        shuffle($nums);
        $code = '';
        for ($i = 0; $i < $length; $i++) {
            $index = mt_rand(0, 9);
            $code .= $nums[$index];
        }
        return $code;
    }

    public static function uploadDzxStatisticsData($action)
    {
        try {
            $file = DISCUZ_ROOT . './source/plugin/tencentcloud_center/lib/tencentcloud_helper.class.php';
            if (!is_file($file)) {
                return;
            }
            require_once $file;
            $data['action'] = $action;
            $data['plugin_type'] = self::PLUGIN_TYPE;
            $data['data']['site_url'] = \TencentCloudHelper::siteUrl();
            $data['data']['site_app'] = \TencentCloudHelper::getDiscuzSiteApp();
            $data['data']['site_id'] = \TencentCloudHelper::getDiscuzSiteID();
            $options = self::getSMSOptionsObject();
            $data['data']['uin'] = \TencentCloudHelper::getUserUinBySecret($options->getSecretID(), $options->getSecretKey());
            $data['data']['cust_sec_on'] = $options->getCustomKey() === $options::CUSTOM_KEY ? 1 : 2;
            $data['data']['others'] = json_encode(array('sms_appid'=>$options->getSDKAppID()));
            \TencentCloudHelper::sendUserExperienceInfo($data);
        } catch (\Exception $exception){
            return;
        }
    }
}
