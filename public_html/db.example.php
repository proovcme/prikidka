<?php
class DB {
    private static $instance = null;
    public static function getConnection() {
        if (self::$instance === null) {
            $socket = '/var/run/mysql8-container/mysqld.sock';
            $port = '3308';
            $db   = 'your_database_name';
            $user = 'your_username';
            $pass = 'your_password';
            $dsn = "mysql:unix_socket=$socket;port=$port;dbname=$db;charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            try {
                self::$instance = new PDO($dsn, $user, $pass, $options);
            } catch (\PDOException $e) {
                die(json_encode(['error' => 'DB Error: ' . $e->getMessage()]));
            }
        }
        return self::$instance;
    }
}
?>