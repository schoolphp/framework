<?php
error_reporting(-1);
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
session_start();
require __DIR__.'/vendor/autoload.php';

if(isset($_POST['db-login'],$_POST['db-pass'],$_POST['db-local'],$_POST['db-name'],$_POST['domain'],$_POST['site-name'],$_POST['email'],$_POST['htaccess'])) {
	$_SESSION['created'] = date('Y');
	$_SESSION['db-login'] = $_POST['db-login'];
	$_SESSION['db-pass'] = $_POST['db-pass'];
	$_SESSION['db-local'] = $_POST['db-local'];
	$_SESSION['db-name'] = $_POST['db-name'];
	$_SESSION['domain'] = $_POST['domain'];
	$_SESSION['site-name'] = $_POST['site-name'];
	$_SESSION['email'] = $_POST['email'];
	$_SESSION['login'] = $_POST['login'];
	$_SESSION['password'] = $_POST['password'];
	$_SESSION['htaccess'] = $_POST['htaccess'];
	if(isset($_POST['sitemap'])) {
		$_SESSION['sitemap'] = \FW\Installer\Sitemap::generateMap($_POST['sitemap']);
	} else {
		$_SESSION['sitemap'] = [];
	}
}

ob_start();
try {
	\FW\Installer\Installer::$basedir = __DIR__;
	\FW\Installer\Installer::init();
	\FW\Installer\Installer::createDir();
	\FW\Installer\Installer::createDB();
	\FW\Installer\Installer::createSitemap();
	\FW\Installer\Installer::delDir();
} catch(Exception $e) {
	$error = $e->getMessage();
}
$phperror = ob_get_clean();

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Установка School-PHP FrameWork</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" href="/favicon.ico" type="image/x-icon">
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
<link href="/skins/components/bower/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="/skins/components/bower/bootstrap/dist/css/bootstrap-theme.min.css" rel="stylesheet">
<link href="/vendor/schoolphp/library/Installer/style.css" rel="stylesheet">
<script src="/skins/components/bower/jquery/dist/jquery.min.js"></script>
<script src="/skins/components/bower/bootstrap/dist/js/bootstrap.min.js" defer></script>
<script src="/vendor/schoolphp/library/Installer/scripts.js"></script>
</head>
<body>
<header style="text-align:center; background-image:url(/vendor/schoolphp/library/Installer/install/skins/img/logo2-bg.jpg);
 position:relative; margin: -40px -30px 0 -30px; padding-bottom: 0;">
	<div style="position:absolute;top: 259px;left: 0; right: 0; margin-left:-20px; font-family: Georgia, serif;font-size: 30px;color: #605C5D;font-style: oblique;">FrameWork version 1.0.4</div>
	<img src="/vendor/schoolphp/library/Installer/install/skins/img/logo2.jpg" alt="School-PHP FrameWork">
</header>
<?php if(isset($error)) {echo '<h1 style="color: #900;">'.$error.'</h1>';} ?>
<aside>
	<h2>Этапы разработки</h2>
	<div<?=(\FW\Installer\Installer::$step == 0 ? ' class="active"' : '');?>>Инициализация проекта</div>
	<div<?=(\FW\Installer\Installer::$step == 1 ? ' class="active"' : '');?>>Создание файлов</div>
	<div<?=(\FW\Installer\Installer::$step == 2 ? ' class="active"' : '');?>>Настройка Базы Данных</div>
	<div<?=(\FW\Installer\Installer::$step == 3 ? ' class="active"' : '');?>>Генерация SiteMap</div>
	<div<?=(\FW\Installer\Installer::$step == 4 ? ' class="active"' : '');?>>Удаление установочных файлов</div>
