<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Category.php';

$database = new Database();
$db = $database->connect();
$category = new Category($db);

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {

    case 'GET':
        if (isset($_GET['id'])) {
            $category->id = $_GET['id'];
            $stmt = $category->read_single();

            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                echo json_encode($row);
            } else {
                echo json_encode(['message' => 'category_id Not Found']);
            }
        } else {
            $stmt = $category->read();

            if ($stmt->rowCount() > 0) {
                $categories_arr = [];
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $categories_arr[] = $row;
                }
                echo json_encode($categories_arr);
            } else {
                echo json_encode(['message' => 'category_id Not Found']);
            }
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'));

        if (empty($data->category)) {
            echo json_encode(['message' => 'Missing Required Parameters']);
            break;
        }

        $category->category = $data->category;

        if ($category->create()) {
            echo json_encode([
                'id'       => $category->id,
                'category' => $category->category
            ]);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'));

        if (empty($data->category)) {
            echo json_encode(['message' => 'Missing Required Parameters']);
            break;
        }

        if (empty($data->id)) {
            echo json_encode(['message' => 'Missing Required Parameters']);
            break;
        }

        $category->id = $data->id;
        $category->category = $data->category;

        // Check category exists
        $stmt = $category->read_single();
        if ($stmt->rowCount() === 0) {
            echo json_encode(['message' => 'category_id Not Found']);
            break;
        }

        if ($category->update()) {
            echo json_encode([
                'id'       => $category->id,
                'category' => $category->category
            ]);
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents('php://input'));

        if (empty($data->id)) {
            echo json_encode(['message' => 'Missing Required Parameters']);
            break;
        }

        $category->id = $data->id;

        // Check category exists
        $stmt = $category->read_single();
        if ($stmt->rowCount() === 0) {
            echo json_encode(['message' => 'category_id Not Found']);
            break;
        }

        try {
            if ($category->delete()) {
                echo json_encode(['id' => $category->id]);
            }
        } catch (PDOException $e) {
            echo json_encode(['message' => 'Cannot delete — category is referenced by existing quotes']);
        }
        break;
}
