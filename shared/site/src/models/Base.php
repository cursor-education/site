<?php
namespace app\models;

use app\helpers\GoogleSpreadsheetsApi;

/**
 *
 */
abstract class Base {
    protected $tableName = null;

    /**
     *
     * @param \Silex\Application $app
     */
    public function __construct(\Silex\Application $app) {
        $this->app = $app;
        $this->cache = $app['cache'];

        $this->cacheRecordsKey = sprintf('db.%s.records', $this->tableName);
    }

    /**
     * return all records
     *
     * @return array
     */
    final public function getAll() {
        $records = $this->cache->fetch($this->cacheRecordsKey);

        if (!$records) {
            return array();
        }

        return $records;
    }

    /**
     * find record by value of column-name
     *
     * @param string $columnName
     * @param string $value
     * @return array|null
     */
    final public function findBy($columnName, $value) {
        $records = $this->getAll();

        foreach ($records as $record) {
            if ($record[$columnName] == $value) {
                return $record;
            }
        }

        return null;
    }

    /**
     * update table records from google-spreadsheets
     *
     * @return array
     */
    final public function update() {
        $records = GoogleSpreadsheetsApi::fetch(
            $this->app['db.google.'.$this->tableName.'.key'],
            $this->app['db.google.'.$this->tableName.'.gid']
        );

        $this->cache->store($this->cacheRecordsKey, $records);

        return $records;
    }

}