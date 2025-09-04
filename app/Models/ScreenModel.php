<?php

namespace App\Models;

use CodeIgniter\Model;

class ScreenModel extends Model
{
    protected $table = 'screens';
    protected $primaryKey = 'id';
    protected $allowedFields = ['cinema_id', 'name', 'rows', 'seats_per_row'];

    public function getScreenWithDetails(int $id)
    {
        return $this->select('screens.*, cinemas.name as cinema_name')
                    ->join('cinemas', 'cinemas.id = screens.cinema_id')
                    ->where('screens.id', $id)
                    ->first();
    }
    
    public function getScreensWithDetails()
    {
        return $this->select('screens.*, cinemas.name as cinema_name')
                    ->join('cinemas', 'cinemas.id = screens.cinema_id')
                    ->findAll();
    }
}