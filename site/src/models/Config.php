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
        $keys = array(
            $this->app['environment'] . '/' . $key,
            $key,
        );

        foreach ($keys as $key) {
            $result = $this->findByKey($key);

            if ($result) {
                return $result;
            }
        }

        return null;
    }

    /**
     *
     */
    public function findByKey($key) {
        $record = $this->findBy('key', $key);
        
        if (!empty($record['value'])) {
            return $record['value'];
        }

        return null;
    }
}