<?php
// web/frontend_cache.php

require_once(dirname(__FILE__).'/switch-pool.php');


if (!in_array(@$_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1', '62.147.141.164')) && !(strpos($_SERVER['REMOTE_ADDR'], '2a01:e35:8aca:4470')!== false))
{
//  die('You are not allowed to access this file. Check '.basename(__FILE__).' for more information.');
}
 
require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');
 
$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'cache', true);
sfContext::createInstance($configuration)->dispatch();
