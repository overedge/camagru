(function() {

  var streaming = false,
      video        = document.querySelector('#video'),
      canvas       = document.querySelector('#canvas'),
      photo        = document.querySelector('#photo'),
      startbutton  = document.querySelector('#startbutton'),
      width = 320,
      height = 320;

  navigator.getMedia = ( navigator.getUserMedia ||
                         navigator.webkitGetUserMedia ||
                         navigator.mozGetUserMedia ||
                         navigator.msGetUserMedia);

  navigator.getMedia(
    {
      video: true,
      audio: false
    },
    function(stream) {
      if (navigator.mozGetUserMedia) {
        video.mozSrcObject = stream;
      } else {
        var vendorURL = window.URL || window.webkitURL;
        video.src = vendorURL.createObjectURL(stream);
      }
      video.play();
    },
    function(err) {
      console.log("An error occured! " + err);
    }
  );

  video.addEventListener('canplay', function(ev){
    if (!streaming) {
      height = 320;
      video.setAttribute('width', width);
      video.setAttribute('height', height);
      canvas.setAttribute('width', width);
      canvas.setAttribute('height', height);
      streaming = true;
    }
  }, false);

  function takepicture(chiffre) {
    canvas.width = width;
    canvas.height = height;
    if (chiffre == 0){
      canvas.getContext('2d').drawImage(video, 0, 0, width, height);
      var data = canvas.toDataURL('image/png');
      photo.setAttribute('src', data);
      photo.style.display = "inline";
    }
    else
    {
      var img = new Image;
      img.src = URL.createObjectURL(document.getElementById('upload').files[0]);
      img.onload = function() {
      canvas.getContext('2d').drawImage(img, 0, 0, width, height);
      var data = canvas.toDataURL('image/png');
      photo.setAttribute('src', data);
      photo.style.display = "none";
      sendpict();
        var m = document.getElementById('upload');
        var k = document.getElementById('uploadsend');
          k.disabled = true;
          k.style.background = "red";
          k.innerHTML = "Selectioner un fichier";
          m.value = "";
      }
    }
  }



  startbutton.addEventListener('click', function(ev){
    takepicture(0);
    sendpict();
    ev.preventDefault();
  }, false);

  document.getElementById('uploadsend').addEventListener('click', function() {
    takepicture(1);
  });

})();



var getHttpRequest = function () {
  var httpRequest = false;

  if (window.XMLHttpRequest) { // Mozilla, Safari,...
    httpRequest = new XMLHttpRequest();
    if (httpRequest.overrideMimeType) {
      httpRequest.overrideMimeType('text/xml');
    }
  }
  else if (window.ActiveXObject) { // IE
    try {
      httpRequest = new ActiveXObject("Msxml2.XMLHTTP");
    }
    catch (e) {
      try {
        httpRequest = new ActiveXObject("Microsoft.XMLHTTP");
      }
      catch (e) {}
    }
  }

  if (!httpRequest) {
    alert('Abandon :( Impossible de créer une instance XMLHTTP');
    return false;
  }

  return httpRequest
}

sendpict = function() {

  var x = document.querySelector('#photo').getAttribute("src");
  filter = document.getElementsByName("filter");
  var n = 0;
  for (var i = 0; i < filter.length; i++)
  {
    if (filter[i].checked == true) {
      n = i;
      break;
    }
  }
  if (x == null){
    alert('Aucune photo');
    return ;
  }
  var xhr = getHttpRequest()
  xhr.open('POST', 'ajax.php?id=' + n, true);
  xhr.setRequestHeader("Content-Type", "application/upload");
  xhr.send("id=" + n + '& '+ x);
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        photo   = document.querySelector('#photo');
        photo.setAttribute('src', "pictures/" + xhr.responseText);
        appendHistory();
      } 
      else {
          alert('Probleme de conenction ajax');
      }
    }
  }
}

var selectedFilter = 0;

activeBTN = function() {
  selectedFilter = 1;
  var l = document.getElementById('startbutton');
  l.disabled = false;
  l.style.background = "green";
  l.innerHTML = "Shoot !"; 

  var m = document.getElementById('upload');
  var k = document.getElementById('uploadsend');
  if (m.files.length == 0) {
      k.disabled = true;
      k.style.background = "red";
      k.innerHTML = "Selectioner un fichier";
  } else {
      k.disabled = false;
      k.style.background = "green";
      k.innerHTML = "Shoot !"
  }
}

radioclick = function(img) {
  for (var i = img.target.tab.length - 1; i >= 0; i--) {
    img.target.tab[i].style.border = "none";
  }
  img.target.selected.style.border = "6px solid green";
  var x = document.getElementsByName('filter');
  for (var i = x.length - 1; i >= 0; i--) {
    if (x[i].value == img.target.index) {
      x[i].checked = true;
      activeBTN();
    }
  }
}

var l = document.getElementById('startbutton');
l.disabled = true;
l.style.background = "red";
l.innerHTML = "Selectioner un filtre"; 
var k = document.getElementById('uploadsend');
k.disabled = true;
k.style.background = "red";
k.innerHTML = "Selectioner un filtre"; 

var filters = document.getElementsByName("filter");
for (var i = 0; i < filters.length; i++) {
    filters[i].addEventListener("click", activeBTN);
}

var imgs = document.getElementsByName("imgs");
for (var i = imgs.length - 1; i >= 0; i--) {
  imgs[i].addEventListener("click", radioclick);
  imgs[i].selected = imgs[i];
  imgs[i].tab = imgs;
  imgs[i].index = i;
}


  appendHistory = function() {
    var photo = document.createElement("IMG");
    var link = document.createElement("a");
    var history = document.getElementById("history");
    var data = document.querySelector("#photo").getAttribute("src");
    document.querySelector("#photo").setAttribute("style", "display: none;");
    photo.setAttribute("src", data);
    photo.setAttribute("style", "width: 120px; height: 120px;");
    link.setAttribute("href", "delete.php?id=" + data.substr(9));
    link.setAttribute("title", "suprimer la photo");
    history.insertBefore(link, history.childNodes[0]);
    link.appendChild(photo);
}

document.getElementById("upload").addEventListener('change', function() { 
  if (selectedFilter == 1) {
      k.disabled = false;
      k.style.background = "green";
      k.innerHTML = "Shoot !"
  }
})