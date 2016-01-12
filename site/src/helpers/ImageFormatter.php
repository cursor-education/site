<?php
namespace app\helpers;

class ImageFormatter {

    /**
     *
     */
    static public function prepare($url) {
        // local
        if (strpos($url, '/') === 0) {
            return $url;
        }

        if (strpos($url, 'http') !== false) {
            $data = file_get_contents($url);
            $data = base64_encode($data);

            return sprintf('data:image/png;base64,%s', $data);
        }

        return $url;
    }
}