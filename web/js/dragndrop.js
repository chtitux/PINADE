
var yum = document.createElement('li');
var msie = /*@cc_on!@*/0;
yum.style.opacity = 1;

var links = document.querySelectorAll('li.liste-index-li > a'), el = null;
for (var i = 0; i < links.length; i++) {
  el = links[i];

  el.setAttribute('draggable', 'true');

  addEvent(el, 'dragstart', function (e) {
    e.dataTransfer.effectAllowed = 'copy'; // only dropEffect='copy' will be dropable
    e.dataTransfer.setData('Text', this.id); // required otherwise doesn't work
  });
}

var myedt = document.querySelector('#myedt');
var myedtList = document.querySelector('#myedt-liste');
var myedtBin = document.querySelector('#myedt-bin');

addEvent(myedtList, 'dragover', function (e) {
  if (e.preventDefault) e.preventDefault(); // allows us to drop
  this.className = 'over';
  e.dataTransfer.dropEffect = 'copy';
  return false;
});

// to get IE to work
addEvent(myedtList, 'dragenter', function (e) {
  this.className = 'over';
  return false;
});

addEvent(myedtList, 'dragleave', function () {
  this.className = '';
});

addEvent(myedtList, 'drop', function (e) {
  if (e.stopPropagation) e.stopPropagation(); // stops the browser from redirecting...why???
  if (e.preventDefault) e.preventDefault(); // allows us to drop

  var el = document.getElementById(e.dataTransfer.getData('Text'));
  
  //el.parentNode.removeChild(el);
  // Remove "hover" effect
  myedtList.className = '';

  // append to the <ul> list in the menu
  appendToMyedt(el);

  // save into the local storage
  addToStorage(el);

  return false;
});

function addToStorage(element)
{
  var edts = [];
  
  if(localStorage.getItem('myedt'))
    try
    {
      edts = JSON.parse(localStorage.getItem('myedt'));
    } catch(e) {}
  
  
  edts.push({'name':element.innerHTML, 'href':element.href});
  
  localStorage.setItem('myedt', JSON.stringify(edts));
}



function appendToMyedt(element)
{ 
  var y = yum.cloneNode(true);
  var a = element.cloneNode(true);
  a.id = "id-"+element.innerHTML;
  y.appendChild();
  myedtList.appendChild(y);
}

/* Manage bin */

addEvent(myedtBin, 'dragover', function (e) {
  if (e.preventDefault) e.preventDefault(); // allows us to drop
  this.className = 'over';
  e.dataTransfer.dropEffect = 'copy';
  return false;
});

// to get IE to work
addEvent(myedtBin, 'dragenter', function (e) {
  this.className = 'over';
  return false;
});

addEvent(myedtBin, 'dragleave', function () {
  this.className = '';
});

addEvent(myedtBin, 'drop', function (e) {
  if (e.stopPropagation) e.stopPropagation(); // stops the browser from redirecting...why???
  if (e.preventDefault) e.preventDefault(); // allows us to drop

  var el = document.getElementById(e.dataTransfer.getData('Text'));
  
  //el.parentNode.removeChild(el);
  // Remove "hover" effect
  myedtBin.className = '';

  el.parentNode.deleteNode();

  return false;
});