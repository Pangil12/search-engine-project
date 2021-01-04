
<?php
include_once 'dbinfo.php';

$totalWords;       // total words in the db
$mostFrequentWords = array(); // most frequent word in the db
$leastFrequentWords = array(); // most frequent word in the db
$mostSearched;     // most searched word

// get total num of words in db (word table)
try {
  $stmt = $conn->prepare("SELECT COUNT(*) FROM Word;");
  $stmt->execute();
  $result = $stmt->fetchAll();
  $totalWords = $result[0][0];
} catch (PDOException $e) {
  error_log($e->getMessage());
}

// get most frequent word from= Word Frequency table
try {
  $stmt = $conn->prepare("SELECT wordID, SUM(frequency) AS summ
                          FROM WordFrequency
                          GROUP BY wordID
                          ORDER BY summ DESC
                          LIMIT 3");
  $stmt->execute();
  $result = $stmt->fetchAll();
  for ($i = 0; $i < 3; $i++) {
    $stmt = $conn->prepare("SELECT word FROM Word WHERE wordID = ".$result[$i][0].";");
    $stmt->execute();
    $wordResult = $stmt->fetchAll();
    array_push($mostFrequentWords, $wordResult[0][0]);
  }
} catch (PDOException $e) {
  error_log($e->getMessage());
}

// get least frequent word from Word Frequency table
try {
  $stmt = $conn->prepare("SELECT wordID, SUM(frequency) AS summ
                          FROM WordFrequency
                          GROUP BY wordID
                          ORDER BY summ ASC
                          LIMIT 3");
  $stmt->execute();
  $result = $stmt->fetchAll();
  for ($i = 0; $i < 3; $i++) {
    $stmt = $conn->prepare("SELECT word FROM Word WHERE wordID = ".$result[$i][0].";");
    $stmt->execute();
    $wordResult = $stmt->fetchAll();
    array_push($leastFrequentWords, $wordResult[0][0]);
  }

} catch (PDOException $e) {
  error_log($e->getMessage());
}

echo '
<table class="table table-striped table-hover">
  <thead class="bg-info">
    <tr>
      <th colspan="2" class="text-center" scope="col">Words</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row">Total</th>
      <td>'.$totalWords.'</td>
    </tr>
    <tr>
      <th scope="row">Top 3 Most Frequent</th>
      <td>'.$mostFrequentWords[0].',
          '.$mostFrequentWords[1].',
          '.$mostFrequentWords[2].'</td>
    </tr>
    <tr>
      <th scope="row">Top 3 Least Frequent</th>
      <td>'.$leastFrequentWords[0].',
          '.$leastFrequentWords[1].',
          '.$leastFrequentWords[2].'</td>
    </tr>
  </tbody>
</table>'
?>
<!-- <tr> optional*
  <th scope="row">Most Searched</th>
  <td>'.$avgIndexingTime.'</td>
</tr> -->

<!-- SELECT f.wordID, w.word, SUM(frequency) As Summ
FROM SearchEngine355.WordFrequency f, SearchEngine355.Word w
WHERE w.wordID = f.wordID
GROUP by w.wordID
ORDER BY Summ DESC; -->
