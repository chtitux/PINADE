<div id="account-menu">
  <?php if($sf_user->isAuthenticated()): ?>
    <b><?php echo link_to("Votre compte", "@account?action=my") ?></b><br/>
    <?php if(count($sf_user->getGuardUser()->getPromotions())>0): ?>
    <ul>
      <?php   foreach($sf_user->getGuardUser()->getPromotions() as $promo): ?>
      <li><?php echo link_to($promo, "@image?categorie=".$promo->getCategorie()->getUrl()."&promo=".$promo->getUrl()."&semaine=") ?></li>
      <?php endforeach ?>
    </ul>
    <?php else: ?>
    <?php endif ?>
    <?php echo link_to('Se déconnecter', '@sf_guard_signout') ?>

  <?php else: ?>
    <?php echo link_to('Se connecter', '@sf_guard_signin') ?>
<?php include_component('sfApply', 'login') ?>

  <?php endif ?>
</div>