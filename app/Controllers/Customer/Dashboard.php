<?php

namespace App\Controllers\Customer;

use App\Controllers\BaseController;
use App\Models\MovieModel;
use App\Models\BookingModel;
use App\Models\ShowModel;

class Dashboard extends BaseController
{
    protected $movieModel;
    protected $bookingModel;
    protected $showModel;

    public function __construct()
    {
        $this->movieModel = new MovieModel();
        $this->bookingModel = new BookingModel();
        $this->showModel = new ShowModel();
        helper(['form', 'url']);
    }

    public function index()
    {
        // Redirect to booking page for now
        return redirect()->to('/booking');
    }

    public function admin()
    {
        // Check if user is admin (you should implement proper authentication)
        if (!session()->get('is_admin')) {
            return redirect()->to('/')->with('error', 'Access denied');
        }

        $data = [
            'totalMovies' => $this->movieModel->countAll(),
            'totalBookings' => $this->bookingModel->countAll(),
            'totalShows' => $this->showModel->countAll(),
            'recentMovies' => $this->movieModel->orderBy('created_at', 'DESC')->findAll(5),
            'recentBookings' => $this->bookingModel->select('bookings.*, movies.title as movie_title')
                                                ->join('shows', 'shows.id = bookings.show_id')
                                                ->join('movies', 'movies.id = shows.movie_id')
                                                ->orderBy('bookings.created_at', 'DESC')
                                                ->findAll(5)
        ];

        return view('templates/header', ['title' => 'Admin Dashboard'])
             . view('admin/dashboard', $data)
             . view('templates/footer');
    }
}
