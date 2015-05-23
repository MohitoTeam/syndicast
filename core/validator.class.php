<?php

class validator {

    public static function email($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) ? true : false;
    }

    public static function url($url) {
        return filter_var($url, FILTER_VALIDATE_URL) ? true : false;
    }

    public static function isEmpty($data) {
        return empty($data) ? true : false;
    }

}

?>