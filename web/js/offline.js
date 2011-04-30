function loaded() {    
  updateOnlineStatus("load", false);
  document.body.addEventListener("offline", function () { updateOnlineStatus("offline", true) }, false);
  document.body.addEventListener("online", function () { updateOnlineStatus("online", true) }, false);
  
  var webappCache = window.applicationCache;
  webappCache.addEventListener("checking", function () { updateOnlineStatus("checking", true) }, false);
  webappCache.addEventListener("noupdate", function () { updateOnlineStatus("noupdate", true) }, false);
  webappCache.addEventListener("downloading", function () { updateOnlineStatus("downloading", true) }, false);
  webappCache.addEventListener("progress", function () { updateOnlineStatus("progress", true) }, false);
  webappCache.addEventListener("updateready", function () { updateOnlineStatus("updateready", true); webappCache.swapCache(); }, false);
  webappCache.addEventListener("cached", function () { updateOnlineStatus("cached", true) }, false);
  webappCache.addEventListener("obselete", function () { updateOnlineStatus("obselete", true) }, false);

}


function updateOnlineStatus(msg, allowUpdate) {
  var status = document.getElementById("status");
  var status2 = document.getElementById("status2");
  status.innerHTML = (navigator.onLine ? "[online]" : "[offline]");
  status2.innerHTML = msg;
}