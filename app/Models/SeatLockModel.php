<?php

namespace App\Models;

use CodeIgniter\Model;

class SeatLockModel extends Model
{
    protected $table = 'seat_locks';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['show_id', 'user_id', 'seat_number', 'locked_until'];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}
