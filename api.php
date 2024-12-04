<?php
include_once 'config.php';
include_once 'database.php';
include_once 'student.php';

$database = new Database();
$db = $database->getConnection();
$student = new Student($db);

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    
    if (isset($data->action)) {
        switch ($data->action) {
            case 'create':
                $student->nim = $data->nim;
                $student->name = $data->name;
                $student->email = $data->email;
                $student->phone = $data->phone;
                $student->major = $data->major;
                
                if ($student->create()) {
                    echo json_encode(["message" => "Student created successfully."]);
                } else {
                    echo json_encode(["message" => "Unable to create student."]);
                }
                break;

            case 'update':
                $student->id = $data->id;
                $student->nim = $data->nim;
                $student->name = $data->name;
                $student->email = $data->email;
                $student->phone = $data->phone;
                $student->major = $data->major;
                
                if ($student->update()) {
                    echo json_encode(["message" => "Student updated successfully."]);
                } else {
                    echo json_encode(["message" => "Unable to update student."]);
                }
                break;

            case 'delete':
                $student->id = $data->id;
                
                if ($student->delete()) {
                    echo json_encode(["message" => "Student deleted successfully."]);
                } else {
                    echo json_encode(["message" => "Unable to delete student."]);
                }
                break;
        }
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        $student->id = $_GET['id'];
        $stmt = $student->readOne();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($row);
    } else {
        $stmt = $student->read();
        $students = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($students, $row);
        }
        echo json_encode($students);
    }
}
?>