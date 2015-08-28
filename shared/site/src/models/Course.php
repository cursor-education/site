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
    public function formatRecord($record) {
        $record['technologies'] = $this->app['coursesTechnologies.model']->getCourseTechnologies($record['id']);

        return parent::formatRecord($record);
    }
}