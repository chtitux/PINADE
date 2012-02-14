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

  public function executeIndex(sfWebRequest $request)
  {
    $query = Doctrine_Core::getTable('Adeserver')
      ->createQuery('a')
      ->leftJoin('a.Edts e')
      ->leftJoin('e.Categories c')
      ->leftJoin('c.Promotions p');

    if(defined('NOM_EDT'))
      $query->addWhere('e.nom = ?', NOM_EDT);

    $this->adeservers = $query->execute();
  }

  public function executeImport(sfWebRequest $request)
  {
    $this->forward404Unless(sfConfig::get('sf_environment') == "dev");

    $this->categories = Doctrine_Core::getTable('Categorie')
      ->createQuery('c')
      ->execute();
    $this->categorie_id = $this->getUser()->getAttribute('pinade/categorie_id');
  }

  public function executeCreateFromImport(sfWebRequest $request)
  {

    $this->forward404Unless(sfConfig::get('sf_environment') == "dev");
    // projectId=19
    // idPianoWeek=31
    // idPianoDay=0,1,2,3,4
    // idTree=147,148,149,113,116,117,118
    $adeurl = urldecode($request->getParameter('adeurl'));
    $patterns = array(
      'projectId'   => "@projectId=([\d]+)&@",
      'idPianoWeek' => "@idPianoWeek=([\d,]+)&@",
      'idPianoDay'  => "@idPianoDay=([\d,]+)&@",
      'idTree'      => "@idTree=([\d,]+)&@",
    );
    
    foreach($patterns as $id => $pattern)
    {
      if(preg_match($pattern, $adeurl, $matches[$id]) == 0)
      {
        $request->setParameter('erreur', "Problème dans l'URL de l'image ($id incorrect/non trouvé) [".$adeurl."]");
        $this->forward('myedt', 'import');
      }
    }

    $projectId = $matches['projectId'][1];
    $idPianoWeek = $matches['idPianoWeek'][1];
    $idPianoDay = $matches['idPianoDay'][1];
    $idTree = $matches['idTree'][1];
    $url = $request->getParameter('url');
    $nom = (strlen($param = $request->getParameter('nom'))) ? $param : $url;

    if(preg_match("@^[0-9a-zA-Z\-_]+$@", $url, $dummy) == 0)
    {
      $request->setParameter('erreur', "URL vide ou avec des caractères invalides ! Il ne peut comporter que des chiffres, lettres non accentuées, - et _");
      $this->forward('myedt', 'import');
    }


    $categorie = Doctrine_Core::getTable('Promotion')
      ->createQuery('p')
      ->leftJoin('p.Categorie c')
      ->andwhere('c.url = "perso"')
      ->andWhere('p.url = ?', array($nom))
      ->execute();

    if($categorie->count() > 0)
    {
      $request->setParameter('erreur', "Nom déjà pris, choisissez en un autre !");
      $this->forward('myedt', 'import');
    }


    $categorie = Doctrine_Core::getTable('Categorie')
      ->find($cat_id = $request->getParameter('categorie_id'));

    if(!$categorie)
    {
      $request->setParameter('erreur', "Erreur de l'administrateur. La catégorie $cat_id n'existe pas !");
      $this->forward('myedt', 'import');
    }

    $promotion = new Promotion();
    $promotion->setIdTree($idTree);
    $promotion->setCategorieId($categorie->getId());
    $promotion->setNom($nom);
    $promotion->setDescription($request->getParameter('description'));
    $promotion->setUrl($url);
    $promotion->setInMenu(true);
    $promotion->save();

    $this->getUser()->setAttribute('pinade/categorie_id', $categorie->getId());

    $this->redirect('@image?categorie='.$categorie->getUrl().'&promo='.$promotion->getUrl().'&semaine=');

   // https://www.emploisdutemps.uha.fr/ade/imageEt?&&&width=800&height=600&lunchName=REPAS&displayMode=1057855&showLoad=false&ttl=1283427991552&displayConfId=8

  }

  public function executeShow(sfWebRequest $request)
  {
    $this->promotion = Doctrine_Core::getTable('Promotion')
      ->createQuery('p')
      ->leftJoin('p.Categorie c')
      ->andwhere('p.url = ? AND c.url = ?', array($request->getParameter('promo'),  $request->getParameter('categorie')))
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

}
