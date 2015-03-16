<?php
namespace app\models;

/**
 *
 */
class CoursePlan extends \app\models\Base {
    protected $tableName = 'coursesPlan';

    /**
     *
     */
    public function formatCoursePlan($courseId) {
        $records = $this->findAllBy('course-id', $courseId);

        foreach ($records as &$record) {
            $subitems = explode("\\n", $record['subitems']);
            $subitems = array_map('trim', $subitems);
            $subitems = array_filter($subitems);

            $record['subitems'] = $subitems;
        }
        
        return $records;
    }
}