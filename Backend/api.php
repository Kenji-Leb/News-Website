<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

$mysqli = new mysqli('localhost', 'root', "", "newswebsitedb");

if ($mysqli->connect_error) {
    die("Connection Error (" . $mysqli->connect_errno . ')' . $mysqli->connect_error);
}

$request_method = $_SERVER["REQUEST_METHOD"];

switch ($request_method) {
    case 'GET':
        $response = getAllNews();
        echo json_encode($response);
        break;
    default:
        echo json_encode([
            "status" => "Invalid request method",
        ]);
        break;
}

function getAllNews()
{
    global $mysqli;
    $query = $mysqli->query("SELECT * FROM news");
    $news = [];

    while ($row = $query->fetch_assoc()) {
        $newsItem = [
            'id' => $row['news_id'],
            'title' => $row['news_name'],
            'text' => $row['news_description']
        ];

        $news[] = $newsItem;
    }

    return [
        "status" => "Success",
        "news" => $news
    ];
}
