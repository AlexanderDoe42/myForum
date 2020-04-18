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

function newSubjectButtonClickEvent() {
  if (getCookie("usrID") == "" || getCookie("usrID") == "wrong") {
    alert("Sign in or sign up to create a new subject");
  } else {
    showBox("subject");
  }
}
function replyButtonClickEvent(postID) {
  if (getCookie("usrID") == "" || getCookie("usrID") == "wrong") {
    alert("Sign in or sign up to reply");
  } else {
    showBox(postID);
  }
}

function showBox(postID) {
  document.getElementById("new-something-body").style.display = "block";
  if (postID == "post") {
    document.newpost.message.value = "";
  } else if (postID == "terms" || postID == "subject") {
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
    document.newpost.message.value = quote;
  }
}

function closeBox(arg) {
  if (arg == "post" &&
      document.newpost.message.value != 0 &&
      !confirm("Discard message?"))
  {
    return;
  }
  document.getElementById("new-something-body").style.display = "none";
}
function printUsername() {
  if (window.XMLHttpRequest) {
    xmlhttp = new XMLHttpRequest();
  }
  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      if (getCookie("usrID") == "") {
        document.location = "index.html";
      } else {
        for (var elm of document.getElementsByClassName("username")) {
          elm.innerHTML = this.responseText;
        }
      }
    }
  }
  xmlhttp.open("GET", "/getusername.php", true);
  xmlhttp.send();
}
function regSuccess() {
  document.register.style.display = "none";
  document.getElementById("reg-success").style.display = "block";
}
function regFailure() {
  document.getElementById("reg-fault").style.display = "block";
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
function checkRegForm() {
  var tmp = true;
  if (document.register.username.value.length < 3) {
    document.getElementById("error-username").innerHTML = "must contain at least 3 characters";
    tmp = false;
  }
  if (document.getElementById("error-username").innerHTML.length > 0) {
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
var colorSwitch = true;
function blinking(obj) {
  var color = (colorSwitch) ? "#ffadad" : "#fff";
  colorSwitch = (colorSwitch) ? false : true;
  obj.style.background = color;
}
var colorSwitch2 = true;
function blinking2(obj) {
  var color = (colorSwitch2) ? "#ffadad" : "#fff";
  colorSwitch2 = (colorSwitch2) ? false : true;
  obj.style.background = color;
}
function checkLoginForm() {
  var tmp = true;
  if (document.login.username.value.length == 0) {
    var myInterval = setInterval(function(){blinking(document.login.username)}, 70);
    setTimeout(
      function() {
        clearInterval(myInterval);
        document.login.username.style.background = "#fff";
      },
      2000
    );
    tmp = false;
  }
  if (document.login.password.value.length == 0) {
    var myInterval2 = setInterval(function(){blinking2(document.login.password)}, 70);
    setTimeout(
      function() {
        clearInterval(myInterval2);
        document.login.password.style.background = "#fff";
      },
      2000
    );
    tmp = false;
  }
  return tmp;
}
function checkNewSubjectForm() {
  var tmp = true;
  if (document.newsubject.title.value.length == 0) {
    var myInterval = setInterval(function(){blinking(document.newsubject.title)}, 200);
    setTimeout(
      function() {
        clearInterval(myInterval);
        document.newsubject.title.style.background = "#fff";
      },
      2000
    );
    tmp = false;
  }
  if (document.newsubject.content.value.length == 0) {
    var myInterval2 = setInterval(function(){blinking2(document.newsubject.content)}, 200);
    setTimeout(
      function() {
        clearInterval(myInterval2);
        document.newsubject.content.style.background = "#fff";
      },
      2000
    );
    tmp = false;
  }
  return tmp;
}
function checkNewPostForm() {
  var tmp = true;
  if (document.newpost.message.value.length == 0) {
    var myInterval = setInterval(function(){blinking(document.newpost.message)}, 200);
    setTimeout(
      function() {
        clearInterval(myInterval);
        document.newpost.message.style.background = "#fff";
      },
      2000
    );
    tmp = false;
  }
  return tmp;
}
function enableSubmit() {
  if ((document.register.username.value.length == 0) ||
      (document.register.password.value.length == 0) ||
      (document.register.passwordRepeat.value.length == 0))
  {
    document.register.submit.setAttribute("disabled", "");
  } else {
    document.register.submit.removeAttribute("disabled");
  }
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
