<?php
namespace app\services;

class HostEntity {
    public $key;
}

class HostService {

    //
    public function parse() {
        $host = null;

        if (isset($_SERVER['HTTP_X_FORWARDED_HOST'])) {
            $host = $_SERVER['HTTP_X_FORWARDED_HOST'];
        }

        $hostLayoutMap = array(
            'webui',
            'java',
        );

        $host = explode('.', $host);
        $hostKey = $host[0];

        if (!in_array($hostKey, $hostLayoutMap)) {
            return null;
        }

        $entity = new HostEntity;
        $entity->key = $hostKey;

        return $entity;
    }
}
