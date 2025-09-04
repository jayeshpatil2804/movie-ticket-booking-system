<?php

namespace App\Models;

use CodeIgniter\Model;

class BookingModel extends Model
{
    protected $table = 'bookings';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['user_id', 'show_id', 'total_price', 'booking_date'];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getBookingsWithDetails($userId)
    {
        return $this->select('bookings.*, movies.title, shows.show_time, cinemas.name as cinema_name, screens.name as screen_name')
                    ->join('shows', 'shows.id = bookings.show_id')
                    ->join('movies', 'movies.id = shows.movie_id')
                    ->join('screens', 'screens.id = shows.screen_id')
                    ->join('cinemas', 'cinemas.id = screens.cinema_id')
                    ->where('bookings.user_id', $userId)
                    ->orderBy('booking_date', 'DESC')
                    ->findAll();
    }
}