</aside>
<main>
	<?php if(\FW\Installer\Installer::$step < 5) { ?>
		<form action="" method="post">
			<table>
				<tr><th colspan="2"><h1>SITE данные</h1></th></tr>
				<tr>
					<th>Имя сайта:</th>
					<td>
						<input type="text" name="site-name" placeholder="site-name" class="form-control" value="<?=(isset($_SESSION['site-name']) ? $_SESSION['site-name'] : '');?>">
						<br><i>* Название сайта. Позволит в будущем ориентироваться в своих проектах.</i>
					</td>
				</tr>
				<tr><th>Домен сайта:</th><td><input type="text" name="domain" placeholder="domain" class="form-control" value="<?=(isset($_SESSION['domain']) ? $_SESSION['domain'] : '');?>"></td></tr>
				<tr><th>Email сайта:</th><td><input type="text" name="email" placeholder="email" class="form-control" value="<?=(isset($_SESSION['email']) ? $_SESSION['email'] : '');?>"></td></tr>
				<tr>
					<th>Тип .htaccess:</th>
					<td>
						<select name="htaccess" title="htaccess type" class="form-control">
							<option value="openserver"<?=(isset($_SESSION['htaccess']) && $_SESSION['htaccess'] == 'openserver' ? 'selected' : '');?>>openserver</option>
							<option value="full"<?=(isset($_SESSION['htaccess']) && $_SESSION['htaccess'] == 'full' ? 'selected' : '');?>>full</option>
						</select>
						<br><i>* Open Server имеет ограничения, из-за чего не будет работать файловый кэш, который прописан в полной версии.
						<br>Некие хостинги так же могут запретить полную версию.</i>
					</td>
				</tr>

				<tr><th colspan="2"><h1>MySQL данные</h1></th></tr>
				<tr><th>MySQL логин</th><td><input type="text" name="db-login" placeholder="db-login" class="form-control" value="<?=(isset($_SESSION['db-login']) ? $_SESSION['db-login'] : '');?>"></td></tr>
				<tr><th>MySQL пароль</th><td><input type="password" name="db-pass" placeholder="db-pass" class="form-control" value="<?=(isset($_SESSION['db-pass']) ? $_SESSION['db-pass'] : '');?>"></td></tr>
				<tr><th>MySQL HOST</th><td><input type="text" name="db-local" placeholder="db-local" class="form-control" value="<?=(isset($_SESSION['db-local']) ? $_SESSION['db-local'] : 'localhost');?>"></td></tr>
				<tr><th>MySQL имя Базы Данных</th><td><input type="text" name="db-name" placeholder="db-name" class="form-control" value="<?=(isset($_SESSION['db-name']) ? $_SESSION['db-name'] : '');?>"></td></tr>

				<tr><th colspan="2"><h1>ADMIN данные</h1></th></tr>
				<tr><th>Admin логин</th><td><input type="text" name="login" placeholder="login" class="form-control" value="<?=(isset($_SESSION['login']) ? $_SESSION['login'] : '');?>"></td></tr>
				<tr><th>Admin пароль</th><td><input type="password" name="password" placeholder="password" class="form-control" value="<?=(isset($_SESSION['password']) ? $_SESSION['password'] : '');?>"></td></tr>
				<tr><th colspan="2"><h1>Дополнительные модули</h1></th></tr>
			</table>
			<div id="sitemap-container"><?=(isset($_SESSION['sitemap']) ? '<pre>'.preg_replace('#\[\s+\]#iu','[]',preg_replace('#\=\>\s*array \(#iu','=> [',str_replace('),','],',var_export($_SESSION['sitemap'],1)))).'</pre>' : '');?></div>
			<button onclick="return sitemapAddElement();" class="btn btn-danger btn-xs">Add new Module</button>
			<hr><input type="submit" class="btn btn-success">
		</form>
	<?php } else { ?>
		<h1 style="color: #090;">Установка завершена!</h1>
		<div>Теперь Вы можете: <a href="/">перейти на главную страницу сайта</a></div>
	<?php } ?>
</main>
<div style="clear: both;"></div>

<div class="log"><h2>Логи инсталлера</h2> <?=\FW\Installer\Installer::$log;?></div>
<div class="php-log"><h2>Логи PHP</h2> <?=$phperror;?></div>
<div style="clear: both;"></div>
<?php
echo '<script>';
if(isset($_SESSION['sitemap']) && count($_SESSION['sitemap'])) {
	$i = 0;
	$i2 = 0;
	$i3 = 0;
	foreach($_SESSION['sitemap'] as $k=>$v) {
		++$i;
		echo 'sitemapAddElement(\''.$k.'\');';
		foreach($v as $k2=>$v2) {
			if($k2 == '/Options') {
				foreach($v2 as $k3=>$v3) {
					echo 'document.getElementById("sitemap-'.$i.'-'.$k3.'").checked = true;';
				}
			} else {
				++$i2;
				echo 'sitemapAddPage('.$i.',\''.$k2.'\');';
				if(!is_array($v2)) {continue;}
				foreach($v2 as $k3 => $v3) {
					++$i3;
					echo 'sitemapAddGet('.$i.','.$i2.',\''.$k3.'\');';
					if(!is_array($v3)) {continue;}
					foreach($v3 as $k4=>$v4) {
						echo 'sitemapAddParams('.$i.','.$i2.','.$i3.',\''.$k4.'\',\''.$v4.'\');';
					}
				}
			}
		}
	}
} else {
	//echo 'sitemapAddElement(\'main\');';
	//echo 'sitemapAddPage(1,\'main\');';
}
echo '</script>';
?>
</body>
</html>