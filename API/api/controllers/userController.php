<?php

    class UserController{
        private $userModel;

        public function __construct($userModel)
        {
            $this->userModel = $userModel;
        }

        public function processRequest($method, $id){
            switch ($method) {
                case 'GET':
                    if($id === null){
                        $this->getAllUsers();
                    }
                    else
                    {
                        $this->getUser($id);
                    }    
                    break;
                case 'POST':
                    $this->create();
                    break;
                case 'PUT':
                    $this->update($id);
                    break;
                case 'DELETE':
                    $this->deleteUser($id);        
                    break;
                default:
                    http_response_code(405);
                    echo json_encode(array('message' => 'Metodo no permitido'));    
            }
        }

        //obtener todos los usuarios
        private function getAllUsers(){
           $result = $this->userModel->read();
           $num = $result->rowCount();
           if($num>0){
                $user_arr=[];
                while ($row=$result->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $user_item = array(
                        'id' => $id,
                        'name' => $name,
                        'email' => $email,
                        'created_at' => $created_at
                    );
                    array_push($user_arr,$user_item);
                }
                echo json_encode($user_arr);
           }else{
                echo json_encode(array('message' => 'No se encontraron usuarios'));
           }
        }// fin de getAllUsers

        //obetener usuario
        private function getUser($id){
            $this->userModel->id = $id;
            if($this->userModel->read_single()){
                $user_item=array('id' => $this->userModel->id, 'name' =>$this->userModel->name,'email' => $this->userModel->email,
                'created_at' =>$this->userModel->created_at);
                echo json_encode($user_item);
            }
            else
            {
                echo json_encode(array('message' => 'no se encuentra el usuario'));
            }
        }


        private function create(){
            $data = json_decode(file_get_contents("php://input"), true);
            $this->userModel->name = $data['name'];
            $this->userModel->email = $data['email'];
            if($this->userModel->create()){
                 echo json_encode(array('message' => 'Usuario creado'));
            }
            else{
                 echo json_encode(array('message' => 'Error al crear el usuario'));
            }
        }
        private function update($id){
            $data = json_decode(file_get_contents("php://input"), true);
            $this->userModel->id = $data['id'];
            $this->userModel->name = $data['name'];
            $this->userModel->email = $data['email'];
            if($this->userModel->update()){
                 echo json_encode(array('message' => 'Usuario actualizado'));
            }
            else{
                 echo json_encode(array('message' => 'Error al actualizar el usuario'));
            }
        }
        
        private function deleteUser($id){
            $this->userModel->id = $id;
            if($this->userModel->delete())
            {
                 echo json_encode(array('message' => 'Usuario eliminado'));
            }
            else{
                 echo json_encode(array('message' => 'Error al eliminar el usuario'));
            }
        }
    }

