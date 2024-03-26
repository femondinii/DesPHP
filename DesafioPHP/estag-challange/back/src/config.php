<?php

class Config {
    public $db;

    function __construct(){
        $this->connectDatabase();
    }

    function connectDatabase(){
        try {
            $this->db = new PDO("pgsql:host=pgsql_desafio;dbname=applicationphp;", "root", "root");
        } catch(PDOException $erro) {
            echo "ERRO =>" . $erro->getMessage();
        }
    }
}