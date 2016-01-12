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

    /**
     *
     */
    public function update() {
        $records = parent::update();
        
        $teachers = $this->app['teachers.model']->getAll();

        foreach ($teachers as &$teacher) {
            $teacher['courses'] = $this->getTeacherCourses($teacher['id']);
        }

        $this->app['teachers.model']->setRecords($teachers);

        return $records;
    }
}