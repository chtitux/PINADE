<?php slot('title'); ?>Accueil<?php end_slot() ?>
<h1>
  Bienvenue sur le site de <b>votre</b> emploi du temps.
</h1>


<h2>Les emplois du temps sélectionnés</h2>

<ul id="liste-index">
<?php foreach($categories as $categorie): ?>
  <li><?php echo image_tag("logos/".$categorie->getLogo(), "alt='logo ".$categorie."'");
   echo " ".link_to($categorie, '@categorie_index?categorie='.$categorie->getUrl()) ?>
    <ul>
    <?php foreach($categorie->getPromotions() as $promotion): ?>
      <li class='liste-index-li' >
        <?php echo link_to($promotion, "@image?categorie=".$categorie->getUrl()."&promo=".$promotion->getUrl()."&semaine=", "id='li-id-".$promotion->getId()."'") ?>
      </li>
    <?php endforeach ?>
    </ul>
  </li>
<?php endforeach ?>
</ul>

