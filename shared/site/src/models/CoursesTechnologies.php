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
        $app = $this->app;
        $technologies = $this->findAllBy('course', $courseId);
        
        return array_map(function ($v) use ($app) {
            $technologyId = $v['technology'];
            return $app['technologies.model']->findBy('id', $technologyId);
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