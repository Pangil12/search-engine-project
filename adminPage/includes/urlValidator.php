<?php
include_once 'dbinfo.php';

$post = $_POST['urlField'];
$url = trim($post);

$header = get_headers($url);
$status = substr($header[0], 9, 3);

if ($status >= 200 && $status < 400) {
    //now add to database
    if (isInDatabase($url, $conn) == false) {
        try {
            $stmt = $conn->prepare("INSERT INTO Page (url) VALUES (:url);");
            $stmt->bindParam(':url', $url);
            $stmt->execute();
            echo 'TRUE';
        } catch (PDOException $e) {
            error_log($e->getMessage());
        }
    } else {
        echo 'EXISTS';
    }
} else {
    echo 'FALSE';
}

function isInDatabase($url, $connection)
{
    try {
        $stmt = $connection->prepare("SELECT EXISTS(SELECT 1 FROM Page WHERE
            url = '". $url ."');");
        $stmt->bindParam(1, $_GET['url'], PDO::PARAM_INT);
        $stmt->execute();
        $res = $stmt->fetchColumn();
        if ($res > 0) {
            return true;
        } else {
            return false;
        }
    } catch (PDOException $e) {
        error_log($e->getMessage());
    }
}
