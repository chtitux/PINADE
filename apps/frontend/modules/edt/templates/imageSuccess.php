          <?php include_partial('global/adsense_imagetop') ?>
          <?php include_partial('title', array('categorie' => $categorie, 'promotion' => $promotion)) ?>
          <h3 class="nom-edt-image"><?php include_partial('global/nom_edt') ?></h3>


          <h1 id="title">Emploi du temps <?php include_slot('title') ?></h1>

          <h2 id="semaine">
            Semaine du <b><?php echo strftime("%e %B %G", $timestamp + 2*60*60) ?></b> au <b><?php echo  strftime("%e %B %G", intval($timestamp) + 5*24*60*60 - 1 ) ?></b>
          </h2>

<?php if($notice): ?>
            <div id="notice"><?php echo nl2br(html_entity_decode($notice)) ?></div>
<?php endif ?>

          <p class="center">
            <?php echo link_to(image_tag('divers/precedent.png', 'alt="<<"'),
              "@image?categorie=".$categorie->getUrl()."&promo=".$promotion->getUrl()."&semaine=$semaine_precedente") ?>

            <?php echo link_to('semaine actuelle', "@image?categorie=".$categorie->getUrl()."&promo=".$promotion->getUrl()."&semaine=") ?>

            <?php echo link_to(image_tag('divers/suivant.png', 'alt=">>"'), "@image?categorie=".$categorie->getUrl()."&promo=".$promotion->getUrl()."&semaine=$semaine_suivante") ?>

          </p>

<?php if($diff_day > 1): ?>
              <div id="error">
                Attention, cet emploi du temps a plus de <?php echo floor($diff_day)." jour".(($diff_day >= 2) ? "s" : "") ?>.
                <?php echo link_to("Actualisez la page", "@image?categorie=".$categorie->getUrl()."&promo=".$promotion->getUrl()."&semaine=$semaine") ?> et 
                <?php echo link_to('contactez-nous', '@message') ?> si cela ne débloque pas cette situation.<br/><br/>
              </div>
<?php endif ?>

          <img
            src='<?php echo url_for("@image_img?categorie=".$categorie->getUrl()."&promo=".$promotion->getUrl()."&semaine=$semaine") ?>'
            alt='emploi du temps <?php echo $categorie." ".$promotion ?>'
            id="imgedt-<?php echo $categorie->getUrl() ?>-<?php echo $promotion->getUrl() ?>"
          />

<?php include_partial('global/adsense_lb') ?>

          <!-- raccourci clavier gauche/droite -->
          <script type="text/javascript">
          document.onkeydown=function(e){
            //Internet Explorer ne prend pas d'objet Event en paramètre, il faut donc aller le chercher dans l'objet window 
            if (typeof e == "undefined" ) e = window.event;

            if(e.keyCode == 37)
            {
              document.location = '<?php echo url_for("@image?categorie=".$categorie->getUrl()."&promo=".$promotion->getUrl()."&semaine=".max(0,$semaine-1)) ?>';
            }
            else if(e.keyCode == 39)
            {
              document.location = '<?php echo url_for("@image?categorie=".$categorie->getUrl()."&promo=".$promotion->getUrl()."&semaine=".max(0,$semaine+1)) ?>';
            }
          }
          </script>
<br/>
<?php if($sf_request->getCookie('default') == $promotion->getId()): ?>
          <form method="post" action="<?php echo url_for('@cookie_reset') ?>">
          <input type="submit" value="Effacer cette page comme page d'accueil" class="button" />
          <input type="hidden" name="key" value="default" />
<?php else: ?>
          <form method="post" action="<?php echo url_for('@cookie_set') ?>">
          <input type="submit" value="Enregistrer cette page comme page d'accueil" class="button" />
          <input type="hidden" name="key" value="default" />
          <input type="hidden" name="value" value="<?php echo $promotion->getId() ?>" />

<?php endif ?>
          </form>

<br/>
<?php // echo link_to("Ajouter à Google Agenda", "http://www.google.com/calendar/render?cid=".urlencode(url_for("@ical?categorie=".$categorie->getUrl()."&promo=".$promotion->getUrl(), true))) ?>

<?php if(sfConfig::get('app_enable_preload', false)): ?>
<span id='preload-img'></span>
<script type="text/javascript">
setTimeout(function() {
  document.getElementById('preload-img').style.cssText="background:url(<?php echo url_for("@image_img?categorie=".$categorie->getUrl()."&promo=".$promotion->getUrl()."&semaine=$semaine_suivante") ?>)";
  if(typeof console != 'undefined')
  {
    console.log("preload de l'image suivante");
  }
}, 3500);
</script>
<?php endif ?>

