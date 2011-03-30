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

<div id="fb-root"></div>
<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId  : '145018112230561',
      status : true, // check login status
      cookie : true, // enable cookies to allow the server to access the session
      xfbml  : true  // parse XFBML
    });
  };

  (function() {
    var e = document.createElement('script');
    e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
    e.async = true;
    document.getElementById('fb-root').appendChild(e);
  }());
</script>


      <fb:login-button>Login with Facebook</fb:login-button>
<?php $sfGuardUser = sfFacebook::getSfGuardUserByFacebookSession(); ?>
Hello <fb:name uid="<?php echo $sfGuardUser ? $sfGuardUser->getProfile()->getFacebookUid() : '' ?>" useyou="false"></fb:name>