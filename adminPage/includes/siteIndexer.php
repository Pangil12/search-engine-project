
<?php
include_once 'dbinfo.php';

$urla = $_POST['URL'];

// start-time; var to keep track of start time
$startTime = microtime(true);

// get date and time and format into mysql's DATETIME format
$startOfIndexingTime = date("Y-m-d H:i:s", $startTime);

// 1. Initialize CURL handle
$ch = curl_init();

define("URL", $urla); // const for url

// 2. Set options
// Url to send the request; defines how we're going to query
// 1st param: curl; 2nd param: option you want to set; 3rd param:url
curl_setopt($ch, CURLOPT_URL, URL);

//return the transfer as a string of the return value of curl_exec()
//instead of outputting it directly. Set to true here
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// whether to include the header in the output. Set to false here
curl_setopt($ch, CURLOPT_HEADER, 0);

// 3. Execute request, fetch the response and store
// output in `$output`
$output = curl_exec($ch);

// check if request was succesful if not echo error
if ($output ===false) {
    echo "CURL ERROR: " . curl_error($ch);
}
// 4. Close and free up the curl handle
curl_close($ch);

//5.Represents an entire HTML or XML document; serves as the root of the document tree.
$DOM = new DOMDocument;
$DOM->loadHTML($output);

// get the body and title element and store in variables
// text will be extracted from these elements
$bodyElement = $DOM->getElementsByTagName('body');
$titleElement = $DOM->getElementsByTagName('title');

// get modified date from header
$headers = get_headers(URL, 3);
$lastModified = $headers['Last-Modified'];
$timestamp = strtotime($lastModified);
$lastModified = date("Y-m-d H:i:s", $timestamp);

// Array of words to filter: Common Words in English
$commonWords = array("the","of","and","to","in","is","you","that","it","he",
"was","for","on","are","as","with","his","they","at","be","this","have","from",
"or","one","had","by","word","but","not","what","all","were","we","when","your",
"can","said","there","use","an","each","which","she","do","how","their","if",
"will","up","other","about","out","many","then","them","these","so","some","her",
"would","make","like","him","into","time","has","look","two","more","write","go",
"see","no","way","could","people","my","than","first","who","its","now","day",
"i'm", "you're","get","it's","there's","they're","come");

// get the text content from the body and title tags
// and store in variables (result will be one long string)
$bodyText = $bodyElement->item(0)->textContent;
$titleText = $titleElement->item(0)->textContent;

// set string to lower-case
$bodyText = strtolower($bodyText);
//$titleText = strtolower($titleText);

// search string for all matches of the regex, in this case words,
// store matching word in $matches array
// PREG_PATTERN_ORDER orders results so that $matches[0] is an array of full pattern matches
preg_match_all("/[a-z']+/", $bodyText, $matches, PREG_PATTERN_ORDER);
preg_match_all("/[a-zA-Z']+/", $titleText, $matches2, PREG_PATTERN_ORDER);
$bodyWordsArray = $matches[0];
$wordsInTitle = $matches2[0];
echo $wordsInTitle;

// filter out the common words from $commonWords array
$filteredBodyWords = array();
foreach ($bodyWordsArray as $word) {
    if (preg_grep("/^$word$/i", $commonWords)) {
        //do nothing
    } else {
        array_push($filteredBodyWords, $word);
    }
}

// filter out the common words in the title from $commonWords array
$filteredTitleWords = array();
foreach ($wordsInTitle as $word) {
    if (preg_grep("/^$word$/i", $commonWords)) {
        //do nothing
    } else {
        array_push($filteredTitleWords, $word);
    }
}

// associative array for words
// this will be our hashmap key:word  value:word count
$map = array();

function addToHashMap(&$wordsArray, &$hashMap)
{
    // store words in the map  hashmap
    // if word already exists in map, increment its count
    foreach ($wordsArray as $word) {
        if (strlen($word) > 1) {
            if (array_key_exists($word, $hashMap)) {
                $temp = $hashMap[$word];
                $hashMap[$word] = $temp + 1;
            } else {
                $hashMap[$word] = 1;
            }
        }
    }
}

addToHashMap($filteredBodyWords, $map);  // add words from body to hashmap
addToHashMap($wordsInTitle, $map);    // add words from title to hashmap
//print_r($map);

  // first insert the words from the hashmap into the Word in DB
  foreach (array_keys($map) as $key) {
      $word = $key;
      try {
          $stmt = $conn->prepare("INSERT INTO Word (word) VALUES (:word);");
          $stmt->bindParam(':word', $word, PDO::PARAM_STR);
          $stmt->execute();
      } catch (PDOException $e) {
          error_log($e->getMessage());
      }
  }

// insert words and corresponding frequencies from hashmap into WordFrequency table
// in db; get pageID and wordID from word/page tables
  $url = URL;
  foreach ($map as $key => $value) {
      $word = $key;
      $count = $value;
      try {
          $stmt = $conn->prepare("INSERT INTO WordFrequency (wordID, pageID, frequency) VALUES
          ((select wordID from Word where word = :word),
          (select pageID from Page where url = :url), :count)
          ON DUPLICATE KEY UPDATE frequency = :count");
          $stmt->bindParam(':word', $word);
          $stmt->bindParam(':count', $count);
          $stmt->bindParam(':url', $url);
          $stmt->execute();
      } catch (PDOException $e) {
          error_log($e->getMessage());
      }
  }

  // total time taken to index page(seconds);
  $endTime = microtime(true);
  $duration = date($endTime-$startTime);
  $duration = round($duration, 3); //round to 2 decimal places
  $endOfIndexingTime = date("Y-m-d H:i:s", $endTime);

  //variable to store count of words on page
  $wordCount = count($map);

  // insert into Page indexing start/end time, runtime of indexing,
  // word count, set isIdexed to true (1);
  try {
      $stmt = $conn->prepare("UPDATE Page SET
            startDateTime = '".$startOfIndexingTime."',
            finishDateTime = '".$endOfIndexingTime."',
            indexTime = '". $duration."',
            pageName = :title,
            lastModifiedDate = :lastModifiedDate,
            numOfWords = '".$wordCount."',
            isIndexed = '1'
            WHERE pageID =
            (SELECT pageID FROM (SELECT * FROM Page) AS pgID WHERE url = :url);");
      $stmt->bindParam(':url', $url);
      $stmt->bindParam(':title', $titleText);
      $stmt->bindParam(':lastModifiedDate', $lastModified);
      $stmt->execute();
      // echo "New record created successfully";
  } catch (PDOException $e) {
      error_log($e->getMessage());
  }

// ALTER TABLE `SearchEngine355`.`Word` AUTO_INCREMENT = 1;
// <?php $mysqltime = date ("Y-m-d H:i:s", $phptime);
?>
