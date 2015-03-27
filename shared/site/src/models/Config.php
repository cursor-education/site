<?php
namespace app\models;

/**
 *
 */
class Config extends \app\models\Base {
    protected $tableName = 'config';

    /**
     *
     */
    public function getByKey($key) {
        $record = $this->findBy('key', $key);

        if (!empty($record['value'])) {
            return $record['value'];
        }

        return null;
    }
}