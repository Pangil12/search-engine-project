<!-- connects to the database server -->
<?php include "var/www/inc/dbinfo.inc"; ?>
<!-- header is now in the includes folder -->
<?php include "includes/header.php"; ?>

<div class="row" style="margin-right: 0; margin-left: 0;">
    <div class="col-sm-1"></div>
    <div class="col-sm-3">
        <br>
        <form class="myForm" action="results.php" method="post" onsubmit="required()">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Search Results"
                        name="searchResultsBox" required
                        oninvalid="this.setCustomValidity('Enter Item to Search')"
                        oninput="this.setCustomValidity('')">
                <div class="input-group-append">
                    <button class="btn btn-info" type="submit">Search </button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="row" style="margin-right: 0; margin-left: 0;">
    <div class="col-sm-12">
        <hr>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <?php
                include_once "adminPage/includes/dbinfo.php";

                // get word from searchBox or results page searchbox
                if (!isset($_POST['searchBox'])){
                  $searchQuery = $_POST['searchResultsBox'];
                } else {
                  $searchQuery = $_POST['searchBox'];
                }
                $searchQuery = trim($searchQuery); // remove whitespace

                // var to keep count of results per search Query
                // will be used for statistics
                $resultsCount = 0;

                try {
                    $stmt = $conn->prepare(
                    "SELECT  f.pageID, p.pageName, p.url, w.word, f.frequency
                     FROM    SearchEngine355.WordFrequency f,
                             SearchEngine355.Word w,
                             SearchEngine355.Page p
                     WHERE   w.word = :word and
                    				 w.wordID = f.wordID and
                    				 p.pageID = f.pageID
                    ORDER BY f.frequency DESC;");
                    $stmt->bindParam(':word', $searchQuery);
                    $stmt->execute();
                    //$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
                    $count = $stmt->rowCOunt();
                } catch (PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }

                if ($count == 0) {
                    echo "<div class='row'>
                            <div class='col-sm-12'>
                                <p>No results found.</p>
                            </div>
                          </div>";
                } else {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                      $resultsCount++;
                        $pageTitle = $row['pageName'];
                        $pageURL = $row['url'];
                        echo "<div class='row'>
                                <div class='col-sm-12'>
                                    <a href='$pageURL'>" . "<h4 class='title'>" . $pageTitle . "</h4></a> \r\n
                                    <a class='urlSubHeader href='$pageURL'> $pageURL </a>
                                </div>
                              </div>
                              <br>";
                    }
                }

                // store search Query and results count in Database
                try {
                    $stmt = $conn->prepare(
                      "INSERT INTO UserSearch (searchQuery, resultCount)
                       VALUES (:searchQuery, :resultCount)
                       ON DUPLICATE KEY UPDATE resultCount = :resultCount;");
                    $stmt->bindParam(':searchQuery', $searchQuery);
                    $stmt->bindParam(':resultCount', $resultsCount);
                    $stmt->execute();
                } catch (PDOException $e) {
                    error_log($e->getMessage());
                }
            ?>
        </div>
    </div>
</div>

<!-- footer is now in the includes folder -->
<?php include "includes/footer.php"; ?>
