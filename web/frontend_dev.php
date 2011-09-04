<?php

require_once(dirname(__FILE__).'/switch-pool.php');

// this check prevents access to debug front controllers that are deployed by accident to production servers.
// feel free to remove this, extend it or make something more sophisticated.
if (!in_array(@$_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1', '2a01:e35:8aca:4470:224:23ff:fe06:c02c', '62.147.141.164'))  && !(strpos($_SERVER['REMOTE_ADDR'], '2a01:e35:2f02:ab80')!== false))
{
  die('You are not allowed to access this file. Check '.basename(__FILE__).' for more information.');
}

require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');

$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'dev', true);
sfContext::createInstance($configuration)->dispatch();
