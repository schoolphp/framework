<?php
error_reporting(-1);
ini_set('log_errors',1);
if(isset($_GET['route'])) {
	$_GET['route'] = preg_replace('#^\/#','',$_GET['route']);
}
include_once './config/config.php';
if(Core::$HTTPS && (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != 'on')) {
	header("Location: ".Core::$DOMAIN.($_SERVER['REQUEST_URI'] ?? ''),TRUE,301);
	exit;
}
if(Core::$STATUS == 0) {
	ini_set('display_errors',0);
	ini_set('display_startup_errors',0);
} else {
	ini_set('display_errors',1);
	ini_set('display_startup_errors',1);
}
Core::$ROOT = __DIR__;
$t = microtime(true);

include './vendor/autoload.php';
include './vendor/schoolphp/library/Core/functions.php';

if(Core::$STUBROUTINE['status']) {
	if(isset($_SERVER['REMOTE_ADDR'])) {
		if(in_array($_SERVER['REMOTE_ADDR'],Core::$STUBROUTINE['ip_access']['ip'])) {
			$allow = 1;
		} elseif(count(Core::$STUBROUTINE['ip_access']['ip_mask'])) {
			foreach(Core::$STUBROUTINE['ip_access']['ip_mask'] as $v) {
				list ($net, $mask) = explode ('/', $v);
				if(( ip2long ($_SERVER['REMOTE_ADDR']) & ~((1 << (32 - $mask)) - 1) ) == ip2long ($net)) {
					$allow = 1;
					break;
				}
			}
		}
	}
	if(empty($allow)) {
		header("HTTP/1.0 503 Service Unavailable");
		require './skins/stubroutine.tpl';
		exit;
	}
}

if(!empty($_GET['URI']) && $_GET['URI'] != 'index.php') {
	$allow = ['skins/components/kcaptcha/index.php'];
	if(in_array($_GET['URI'],$allow) || strpos($_GET['URI'],'/') === false) {
		require_once './'.$_GET['URI'];
	} else {
		header("Location: /404");
	}
	exit;

}
require './'.Core::$CONT.'/_allmodules.php';

class FrontController {
	static function getComponent($route,$tempGET = false) {
		$GET = $_GET;
		$content = self::init($route,$tempGET);
		$_GET = $GET;
		return $content;
	}

