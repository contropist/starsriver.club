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
if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
//不是ajax请求直接退出
if( !isset($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest') {
    exit('Access Denied');
}
use TencentDiscuzSMS\SMSActions;
try {
    $dzxSMS = new SMSActions();
    global $_G;
    $phone = $dzxSMS->filterPostParam('phone');
    $type = intval($dzxSMS->filterPostParam('type'));
    //手机号验证
    if (!$dzxSMS::isPhoneNumber($phone)) {
        $dzxSMS->jsonReturn($dzxSMS::CODE_INVALID_PHONE);
    }
    $bindUid = $dzxSMS::getUidByPhone($phone);
    //手机号未绑定
    if (empty($bindUid) && $type === $dzxSMS::TYPE_LOGIN) {
        $dzxSMS->jsonReturn($dzxSMS::CODE_PHONE_UNBIND);
    }
    //手机号已被使用
    if ($type === $dzxSMS::TYPE_BIND && !empty($bindUid) && $bindUid != $_G['uid']) {
        $dzxSMS->jsonReturn($dzxSMS::CODE_PHONE_USED);
    }

    //发送验证码
    $dzxSMS->sendVerifyCodeSMS($phone,$type,$_G['uid']);
    $dzxSMS->jsonReturn($dzxSMS::CODE_SUCCESS);
} catch (\Exception $exception) {
    $dzxSMS->jsonReturn($dzxSMS::CODE_EXCEPTION,array(),$exception->getMessage().'  请联系管理员解决');
}
