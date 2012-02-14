<?php

/**
 * json actions.
 *
 * @package    edt
 * @subpackage json
 * @author     Théophile Helleboid, Michael Muré
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class jsonActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeInfos(sfWebRequest $request)
  {

    if(defined('NOM_EDT')) {
        $edt = Doctrine::getTable("Edt")
          ->createQuery("e")
          ->leftJoin('e.Adeserver a')
          ->where('e.nom = ?', NOM_EDT)->execute()->getFirst();
    } else {
      throw new sfException("Pas de NOM_EDT défini");
    }
    if(!$edt) {
      throw new sfException("Pas d'edt ".NOM_EDT);
    }

    $json_data = array(
      "edtNom"			=> $edt->getNom(),
      "adeServerUrl"		=> $edt->getAdeserver()->getAdeUrl(),
      "adeIdentifier"		=> $edt->getAdeserver()->getIdentifier(),
      "edtStartTimestamp"	=> (int) $edt->getStartTimestamp(),
      "edtProjectId"		=> $edt->getAdeProjectId(),
      "edtIdPianoDay"		=> $edt->getIdPianoDay(),
      "edtDisplayMode"		=> $edt->getDisplayMode(),
      "edtDisplayConfId"	=> $edt->getDisplayConfId(),
    );
    $this->getResponse()->setContentType("application/json");
    $this->getResponse()->setContent(json_encode($json_data));
    $this->setLayout(false);
    return sfView::NONE;
  }
}

