<?php
class Con {
    private $host = '195.35.53.20';
    private $username = 'u747325399_khwakha';
    private $password = 's49+cIZ#qU7';
    private $database = 'u747325399_kwakha';
    private $conn;

    public function connect() {
        $this->conn = null;

        try {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);
            if ($this->conn->connect_error) {
                throw new Exception("Connection failed: " . $this->conn->connect_error);
            }
        } catch(Exception $e) {
            die("Connection error: " . $e->getMessage());
        }

        return $this->conn;
    }
}


$universal_url = "https://grinpath.com";

?>