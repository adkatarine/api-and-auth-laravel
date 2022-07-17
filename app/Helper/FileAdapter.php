<?php

namespace App\Helper;

class FileAdapter {

    public static function file(Object $file) {
        $type = $file->extension();

        if($type == 'json') {
            return self::json($file);
        } else {
            return self::xml($file);
        }
    }

    private static function json($file) {
        $json = file_get_contents($file);
        return json_decode($json)->products;
    }

    private static function xml($file) {
        return simplexml_load_file($file);
    }
}

?>
