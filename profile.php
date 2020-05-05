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
<link rel="stylesheet" type="text/css" href="style/fileupload.css">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

</head>
<body>
<?php
  if (!isset($_COOKIE['usrID'])) {
    echo "<p style='font-size:10em;'>ERROR 401</p>";
    exit;
  }
  $userID = $_COOKIE['usrID'];
  $myForumDB = new MyDB();
  $user = $myForumDB->getUser($userID);
  if ($user['HasAProfilePicture']) {
    $pictureID = $userID;
  } else {
    $pictureID = 0;
  }
  $profilePicture = '<img class="profilepicture" src="images/id' . $pictureID . '.JPG">';
?>
<div class="main-container">
  <div id="forumhead"></div>
  <div id="user-info"></div>
  <div class="columns profile">
    <div class="profilecolumn1">
      <div onmouseover="showUploadButton()" onmouseout="hideUploadButton()" class="qube">
        <button onclick="showBox()" id="uploadbutton">Upload a new photo</button>
      </div>
      <?php echo $profilePicture ?>
    </div>
    <div class="profilecolumn2">
      <div class="username"></div>
      <div>
        registration date: <?php echo $user['RegistrationDate'] ?>
      </div>
      <div>
        my subjects: <?php echo $user['NumberOfSubjects'] ?>
      </div>
      <div>
        my posts: <?php echo $user['NumberOfPosts'] ?>
      </div>
    </div>
  </div>
</div>
<div id="new-something-body">
  <div id="new-something-box">
    <div id="close-button" class="cursor-pointer" onclick="closeBox()">
      <div></div>
    </div>
    <form id="newsomething-form" name="newpicture" action="upload.php" method="post" enctype="multipart/form-data">
      <div>
        <label for="fileToUpload">Choose image to upload (PNG, JPG, GIF)</label>
        <input type="file" name="fileToUpload" id="fileToUpload" accept="image/jpeg, image/gif, image/x-png">
      </div>
      <div class="preview">
        <p>No file is currently selected for upload</p>
      </div>
      <div>
        <button id="submit" disabled>Upload</button>
      </div>
    </form>
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
<script src="js/fileupload.js"></script>

</body>
</html>
