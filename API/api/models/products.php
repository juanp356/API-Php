<?php


class Products{

    //variable para guardar la conexion a la base de datos
    private $conn;
    //nombre de la tabla en la base de datos
    private $table_name = 'products';

    //atributos del modelo
    public $id;
    public $name;
    public $quantity;
    public $price;

    //contructor de la clase producto
    public function __construct($db)
    {
        $this->conn = $db; //aqui viene el PDO en el cual nos conectamos al MySQL
    }

    //metodo para listar los productos
    public function readProducts(){
        $query = 'SELECT * FROM '. $this->table_name . ' ORDER BY id DESC'; // aqui tenemos la consulta para la base de datos 
        $stmt = $this->conn->prepare($query);// aqui preparamos la consulta
        $stmt->execute();//aqui se ejecuta
        return $stmt;// y aqui la retornamos si se ejecuta de manera correcta
    }// fin del metodo 

    //metodo para buscar por id
    public function read_singleProducts(){
        $query = 'SELECT id,name,quantity, price FROM ' . $this->table_name . ' WHERE id = ? LIMIT 1';//consulta SQL
         $stmt = $this->conn->prepare($query);//preparamos la consulta
        $stmt->bindParam(1, $this->id);//aqui se manda el id de esta clase a la consulta SQL(preparando el dato en este caso el id)
        $stmt->execute();// Aui ejecutamos la consulta
        $row =  $stmt->fetch(PDO::FETCH_ASSOC); // aqui sacamos una fila del resultado de la Query 
        if($row){ // aqui el row se ejecuta si recupero una fila de la bd con datos en este caso entraria al if y si no retorna falso
            $this->name = $row['name'];
            $this->quantity = $row['quantity'];
            $this->price = $row['price'];
            return true;
        }
        return false;
    }//fin del metodo read_single_products

    //metodo para crear un producto
    public function createProducts(){
        //consulta SQL
        $query = 'INSERT INTO ' . $this->table_name . ' (name,quantity,price) VALUES (:name, :quantity, :price)';
        //aqui preparamos la consulta SQL 
        $stmt = $this->conn->prepare($query);

        //limpiar datos que no deben ir como scripts js o demas y no se inserten cosas que no deben en la bd
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->quantity = htmlspecialchars(strip_tags($this->quantity));
        $this->price = htmlspecialchars(strip_tags($this->price));

        // preparamos los datos para mandarlos en la consulta SQL.
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':quantity', $this->quantity);
        $stmt->bindParam(':price',$this->price);         

        // aqui si el statement se ejecuta de manera correcta nos retorna true o si no false
        if($stmt->execute())
        {
            return true;
        }
        return false;
    }//fin del metodo create



    //metodo para actualizar un producto
    public function updateProducts(){
        //consulta SQL
        $query = 'UPDATE ' . $this->table_name . ' SET name = :name, quantity = :quantity, price = :price WHERE id = :id';
        //aqui preparamos la consulta SQL 
        $stmt = $this->conn->prepare($query);

        //limpiar datos que no deben ir como scripts js o demas y no se inserten cosas que no deben en la bd
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->quantity = htmlspecialchars(strip_tags($this->quantity));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // preparamos los datos para mandarlos en la consulta SQL.
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':quantity', $this->quantity);
        $stmt->bindParam(':price',$this->price); 
        $stmt->bindParam(':id', $this->id);        

        // aqui si el statement se ejecuta de manera correcta nos retorna true o si no false
        if($stmt->execute())
        {
            return true;
        }
        return false;
    }//fin del metodo update


     public function deleteProducts(){
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









}