<?php
if (!defined('SYSTEM_ROOT')) {
	die('Insufficient Permissions');
}
if (ROLE !== 'admin') {
	msg('权限不足!');
	die;
}
switch ($_GET['action']) {
	case 'ok': //成功回显
		echo '<div class="alert alert-success">设置保存成功</div>';
		break;
	case 'test': //测试验证码
		$response = get_recaptcha(option::get('loyisa_recaptcha_secretkey'), $_POST['g-recaptcha-response'], $_SERVER["REMOTE_ADDR"]);
		if ($response->success) {
			echo '<div class="alert alert-success">验证码测试成功!</div>';
		} else {
			switch ($response->errorcodes) {
				case '{[0] => "missing-input-secret"}':
				case '{[0] => "invalid-input-secret"}':
					echo '<div class="alert alert-danger">验证码配置错误!</div>';
					break;
				case '{[0] => "timeout-or-duplicate"}':
					echo '<div class="alert alert-danger">验证码已超时!请重新验证</div>';
					break;
				default:
					echo '<div class="alert alert-danger">验证码验证失败!请重新验证</div>';
			}
		}
		break;
	case 'save': //保存设置
		option::set('loyisa_recaptcha_register', intval($_POST['loyisa_recaptcha_register']));
		option::set('loyisa_recaptcha_login', intval($_POST['loyisa_recaptcha_login']));
		option::set('loyisa_recaptcha_sitekey', $_POST['loyisa_recaptcha_sitekey']);
		option::set('loyisa_recaptcha_secretkey', $_POST['loyisa_recaptcha_secretkey']);
		option::set('loyisa_recaptcha_theme', $_POST['loyisa_recaptcha_theme']);
		ReDirect(SYSTEM_URL . 'index.php?mod=admin:setplug&plug=loyisa_recaptcha&action=ok');
		die;
	default:
		break;
}


?>
<h3>reCAPTCHA验证码设置</h3>
<form action="index.php?mod=admin:setplug&plug=loyisa_recaptcha&action=save" method="post">
	<div class="table-responsive">
		<input type="number" name="cron_asyn" value="0" hidden="" />
		<table class="table table-hover">
			<thead>
				<tr>
					<th style="width:40%">参数</th>
					<th>值</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><b>注册验证码</b></td>
					<td><input type="checkbox" name="loyisa_recaptcha_register" id="loyisa_recaptcha_register" value="1" <?php echo option::get('loyisa_recaptcha_register') ? 'checked' : ''; ?> /> 在注册界面显示验证码</td>
				</tr>
				<tr>
					<td><b>登录验证码</b></td>
					<td><input type="checkbox" name="loyisa_recaptcha_login" id="loyisa_recaptcha_login" value="1" <?php echo option::get('loyisa_recaptcha_login') ? 'checked' : ''; ?> /> 在登录界面显示验证码</td>
				</tr>

				<tr>
					<td><b>Site Key</b></td>
					<td><input type="text" name="loyisa_recaptcha_sitekey" id="loyisa_recaptcha_sitekey" value="<?php echo option::get('loyisa_recaptcha_sitekey') ?>" class="form-control" /></td>
				</tr>
				<tr>
					<td><b>Serect Key</b></td>
					<td><input type="text" name="loyisa_recaptcha_secretkey" id="loyisa_recaptcha_secretkey" value="<?php echo option::get('loyisa_recaptcha_secretkey') ?>" class="form-control" /></td>
				</tr>
				<tr>
					<td><b>验证码样式</b></td>
					<td>
						<div class="input-group">
							<select name="loyisa_recaptcha_theme" class="form-control">
								<option value="light" <?php echo option::get('loyisa_recaptcha_theme') == 'light' ? 'selected' : ''; ?>>浅色</option>
								<option value="dark" <?php echo option::get('loyisa_recaptcha_theme') == 'dark' ? 'selected' : ''; ?>>深色</option>
							</select>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<br />
	<input type="submit" class="btn btn-primary" value="提交更改" />&nbsp;&nbsp;&nbsp;
</form>

<br />
请在 reCAPTCHA API 管理页面，选择添加的域名，复制 Public Key 和 Private Key 到上面的表单中即可
<br /><br />
<b>相关链接：</b><a href="https://www.google.com/recaptcha/admin#list" target="_blank">管理 reCAPTCHA API</a> |
<a href="https://www.google.com/recaptcha/admin#createsite" target="_blank">添加 reCAPTCHA API</a>
<br />你可能需要翻墙才能打开上述地址
<br /><br /><br />
<div class="well">
	<b>测试验证码：</b><br />
	<form action="index.php?mod=admin:setplug&plug=loyisa_recaptcha&action=test" method="post">
		<?php loyisa_recaptcha_show(); ?>
		<button type="submit" class="btn btn-primary">提交</button>
	</form>
</div>