<!DOCTYPE html>
<html>
<head>
</head>
<body>

<?php
error_log($_SERVER['REMOTE_ADDR'], 0);

function drawSubject($title, $author, $subjectID) {
  static $bg = "";
  $bg = ($bg == "bg1") ? "bg2" : "bg1";
  echo '
    <div class="post ' . $bg . '">
      <div class="inner">
        <div class="columns">
          <div class="postbody">
            <div class="posthead">
              <div>by ' . $author . '</div>
            </div>
            <div>
              <a href="subject.php?id=' . $subjectID . '">
                ' . $title . '
              </a>
            </div>
          </div>
          <div class="postprofile">
            <div>' . $author . '</div>
            <div>Posts: 242</div>
          </div>
        </div>
        <div class="back2top"><a class="top" href="#top"><img src="icons/angle-circle-arrow-up.png" style="width:18px;height:18px;"></a></div>
      </div>
    </div>
  ';
}
try {
  $conn = new PDO("mysql:host=localhost;dbname=myForum", "alex", "svetly");
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql="SELECT * FROM Subjects";
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  $result = $stmt->fetch();
  while ($result) {
    $authorID = $result['AuthorID'];
    $sql="SELECT Username FROM Users WHERE UserID = '$authorID'";
    $stmt2 = $conn->prepare($sql);
    $stmt2->execute();
    $result2 = $stmt2->fetch();
    $author = $result2['Username'];
    drawSubject($result['Title'], $author, $result['SubjectID']);
    $result = $stmt->fetch();
  }
}
catch(PDOException $e) {
  error_log($e->getMessage(), 0);
}
$conn = null;
?>
</body>
</html>
