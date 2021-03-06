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
     *
     */
    final public function setRecords($records) {
        $this->cache->store($this->cacheRecordsKey, $records);
    }

    /**
     *
     */
    final public function setByKey($key, $record) {
        $records = $this->getAll();
        $records[$key] = $record;

        $this->setRecords($records);
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
            if (!empty($record[$columnName])
             && $record[$columnName] == $value)
            {
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
            if (!empty($record[$columnName])
             && $record[$columnName] == $value)
            {
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
    public function update() {
        $records = GoogleSpreadsheetsApi::fetch(
            $this->getGoogleDbKey(),
            $this->getGoogleDbGid()
        );

        foreach ($records as &$record) {
            $record = $this->formatRecord($record);
        }

        $records = array_filter($records);

        $this->setRecords($records);

        return $records;
    }

    /**
     * custom format each record
     * 
     * @param array $record
     * @return array $record
     */
    public function formatRecord($record) {
        if (isset($record['active'])
         && $record['active'] != '1')
        {
            return null;
        }

        foreach ($record as $key => $value) {
            if ($value == '-') {
                $record[$key] = null;
            }
        }

        return $record;
    }
}