<?php
/* SVN FILE: $Id: bootstrap.php 7945 2008-12-19 02:16:01Z gwoo $ */
/**
 * Short description for file.
 *
 * Long description for file
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework (http://www.cakephp.org)
 * Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 * @link          http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.app.config
 * @since         CakePHP(tm) v 0.10.8.2117
 * @version       $Revision: 7945 $
 * @modifiedby    $LastChangedBy: gwoo $
 * @lastmodified  $Date: 2008-12-18 18:16:01 -0800 (Thu, 18 Dec 2008) $
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
/**
 *
 * This file is loaded automatically by the app/webroot/index.php file after the core bootstrap.php is loaded
 * This is an application wide file to load any function that is not used within a class define.
 * You can also use this to include or require any files in your application.
 *
 */
/**
 * The settings below can be used to set additional paths to models, views and controllers.
 * This is related to Ticket #470 (https://trac.cakephp.org/ticket/470)
 *
 * $modelPaths = array('full path to models', 'second full path to models', 'etc...');
 * $viewPaths = array('this path to views', 'second full path to views', 'etc...');
 * $controllerPaths = array('this path to controllers', 'second full path to controllers', 'etc...');
 *
 */
//EOF

date_default_timezone_set('America/Chicago');

//CONSTANTS
define('SECONDS_IN_A_DAY', 86400);
define('COMPANY_NAME', 'Cannonball');
define('REPLY_EMAIL', 'no-reply@cannonballagency.com');

//USER TYPE CONSTANTS
define('ADMIN_USER', 1);
define('ADMIN_SUPERUSER', 2);

// COLLECTION TYPE CONSTANTS
define('UNIDIRECTIONAL', 1);
define('BIDIRECTIONAL', 2);

//SET ASSET UPLOAD PATH
define('ASSET_FOLDER', 'assets');
define('ASSET_PATH', "https://cbdam.s3.amazonaws.com" . DS);
define('ASSET_REL_PATH', "https://cbdam.s3.amazonaws.com" . DS);
define('ASSET_UPLOAD_PATH', '/app/webroot/' . ASSET_FOLDER);    // not used with S3
define('BUCKET', 'cbdam');

//FIREPHP SETUP
ob_start();
App:: import ( 'Vendor', 'FirePHP', array ( 'file' => 'FirePHP.class.php'));
function fb()
{
  $instance = FirePHP::getInstance(true);
  $args = func_get_args();
  return call_user_func_array(array($instance,'fb'),$args);
  return true;
}
?>