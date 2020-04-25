<?php

class MyDB extends Dbh {
  private function drawSubject($title, $author, $subjectID) {
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
  private function drawPost($postID, $content, $author, $title) {
    static $bg = "";
    $bg = ($bg == "bg1") ? "bg2" : "bg1";
    echo '
      <div id="post' . $postID . '" class="post ' . $bg . '">
        <div class="inner">
          <div class="columns">
            <div class="postbody">
              <div class="posthead">
                <div>' . $title . '</div>
                <div>by <span id="author_post' . $postID . '">' . $author . '</span></div>
              </div>
              <button class="quote-button" onclick="replyButtonClickEvent(' . $postID . ')"><img src="/icons/double_quotation_mark.png"></button>
              <div id="post_content' . $postID . '" class="post_content">' . $content . '</div>
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

  public function getSubjects() {
    $conn = $this->connect();
    $sql="SELECT * FROM Subjects";
    $stmt = $conn->query($sql);
    while ($row = $stmt->fetch()) {
      $authorID = $row['AuthorID'];
      $sql="SELECT Username FROM Users WHERE UserID = '$authorID'";
      $stmt2 = $conn->prepare($sql);
      $stmt2->execute();
      $row2 = $stmt2->fetch();
      $author = $row2['Username'];
      $this->drawSubject($row['Title'], $author, $row['SubjectID']);
    }
  }
  public function getDescription($subjectID) {
    $conn = $this->connect();
    $sql="SELECT * FROM Subjects WHERE SubjectID = '$subjectID'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch();
    $authorID = $result['AuthorID'];
    $sql="SELECT Username FROM Users WHERE UserID = '$authorID'";
    $stmt2 = $conn->prepare($sql);
    $stmt2->execute();
    $result2 = $stmt2->fetch();
    $author = $result2['Username'];
    $this->drawPost(0, $result['Content'], $author, $result['Title']);
    $result = $stmt->fetch();
  }
  private function getTitle($subjectID) {
    $conn = $this->connect();
    $sql="SELECT * FROM Subjects WHERE SubjectID = '$subjectID'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch();
    return $result['Title'];
  }
  public function getPosts($subjectID) {
    $conn = $this->connect();
    $sql="SELECT * FROM Posts WHERE SubjectID = '$subjectID'";
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
      $this->drawPost($result['PostID'], $result['PostContent'], $author, "Re: " . $this->getTitle($subjectID));
      $result = $stmt->fetch();
    }
  }
  public function getUsername($usrID) {
    $conn = $this->connect();
    $sql = "SELECT Username FROM Users WHERE UserID = '$usrID'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch();
    if ($result) {
      echo $result['Username'];
    } else {
      setcookie("usrID", "", time() - 3600);
    }
  }
  public function login($username, $password) {
    $username = $this->test_input($username);
    $password = $this->test_input($password);

    $conn = $this->connect();
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
  public function signup($username, $password) {
    try {
      $conn = $this->connect();
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $sql = "INSERT INTO Users (Username, Password, RegistrationDate)
              VALUES (:username, :password, CURDATE())";
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(":username", $username, PDO::PARAM_STR);
      $stmt->bindValue(":password", $password, PDO::PARAM_STR);
      $stmt->execute();
      echo "<script>document.body.onload = function() { regSuccess(); }</script>";
    }
    catch(PDOException $e) {
      echo "<script>document.body.onload = function() { regFailure(); }</script>";
      error_log($e->getMessage(), 0);
    }
    $conn = null;
  }
  public function newSubject($title, $content, $usrID) {
    try {
      $conn = $this->connect();
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
  }
  public function newPost($msg, $usrID, $subjectID) {
    try {
      $conn = $this->connect();
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
  }
  public function getRegistrationDate() {
    $usrID = $_COOKIE['usrID'];
    try {
      $conn = $this->connect();
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "SELECT RegistrationDate FROM Users WHERE UserID = '$usrID'";
      $stmt = $conn->prepare($sql);
      $stmt->execute();
      $result = $stmt->fetch();
      echo $result['RegistrationDate'];
    }
    catch(PDOException $e) {
      error_log($e->getMessage(), 0);
    }
    $conn = null;
  }
  public function getNumberOfSubjects() {
    $usrID = $_COOKIE['usrID'];
    try {
      $conn = $this->connect();
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "SELECT NumberOfSubjects FROM Users WHERE UserID = '$usrID'";
      $stmt = $conn->prepare($sql);
      $stmt->execute();
      $result = $stmt->fetch();
      echo $result['NumberOfSubjects'];
    }
    catch(PDOException $e) {
      error_log($e->getMessage(), 0);
    }
    $conn = null;
  }
  public function getNumberOfPosts() {
    $usrID = $_COOKIE['usrID'];
    try {
      $conn = $this->connect();
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "SELECT NumberOfPosts FROM Users WHERE UserID = '$usrID'";
      $stmt = $conn->prepare($sql);
      $stmt->execute();
      $result = $stmt->fetch();
      echo $result['NumberOfPosts'];
    }
    catch(PDOException $e) {
      error_log($e->getMessage(), 0);
    }
    $conn = null;
  }
  public function searchForUser($username) {
    try {
      $conn = $this->connect();
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "SELECT Username FROM Users WHERE Username = " . $conn->quote($username);
      $stmt = $conn->prepare($sql);
      $stmt->execute();
      $result = $stmt->fetch();
      if ($result) {
        echo "taken";
      }
    }
    catch(PDOException $e) {
      error_log($e->getMessage(), 0);
    }
    $conn = null;
  }
}

?>
