<?php
        include_once 'dbinfo.php';
        echo "<table class='table table-striped table-hover' id='myTable'>";
        echo "<thead class='bg-info'>
                <th scope='col'><input type='checkbox' id='checkAll'></th>
                <th scope='col'>Page Title</th>
                <th scope='col'>URL</th>
                <th scope='col'>Last Updated</th>
                <th scope='col'>Last Indexed</th>
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
                return "<td>" . parent::current() . "</td>" . "\n";
            }

            public function beginChildren()
            {
                echo "<tr>
                        <th scope='row'><input type='checkbox'></th>\n";
            }

            public function endChildren()
            {
                echo "</tr>" . "\n";
            }
        }

        try {
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare("SELECT pageName, url,
              DATE_FORMAT(lastModifiedDate, '%b. %e, %Y %l:%i %p'),
              DATE_FORMAT(finishDateTime, '%b. %e, %Y %l:%i %p')
              FROM Page
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
