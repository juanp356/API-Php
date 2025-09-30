<?php 

class Database 
{
    private $host = 'localhost';
    private $db_name = 'apidb';
    private $username = 'developer';
    private $password = 'developer';

    static public function connect(){
        $link = new PDO("mysql:host=localhost;dbname=apidb","developer","developer");
        return $link;
    }

}