<h1>Compte utilisateur</h1>
<ul>
  <li>Compte : <b><?php echo $sf_user->getGuardUser() ?></b></li>
  <li>Email : <b><?php echo $sf_user->getGuardUser()->getEmailAddress() ?></b></li>
  <li>Emplois du temps :
    <?php if(count($promotions)>0): ?>
    <ul>
      <?php   foreach($promotions as $promo): ?>
      <li><?php echo link_to($promo, "@image?categorie=".$promo->getCategorie()->getUrl()."&promo=".$promo->getUrl()."&semaine=") ?></li>
      <?php endforeach ?>
    </ul>
    <?php else: ?>
      <i>pas d'emploi du temps enregistrés</i>
    <?php endif ?>
  </li>
</ul>