<!DOCTYPE html>
<html>
<head>
</head>
<body>

<?php
if (!isset($_COOKIE['usrID']) || empty($_POST['message']) || !isset($_GET['id'])) {
  echo "<p style='font-size:10em;'>ERROR 401</p>";
  exit;
}
$msg = test_input($_POST['message']);
$usrID = $_COOKIE['usrID'];
$subjectID = $_GET['id'];
try {
  $dsn = "mysql:host=localhost;dbname=myForum;charset=utf8";
  $conn = new PDO($dsn, "alex", "svetly");
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "INSERT INTO Posts (AuthorID, PostContent, SubjectID)
          VALUES (:usrID, :msg, :subjectID)";
  $stmt = $conn->prepare($sql);
  $stmt->bindValue(":usrID", $usrID, PDO::PARAM_STR);
  $stmt->bindValue(":msg", $msg, PDO::PARAM_STR);
  $stmt->bindValue(":subjectID", $subjectID, PDO::PARAM_STR);
  $stmt->execute();
  $sql = "UPDATE Users
          SET NumberOfPosts = NumberOfPosts + 1
          WHERE UserID = " . $conn->quote($usrID);
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  $sql = "UPDATE Subjects
          SET NumberOfPosts = NumberOfPosts + 1
          WHERE SubjectID = " . $conn->quote($subjectID);
  $stmt = $conn->prepare($sql);
  $stmt->execute();
}
catch(PDOException $e) {
  error_log($e->getMessage(), 0);
}
$conn = null;
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>
<script>
  var curURL = window.location.href;
  var pos = curURL.indexOf("?id=");
  var subjectID = curURL.slice(pos);
  document.location = "subject.php" + subjectID;
</script>

</body>
</html>
