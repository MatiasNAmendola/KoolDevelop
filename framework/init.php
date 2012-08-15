<?php
/**
 * Framework Entry point
 *
 * @author Elze Kool
 * @copyright Elze Kool, Kool Software en Webdevelopment
 *
 * @package KoolDevelop
 * @subpackage Core
 **/

ini_set('display_errors', '1');

if (!defined('_APP_PATH_')) {
	throw new Exception("Application path not set!");
}

if (!defined('_FRAMEWORK_PATH_')) {
	throw new Exception("Framework path not set!");
}

if (!defined('_LIBS_PATH_')) {
	throw new Exception("Libraries path not set!");
}

if (!defined('_CONFIG_PATH_')) {
	throw new Exception("Configuration path not set!");
}

if (!defined('DS')) {
	/**
	 * Directory Seperator
	 * @var string
	 */
	define('DS', DIRECTORY_SEPARATOR);
}


// Load shorthand functions
require _FRAMEWORK_PATH_ . DS . 'shorthand.php';

// Load AutoLoader
require_once _FRAMEWORK_PATH_ . DS . 'AutoLoader.php';
$autoload = KoolDevelop\AutoLoader::getInstance();

try {

    // Load Bootstrapper
    $bootstrapper = new \Bootstrapper();
    $bootstrapper->init();

    // Get current environment, and save this in the configuration class
    \KoolDevelop\Configuration::setCurrentEnvironment($bootstrapper->getEnvironment());

    // Start routing
    $bootstrapper->route();

} catch(\Exception $e) {

    // Clear all output buffering
	while(ob_get_level() != 0) {
		ob_end_clean();
	}

	// Send Error to Handler
	\KoolDevelop\ErrorHandler::getInstance()->handleException(__f($e,'kooldevelop'));
	die();
	
}

?>