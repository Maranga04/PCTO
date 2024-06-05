<?php
class DB {
    private static $instance = NULL;

    public static function get() {
        if (self::$instance === NULL) {
            self::$instance = new PDO('mysql:host=localhost;dbname=pcto', 'root', '', [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        }
        return self::$instance;
    }
}
?>
