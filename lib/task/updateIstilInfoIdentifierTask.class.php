<?php

class updateIstilInfoIdentifierTask extends updateLyon1IdentifierTask
{
  protected function configure()
  {
    $this->nom_edt = "istil-info";
    parent::configure();
  }
}