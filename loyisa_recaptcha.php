<?php
/*
Plugin Name: reCAPTCHA验证码
Version: 1.0
Description: 在注册的时候，要求用户输入难度较高的 reCAPTCHA 验证码，来起到防止恶意注册和打击重复注册的作用，基于无名智者的版本进行升级和修改
Author: Loyisa
Author Email: loyisa@vip.qq.com
Author URL: https://loyisa.cn
For: V3.4+
*/
if (!defined('SYSTEM_ROOT')) {
    die('Insufficient Permissions');
}

//显示验证码
function loyisa_recaptcha_show()
{
    // 检测是否开启注册验证码
    if (SYSTEM_PAGE == 'reg' && option::get('loyisa_recaptcha_register') == 0) {
        return;
    }
    // 检测是否开启登录验证码
    if (SYSTEM_PAGE == 'login' && option::get('loyisa_recaptcha_login') == 0) {
        return;
    }
    show_recaptcha(option::get('loyisa_recaptcha_sitekey'), option::get('loyisa_recaptcha_theme'));
}

// 检查验证码
function loyisa_recaptcha_check()
{
    // 检测是否开启注册验证码
    if (SYSTEM_PAGE == 'admin:reg' && option::get('loyisa_recaptcha_register') == 0) {
        return;
    }
    // 检测是否开启登录验证码
    if (SYSTEM_PAGE == 'admin:login' && option::get('loyisa_recaptcha_login') == 0) {
        return;
    }
    // 获取验证码
    $response = get_recaptcha(option::get('loyisa_recaptcha_secretkey'), $_POST['g-recaptcha-response'], $_SERVER["REMOTE_ADDR"]);
    // 检测验证码 并根据错误代码输出语句
    if (!$response->success) {
        switch ($response->errorcodes) {
            case '{[0] => "missing-input-secret"}':
            case '{[0] => "invalid-input-secret"}':
                msg('验证码配置错误!');
                break;
            case '{[0] => "timeout-or-duplicate"}':
                msg('验证码已超时!请重新验证');
                break;
            default:
                msg('验证码验证失败!请重新验证');
        }
    }
}

/**
 * 获取验证码json
 * @param string $secret
 * @param string $response
 * @param string $remoteip
 * @return object ReCaptchaResponse
 */
function get_recaptcha($secret, $response, $remoteip)
{
    // 和recaptcha服务器二次校验
    $getjsonurl = file_get_contents('https://www.recaptcha.net/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $response . '&remoteip=' . $remoteip);
    // 解析获取到的json
    $response = json_decode($getjsonurl);
    return $response;
}

function show_recaptcha($sitekey, $theme)
{
    echo '<script src="https://www.recaptcha.net/recaptcha/api.js" async defer></script>
  <div class="g-recaptcha" data-sitekey="' . $sitekey . '" data-theme="' . $theme . '"></div>';
}

// hook登录/注册界面
addAction('reg_page_2', 'loyisa_recaptcha_show');
addAction('login_page_2', 'loyisa_recaptcha_show');
addAction('admin_reg_1', 'loyisa_recaptcha_check');
addAction('admin_login_1', 'loyisa_recaptcha_check');
