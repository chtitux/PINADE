<?php

/**
 * myedt actions.
 *
 * @package    edt
 * @subpackage myedt
 * @author     Théophile Helleboid, Michael Muré <contact@iariss.fr>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class myedtActions extends sfActions
{

  public function executeImport(sfWebRequest $request)
  {}

  public function executeCreateFromImport(sfWebRequest $request)
  {
    // projectId=19
    // idPianoWeek=31
    // idPianoDay=0,1,2,3,4
    // idTree=147,148,149,113,116,117,118
    $url = urldecode($request->getParameter('url'));
    $patterns = array(
      'projectId'   => "@projectId=([\d]+)&@",
      'idPianoWeek' => "@idPianoWeek=([\d]+)&@",
      'idPianoDay'  => "@idPianoDay=([\d,]+)&@",
      'idTree'      => "@idTree=([\d,]+)&@",
    );
    
    foreach($patterns as $id => $pattern)
    {
      if(preg_match($pattern, $url, $matches[$id]) == 0)
      {
        $request->setParameter('erreur', "Problème dans l'URL de l'image ($id incorrect/non trouvé)");
        $this->forward('myedt', 'import');
      }
    }

    $projectId = $matches['projectId'][1];
    $idPianoWeek = $matches['idPianoWeek'][1];
    $idPianoDay = $matches['idPianoDay'][1];
    $idTree = $matches['idTree'][1];
    $nom = $request->getParameter('nom');

    if($request->getParameter('nom') == "" || (preg_match("@^[0-9a-zA-Z\-_]+$@", $nom, $dummy) == 0))
    {
      $request->setParameter('erreur', "Nom vide ou avec des caractères invalides ! Il ne peut comporter que des chiffres, lettres non accentuées, - et _");
      $this->forward('myedt', 'import');
    }


    $categorie = Doctrine_Core::getTable('Promotion')
      ->createQuery('p')
      ->leftJoin('p.Categorie c')
      ->where('c.url = "perso"')
      ->andWhere('p.url = ?', array($nom))
      ->execute();

    if($categorie->count() > 0)
    {
      $request->setParameter('erreur', "Nom déjà pris, choisissez en un autre !");
      $this->forward('myedt', 'import');
    }


    $categorie = Doctrine_Core::getTable('Categorie')
      ->createQuery('c')
      ->where('c.url = "perso"')
      ->execute()->getFirst();

    if(!$categorie)
    {
      $request->setParameter('erreur', "Erreur de l'administrateur. La catégorie 'perso' n'existe pas !");
      $this->forward('myedt', 'import');
    }

    $promotion = new Promotion();
    $promotion->setProjectId($projectId);
    // $promotion->setIdPianoWeek($idPianoWeek);
    $promotion->setIdPianoDay($idPianoDay);
    $promotion->setIdTree($idTree);
    $promotion->setCategorieId($categorie->getId());
    $promotion->setNom($nom);
    $promotion->setDescription($request->getParameter('description'));
    if($this->getUser()->isAuthenticated())
    {
      $promotion->setUrl(md5(rand().$nom.rand()."45 @^\`"));
      $promotion->setOwnerId($this->getUser()->getGuardUser()->getId());
    }
    else
    {
      $promotion->setUrl($nom);
    }
    $promotion->save();

    $this->redirect('@image?categorie=perso&promo='.$promotion->getUrl().'&semaine=');

   // https://www.emploisdutemps.uha.fr/ade/imageEt?&&&width=800&height=600&lunchName=REPAS&displayMode=1057855&showLoad=false&ttl=1283427991552&displayConfId=8

  }

  public function executeShow(sfWebRequest $request)
  {
    $this->promotion = Doctrine_Core::getTable('Promotion')
      ->createQuery('p')
      ->leftJoin('p.Categorie c')
      ->where('p.url = ? AND c.url = ?', array($request->getParameter('promo'),  $request->getParameter('categorie')))
      ->execute()
      ->getFirst();
    $this->forward404Unless($this->promotion);
    
    foreach(range(0, 52) as $semaine)
    {
      $images[$semaine] = new AdeImage($this->promotion, $semaine);
    }
    $this->images = $images;
    $this->cookie = $request->getCookie('default');

  }

  /**
   * Copie une "promotion" vers un compte perso
   */
  public function executeCopy(sfWebRequest $request)
  {
    $promotion = Doctrine_Core::getTable('Promotion')->find($request->getParameter('id'));
    $this->forward404Unless($promotion);

    // Il faut être authentifier pour copier vers son propre compte
    $this->forward404Unless($this->getUser()->isAuthenticated());

    $newPromo = $promotion->copy();
    $newPromo->setOwnerId($this->getUser()->getGuardUser()->getId());
    $newPromo->setUrl(md5(time()."4@ç_é{2".rand())); // md5 is generated with a "salt"
    $newPromo->setCategorieId(Doctrine_Core::getTable('Categorie')
      ->createQuery('c')
      ->where('c.url = "perso"')
      ->execute()->getFirst()->getId()
    );
    $newPromo->save();

    $this->redirect("@image?categorie=".$newPromo->getCategorie()->getUrl()."&promo=".$newPromo->getUrl()."&semaine=");
  }
}
