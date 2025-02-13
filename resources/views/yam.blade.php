

@section('content')
    @php
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

        // Initialize an array to hold the table data
        $tableData = [];

        if ($tables->length > 0) {
            // Loop through all the tables
            foreach ($tables as $table) {
                // Loop through all rows in the table
                $rows = $table->getElementsByTagName('tr');
                foreach ($rows as $row) {
                    $cols = $row->getElementsByTagName('td');
                    $rowData = [];
                    foreach ($cols as $col) {
                        $rowData[] = $col->nodeValue;
                    }
                    if (!empty($rowData)) {
                        $tableData[] = $rowData;
                    }
                }
            }
        }
    @endphp

    <table border="1" align="center">
        @foreach ($tableData as $row)
            <tr>
                @foreach ($row as $col)
                    <td>{{ $col }}</td>
                @endforeach
            </tr>
        @endforeach
    </table>

