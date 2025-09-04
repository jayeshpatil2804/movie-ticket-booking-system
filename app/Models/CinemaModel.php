<?php

namespace App\Models;

use CodeIgniter\Model;

class CinemaModel extends Model
{
    protected $table = 'cinemas';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'address'];
}