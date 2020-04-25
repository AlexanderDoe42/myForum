<?php
  include 'includes/autoloader.inc.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Forum</title>
<link rel="icon" href="icons/favicon.ico">
<link rel="stylesheet" type="text/css" href="font/flaticon.css">
<link rel="stylesheet" type="text/css" href="style/main.css">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

</head>
<body>
<?php
  if (!isset($_COOKIE['usrID'])) {
    echo "<p style='font-size:10em;'>ERROR 401</p>";
    exit;
  }
  $myForumDB = new MyDB();
?>
<div class="main-container">
  <div id="forumhead"></div>
  <div id="user-info"></div>
  <div class="columns">
    <div class="columns1">
      <div class="profile-picture">
        <img src="images/id25.JPG">
      </div>
    </div>
    <div class="columns2">
      <div class="username"></div>
      <div>
        registration date: <?php $myForumDB->getRegistrationDate() ?>
      </div>
      <div>
        my subjects: <?php $myForumDB->getNumberOfSubjects() ?>
      </div>
      <div>
        my posts: <?php $myForumDB->getNumberOfPosts() ?>
      </div>
    </div>
  </div>
</div>

<script>
  $("#forumhead").load("html/forumhead.html");
  $("#user-info").load("html/user-info.html", function(responseTxt, statusTxt, xhr){
    if(statusTxt == "success") {
      userCondition();
    }
  });
</script>
<script src="js/main.js"></script>

</body>
</html>
