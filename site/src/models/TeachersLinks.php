<?php
namespace app\models;

/**
 *
 */
class TeachersLinks extends \app\models\Base {
    protected $tableName = 'teachersLinks';

    /**
     *
     */
    public function getTeacherLinks($teacherId) {
        $links = $this->findAllBy('teacher', $teacherId);

        $links = array_map(function ($v) {
            if ($v['active'] != '1') {
                return null;
            }

            return array(
                'type' => isset($v['type']) ? $v['type'] : '',
                'url' => isset($v['url']) ? $v['url'] : '',
            );
        }, $links);

        $links = array_filter($links);

        return $links;
    }

    /**
     *
     */
    public function update() {
        $records = parent::update();

        $teachers = $this->app['teachers.model']->getAll();

        foreach ($teachers as &$teacher) {
            $teacher['links'] = $this->getTeacherLinks($teacher['id']);
        }

        $this->app['teachers.model']->setRecords($teachers);

        return $records;
    }
}