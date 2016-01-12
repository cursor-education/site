<?php
namespace app\models;

/**
 *
 */
class Pages extends \app\models\Base {
    protected $tableName = 'pages';

    /**
     *
     */
    public function findAndMerge($key, array $values, array $defaults = array()) {
        $page = array();

        foreach ($values as $value) {
            $metas = (array) $this->findBy($key, $value);
            
            foreach ($metas as $metaKey => $metaValue) {
                if ($metaValue) {
                    $page[$metaKey] = $metaValue;
                }
            }
        }

        if (!empty($defaults)) {
            foreach ($defaults as $defaultsKey => $defaultsValue) {
                if (empty($page[$defaultsKey])) {
                    $page[$defaultsKey] = $defaultsValue;
                }
            }
        }

        return $page;
    }
}