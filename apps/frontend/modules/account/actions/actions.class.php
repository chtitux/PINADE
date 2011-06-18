<?php

/**
 * account actions.
 *
 * @package    edt
 * @subpackage account
 * @author     Théophile Helleboid
 */
class accountActions extends sfActions
{
  /**
   * 
   */
  public function executeMy(sfWebRequest $request)
  {
    $this->promotions = $this->getUser()->getGuardUser()->getPromotions();
  }


}