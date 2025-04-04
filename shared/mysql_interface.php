<?php

class MysqlInterface {
    private $conn_obj;

    public function __construct($database=null)
    {
        global $MYSQL_HOSTNAME, $MYSQL_USER_NAME, $MYSQL_USER_PASS, $MYSQL_PORT;

        $this->conn_obj = new mysqli($MYSQL_HOSTNAME, $MYSQL_USER_NAME, $MYSQL_USER_PASS, $database, $MYSQL_PORT);
    }

    public function query($query) {
        $res = $this->conn_obj->query($query, MYSQLI_USE_RESULT);
        if($res === false) {
            die($this->conn_obj->error);
        }

        return $res;
    }

    public function querySingle($query) {
        $res = $this->conn_obj->query($query, MYSQLI_USE_RESULT);
        if($res === false) {
            die($this->conn_obj->error);
        }

        $resData = $res->fetch_assoc();
        $res->close();
        return $resData;
    }

    public function prepared_insert($query, $bindings) {
        $this->conn_obj->execute_query($query, $bindings);
    }
}
