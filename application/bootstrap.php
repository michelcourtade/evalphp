<?php
ob_start();
setlocale(LC_ALL, 'fr_FR.UTF8');
setlocale(LC_NUMERIC, 'C');

define('APP_ENV', 'DEV');

if(defined("APP_ENV") && (APP_ENV == "PROD")) {
    error_reporting(0);
}
else {
    error_reporting(E_ALL ^E_DEPRECATED^E_NOTICE^E_WARNING^E_STRICT);
    ini_set("display_errors","1");
}

ini_set("memory_limit" , "256M");
ini_set("session.bug_compat_warn", "off");
ini_set("max_execution_time", "60");


$currentDir = dirname(__FILE__);
define('ROOT_DIR', 				realpath($currentDir.'/..'));
ini_set('include_path','.:'
            .ROOT_DIR.'/application/models:'
            .ROOT_DIR.'/application');

require_once __DIR__.'/../vendor/autoload.php';

$dbConfig = parse_ini_file('config/parameters_'.strtolower(APP_ENV).'.ini',TRUE);
define('DB_HOST', $dbConfig['DB_HOST']);
define('DB_NAME', $dbConfig['DB_NAME']);
define('DB_USERNAME', $dbConfig['DB_USERNAME']);
define('DB_PASSWORD', $dbConfig['DB_PASSWORD']);

$config = parse_ini_file('config/application.ini',TRUE);

if($debug) { $config["DB_DataObject"]["debug"] = $debug;}

foreach($config as $class => $values) {
    $options = &PEAR::getStaticProperty($class,'options');
    $options = $values;
}
require_once 'defines.php';

define('DIR_STATIC',			'/data');
define('DIR_THEMES',			'/themes');
define('DIR_THEME',				'/' . THEME);
define('DIR_CSS', 				'/css');
define('DIR_JS', 				'/js');
define('DIR_IMG', 				'/images');
define('DIR_CACHE',				'/cache');

define('PATH_THEME',			DIR_THEMES . DIR_THEME);

define('PATH_CSS',				DIR_CSS);
define('PATH_JS', 				DIR_JS);
define('PATH_IMG',				DIR_IMG);
define('PATH_THEME_CSS',		DIR_THEMES . DIR_THEME . DIR_CSS);
define('PATH_THEME_JS',  		DIR_THEMES . DIR_THEME . DIR_JS);
define('PATH_THEME_IMG',		DIR_THEMES . DIR_THEME . DIR_IMG);
define('PATH_CACHE',			DIR_THEMES . DIR_THEME . DIR_CACHE);

define('ROOT_PATH_STATIC',		ROOT_DIR . DIR_STATIC);
define('ROOT_PATH_THEME',		ROOT_PATH_STATIC . DIR_THEMES . DIR_THEME);
define('ROOT_PATH_CSS',			ROOT_PATH_STATIC . PATH_CSS);
define('ROOT_PATH_JS',  		ROOT_PATH_STATIC . PATH_JS);
define('ROOT_PATH_IMG',			ROOT_PATH_STATIC . PATH_IMG);
define('ROOT_PATH_THEME_CSS',	ROOT_PATH_STATIC . PATH_THEME_CSS);
define('ROOT_PATH_THEME_JS',  	ROOT_PATH_STATIC . PATH_THEME_JS);
define('ROOT_PATH_THEME_IMG',	ROOT_PATH_STATIC . PATH_THEME_IMG);
define('ROOT_PATH_CACHE',		ROOT_PATH_STATIC . PATH_CACHE);

require_once(dirname(__FILE__).'/autoload.php');

$AuthOptions = array(
	'dsn' 			=> "mysql://" . DB_USERNAME . ":" . DB_PASSWORD . "@" . DB_HOST . "/" . DB_NAME,
	'table' 		=> TABLE_USERS,
	'usernamecol' 	=> "email",
	'passwordcol' 	=> "passwd",
	'cryptType'		=> "md5",
	'db_fields' 	=> "*",
	"enableLogging"	=> true,
	'db_options'	=> array('portability' => MDB2_PORTABILITY_ALL ^ MDB2_PORTABILITY_FIX_CASE),
);

// Translation2 params
$driver = $config["DB_DataObject"]["db_driver"];
$dbinfo = array(
    'hostspec' => DB_HOST,
    'database' => DB_NAME,
    'phptype'  => 'mysql',
    'username' => DB_USERNAME,
    'password' => DB_PASSWORD,
	'charset'  => 'utf8'
);
$params = array(
    'langs_avail_table' => TABLE_PREFIX.TABLE_LANGSAVAIL,
    'lang_id_col'     => 'id',
    'lang_name_col'   => 'name',
    'lang_meta_col'   => 'meta',
    'lang_errmsg_col' => 'error_text',
    'strings_default_table' => TABLE_PREFIX.TABLE_MESSAGES, 
    'string_id_col'         => 'id',
    'string_page_id_col'    => 'mbc_site',
    'string_text_col'       => '%s',
	'cacheOptions'			=> array('cacheDir' => ROOT_PATH_CACHE, 'lifeTime' => 3600*24)
);
$a = new Auth("MDB2", $AuthOptions, "Tools::loginFunction");

if($a->checkAuth()) {
    $user = User::getUserById($a->getAuthData('user_id'));
}