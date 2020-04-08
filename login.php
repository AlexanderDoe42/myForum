<!DOCTYPE html>
<html>
<head>
</head>
<body>

<?php
$username = $_POST['username'];
$password = $_POST['password'];

try {
    $conn = new PDO("mysql:host=localhost;dbname=myForum", "alex", "svetly");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT UserID, Username, Password
            FROM Users
            WHERE Username = '$username' AND
                  Password = '$password'";
    $stmt = $conn->prepare($sql);
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
?>
<script>document.location = 'index.html';</script>

</body>
</html>
