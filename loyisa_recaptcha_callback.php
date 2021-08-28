<?php
if (!defined('SYSTEM_ROOT')) { die('Insufficient Permissions'); } 

function callback_init() {
	// 插件配置
	option::add('loyisa_recaptcha_register', 1);
	option::add('loyisa_recaptcha_login', 1);
	option::add('loyisa_recaptcha_score', 0.5);
	option::add('loyisa_recaptcha_sitekey', '');
	option::add('loyisa_recaptcha_secretkey', '');
}

function callback_remove() {
	// 禁用插件时移除配置文件
	option::del('loyisa_recaptcha_site_key');
	option::del('loyisa_recaptcha_secret_key');
}
