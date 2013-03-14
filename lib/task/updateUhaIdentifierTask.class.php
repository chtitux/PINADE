<?php

class updateUhaIdentifierTask extends updateIdentifierTask
{
  protected function configure()
  {
    // Configuration de la tâche
    // urls  : la séquence à enchaîner pour dérouler et cliquer sur un emploi du temps
    // name  : le nom de la tache après pinade:
    // ade_server_name : le nom de l'adeserver dans la base de données
    parent::configure();

    $this->name = "identifier-uha";
    $this->ade_server_name = "uha";

    $this->urls = array(
      'custom/modules/plannings/plannings.jsp', // Mandatory (because of ADE)
      'standard/gui/tree.jsp?category=trainee&expand=false&forceLoad=false&reload=false&scroll=0', // Select groups of students
      'standard/gui/tree.jsp?branchId=199&reset=true&forceLoad=false&scroll=0', // Select a group (ENSISA Lumiere)
      'standard/gui/tree.jsp?branchId=203&reset=false&forceLoad=false&scroll=0', // Select a group (Ingénieurs)
      'standard/gui/tree.jsp?branchId=143&reset=false&forceLoad=false&scroll=0', // Select a group (3A)
      'standard/gui/tree.jsp?selectBranchId=145&reset=true&forceLoad=false&scroll=0', // "Click" on a group (3A Info S5)
    );

  }



  protected function getAuthentication()
  {
    $this->logSection('auth', 'start Authentification');

    $handle = curl_init($url = "https://cas.uha.fr/cas/login?service=http://www.emploisdutemps.uha.fr:80/ade/standard/gui/interface.jsp");
    curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false); /* curl does not like the ss cert. of uha.fr */
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
    // curl_setopt($handle, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; fr; rv:1.9.2.7) Gecko/20100701 Firefox/3.6.7 contact@iariss.fr");
    curl_setopt($handle, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.31 (KHTML, like Gecko) Chrome/26.0.1411.0 Safari/537.31");
    curl_setopt($handle, CURLOPT_HEADER, true);

    // Use a file to stock the cookies
    // You do NOT need cookies when you start a new authentification
    unlink($this->ade_server->getCookieFile());
    // Use a file to stock the cookies
    curl_setopt($handle, CURLOPT_COOKIEFILE, $this->ade_server->getCookieFile());
    curl_setopt($handle, CURLOPT_COOKIEJAR, $this->ade_server->getCookieFile());

    $this->logSection('auth', "get LT from cas.uha.fr");
    $login_page = curl_exec($handle);
    file_put_contents("/tmp/debug01.txt", $login_page);
    curl_close($handle); // cURL write cookies in the Cookies Jar file

    $this->logSection('auth', "Fichier des cookies :\n".file_get_contents($this->ade_server->getCookieFile()));

    /* Get url_post for login */
    $this->logSection('auth', 'get "url action="" parameter from cas.uha.fr ');
    $pattern = '@action="([^"]+)" method=@';
    if(preg_match($pattern, $login_page, $matches) == 0)
      throw new Exception("Pas de action='' dans la page cas.uha.fr");

    $url_post = $matches[1];    // or cas/login

    $this->logSection('auth', "url post parameter got : $url_post");


    /* Get lt for login */
    $this->logSection('auth', 'get "lt" parameter from cas.uha.fr ');
    $pattern = '@name="lt" value="([^"]+)" />@';
    if(preg_match($pattern, $login_page, $matches) == 0)
      throw new Exception("Pas de token 'lt' dans la page cas.uha.fr");

    $lt = $matches[1];

    $this->logSection('auth', "lt parameter got : $lt");

    /* Get CAS Cookie and link to emploidutemps.uha.fr */
    $data_string = base64_decode($this->ade_server->getLogin())."&lt=$lt&execution=e1s1&_eventId=submit&submit=Connexion";
    $handle = curl_init($url = "https://cas.uha.fr".$url_post); //."?service=http://www.emploisdutemps.uha.fr:80/ade/standard/gui/interface.jsp");
    curl_setopt($handle, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded')); 
    //curl_setopt($handle, CURLOPT_VERBOSE, 1);
    curl_setopt($handle, CURLOPT_POST, true);
    curl_setopt($handle, CURLOPT_POSTFIELDS, $data_string /* $data */);
    curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false); /* curl does not like the ss cert. of uha.fr */
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($handle, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; fr; rv:1.9.2.7) Gecko/20100701 Firefox/3.6.7 contact@iariss.fr");
    curl_setopt($handle, CURLOPT_REFERER, "https://cas.uha.fr/cas/login?");
    curl_setopt($handle, CURLOPT_HEADER, true);
    // Follow the redirect (patched on 2013-03-14)
    curl_setopt($handle, CURLOPT_FOLLOWLOCATION, true );

    // Use a file to stock the cookies
    // You DO need cookies when you start a new authentification
    curl_setopt($handle, CURLOPT_COOKIEJAR, $this->ade_server->getCookieFile());
    // Use a file to stock the cookies
    curl_setopt($handle, CURLOPT_COOKIEFILE, $this->ade_server->getCookieFile());

    $this->logSection('auth', "get link for emploisdutemps.uha.fr");
    $content = curl_exec($handle);
    $this->logSection('auth', "content : $content");
    file_put_contents("/tmp/debug02.txt", $content);

    curl_close($handle); // cURL write cookies in the Cookies Jar file

    $this->logSection('auth', "Fichier des cookies :\n".file_get_contents($this->ade_server->getCookieFile()));


  }

}
