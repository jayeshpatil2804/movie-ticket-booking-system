<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Test extends Controller
{
    public function index()
    {
        $db = \Config\Database::connect();
        $query = $db->query("SHOW COLUMNS FROM movies");
        $columns = $query->getResultArray();
        
        echo "<h2>Movies Table Structure</h2>";
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>Column</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        
        $requiredColumns = [
            'id', 'title', 'description', 'duration', 'genre', 'language', 
            'certificate', 'director', 'cast', 'poster_url', 'trailer_url', 
            'status', 'is_featured', 'is_active', 'created_at', 'updated_at', 'deleted_at'
        ];
        
        $missingColumns = $requiredColumns;
        
        foreach ($columns as $col) {
            echo "<tr>";
            echo "<td>{$col['Field']}</td>";
            echo "<td>{$col['Type']}</td>";
            echo "<td>{$col['Null']}</td>";
            echo "<td>{$col['Key']}</td>";
            echo "<td>" . ($col['Default'] ?? 'NULL') . "</td>";
            echo "<td>{$col['Extra']}</td>";
            echo "</tr>";
            
            // Remove from missing columns if found
            $key = array_search($col['Field'], $missingColumns);
            if ($key !== false) {
                unset($missingColumns[$key]);
            }
        }
        echo "</table>";
        
        if (!empty($missingColumns)) {
            echo "<h3>Missing Columns:</h3>";
            echo "<ul>";
            foreach ($missingColumns as $col) {
                echo "<li>$col</li>";
            }
            echo "</ul>";
        }
    }
}