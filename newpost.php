<!DOCTYPE html>
<html>
<head>
</head>
<body>

<?php
$msg = $_POST['message'];
$usrID = $_COOKIE['usrID'];
$subjectID = $_GET['id'];
$msg = str_replace("'", "\'", $msg);
try {
  $conn = new PDO("mysql:host=localhost;dbname=myForum", "alex", "svetly");
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "INSERT INTO Posts (AuthorID, PostContent, SubjectID)
          VALUES ('$usrID', '$msg', '$subjectID')";
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  $sql = "UPDATE Users
          SET NumberOfPosts = NumberOfPosts + 1
          WHERE UserID = '$usrID'";
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  $sql = "UPDATE Subjects
          SET NumberOfPosts = NumberOfPosts + 1
          WHERE SubjectID = '$subjectID'";
  $stmt = $conn->prepare($sql);
  $stmt->execute();
}
catch(PDOException $e) {
  error_log($e->getMessage(), 0);
}
$conn = null;
?>
<script>
  var curURL = window.location.href;
  var pos = curURL.indexOf("?id=");
  var subjectID = curURL.slice(pos);
  document.location = "subject.php" + subjectID;
</script>

</body>
</html>
