<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <title><?php include_slot('title') ?> - Emploi du temps ENSISA</title>
    <link rel="shortcut icon" href="/favicon.ico" />
    <?php include_stylesheets() ?>
    <link rel="stylesheet" href="/css/mobile.css" type="text/css" media="handheld, only screen and (max-device-width: 480px)" />
    <?php include_javascripts() ?>
  </head>
  <body>
    <div id="global">
      <div id="entete">
        <ul>
          <li><?php echo link_to('Accueil', '@homepage') ?></li>
          <li><?php echo link_to('Annales', 'http://annales.iariss.fr/') ?></li>
          <li><?php echo link_to('Trombinoscope', 'http://trombi.iariss.fr/') ?></li>
          <li><?php echo link_to('Blog', 'http://ensisa11.iariss.fr/') ?></li>
        </ul>
      </div>
      <div id="centre">
        <div id="contenu">
          <?php echo $sf_content ?>
        </div>

        <div id="navigation">
          <div id="menu">
            <ul>
              <li id='accueil-menu'>
                <?php echo link_to('Accueil', '@homepage', "inline") ?>
              </li>
<?php $filieres = sfConfig::get('sf_filieres') ?>
<?php foreach($filieres as $id_f => $filiere): ?>
              <li><?php echo image_tag("logos/$id_f.png", "alt='logo $id_f'") ?><?php echo $filiere['nom'] ?>
                <ul>
  <?php foreach($filiere['promotions'] as $id_p => $promo): ?>
                  <li><?php echo link_to($promo['nom'], "@image?filiere=$id_f&promo=$id_p&semaine=".$sf_request->getParameter('semaine')) ?></li>
  <?php endforeach ?>
                </ul>
              </li>
<?php endforeach ?>
            </ul>
          </div>
          
          <div id="pub" style="text-align:justify; font-size:80%">
            <p>Tu souhaites avoir ton emploi du temps sur <b>Google Agenda</b> ou un logiciel similaire&nbsp;?
            Nous cherchons des beta-testeurs pour cette nouvelle fonctionnalité&nbsp;!<br/>
            Contacte nous (<code>contact@iariss.fr</code>), tu recevras la marche à suivre&nbsp;!
            </p>
          </div>
        </div>
     
      </div>

      <div id="pied">
        <p>
          Emploi du temps réalisé par <?php echo link_to('IARISS', 'http://iariss.fr/') ?>
          - <a href="mailto:contact@iariss.fr">Contact</a>
          - <?php echo link_to('FAQ', '@faq') ?>
        </p>
      </div>
    </div>

    <!-- Piwik -->
    <script type="text/javascript">
      var pkBaseURL = (("https:" == document.location.protocol) ? "https://piwik.iariss.fr/" : "http://piwik.iariss.fr/");
      document.write(unescape("%3Cscript src='" + pkBaseURL + "piwik.js' type='text/javascript'%3E%3C/script%3E"));
    </script>
    <script type="text/javascript">
      try {
        var piwikTracker = Piwik.getTracker(pkBaseURL + "piwik.php", 9);
        piwikTracker.trackPageView();
        piwikTracker.enableLinkTracking();
      } catch( err ) {}
    </script><noscript><p><img src="http://piwik.iariss.fr/piwik.php?idsite=9" style="border:0" alt="" /></p></noscript>
    <!-- End Piwik Tag -->
  </body>
</html>
