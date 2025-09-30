<?php

class User 
{
    private $conn;
    private $table_name = 'users';

    public $id;
    public $name;
    public $email;
    public $created_at;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function read(){
        $query = 'SELECT * FROM '. $this->table_name . ' ORDER BY created_at DESC';
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }// fin del metodo read

    public function read_single(){
        $query = 'SELECT id,name,email, created_at FROM ' . $this->table_name . ' WHERE id = ? LIMIT 1';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row =  $stmt->fetch(PDO::FETCH_ASSOC);
        if($row){
            $this->name = $row['name'];
            $this->email = $row['email'];
            $this->created_at = $row['created_at'];
            return true;
        }
        return false;
    }// fin del metodo read_single

    public function create(){
        $query = 'INSERT INTO ' . $this->table_name . ' (name,email) VALUES (:name, :email)';
        $stmt = $this->conn->prepare($query);

        //limpiar datos
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));

        // preparamos los datos.
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);         


        if($stmt->execute())
        {
            return true;
        }
        return false;
    }// fin del metodo create

    public function update(){
        $query = 'UPDATE ' . $this->table_name . ' SET name = :name, email = :email WHERE id = :id';
        $stmt = $this->conn->prepare($query);

        //limpiar datos
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->id = htmlspecialchars(strip_tags($this->id));

         // preparamos los datos
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':id', $this->id);

        if($stmt->execute())
        {
            return true;
        }
        return false;

    }//fin del metodo update


    public function delete(){
        $query = 'DELETE FROM ' . $this->table_name . ' WHERE id = :id';
        $stmt = $this->conn->prepare($query);

        //limpiar datos
        $this->id = htmlspecialchars(strip_tags($this->id));

        // preparamos los datos
        $stmt->bindParam(':id', $this->id);

        if($stmt->execute())
        {
            return true;
        }
        return false;

    }//fin del metodo delete






}//fin de la clase