	static function init($route = '',$tempGET = false) {
		if($tempGET) {
			$GET = $_GET;
			$_GET = ($tempGET === true ? ['ajax'=>1] : $tempGET);
		}
		if(empty($route)) {
			$_GET['_module'] = 'main';
			$_GET['_page'] = 'main';
			if(file_exists(__DIR__.'/'.Core::$CONT.'/main/sitemap/sitemap.php')) {
				Core::$SITEMAP = require __DIR__.'/'.Core::$CONT.'/main/sitemap/sitemap.php';
			}
		} else {
			if(Core::$SHORTLINK) {
				$matches = [];
				if(preg_match('#^('.implode('|', Core::$LANGUAGE['allow']).')\/#ius', $route, $matches)) {
					$shortroute = preg_replace('#^'.preg_quote($matches[0]).'#ius', '', $route);
				}
				else {
					$shortroute = $route;
				}
				$res = q("
				SELECT `short`,`full`
				FROM `fw_shortlink`
				WHERE `short` = '".es(trim($shortroute, '/'))."'
				   OR `full`  = '".es(trim($shortroute, '/'))."'
			");
				if($res->num_rows) {
					$row = $res->fetch_assoc();
					Core::$META['shortlink'] = '/'.$row['short'];
					Core::$META['canonical'] = '/'.$row['full'];
					$route = (count($matches) ? $matches[0] : '').$row['full'];
				}
				$res->close();
			}

			$i = 0;
			$temp = explode('/', $route);

			if(Core::$LANGUAGE['status']) {
				if(in_array($temp[$i], Core::$LANGUAGE['allow'])) {
					Core::$LANGUAGE['lang'] = $temp[$i++];
				}
			}

			if(isset($temp[$i]) && $temp[$i] == 'admin') {
				define('ADMIN', true);
				Core::$CONT = Core::$CONT.'/admin';
				Core::$MAINTPL = 'admin.tpl';
				++$i;
			}
			if(empty($temp[$i])) {
				$temp[$i] = 'main';
			}
			if(file_exists(__DIR__.'/'.Core::$CONT.'/'.$temp[$i].'/sitemap/sitemap.php')) {
				Core::$SITEMAP[$temp[$i]] = require __DIR__.'/'.Core::$CONT.'/'.$temp[$i].'/sitemap/sitemap.php';
			}
			else {
				Core::$SITEMAP = require __DIR__.'/config/sitemap'.(defined('ADMIN') ? '_admin' : '').'.php';
			}
			require __DIR__.'/config/sitemap'.(defined('ADMIN') ? '_admin' : '').'_core.php';

			if(!isset($temp[$i])) {
				$_GET['_module'] = 'main';
				$_GET['_page'] = 'main';
			}
			elseif(in_array($temp[$i], Core::$SITEMAP['single'])) {
				$_GET['_module'] = 'static';
				$_GET['_page'] = $temp[$i++];
			}
			else {
				$temp[$i] = (string)$temp[$i];
				if(!isset(Core::$SITEMAP[$temp[$i]]) || !preg_match('#^[a-z0-9_-]+$#ius', $temp[$i])) {
					$_GET['_module'] = 'static';
					$_GET['_page'] = '404';
					goto page404;
				}
				else {
					$_GET['_module'] = $temp[$i++];
				}

				if(!isset($temp[$i])) {
					$key = key(Core::$SITEMAP[$_GET['_module']]);
					$_GET['_page'] = (!isset(Core::$SITEMAP[$_GET['_module']][$key]) ? 'main' : $key);
				}
				elseif(isset(Core::$SITEMAP[$_GET['_module']][$temp[$i]])) {
					$temp[$i] = (string)$temp[$i];
					$_GET['_page'] = $temp[$i++];
				}
				else {
					$key = key(Core::$SITEMAP[$_GET['_module']]);
					$_GET['_page'] = (!isset(Core::$SITEMAP[$_GET['_module']][$key]) ? 'main' : $key);
				}

				page404:

				if(isset(Core::$SITEMAP[$_GET['_module']][$_GET['_page']]) && is_array(Core::$SITEMAP[$_GET['_module']][$_GET['_page']])) {
					foreach(Core::$SITEMAP[$_GET['_module']][$_GET['_page']] as $k => $v) {
						if(!isset($temp[$i])) {
							if(!empty($v['req'])) {
								$_GET['_module'] = 'static';
								$_GET['_page'] = '404';
								goto page404;
							} elseif(isset($v['default'])) {
								$_GET[$k] = $v['default'];
							}
						} else {
							if(!empty($v['req']) && empty($temp[$i])) {
								$_GET['_module'] = 'static';
								$_GET['_page'] = '404';
								goto page404;
							}
							if(!isset($v['type'])) {
								$temp[$i] = (string)$temp[$i];
							} else {
								if($v['type'] == 'string') $temp[$i] = (string)$temp[$i];
								elseif($v['type'] == 'int') $temp[$i] = (int)$temp[$i];
								elseif($v['type'] == 'array') $temp[$i] = (array)$temp[$i];
								elseif($v['type'] == 'boolean') $temp[$i] = (boolean)$temp[$i];
								else {
									$_GET['_module'] = 'static';
									$_GET['_page'] = '404';
									goto page404;
								}
							}
							if(isset($v['rules']) && !preg_match('#^'.$v['rules'].'$#ius', $temp[$i])) {
								$_GET['_module'] = 'static';
								$_GET['_page'] = '404';
								goto page404;
							}
							$_GET[$k] = $temp[$i];
						}
						++$i;
					}
				}
			}
			if(isset($temp[$i])) {
				$_GET['_module'] = 'static';
				$_GET['_page'] = '404';
			}
			unset($temp, $key);
		}

		ob_start();
		if(defined('ADMIN')) {
			require './'.Core::$CONT.'/_allmodules.php';
		}
		if(!empty(Core::$SITEMAP[$_GET['_module']]['/OPTIONS']['config'])) {
			require './'.Core::$CONT.'/'.$_GET['_module'].'/config/config.php';
		}

		if(!empty(Core::$SITEMAP[$_GET['_module']]['/OPTIONS']['controller'])) {
			require './'.Core::$CONT.'/'.$_GET['_module'].'/controller/controller.php';
		} else {
			if(!empty(Core::$SITEMAP[$_GET['_module']]['/OPTIONS']['allpages'])) {
				require './'.Core::$CONT.'/'.$_GET['_module'].'/_allpages.php';
			}

			require './'.Core::$CONT.'/'.$_GET['_module'].'/'.$_GET['_page'].'.php';

			if(!empty(Core::$SITEMAP[$_GET['_module']]['/OPTIONS']['before'])) {
				require './'.Core::$CONT.'/'.$_GET['_module'].'/view'.Core::$SKIN.'/_before.tpl';
			}

			require './'.Core::$CONT.'/'.$_GET['_module'].'/view'.Core::$SKIN.'/'.$_GET['_page'].'.tpl';

			if(!empty(Core::$SITEMAP[$_GET['_module']]['/OPTIONS']['after'])) {
				require './'.Core::$CONT.'/'.$_GET['_module'].'/view'.Core::$SKIN.'/_after.tpl';
			}
		}
		if($tempGET) {
			$_GET = $GET;
		}

		if(Core::$AUTOCANONICAL && empty(Core::$META['canonical'])) {
			Core::$META['canonical'] = createUrl('this');
		}

		return ob_get_clean();
	}
}

$content = FrontController::init($_GET['route'] ?? '');
if(isset($_GET['ajax'])) {
	echo $content;
	exit;
}
include './skins'.Core::$SKIN.'/'.Core::$MAINTPL;
exit;
