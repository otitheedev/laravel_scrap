<?php

$url = "https://www.bb.org.bd/en/index.php/econdata/exchangerate";

// Initialize cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$html = curl_exec($ch);
curl_close($ch);

// Remove UTF-8 BOM if it exists
$html = preg_replace('/^\xEF\xBB\xBF/', '', $html);

// Load HTML into DOMDocument
$dom = new DOMDocument();
libxml_use_internal_errors(true);
$dom->loadHTML($html);
libxml_clear_errors();

// Initialize XPath
$xpath = new DOMXPath($dom);

// Find the table with class 'customListTable' or 'cli_table'
//$tables = $xpath->query('//table[contains(@class, "customListTable") or contains(@class, "cli_table")]');


$tables = $xpath->query('//table');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exchange Rate Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Exchange Rate Data</h2>
        
        <?php if ($tables->length > 0): ?>
            <?php foreach ($tables as $table): ?>
                <div class="table-responsive mb-4">
                    <table class="table table-bordered table-striped table-hover">
                        
                            <tr>
                                <?php
                                $headerRow = $table->getElementsByTagName('tr')->item(0);
                                if ($headerRow) {
                                    foreach ($headerRow->getElementsByTagName('th') as $th) {
                                        echo "<th>" . htmlspecialchars($th->nodeValue) . "</th>";
                                    }
                                }
                                ?>
                            </tr>
                       
                        <tbody >
                            <?php
                            $rows = $table->getElementsByTagName('tr');
                            foreach ($rows as $index => $row) {
                                if ($index === 0) continue; // Skip header row
                                echo "<tr>";
                                foreach ($row->getElementsByTagName('td') as $col) {
                                    echo "<td>" . htmlspecialchars($col->nodeValue) . "</td>";
                                }
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-danger text-center">No tables found.</p>
        <?php endif; ?>
    </div>
</body>
</html>

