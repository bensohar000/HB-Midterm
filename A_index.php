<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Author.php';

$database = new Database();
$db = $database->connect();
$author = new Author($db);

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {

    case 'GET':
        if (isset($_GET['id'])) {
            $author->id = $_GET['id'];
            $stmt = $author->read_single();

            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                echo json_encode($row);
            } else {
                echo json_encode(['message' => 'author_id Not Found']);
            }
        } else {
            $stmt = $author->read();

            if ($stmt->rowCount() > 0) {
                $authors_arr = [];
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $authors_arr[] = $row;
                }
                echo json_encode($authors_arr);
            } else {
                echo json_encode(['message' => 'author_id Not Found']);
            }
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'));

        if (empty($data->author)) {
            echo json_encode(['message' => 'Missing Required Parameters']);
            break;
        }

        $author->author = $data->author;

        if ($author->create()) {
            echo json_encode([
                'id'     => $author->id,
                'author' => $author->author
            ]);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'));

        if (empty($data->author)) {
            echo json_encode(['message' => 'Missing Required Parameters']);
            break;
        }

        if (empty($data->id)) {
            echo json_encode(['message' => 'Missing Required Parameters']);
            break;
        }

        $author->id = $data->id;
        $author->author = $data->author;

        // Check author exists
        $stmt = $author->read_single();
        if ($stmt->rowCount() === 0) {
            echo json_encode(['message' => 'author_id Not Found']);
            break;
        }

        if ($author->update()) {
            echo json_encode([
                'id'     => $author->id,
                'author' => $author->author
            ]);
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents('php://input'));

        if (empty($data->id)) {
            echo json_encode(['message' => 'Missing Required Parameters']);
            break;
        }

        $author->id = $data->id;

        // Check author exists
        $stmt = $author->read_single();
        if ($stmt->rowCount() === 0) {
            echo json_encode(['message' => 'author_id Not Found']);
            break;
        }

        try {
            if ($author->delete()) {
                echo json_encode(['id' => $author->id]);
            }
        } catch (PDOException $e) {
            echo json_encode(['message' => 'Cannot delete — author is referenced by existing quotes']);
        }
        break;
}
