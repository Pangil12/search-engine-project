<?php
        include_once 'dbinfo.php';
        echo "<table class='table table-striped table-hover'>";
        echo "<thead class='bg-info'>
                <th scope='col'>Search Query</th>
                <th scope='col'>Number of Page Results</th>
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
                return "\n  " . "<td>" . parent::current() . "</td>" . "\n";
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
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare("SELECT searchQuery, resultCount FROM UserSearch
                                    ORDER BY resultCount DESC");
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
