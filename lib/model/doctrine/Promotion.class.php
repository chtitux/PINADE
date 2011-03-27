<?php

/**
 * Promotion
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    edt
 * @subpackage model
 * @author     Théophile Helleboid, Michael Muré
 * @version    SVN: $Id: Builder.php 7691 2011-02-04 15:43:29Z jwage $
 */
class Promotion extends BasePromotion
{
  public function __toString()
  {
    return $this->getNom();
  }
  public function getUrlToWeek($week)
  {
    return "@image?filiere=".$this->getFiliere()->getUrl()."&promo=".$this->getUrl()."&semaine=".$week;
  }
}
