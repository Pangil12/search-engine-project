
<?php
include_once 'dbinfo.php';

$url = $_POST['URL'];

// get modified date from header
$headers = get_headers(URL, 3);
$lastModified = $headers['Last-Modified'];
$timestamp = strtotime($lastModified);
$lastModified = date("Y-m-d H:i:s", $timestamp);

  // insert into Page the lastModifiedDate
  try {
      $stmt = $conn->prepare("UPDATE Page SET
            lastModifiedDate = :lastModifiedDate
            WHERE pageID =
            (SELECT pageID FROM (SELECT * FROM Page) AS pgID WHERE url = :url);");
      $stmt->bindParam(':url', $url);
      $stmt->bindParam(':lastModifiedDate', $lastModified);
      $stmt->execute();
  } catch (PDOException $e) {
      error_log($e->getMessage());
  }
// ALTER TABLE `SearchEngine355`.`Word` AUTO_INCREMENT = 1;
?>
