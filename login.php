<!DOCTYPE html>
<html>
<head>
</head>
<body>

<?php
$username = test_input($_POST['username']);
$password = test_input($_POST['password']);

try {
  $dsn = "mysql:host=localhost;dbname=myForum;charset=utf8";
  $conn = new PDO($dsn, "alex", "svetly");
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT UserID, Username, Password
          FROM Users
          WHERE Username = :username AND
                Password = :password";
  $stmt = $conn->prepare($sql);
  $stmt->bindValue(":username", $username, PDO::PARAM_STR);
  $stmt->bindValue(":password", $password, PDO::PARAM_STR);
  $stmt->execute();
  $result = $stmt->fetch();
  if (!$result) {
    setcookie("usrID", "wrong", time() + (86400 * 365), "/");
  } else {
    setcookie("usrID", $result['UserID'], time() + (86400 * 365), "/");
  }
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
  if (pos == -1) {
    document.location = "index.html";
  } else {
    var subjectID = curURL.slice(pos);
    document.location = "subject.php" + subjectID;
  }
</script>

</body>
</html>
