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
        registration date:
        <?php
        $usrID = $_COOKIE['usrID'];
        try {
          $conn = new PDO("mysql:host=localhost;dbname=myForum", "alex", "svetly");
          $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          $sql = "SELECT RegistrationDate FROM Users WHERE UserID = '$usrID'";
          $stmt = $conn->prepare($sql);
          $stmt->execute();
          $result = $stmt->fetch();
          echo $result['RegistrationDate'];
        }
        catch(PDOException $e) {
          error_log($e->getMessage(), 0);
        }
        $conn = null;
        ?>
      </div>
      <div>
        my subjects:
        <?php
        $usrID = $_COOKIE['usrID'];
        try {
          $conn = new PDO("mysql:host=localhost;dbname=myForum", "alex", "svetly");
          $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          $sql = "SELECT NumberOfSubjects FROM Users WHERE UserID = '$usrID'";
          $stmt = $conn->prepare($sql);
          $stmt->execute();
          $result = $stmt->fetch();
          echo $result['NumberOfSubjects'];
        }
        catch(PDOException $e) {
          error_log($e->getMessage(), 0);
        }
        $conn = null;
        ?>
      </div>
      <div>
        my posts:
        <?php
        $usrID = $_COOKIE['usrID'];
        try {
          $conn = new PDO("mysql:host=localhost;dbname=myForum", "alex", "svetly");
          $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          $sql = "SELECT NumberOfPosts FROM Users WHERE UserID = '$usrID'";
          $stmt = $conn->prepare($sql);
          $stmt->execute();
          $result = $stmt->fetch();
          echo $result['NumberOfPosts'];
        }
        catch(PDOException $e) {
          error_log($e->getMessage(), 0);
        }
        $conn = null;
        ?>
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
