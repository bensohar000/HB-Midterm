<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Quote.php';

$database = new Database();
$db = $database->connect();
$quote = new Quote($db);

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {

    case 'GET':
        if (isset($_GET['id'])) {
            // GET single quote by id
            $quote->id = $_GET['id'];
            $stmt = $quote->read_single();

            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                echo json_encode($row);
            } else {
                echo json_encode(['message' => 'No Quotes Found']);
            }

        } else {
            // GET all quotes (with optional author_id / category_id filters)
            if (isset($_GET['author_id'])) {
                $quote->author_id = $_GET['author_id'];
            }
            if (isset($_GET['category_id'])) {
                $quote->category_id = $_GET['category_id'];
            }

            $stmt = $quote->read();

            if ($stmt->rowCount() > 0) {
                $quotes_arr = [];
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $quotes_arr[] = $row;
                }
                echo json_encode($quotes_arr);
            } else {
                echo json_encode(['message' => 'No Quotes Found']);
            }
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'));

        if (
            empty($data->quote) ||
            empty($data->author_id) ||
            empty($data->category_id)
        ) {
            echo json_encode(['message' => 'Missing Required Parameters']);
            break;
        }

        $quote->quote = $data->quote;
        $quote->author_id = $data->author_id;
        $quote->category_id = $data->category_id;

        if (!$quote->authorExists()) {
            echo json_encode(['message' => 'author_id Not Found']);
            break;
        }

        if (!$quote->categoryExists()) {
            echo json_encode(['message' => 'category_id Not Found']);
            break;
        }

        if ($quote->create()) {
            echo json_encode([
                'id'          => $quote->id,
                'quote'       => $quote->quote,
                'author_id'   => $quote->author_id,
                'category_id' => $quote->category_id
            ]);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'));

        if (
            empty($data->quote) ||
            empty($data->author_id) ||
            empty($data->category_id)
        ) {
            echo json_encode(['message' => 'Missing Required Parameters']);
            break;
        }

        if (empty($data->id)) {
            echo json_encode(['message' => 'Missing Required Parameters']);
            break;
        }

        $quote->id = $data->id;
        $quote->quote = $data->quote;
        $quote->author_id = $data->author_id;
        $quote->category_id = $data->category_id;

        if (!$quote->authorExists()) {
            echo json_encode(['message' => 'author_id Not Found']);
            break;
        }

        if (!$quote->categoryExists()) {
            echo json_encode(['message' => 'category_id Not Found']);
            break;
        }

        // Check quote exists
        $stmt = $quote->read_single();
        if ($stmt->rowCount() === 0) {
            echo json_encode(['message' => 'No Quotes Found']);
            break;
        }

        if ($quote->update()) {
            echo json_encode([
                'id'          => $quote->id,
                'quote'       => $quote->quote,
                'author_id'   => $quote->author_id,
                'category_id' => $quote->category_id
            ]);
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents('php://input'));

        if (empty($data->id)) {
            echo json_encode(['message' => 'Missing Required Parameters']);
            break;
        }

        $quote->id = $data->id;

        // Check quote exists
        $stmt = $quote->read_single();
        if ($stmt->rowCount() === 0) {
            echo json_encode(['message' => 'No Quotes Found']);
            break;
        }

        if ($quote->delete()) {
            echo json_encode(['id' => $quote->id]);
        }
        break;
}
