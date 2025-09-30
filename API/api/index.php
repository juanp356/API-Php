<?php
    //encabezados
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json; charset=UTF-8');
    //metodo http
    header('Access-Control-Allow-Methods: POST,GET,PUT,DELETE');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');


    include_once  'config/Database.php'; 
    include_once  'models/users.php'; 
    include_once  'controllers/userController.php'; 
    include_once  'models/products.php';
    include_once  'controllers/productController.php';

    $database = new Database();
    $db = $database->connect();

    if($db == null){
        http_response_code(503); // servicio no disponible 
        echo json_encode(array('message' => 'Error de conexion a
         la base de datos'));
        exit;
    }

    //obtenemos el metodo http
    $method = $_SERVER['REQUEST_METHOD'];

    $uri = [];
    if(isset($_GET['url'])){
        $uri = explode('/', $_GET['url']);
    }


    $resource = isset($uri[0]) ? $uri[0] : null;
    $id = isset($uri[1]) ? $uri[1] : null;

    switch ($resource) {
    case 'users':
        $userModel = new User($db);
        $userController = new UserController($userModel);
        $userController->processRequest($method, $id);
        break;

    case 'products':
        $productModel = new Products($db);
        $productController = new ProductController($productModel);
        $productController->processRequest($method, $id);
        break;

    default:
        http_response_code(404);
        echo json_encode(['message' => 'Recurso no encontrado']);
        break;
}

