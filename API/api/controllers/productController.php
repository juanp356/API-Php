<?php

    class ProductController{
        private $productModel; //Aqui guardamos la instancia del modelo producto

        public function __construct($productModel)
        {
            $this->productModel = $productModel; 
        }

        public function processRequest($method, $id){
            //metodo principal que procesa la peticion http segun lo que se necesite y si el id existe
            switch ($method) {
                case 'GET':
                    if($id === null){
                        $this->getAllProducts(); // si no hay id listamos todos los productos
                    }
                    else
                    {
                        $this->getProduct($id); // aqui busca un producto por id
                    }    
                    break;
                case 'POST':
                    $this->createProduct();//aqui creamos un nuevo producto
                    break;
                case 'PUT':
                    $this->updateProduct($id); // actualiza un producto por medio del id
                    break;
                case 'DELETE':
                    $this->deleteProduct($id);// elimina un producto por medio del id     
                    break;
                default:  // si no es ninguno de estos metodos suelta un 405 que es un metodo no soportado
                    http_response_code(405);
                    echo json_encode(array('message' => 'Metodo no permitido'));    
            }
        }

        //obtener todos los productos
        private function getAllProducts(){
           $result = $this->productModel->readProducts(); // aqui llama al metodo listar del modelo 
           $num = $result->rowCount();// aqui se lee la cantidad de filas devueltas
           if($num>0){
                $product_arr=[]; // aqui tenemos un array para guardar los productos
                while ($row=$result->fetch(PDO::FETCH_ASSOC)) { // por cada fila que se devuelva fetch trae un array con las columnas
                    extract($row); // aqui se convierten las claves del array en variables
                    $product_item = array(
                        'id' => $id, // aqui se usa la variable id 
                        'name' => $name, // aqui se usa la variable name
                        'quantity' => $quantity, // aqui se usa la variable quantity
                        'price' => $price // aqui se usa la variable price
                    );
                    array_push($product_arr,$product_item); //aqui agregamos el product_item al al product_arr
                }
                echo json_encode($product_arr);// Aqui mostramos el JSON con los productos
           }else{
                echo json_encode(array('message' => 'No se encontraron productos')); // y si no hay productos respondemos
           }
        }// fin de getAllProducts

        //obetener productos
        private function getProduct($id){
            $this->productModel->id = $id; // aqui le asignamos el id al modelo
            if($this->productModel->read_singleProducts()){ // aqui debe cargar los datos del modelo y nos tiene que devolver true si encontro algo
                $product_item=array('id' => $this->productModel->id, 'name' =>$this->productModel->name,'quantity' => $this->productModel->quantity,
                'price' =>$this->productModel->price); // aqui toma lo que son todos los datos del modelo
                echo json_encode($product_item);// aqui se manda la respuesta JSON
            }
            else
            {
                echo json_encode(array('message' => 'no se encuentra el producto')); // aqui respondemos si no se encontro algo
            } 
        }


        private function createProduct(){
            $data = json_decode(file_get_contents("php://input"), true); // aqui leemos la peticion y lo decodifica a un array asociativo
            $this->productModel->name = $data['name'];
            $this->productModel->quantity = $data['quantity'];  // Aqui se asigna desde el JSON al modelo
            $this->productModel->price = $data['price'];
            if($this->productModel->createProducts()){
                 echo json_encode(array('message' => 'Producto creado'));
            }
            else{
                 echo json_encode(array('message' => 'Error al crear el producto'));
            }
        }


        private function updateProduct($id){
            $data = json_decode(file_get_contents("php://input"), true);// aqui leemos la peticion y lo decodifica a un array asociativo
            $this->productModel->id = $data['id'];
            $this->productModel->name = $data['name'];
            $this->productModel->quantity = $data['quantity'];// Aqui se asigna desde el JSON al modelo
            $this->productModel->price = $data['price'];
            if($this->productModel->updateProducts()){
                 echo json_encode(array('message' => 'Producto actualizado'));
            }
            else{
                 echo json_encode(array('message' => 'Error al actualizar el producto'));
            }
        }
        
        private function deleteProduct($id){// asigna al modelo el id por el que se quiere eliminar
            $this->productModel->id = $id;
            if($this->productModel->deleteProducts())
            {
                 echo json_encode(array('message' => 'Producto eliminado'));
            }
            else{
                 echo json_encode(array('message' => 'Error al eliminar el producto'));
            }
        }

    }        