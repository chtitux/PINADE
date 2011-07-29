<?php
/**
 * edt tools.
 *
 * @package    tools
 * @subpackage AdeBrowser
 * @author     T. Helleboid <t.helleboid@iariss.fr>, M.Muré <m.mure@iariss.fr>
 */

class AdeBrowser
{
  protected
    $ade_cookie,
    $cas_cookie,
    $ade_ticket,
    $curl_handle,
    $content;

  public function getUrl($url, $post_fields = null)
  {
    // If an sfAdeException is
    try {
      return $this->ProcessUrl($url, $post_fields);
    }
    catch(sfAdeException $e)
    {
      sfContext::getInstance()->getLogger()->info('Mauvais cookie ("'.$e->getMessage().'"). Tentative de renouvellement');
      $this->getAuthentication();

      return $this->ProcessUrl($url, $post_fields);

    }
  }

  protected function ProcessUrl($url, $post_fields)
  {
    $log_message = time().' Get link : "'.$url.'"';
    if(strlen($post_fields))
      $log_message .= " POST : \"$post_fields\"";

    sfContext::getInstance()->getLogger()->info($log_message);
    $handle = curl_init($url);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false); 
    curl_setopt($handle, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; fr; rv:1.9.2.7) Gecko/20100701 Firefox/3.6.7 contact@iariss.fr");
    curl_setopt($handle, CURLOPT_FOLLOWLOCATION, true);

    // Use a file to stock the cookies
    // Do not allow to modify cookies here, because it does not work if you do
    curl_setopt($handle, CURLOPT_COOKIEFILE, sfConfig::get('app_ade_cookiefile'));


    if(strlen($post_fields))
    {
      curl_setopt($handle, CURLOPT_POST, true);
      curl_setopt($handle, CURLOPT_POSTFIELDS, $post_fields);
    }
    $this->content = curl_exec($handle);
    $this->curl_handle = $handle;

    if(strpos(curl_getinfo($handle, CURLINFO_EFFECTIVE_URL), "http://adeweb.univ-lyon1.fr/ade/standard/redirectIndex.jsp") !== false)
    {
      // Le cookie n'est pas trouvé
      throw new sfAdeException("Problème d'authentification ADE (redirection)");
    }

    if(strpos($this->content, "Deconnected") !== false)
    {
      // Le cookie n'est pas trouvé
      throw new sfAdeException("Problème d'authentification CAS/ADE (Deconnected)");
    }

    return $this->content;
  }

  protected function getAuthentication()
  {
    sfContext::getInstance()->getLogger()->info('get ADE Cookie');

    /* Get CAS Cookie and link to adeweb.univ-lyon1.fr */
    $data_string = base64_decode(sfConfig::get('app_cas_login'))."&x=33&y=10";
    $handle = curl_init("http://adeweb.univ-lyon1.fr/ade/standard/gui/interface.jsp?top=top");
    curl_setopt($handle, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded')); 
    curl_setopt($handle, CURLOPT_POST, true);
    curl_setopt($handle, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false); /* curl does not like the ss cert. of uha.fr */
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($handle, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; fr; rv:1.9.2.7) Gecko/20100701 Firefox/3.6.7 chtitux@chtitux.org");
    curl_setopt($handle, CURLOPT_REFERER, "http://adeweb.univ-lyon1.fr/ade/standard/index.jsp");
    curl_setopt($handle, CURLOPT_HEADER, true);
    curl_setopt($handle, CURLOPT_FOLLOWLOCATION, true);

    // Use a file to stock the cookies
    // You do not need old cookies when you start a new authentification
    curl_setopt($handle, CURLOPT_COOKIEJAR, sfConfig::get('app_ade_cookiefile'));

    $content = curl_exec($handle);
    curl_close($handle); // cURL write cookies in the Cookies Jar file

    sfContext::getInstance()->getLogger()->info(file_get_contents(sfConfig::get('app_ade_cookiefile')));
  }
  

}