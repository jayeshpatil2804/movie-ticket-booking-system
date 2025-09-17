<?php

namespace App\Models;

use CodeIgniter\Model;

class ShowModel extends Model
{
    protected $table = 'shows';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['movie_id', 'screen_id', 'show_time', 'ticket_price', 'status']; // Updated to use 'ticket_price'

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'movie_id'  => 'required|integer',
        'screen_id' => 'required|integer',
        'show_time' => 'required|valid_date',
        'ticket_price' => 'required|decimal', // Updated to use 'ticket_price'
    ];

    public function getShowsWithDetails()
    {
        return $this->select('shows.*, movies.title as movie_title, screens.name as screen_name, cinemas.name as cinema_name, shows.ticket_price as price') // Added alias 'price' for the view
                    ->join('movies', 'movies.id = shows.movie_id')
                    ->join('screens', 'screens.id = shows.screen_id')
                    ->join('cinemas', 'cinemas.id = screens.cinema_id')
                    ->orderBy('show_time', 'ASC')
                    ->findAll();
    }
    
    public function getShowWithDetails(int $id)
    {
        return $this->select('shows.*, movies.title as movie_title, screens.name as screen_name, screens.cinema_id, cinemas.name as cinema_name, shows.ticket_price as price') // Added alias 'price' for the view
                    ->join('movies', 'movies.id = shows.movie_id')
                    ->join('screens', 'screens.id = shows.screen_id')
                    ->join('cinemas', 'cinemas.id = screens.cinema_id')
                    ->where('shows.id', $id)
                    ->first();
    }
}