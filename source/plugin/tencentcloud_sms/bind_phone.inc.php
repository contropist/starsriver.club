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
//不是ajax请求直接返回html页面inajax
if( !isset($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest') {
    return;
}
use TencentDiscuzSMS\SMSActions;
try {
    $dzxSMS = new SMSActions();
    global $_G;
    if ( empty($_G['uid']) ) {
        $dzxSMS->jsonReturn($dzxSMS::CODE_NEED_LOGIN);
    }
    $phone = $dzxSMS->filterPostParam('phone');
    $verifyCode = $dzxSMS->filterPostParam('verifyCode');
    //验证手机号
    if ( !$dzxSMS::isPhoneNumber($phone) ) {
        $dzxSMS->jsonReturn($dzxSMS::CODE_INVALID_PHONE);
    }
    $bindUid = $dzxSMS::getUidByPhone($phone);
    //手机号已被使用
    if ( !empty($bindUid) && $bindUid != $_G['uid']) {
        $dzxSMS->jsonReturn($dzxSMS::CODE_PHONE_USED);
    }

    $DBVerifyCode = $dzxSMS->getVerifyCodeByPhone($phone);
    //验证码比对
    if ( empty($DBVerifyCode) || $DBVerifyCode['verify_code'] !== $verifyCode) {
        $dzxSMS->jsonReturn($dzxSMS::CODE_INVALID_VERIFY_CODE);
    }

    //验证码状态变为已使用
    $dzxSMS->loseCodeEfficacy($DBVerifyCode['id']);
    //绑定手机号
    $dzxSMS->userBindPhone($phone, $_G['uid']);
    $dzxSMS->jsonReturn($dzxSMS::CODE_SUCCESS);

} catch (\Exception $exception) {
    $dzxSMS->jsonReturn($dzxSMS::CODE_EXCEPTION,array(),$exception->getMessage());
}

