<?php

/**
 * edt actions.
 *
 * @package    edt
 * @subpackage offline
 * @author     T. Helleboid <t.helleboid@iariss.fr>
 * see : http://diveintohtml5.org/offline.html
 */
class offlineActions extends sfActions
{
 /**
  * Executes manifest action
  *
  * @param sfRequest $request A request object
  */
  public function executeManifest(sfWebRequest $request)
  {
    $this->promotion = Doctrine_Core::getTable('Promotion')
      ->createQuery('p')
      ->leftJoin('p.Categorie c')
      ->where('p.url = ? AND c.url = ?', array($request->getParameter('promo'),  $request->getParameter('categorie')))
      ->execute()
      ->getFirst();
    $this->forward404Unless($this->promotion);

    $this->categories = Doctrine_Core::getTable('Categorie')
      ->createQuery('c')
      ->execute();

    $this->semaine = intval($request->getParameter('semaine', AdeTools::getSemaineNumber()));


    $adeImage = new AdeImage($this->promotion, $this->semaine);

    // Disable layout, it's a plain text file
    $this->setLayout(false);
    $this->getResponse()->setContentType('text/cache-manifest');

    $this->categorie = $this->promotion->getCategorie();

    $this->adeImage = $adeImage;

  }

  public function executeEnable(sfWebRequest $request)
  {
  }
}
