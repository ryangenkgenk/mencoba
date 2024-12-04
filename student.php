<?php
class Student {
    private $conn;
    private $table_name = "students";

    public $id;
    public $nim;
    public $name;
    public $email;
    public $phone;
    public $major;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                SET nim=:nim, name=:name, email=:email, phone=:phone, major=:major";

        $stmt = $this->conn->prepare($query);

        $this->nim = htmlspecialchars(strip_tags($this->nim));
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->phone = htmlspecialchars(strip_tags($this->phone));
        $this->major = htmlspecialchars(strip_tags($this->major));

        $stmt->bindParam(":nim", $this->nim);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":phone", $this->phone);
        $stmt->bindParam(":major", $this->major);

        try {
            if($stmt->execute()) {
                return true;
            }
            return false;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        
        try {
            $stmt->execute();
            return $stmt;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        
        try {
            $stmt->execute();
            return $stmt;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . "
                SET nim=:nim, name=:name, email=:email, phone=:phone, major=:major
                WHERE id=:id";

        $stmt = $this->conn->prepare($query);

        $this->nim = htmlspecialchars(strip_tags($this->nim));
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->phone = htmlspecialchars(strip_tags($this->phone));
        $this->major = htmlspecialchars(strip_tags($this->major));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(":nim", $this->nim);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":phone", $this->phone);
        $stmt->bindParam(":major", $this->major);
        $stmt->bindParam(":id", $this->id);

        try {
            if($stmt->execute()) {
                return true;
            }
            return false;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(":id", $this->id);

        try {
            if($stmt->execute()) {
                return true;
            }
            return false;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}
?>