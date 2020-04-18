<!DOCTYPE html>
<html>
<head>
</head>
<body>

<?php
if (!isset($_COOKIE['usrID']) || empty($_POST['title']) || empty($_POST['content'])) {
  echo "<p style='font-size:10em;'>ERROR 401</p>";
  exit;
}
$title = test_input($_POST['title']);
$content = test_input($_POST['content']);
$usrID = $_COOKIE['usrID'];

try {
  $dsn = "mysql:host=localhost;dbname=myForum;charset=utf8";
  $conn = new PDO($dsn, "alex", "svetly");
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "INSERT INTO Subjects (AuthorID, Created, Title, Content)
          VALUES (:usrID, NOW(), :title, :content)";
  $stmt = $conn->prepare($sql);
  $stmt->bindValue("usrID", $usrID, PDO::PARAM_STR);
  $stmt->bindValue("title", $title, PDO::PARAM_STR);
  $stmt->bindValue("content", $content, PDO::PARAM_STR);
  $stmt->execute();
  $sql = "UPDATE Users
          SET NumberOfSubjects = NumberOfSubjects + 1
          WHERE UserID = " . $conn->quote($usrID);
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
<script> document.location = 'index.html'; </script>

</body>
</html>
