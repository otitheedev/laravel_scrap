<?php

$url = "https://www.nu.ac.bd";

// Initialize cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$html = curl_exec($ch);
curl_close($ch);

// Remove UTF-8 BOM if it exists (in case of any hidden BOM in the source)
$html = preg_replace('/^\xEF\xBB\xBF/', '', $html);

// Load HTML into DOMDocument
$dom = new DOMDocument();
libxml_use_internal_errors(true); // Ignore any parsing errors
$dom->loadHTML($html);
libxml_clear_errors();

// Initialize XPath to query DOM
$xpath = new DOMXPath($dom);

// Find the table with class 'customListTable' or 'cli_table'
$tables = $xpath->query('//table[contains(@class, "customListTable") or contains(@class, "cli_table")]');

// Check if tables were found
if ($tables->length > 0) {
    echo "Found " . $tables->length . " tables.<br>";

    // Loop through all the tables
    foreach ($tables as $table) {
        // Get the table class attribute
        echo "Table class: " . $table->getAttribute('class') . "<br><br>";

        // Loop through all rows in the table
        $rows = $table->getElementsByTagName('tr');
        echo "Found " . $rows->length . " rows.<br>";

        foreach ($rows as $row) {
            $cols = $row->getElementsByTagName('td');
            echo "Found " . $cols->length . " columns in this row.<br><br>";
            foreach ($cols as $col) {
                echo "Column data: " . $col->nodeValue . "<br><br>";
            }
        }
    }
} else {
    echo "No tables found.<br>";
}

?>
