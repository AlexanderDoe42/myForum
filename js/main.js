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
  var htmlForQuoteAuthor = '<div class="quote_author"><img src="/icons/double_quotation_mark.png">';
  var len = document.getElementsByClassName("post_content").length;
  for (i = 0; i < len; i++) {
    var bg_color = "bg4";
    while (document.getElementsByClassName("post_content")[i].innerHTML.indexOf('[quote="') != -1) {
      bg_color = (bg_color == "bg4") ? "bg3" : "bg4";
      document.getElementsByClassName("post_content")[i].innerHTML =
      document.getElementsByClassName("post_content")[i].innerHTML.replace('[quote="', '<div class="quote ' + bg_color + '">' + htmlForQuoteAuthor);
    }
    document.getElementsByClassName("post_content")[i].innerHTML =
    document.getElementsByClassName("post_content")[i].innerHTML.replace(/"\]/g, '</div> <!-- quote_author -->');
    document.getElementsByClassName("post_content")[i].innerHTML =
    document.getElementsByClassName("post_content")[i].innerHTML.replace(/\[\/quote\]/g, '</div>');
  }
  document.getElementById("posts").style.display = "block";
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

function showBox(postID) {
  document.getElementById("new-something-body").style.display = "block";
  if (postID == undefined) {
    document.getElementById("message").innerHTML = "";
  } else if (postID == "terms") {
    //do nothing
  } else {
    var msg = document.getElementById("post_content" + postID).innerHTML;
    msg = msg.replace(/<div class="quote bg3">/g, '[quote="');
    msg = msg.replace(/<div class="quote bg4">/g, '[quote="');
    msg = msg.replace(/<div class="quote_author"><img src="\/icons\/double_quotation_mark.png">/g, '');
    msg = msg.replace(/<\/div> <!-- quote_author -->/g, '"]');
    msg = msg.replace(/<\/div>/g, '[/quote]');
    var quote = '[quote="' +
                document.getElementById("author_post" + postID).innerHTML +
                '"]' +
                msg +
                "[/quote]";
    document.getElementById("message").innerHTML = quote;
  }
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
function showHint(str) {
  if (str.length == 0) {
    return;
  }
  if (window.XMLHttpRequest) {
    xmlhttp = new XMLHttpRequest();
  }
  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      if (this.responseText.indexOf("taken") != -1) {
        document.getElementById("error-username").innerHTML = "this name is already taken";
      } else {
        document.getElementById("error-username").innerHTML = "";
      }
    }
  }
  xmlhttp.open("GET", "/searchforuser.php?q=" + str, true);
  xmlhttp.send();
}
function checkForm() {
  var tmp = true;
  if (document.register.username.value.length < 3) {
    document.getElementById("error-username").innerHTML = "must contain at least 3 characters";
    tmp = false;
  }
  if (document.register.password.value.length < 3) {
    document.getElementById("error-password").innerHTML = "must contain at least 3 characters";
    tmp = false;
  }
  if (document.register.password.value != document.register.passwordRepeat.value) {
    document.getElementById("error-passwordRepeat").innerHTML = "the passwords do not match";
    tmp = false;
  }
  if (!document.register.terms.checked) {
    document.getElementById("error-terms").innerHTML = "must be accepted";
    tmp = false;
  }
  return tmp;
}
function cleanError(str) {
  document.getElementById("error-" + str).innerHTML = "";
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
