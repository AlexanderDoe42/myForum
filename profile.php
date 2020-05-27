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
<link rel="stylesheet" type="text/css" href="style/profile.css">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

</head>
<body>
<?php
  $status = "";
  $whose = "my ";
  if (isset($_GET['id'])) {
    $userID = Dbh::test_input($_GET['id']);
    if (isset($_COOKIE['usrID'])) {
      $myID = Dbh::test_input($_COOKIE['usrID']);
      if ($myID == $userID) {
        header("Location: profile.php", TRUE, 307);
        exit;
      }
    }
    $status = "true";
  } elseif (isset($_COOKIE['usrID'])) {
    $userID = Dbh::test_input($_COOKIE['usrID']);
  } else {
    echo "<p style='font-size:10em;'>ERROR 401</p>";
    exit;
  }
  $myForumDB = new MyDB();
  $user = $myForumDB->getUser($userID);
  $registered = new DateTime();
  $registered->setTimestamp($user['RegistrationDateTimestamp'] - $myForumDB->tzOffset);
  if ($status == "true") {
    $lastSeen = new DateTime();
    $lastSeen->setTimestamp($user['LastSeenTimestamp'] - $myForumDB->tzOffset);
    if (time() - $user['LastSeenTimestamp'] < 240) {
      $status = '<div class="profile__column2__last-seen">
                   <div class="online">Online</div>
                 </div>';
    } else {
      $status = '<div class="profile__column2__last-seen">
                   last seen <div class="datetime">' . $lastSeen->format('F d, Y H:i') . '</div>
                 </div>';
    }
    $whose = "";
  }
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
    <div class="profile__column1">
      <div class="profile__column1__button">
        <button onclick="showBox()" id="uploadbutton">Upload a new photo</button>
      </div>
      <?php echo $profilePicture ?>
    </div>
    <div class="profile__column2">
      <h3><?php echo $user['Username'] ?></h3>
      <?php echo $status ?>
      <table>
        <tr>
          <td>registered</td>
          <td><?php echo $registered->format('F d, Y') ?></td>
        </tr>
        <tr class="trlink" id="mySubjects">
          <td><?php echo $whose ?>subjects</td>
          <td><?php echo $user['NumberOfSubjects'] ?></td>
        </tr>
        <tr class="trlink" id="myPosts">
          <td><?php echo $whose ?>posts</td>
          <td><?php echo $user['NumberOfPosts'] ?></td>
        </tr>
      </table>
    </div>
  </div>
  <div id="users-posts"></div>
</div>
<footer></footer>

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

<script src="js/main.js"></script>
<script src="js/profile.js"></script>

</body>
</html>
