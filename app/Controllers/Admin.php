<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Admin extends Controller
{
    public function index()
    {
        // Use the new admin header for the dashboard
        echo view('templates/admin_header');
        echo view('admin/dashboard');
        echo view('templates/footer');
    }
}