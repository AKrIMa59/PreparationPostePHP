<?php

require 'config/configBDD.php';

class mysql {
    private $host;
    private $user;
    private $password;
    private $database;
    private $port;
    private $mysqli;

    function __construct() {
        $this->host = $GLOBALS['GLPI_host'];
        $this->user = $GLOBALS['GLPI_user'];
        $this->password = $GLOBALS['GLPI_password'];
        $this->database = $GLOBALS['GLPI_database'];
        $this->port = $GLOBALS['GLPI_port'];
    }

    public function displayConfig() {
        echo "Host: " . $this->host . "<br>";
        echo "User: " . $this->user . "<br>";
        echo "Password: " . $this->password . "<br>";
        echo "Database: " . $this->database . "<br>";
        echo "Port: " . $this->port . "<br>";
    }

    public function connect() {
        $mysqli = new mysqli($this->host, $this->user, $this->password, $this->database, $this->port);
        if ($mysqli->connect_errno) {
            echo "Failed to connect to MySQL: " . $mysqli->connect_error;
        }
        $this->mysqli = $mysqli;
        return $mysqli;
    }

    public function query($query) {
        $mysqli = $this->connect();
        $result = $mysqli->query($query);
        $mysqli->close();
        return $result;
    }

    public function queryArray($query) {
        $result = $this->query($query);
        $array = array();
        while ($row = $result->fetch_assoc()) {
            array_push($array, $row);
        }
        return $array;
    }

    public function queryObject($query) {
        $result = $this->query($query);
        $object = $result->fetch_object();
        return $object;
    }

    public function insertQuery($query){
        $mysqli = $this->connect();
        $mysqli->query($query);
        return $mysqli->insert_id;
    }

    public function insertQueryObject($query, $object){
        $mysqli = $this->connect();
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param($object->bind, $object->values);
        $stmt->execute();
        $mysqli->close();
        return $mysqli->insert_id;
    }

    public function escapeString($string){
        $mysqli = $this->connect();
        $string = $mysqli->real_escape_string($string);
        $mysqli->close();
        return $string;
    }
}
