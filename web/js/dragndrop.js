
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

  bin.className = '';

  
  var y = yum.cloneNode(true);
  y.appendChild(el.cloneNode(true));
  binList.appendChild(y);



  return false;
});