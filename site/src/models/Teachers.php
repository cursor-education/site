<?php
namespace app\models;

use app\helpers\GoogleSpreadsheetsApi;
use app\helpers\ImageFormatter;

/**
 *
 */
class Teachers extends \app\models\Base {
    protected $tableName = 'teachers';

    /**
     *
     */
    public function formatRecord($record) {
        if (!($record = parent::formatRecord($record))) {
            return null;
        }

        $record['courses'] = $this->app['teachersCourses.model']->getTeacherCourses($record['id']);
        $record['links'] = $this->app['teachersLinks.model']->getTeacherLinks($record['id']);

        if (is_array($info = $this->updatePersonalInformation($record))) {
            $record = array_merge($record, $info);
        }

        return $record;
    }

    /**
     *
     */
    public function updatePersonalInformation($record) {
        if (empty($record['info_key'])) {
            return null;
        }

        list($infoGoogleKey, $infoGoogleGid) = explode(';', $record['info_key'], 2);
        
        $infoRecords = GoogleSpreadsheetsApi::fetch(
            $infoGoogleKey,
            $infoGoogleGid
        );

        if (empty($infoRecords[1])) {
            return null;
        }

        $info = $infoRecords[1];
        $info = parent::formatRecord($info);

        $result = array(
            'desc_short' => $info['short_description'],
            'desc_full' => $info['full_description'],
            'photo' => $info['photo_url'],
            'background_image' => $info['background_image_url'],
            'update_secret' => $info['secret'],
        );
        $result['desc_full'] = str_replace('\n', '<br>', $result['desc_full']);

        return $result;
    }

    /**
     *
     * @return array
     */
    public function filterByCourseGroup($courseGroup) {
        $teachers = $this->getAll();
        $teachersByCourseGroup = array();

        foreach ($teachers as $teacherKey => $teacher) {
            if (in_array($courseGroup, $teacher['courses'])) {
                $teachersByCourseGroup[$teacherKey] = $teacher;
            }
        }

        return $teachersByCourseGroup;
    }
}