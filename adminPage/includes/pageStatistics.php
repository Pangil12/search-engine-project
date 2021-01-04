<?php
include_once 'dbinfo.php';

$totalPages;       // total num of pages in the db
$totalIndexed;     // total num of pages indexed
$avgIndexingTime;  // avg indexing time b/w all Pages
$indexesPerDay;    // Estimated num of indexes per pay

// get total num of pages from db
try {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM Page;");
    $stmt->execute();
    $result = $stmt->fetchAll();
    $totalPages = $result[0][0];
} catch (PDOException $e) {
    error_log($e->getMessage());
}

// get total num of pages indexed db
try {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM Page WHERE isIndexed = 1;");
    $stmt->execute();
    $result = $stmt->fetchAll();
    $totalIndexed = $result[0][0];
} catch (PDOException $e) {
    error_log($e->getMessage());
}

// get sum of all the indexing times
try {
    $stmt = $conn->prepare("SELECT SUM(indexTime) FROM Page;");
    $stmt->execute();
    $result = $stmt->fetchAll();
    $avgIndexingTime = $result[0][0];
} catch (PDOException $e) {
    error_log($e->getMessage());
}
// divide by total num of pages to get avg.
$avgIndexingTime = $avgIndexingTime / $totalIndexed;
// round to two decimal places
$avgIndexingTime = round($avgIndexingTime, 2);
// divide seconds in a day by avg indexing time to get avg indexes per day
$indexesPerDay = 86400 / $avgIndexingTime;
// round to nearest whole number
$indexesPerDay = round($indexesPerDay);

echo '
<table class="table table-striped table-hover">
  <thead class="bg-info">
    <tr>
      <th colspan="2" class="text-center" scope="col">Pages</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row">Total</th>
      <td>'.$totalPages.'</td>
    </tr>
    <tr>
      <th scope="row">Indexed</th>
      <td>'.$totalIndexed.'</td>
    </tr>
    <tr>
      <th scope="row">Average Indexing Time (s)</th>
      <td>'.$avgIndexingTime.'</td>
    </tr>
    <tr>
      <th scope="row">Estimated Indexes/day</th>
      <td>'.$indexesPerDay.'</td>
    </tr>
  </tbody>
</table>';
