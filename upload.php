<?php
  include 'includes/autoloader.inc.php';
?>
<?php

$userID = Dbh::test_input($_COOKIE['usrID']);
$target_dir = "images/";
$target_file = $target_dir . "id" . $userID . ".JPG";
$uploadOK = 1;
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
if (isset($_POST["submit"])) {
  $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
  if ($check !== false) {
    $uploadOK = 1;
  } else {
    $uploadOK = 0;
  }
}
if ($_FILES["fileToUpload"]["size"] > 500000) {
  $uploadOK = 0;
}
if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
  $uploadOK = 0;
}
if ($uploadOK == 0) {
  $result = "failure";
} elseif (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
  $myForumDB = new MyDB();
  $myForumDB->userProvidedAPicture($userID);
  $result = "success";
} else {
  $result = "failure";
}
header("Location: profile.php?q=" . $result, TRUE, 301);

?>
