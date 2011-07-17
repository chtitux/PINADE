<?php
setlocale(LC_ALL, 'fr_FR.utf8');
date_default_timezone_set('Europe/Paris');


require_once dirname(__FILE__).'/../lib/vendor/symfony/lib/autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();

class ProjectConfiguration extends sfProjectConfiguration
{
  public function setup()
  {
    $this->enablePlugins('sfDoctrinePlugin');

    // Authentification
    $this->enablePlugins('sfDoctrineGuardPlugin');
    $this->enablePlugins('sfFacebookConnectPlugin');
require '/var/www/pinade/pinade-git/plugins/Facebook/facebook-php-sdk-ab2d46d/src/facebook.php';
  
    // config ADE
    $this->enablePlugins('sfADEConfigPlugin');
  }
}
