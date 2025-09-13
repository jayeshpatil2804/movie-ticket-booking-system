<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\MovieModel;
use App\Models\ShowModel;
use App\Models\BookingModel;
use App\Models\UserModel;

class Admin extends Controller
{
    protected $movieModel;
    protected $showModel;
    protected $bookingModel;
    protected $userModel;

    public function __construct()
    {
        $this->movieModel = new MovieModel();
        $this->showModel = new ShowModel();
        $this->bookingModel = new BookingModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        // Get counts for dashboard
        $data = [
            'totalMovies' => $this->movieModel->countAllResults(),
            'totalShows' => $this->showModel->countAllResults(),
            'totalBookings' => $this->bookingModel->countAllResults(),
            'totalUsers' => $this->userModel->where('role', 'user')->countAllResults(),
            'recentMovies' => $this->movieModel->select('*')->orderBy('created_at', 'DESC')->findAll(5),
            'recentBookings' => $this->bookingModel->select('bookings.*, users.name as user_name, movies.title as movie_title')
                                                 ->join('users', 'users.id = bookings.user_id')
                                                 ->join('shows', 'shows.id = bookings.show_id')
                                                 ->join('movies', 'movies.id = shows.movie_id')
                                                 ->orderBy('bookings.booking_date', 'DESC')
                                                 ->findAll(5)
        ];

        // Use the new admin header for the dashboard
        echo view('templates/admin_header');
        echo view('admin/dashboard', $data);
        echo view('templates/footer');
    }
}