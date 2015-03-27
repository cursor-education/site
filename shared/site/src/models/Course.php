<?php
namespace app\models;

/**
 *
 */
class Course extends \app\models\Base {
    protected $tableName = 'courses';

    /**
     *
     */
    public function mapTechnologies($record, $technologies) {
        $_technologies = array();

        foreach ($record['technologies'] as $val) {
            if (!empty($technologies[$val])) {
                $_technologies[$val] = $technologies[$val];
            }
        }

        $record['technologies'] = $_technologies;
        return $record;
    }

    /**
     *
     */
    public function formatRecord($record) {
        $record = parent::formatRecord($record);

        $record['technologies'] = explode(",", $record['technologies']);
        $record['technologies'] = array_map('trim', $record['technologies']);
        $record['technologies'] = array_filter($record['technologies']);

        return $record;
    }
}