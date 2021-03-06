<?php

// Kickstart the framework
$f3=require('lib/base.php');

// A few version checks
if ((float)PCRE_VERSION<7.9)
	trigger_error('PCRE version is out of date');
if ( FALSE!==version_compare(PHP_VERSION, '7.0.0', '<=') )
{
	echo "You do not meet the minimum requirements to run this script on this server ( PHP 7.0.0+ required ).<br>You are running ".PHP_VERSION;
	exit;
}

error_reporting(defined('E_STRICT') ? E_ALL | E_STRICT : E_ALL );
// Caching has to be disabled, otherwise saving config will only take effect after a few reloads - very erratic
ini_set('opcache.enable', 0);

// Load installer configuration
$f3->config('cfg/config.ini');

// Load user's server configuration
$f3->dbCFG = new \DB\Jig ( "cfg/" , \DB\Jig::FORMAT_JSON );
$f3->set('installerCFG', $f3->dbCFG->read('config.json'));
if ( "" == $language = $f3->get('installerCFG.language')) $language = "en";

/** Define the basic language **/
$f3->set('ENCODING','UTF-8');
$f3->set('LANGUAGE',$language);
setlocale(LC_ALL, __transLocale);		// http://www.php.net/setlocale

// Set a fallback page title
$f3->set('module', '');

if ( file_exists('lock.file') ) $f3->set('locked',TRUE);
else include("inc/routes.php");

$f3->route( ['GET /', 'GET /*'],
	function($f3) {
		$view = new Template;
		$f3->set('content', $view->render('welcome.htm'));
	}
);

$f3->route('GET /debug',
	function($f3) {
		$view = new View;
		ksort($f3['installerCFG']);
		$config = $f3['installerCFG'];
		foreach($config as &$cfg)
		{
			if(isset($cfg['pass'])) $cfg['pass'] = "*** removed ***";
		}
		$f3->set('content', "<pre>".print_r($config,TRUE)."</pre>");
	}
);

// Limit settings to adjust the conversion speed
include('cfg/speed.php');

$f3->run();
echo Template::instance()->render('layout.htm');

?>