<!DOCTYPE html>
<html>
<head>
</head>
<body>

<!--div class="post">
  <div class="inner">
    <div class="columns">
      <div class="postbody">
        <div class="columns">
          <div class="posthead">
            <div>Re: DDOS attack on board</div>
            <div>by ' . $author . ' » Thu Jun 21, 2018 8:08 am</div>
          </div>
          <div class="reply-button"><button type="button" name="button">button</button></div>
        </div>
        <div>' . $content . '</div>
      </div>
      <div class="postprofile">
        <div>NewToPHPBoards</div>
        <div>Registered User</div>
        <div>Posts: 242</div>
        <div>Joined: Wed Feb 03, 2016 1:38 pm</div>
      </div>
    </div>
    <div class="back2top"><a class="top" href="#top"><img src="icons/angle-circle-arrow-up.png" style="width:18px;height:18px;"></a></div>
  </div>
</div-->



<?php
error_log($_SERVER['REMOTE_ADDR'], 0);

function drawPost($content, $author) {
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
            <button class="reply-button">button</button>
            <div>' . $content . '</div>
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
  $sql="SELECT * FROM Posts";
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
    drawPost($result['PostContent'], $author);
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
