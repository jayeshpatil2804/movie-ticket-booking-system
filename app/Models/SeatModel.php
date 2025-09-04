<?php

namespace App\Models;

use CodeIgniter\Model;

class SeatModel extends Model
{
    protected $table = 'seats';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['screen_id', 'row_number', 'seat_number', 'status'];
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getSeatsByScreenId($screenId)
    {
        return $this->where('screen_id', $screenId)->orderBy('row_number', 'ASC')->orderBy('seat_number', 'ASC')->findAll();
    }
}