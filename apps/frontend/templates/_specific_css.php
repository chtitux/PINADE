<?php
if(defined('NOM_EDT')) {
  if(file_exists(sfConfig::get('sf_web_dir')."/css/".NOM_EDT.".css"))
  {
    echo '<link rel="stylesheet" type="text/css" media="screen" href="/css/'.NOM_EDT.'.css" />';
  }
}

