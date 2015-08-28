<?php
namespace app\models;

/**
 *
 */
class CoursesTechnologies extends \app\models\Base {
    protected $tableName = 'coursesTechnologies';

    /**
     *
     */
    public function getCourseTechnologies($courseId) {
        $technologies = $this->findAllBy('course', $courseId);
        
        return array_map(function ($v) {
            $technologyId = $v['technology'];
            return $this->app['technologies.model']->findBy('id', $technologyId);
        }, $technologies);
    }

    /**
     *
     */
    // public function update() {
    //     $records = parent::update();
        
    //     $courses = $this->app['courses.model']->getAll();

    //     foreach ($courses as &$course) {
    //         if (!empty($course['id'])) {
    //             $courses['technologies'] = $this->getCourseTechnologies($course['id']);
    //         }
    //     }

    //     $this->app['courses.model']->setRecords($courses);

    //     return $records;
    // }
}