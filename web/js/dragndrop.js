
var eat = ['yum!', 'gulp', 'burp!', 'nom'];
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

var bin = document.querySelector('#bin');
var binList = document.querySelector('#bin > ul');

addEvent(bin, 'dragover', function (e) {
  if (e.preventDefault) e.preventDefault(); // allows us to drop
  this.className = 'over';
  e.dataTransfer.dropEffect = 'copy';
  return false;
});

// to get IE to work
addEvent(bin, 'dragenter', function (e) {
  this.className = 'over';
  return false;
});

addEvent(bin, 'dragleave', function () {
  this.className = '';
});

addEvent(bin, 'drop', function (e) {
  if (e.stopPropagation) e.stopPropagation(); // stops the browser from redirecting...why???
  if (e.preventDefault) e.preventDefault(); // allows us to drop

  var el = document.getElementById(e.dataTransfer.getData('Text'));
  
  //el.parentNode.removeChild(el);
  // Remove "hover" effect
  bin.className = '';

  // append to the <ul> list in the menu
  appendToBin(el);

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

function restoreStorage()
{
  var edts = [];
  
  if(localStorage.getItem('myedt'))
    try
    {
      edts = JSON.parse(localStorage.getItem('myedt'));
    } catch(e) {}
  
  for(var i = 0; i < edts.length; i++)
  {
    var item = document.createElement('a');
    item.href = edts[i].href;
    item.innerHTML = edts[i].name;
    appendToBin(item);
  }
  return true;
}

function appendToBin(element)
{ 
  var y = yum.cloneNode(true);
  y.appendChild(element.cloneNode(true));
  binList.appendChild(y);
}
  