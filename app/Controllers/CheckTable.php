<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\Database\ConnectionInterface;

class CheckTable extends Controller
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $query = $this->db->query("SHOW COLUMNS FROM movies");
        $columns = $query->getResultArray();
        
        echo "<pre>Movies Table Structure:\n";
        print_r($columns);
        
        // Also check if there are any records
        $query = $this->db->query("SELECT * FROM movies LIMIT 1");
        $movie = $query->getRowArray();
        
        echo "\nSample Movie Record:\n";
        print_r($movie);
        
        echo "</pre>";
    }
}
