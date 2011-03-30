<?php slot('title'); ?>Accueil<?php end_slot() ?>
<h1>
  Bienvenue sur l'emploi du temps de l'ENSISA.
</h1>

<h2>
  Selectionnez votre filière:
</h2>



<ul >
<?php foreach($filieres as $filiere): ?>
  <li><?php echo image_tag("logos/".$filiere->getLogo(), "alt='logo ".$filiere."'");
   echo " ".link_to($filiere, '@filiere_index?filiere='.$filiere->getUrl()) ?>
  </li>
<?php endforeach ?>
</ul>

<?php use_helper('sfFacebookConnect')?>
<?php echo include_facebook_connect_script() ?>


      <fb:login-button>Login with Facebook</fb:login-button>
<?php $sfGuardUser = sfFacebook::getSfGuardUserByFacebookSession(); ?>
Hello <fb:name uid="<?php echo $sfGuardUser ? $sfGuardUser->getProfile()->getFacebookUid() : '' ?>" useyou="false"></fb:name>