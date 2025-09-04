<?php

namespace App\Models;

use CodeIgniter\Model;

class BookingSeatModel extends Model
{
    protected $table = 'booking_seats';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['booking_id', 'seat_id'];
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}