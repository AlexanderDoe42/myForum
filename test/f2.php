<!DOCTYPE html>
<html>
<head>
<style>
table {
    width: 100%;
    border-collapse: collapse;
}

table, td, th {
    border: 1px solid black;
    padding: 5px;
}

th {text-align: left;}
</style>
</head>
<body>

<?php
$q = intval($_GET['q']);

try {
    $conn = new PDO("mysql:host=localhost;dbname=myForum", "alex", "svetly");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql="SELECT * FROM Users WHERE UserID = '$q'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch();
    echo "<table>
    <tr>
    <th>ID</th>
    <th>Username</th>
    <th>Password</th>
    </tr>";
      echo "<tr>";
      echo "<td>" . $result['UserID'] . "</td>";
      echo "<td>" . $result['Username'] . "</td>";
      echo "<td>" . $result['Password'] . "</td>";
      echo "</tr>";
    echo "</table>";
}
catch(PDOException $e) {
    error_log($e->getMessage(), 0);
}
$conn = null;
?>
</body>
</html>
