<?php
namespace app\models;

/**
 *
 */
class TeachersCourses extends \app\models\Base {
    protected $tableName = 'teachersCourses';

    /**
     *
     */
    public function getTeacherCourses($teacherId) {
        $courses = $this->findAllBy('teacher', $teacherId);
        
        return array_map(function ($v) {
            return $v['course'];
        }, $courses);
    }
}