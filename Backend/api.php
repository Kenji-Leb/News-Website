<?php
    header("Content-Type: application/json");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
    header("Access-Control-Allow-Headers: Content-Type");
    header('Content-Type: application/json');

    $mysqli = new mysqli('localhost', 'root', "", "todo");

    if($mysqli->connection_error){
        die("Connection Error (" . $mysqli->connect_errno . ')' . $mysqli->connect_error);
    }

    $request_method = $_SERVER["REQUEST_METHOD"];
    // GET
    // POST
    // PUT
    // DELETE

    switch ($request_method) {
        case 'GET':
            if(!empty($_GET["id"])){
                $id = intval($_GET["id"]);
                $response = getTodo($id);
                echo json_encode($response);
            }else{
                $response = getAllTodos();
                echo json_encode($response);
            }
            break;
        case 'POST':
            if(!empty($_POST["text"])){
                $text= $_POST["text"];
                $response = createTodo($text);
                echo json_encode($response);
            }else{
                echo json_encode([
                    "status"=>"text is requried",
                ]);
            }

            break;
        case 'PUT':
            if(!empty($_GET["id"])){
                $id = intval($_GET["id"]);
                $response = toggleTodoStatus($id);
                echo json_encode($response);
            }else{
                echo json_encode([
                    "status"=>"something went wrong",
                ]);
            }
            break;
        case 'DELETE':
            if(!empty($_GET["id"])){
                $id = intval($_GET["id"]);
                $response = deleteTodo($id);
                echo json_encode($response);
            }else{
                echo json_encode([
                    "status"=>"something went wrong",
                ]);
            }
            break;
        
        default:
            echo json_encode([
                "status"=>"something went wrong",
            ]);
            break;
    }

    function getAllTodos(){
        global $mysqli;
        $query = $mysqli->prepare("SELECT * FROM todo");
        $query->execute();
        $query->store_result();
        $num_rows = $query->num_rows();

        if($num_rows == 0) {
            $response["status"] = "No todos";
        }else{
            $todos = [];
            $query->bind_result($id, $text, $done);
            while($query->fetch()){
                $todo = [
                    'id' => $id,
                    'text' => $text,
                    'done' => $done
                ];

                $todos[] = $todo;
            }

            $response["status"] = "Success";
            $response["todos"] = $todos;
        }

        return $response;
    }

    function getTodo($id){
        global $mysqli;
        $query = $mysqli->prepare("SELECT * FROM todo WHERE id = ?");
        $query->bind_param("i", $id);
        $query->execute();
        $query->store_result();
        
        $query->bind_result($id, $text, $done);
        $query->fetch();

        $response["status"] = "Success";
        $response["todo"] = [
            "id" => $id,
            "text" => $text,
            "done" => $done
        ];

        return $response;
    }

    function createTodo($text){
        global $mysqli;
        $response;
        $query = $mysqli->prepare("INSERT INTO todo (text) VALUES (?)");
        $query->bind_param("s", $text);
        if($query->execute()){
            $response["status"] = "Success";
        }else{
            $response["status"] = "Failed";
        }

        return $response;
    }

    function toggleTodoStatus($id){
        global $mysqli;

        $todo = getTodo($id);
        $new_status = $todo["todo"]["done"] == 1 ? 0 : 1;
        $response;
        $query = $mysqli->prepare("UPDATE todo SET done = ? WHERE id = ?");
        $query->bind_param("ii", $new_status, $id);
        if($query->execute()){
            $response["status"] = "Success";
        }else{
            $response["status"] = "Failed";
        }

        return $response;
    }

    function deleteTodo($id){
        global $mysqli;
        $query = $mysqli->prepare("DELETE FROM todo WHERE id = ?");
        $query->bind_param("i", $id);
        $query->execute();
        $query->store_result();

        $response["status"] = "Success";

        return $response;
    }
