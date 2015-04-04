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
     * find all records by value of column-name
     *
     * @param string $columnName
     * @param string $value
     * @return array|null
     */
    final public function findAllBy($columnName, $value) {
        $results = array();
        $records = $this->getAll();

        foreach ($records as $record) {
            if ($record[$columnName] == $value) {
                $results[] = $record;
            }
        }

        return $results;
    }

    /**
     *
     */
    public function getGoogleDbKey() {
        $value = $this->app['config.model']->getByKey($this->tableName . '.key');
        
        if (empty($value)) {
            $value = $this->app['db.google.' . $this->tableName . '.key'];
        }

        return $value;
    }

    /**
     *
     */
    public function getGoogleDbGid() {
        $value = $this->app['config.model']->getByKey($this->tableName . '.gid');

        if (empty($value)) {
            $value = $this->app['db.google.' . $this->tableName . '.gid'];
        }

        return $value;
    }

    /**
     * update table records from google-spreadsheets
     *
     * @return array
     */
    final public function update() {
        $records = GoogleSpreadsheetsApi::fetch(
            $this->getGoogleDbKey(),
            $this->getGoogleDbGid()
        );

        foreach ($records as &$record) {
            $record = $this->formatRecord($record);
        }

        $this->cache->store($this->cacheRecordsKey, $records);

        return $records;
    }

    /**
     * custom format each record
     * 
     * @param array $record
     * @return array $record
     */
    public function formatRecord($record) {
        foreach ($record as $key => $value) {
            if ($value == '-') {
                $record[$key] = null;
            }
        }

        return $record;
    }
}