function setCookie(cname, cvalue, exdays) {
  var d = new Date();
  d.setTime(d.getTime() + (exdays*24*60*60*1000));
  var expires = "expires="+ d.toUTCString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
  var name = cname + "=";
  var decodedCookie = document.cookie;
  var ca = decodedCookie.split(';');
  for(var i = 0; i <ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length);
    }
  }
  return "";
}

function loadPosts() {
  if (window.XMLHttpRequest) {
    xmlhttp = new XMLHttpRequest();
  }
  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("posts").innerHTML = this.responseText;
    }
  }
  xmlhttp.open("GET", "getposts.php", true);
  xmlhttp.send();
}

function loadSubjects() {
  if (window.XMLHttpRequest) {
    xmlhttp = new XMLHttpRequest();
  }
  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("subjects").innerHTML = this.responseText;
    }
  }
  xmlhttp.open("GET", "getsubjects.php", true);
  xmlhttp.send();
}

function logout() {
  setCookie('usrID', '', -1);
  document.getElementById("havingloggedin").style.display = "none";
  document.getElementById("login-form").style.display = "block";
}

function showBox() {
  document.getElementById("new-something-body").style.display = "block";
}

function closeBox() {
  document.getElementById("new-something-body").style.display = "none";
}
function printUsername() {
  if (window.XMLHttpRequest) {
    xmlhttp = new XMLHttpRequest();
  }
  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      for (var elm of document.getElementsByClassName("username")) {
        elm.innerHTML = this.responseText;
      }
    }
  }
  xmlhttp.open("GET", "/getusername.php", true);
  xmlhttp.send();
}
function userCondition() {
  if (getCookie("usrID") == "" || getCookie("usrID") == "wrong") {
    document.getElementById('havingloggedin').style.display = "none";
    document.getElementById("login-form").style.display = "block";
  } else {
    printUsername();
    document.getElementById("login-form").style.display = "none";
    document.getElementById('havingloggedin').style.display = "block";
  }
  if (getCookie('usrID') == "wrong") {
    document.getElementById("wrong-password").style.display = "block";
    setCookie('usrID', '', -1);
  } else {
    document.getElementById("wrong-password").style.display = "none";
  }
}
var t = false;
document.onclick = function(e) {
  if (!t && (e.target.id == 'username')) {
    document.getElementById("dropdown").style.display = "block";
    t = true;
  } else if ($(e.target).closest("#dropdown").length > 0) {
    // do nothing
  } else if (t) {
    document.getElementById("dropdown").style.display = "none";
    t = false;
  }
}
