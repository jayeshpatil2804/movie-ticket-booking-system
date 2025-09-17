<?php

namespace App\Controllers\Customer;

use App\Controllers\BaseController;
use App\Models\BookingModel;

class Bookings extends BaseController
{
    protected $bookingModel;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
    }

    public function list()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/auth/login');
        }

        $bookings = $this->bookingModel
            ->select('bookings.*, shows.show_time, movies.title as movie_title, cinemas.name as cinema_name, screens.name as screen_name')
            ->join('shows', 'shows.id = bookings.show_id')
            ->join('movies', 'movies.id = shows.movie_id')
            ->join('screens', 'screens.id = shows.screen_id')
            ->join('cinemas', 'cinemas.id = screens.cinema_id')
            ->where('bookings.user_id', $userId)
            ->orderBy('bookings.created_at', 'DESC')
            ->findAll();

        return view('templates/header', ['title' => 'My Bookings'])
             . view('customer/bookings/list', ['bookings' => $bookings])
             . view('templates/footer');
    }
}
