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
        if (!isset($key) || !isset($gid)) {
            throw new \Exception(sprintf("No key [%s] or gid [%s] provided.", $key, $gid));
        }

        $url = sprintf(
            'https://docs.google.com/spreadsheets/d/%s/export?gid=%s&format=%s',
            $key,
            $gid,
            'tsv'
        );

        $page = trim(file_get_contents($url));

        if (strpos($page, '<!DOCTYPE html>') === 0) {
            throw new \Exception(sprintf("The spreadsheet is private (key [%s], gid [%s] provided).", $key, $gid));
        }

        $csv = explode("\n", $page);

        $records = array();
        
        $columns = explode("\t", $csv[0]);
        $columns = array_map('trim', $columns);

        for ($i = 1; $i < count($csv); $i++) {
            $row = explode("\t", $csv[$i]);
            $row = array_map('trim', $row);

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