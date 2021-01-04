<?php
  include_once 'dbinfo.php';
        echo "<table class='table table-striped table-hover'>";
        echo "<thead class='bg-info'>
                <tr>
                    <th scope='col'>ID</th>
                    <th scope='col'>Page Title</th>
                    <th scope='col'>URL</th>
                    <th scope='col'>Last Indexed</th>
                    <th scope='col'>Last Updated</th>
                    <th scope='col'>Indexing Time (s)</th>
                </tr>
              </thead>
              <tbody>\n";

        class TableRows extends RecursiveIteratorIterator
        {
            public function __construct($it)
            {
                parent::__construct($it, self::LEAVES_ONLY);
            }
            public function current()
            {
                return "<td>" . parent::current() . "</td>";
            }
            public function beginChildren()
            {
                echo "<tr>";
            }
            public function endChildren()
            {
                echo "</tr>" . "\n";
            }
        }

        try {
            $stmt = $conn->prepare("SELECT pageID, pageName, url,
              DATE_FORMAT(finishDateTime, '%b. %e, %Y %l:%i %p'),
              DATE_FORMAT(lastModifiedDate, '%b. %e, %Y %l:%i %p'),
              indexTime FROM Page
              WHERE isIndexed = 1
              ORDER BY finishDateTime DESC");
            $stmt->execute();

            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            foreach (new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
                echo $v;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

        $conn = null;

        echo "</tbody>" . "\n" . "</table>" . "\n";
