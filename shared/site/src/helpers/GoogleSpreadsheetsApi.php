<?php
namespace app\helpers;

class GoogleSpreadsheetsApi {

    /**
     * get & parse records from google spreadsheet
     *
     * @param string $key
     * @param string $gid
     * @return array
     */
    static public function fetch($key, $gid) {
        $url = sprintf(
            'https://docs.google.com/spreadsheets/d/%s/export?gid=%s&format=%s',
            $key,
            $gid,
            'tsv'
        );

        $csv = trim(file_get_contents($url));
        $csv = explode("\n", $csv);

        $records = array();
        $columns = explode("\t", $csv[0]);

        for ($i = 1; $i < count($csv); $i++) {
            $row = explode("\t", $csv[$i]);
            $record = array_combine($columns, $row);

            if (isset($record['id'])) {
                $records[$record['id']] = $record;
            }
            else {
                $records[] = $record;
            }
        }

        return $records;
    }
}