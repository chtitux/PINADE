function get_cookie ( cookie_name )
{
    // http://www.thesitewizard.com/javascripts/cookies.shtml
    var cookie_string = document.cookie ;
    if (cookie_string.length != 0) {
        var cookie_value = cookie_string.match (
                        cookie_name +
                        '=([^;]*)' );
        if(cookie_value == null)
          return '';
        else
          return decodeURIComponent ( cookie_value[1] ) ;
    }
    return '' ;
}

function set_style() {
  var style = get_cookie('css');
  if(style.length == 0)
    return;
  var headID = document.getElementsByTagName("head")[0];         
  var cssNode = document.createElement('link');
  cssNode.type = 'text/css';
  cssNode.rel = 'stylesheet';
  cssNode.href = '/css/' + style+ '.css';
  cssNode.media = 'screen';
  headID.appendChild(cssNode);


}
set_style();