<!DOCTYPE html>
<html>
<head>

</head>
<body>

<?php
$usrID = $_COOKIE['usrID'];
try {
  $conn = new PDO("mysql:host=localhost;dbname=myForum", "alex", "svetly");
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT Username FROM Users WHERE UserID = '$usrID'";
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  $result = $stmt->fetch();
  if ($result) {
    echo $result[0];
  }
}
catch(PDOException $e) {
  error_log($e->getMessage(), 0);
}
$conn = null;
?>

</body>
</html>
