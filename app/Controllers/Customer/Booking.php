<?php

namespace App\Controllers\Customer;

use App\Controllers\BaseController;
use App\Models\MovieModel;
use App\Models\ShowModel;
use App\Models\BookingModel;
use App\Models\ScreenModel;
use App\Models\CinemaModel;

class Booking extends BaseController
{
    protected $movieModel;
    protected $showModel;
    protected $bookingModel;
    protected $screenModel;
    protected $cinemaModel;
    protected $ticketPrice = 200; // Default ticket price in INR
    protected $seatsPerRow = 10;
    protected $seatRows = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];

    public function __construct()
    {
        $this->movieModel = new MovieModel();
        $this->showModel = new ShowModel();
        $this->bookingModel = new BookingModel();
        $this->screenModel = new ScreenModel();
        $this->cinemaModel = new CinemaModel();
        helper(['form', 'url']);
    }

    /**
     * Show list of all movies with featured and now showing sections
     */
    public function index()
    {
        // First, check if the status column exists in the movies table
        $db = \Config\Database::connect();
        $query = $db->query("SHOW COLUMNS FROM movies LIKE 'status'");
        $statusColumnExists = $query->getRow() !== null;
        
        $data = [
            'featuredMovies' => $this->movieModel->where('is_featured', 1)->findAll(4),
            'nowShowing' => $statusColumnExists 
                ? $this->movieModel->where('status', 'now_showing')->findAll(8)
                : $this->movieModel->findAll(8), // Fallback to all movies if status column doesn't exist
            'comingSoon' => $statusColumnExists 
                ? $this->movieModel->where('status', 'coming_soon')->findAll(4)
                : [], // Empty array if status column doesn't exist
            'pageTitle' => 'Movie Ticket Booking - Book Your Favorite Movies Online'
        ];
        
        return view('templates/header', [
                'title' => 'Movie Ticket Booking',
                'styles' => ['/assets/css/homepage.css']
            ])
            . view('customer/booking/movies', $data)
            . view('templates/footer', [
                'scripts' => ['/assets/js/homepage.js']
            ]);
    }

    /**
     * Show available shows for a specific movie
     */
    public function shows($movieId)
    {
        $movie = $this->movieModel->find($movieId);
        if (!$movie) {
            return redirect()->to('/booking')->with('error', 'Movie not found.');
        }

        $shows = $this->showModel->select('shows.*, cinemas.name as cinema_name, screens.name as screen_name')
                                ->join('screens', 'screens.id = shows.screen_id')
                                ->join('cinemas', 'cinemas.id = screens.cinema_id')
                                ->where('movie_id', $movieId)
                                ->where('show_time >', date('Y-m-d H:i:s'))
                                ->orderBy('show_time', 'ASC')
                                ->findAll();

        $data = [
            'movie' => $movie,
            'shows' => $shows
        ];
        
        return view('templates/header', ['title' => 'Select Showtime'])
             . view('customer/booking/shows', $data)
             . view('templates/footer');
    }

    /**
     * Show seat selection for a specific show
     */
    public function seats($showId)
    {
        $show = $this->showModel->select('shows.*, movies.title as movie_title, movies.poster, screens.name as screen_name, cinemas.name as cinema_name, screens.capacity')
                              ->join('movies', 'movies.id = shows.movie_id')
                              ->join('screens', 'screens.id = shows.screen_id')
                              ->join('cinemas', 'cinemas.id = screens.cinema_id')
                              ->where('shows.id', $showId)
                              ->first();

        if (!$show) {
            return redirect()->to('/booking')->with('error', 'Show not found.');
        }

        // Get already booked seats for this show
        $bookedSeats = $this->bookingModel->getBookedSeats($showId);
        $bookedSeatNumbers = array_column($bookedSeats, 'seat_number');
        
        // Generate seat layout
        $seatLayout = [];
        foreach ($this->seatRows as $row) {
            $seatRow = [];
            for ($i = 1; $i <= $this->seatsPerRow; $i++) {
                $seatNumber = $row . $i;
                $seatRow[] = [
                    'number' => $seatNumber,
                    'booked' => in_array($seatNumber, $bookedSeatNumbers)
                ];
            }
            $seatLayout[$row] = $seatRow;
        }

        $data = [
            'show' => $show,
            'bookedSeats' => $bookedSeatNumbers,
            'seatLayout' => $seatLayout,
            'ticketPrice' => $show['ticket_price'] ?? $this->ticketPrice
        ];
        
        echo view('templates/header', [
            'title' => 'Select Seats',
            'css' => ['seat-selection'],
            'styles' => ['/assets/css/seat-selection.css']
        ]);
        echo view('customer/booking/seats', $data);
        echo view('templates/footer', [
            'scripts' => ['/assets/js/seat-selection.js']
        ]);
    }

    /**
     * Process the booking
     */
    public function process()
    {
        // Check if it's an AJAX request
        $isAjax = $this->request->isAJAX();
        
        try {
            // Validate CSRF token
            if (! $this->request->getPost($this->request->getPost('csrf_test_name'))) {
                throw new \RuntimeException('Invalid CSRF token');
            }
            
            $showId = $this->request->getPost('show_id');
            $seats = json_decode($this->request->getPost('seats'), true);
            
            if (empty($showId) || empty($seats) || !is_array($seats)) {
                throw new \RuntimeException('Invalid request data');
            }
            
            // Get show details
            $show = $this->showModel->find($showId);
            if (!$show) {
                throw new \RuntimeException('Show not found');
            }
            
            // Check if seats are still available
            $bookedSeats = $this->bookingModel->getBookedSeats($showId);
            $bookedSeatNumbers = array_column($bookedSeats, 'seat_number');
            
            $alreadyBooked = array_intersect($seats, $bookedSeatNumbers);
            if (!empty($alreadyBooked)) {
                throw new \RuntimeException('Some seats are no longer available. Please refresh the page and try again.');
            }
            
            // Start database transaction
            $db = \Config\Database::connect();
            $db->transBegin();
            
            try {
                // Create booking
                $bookingData = [
                    'user_id' => session()->get('user_id'),
                    'show_id' => $showId,
                    'booking_number' => 'BK' . time() . rand(1000, 9999),
                    'total_amount' => count($seats) * ($show['ticket_price'] ?? $this->ticketPrice),
                    'status' => 'confirmed',
                    'payment_status' => 'completed',
                ];
                
                $bookingId = $this->bookingModel->insert($bookingData);
                
                if (!$bookingId) {
                    throw new \RuntimeException('Failed to create booking');
                }
                
                // Book seats
                $bookingSeatModel = new \App\Models\BookingSeatModel();
                foreach ($seats as $seat) {
                    $bookingSeatModel->insert([
                        'booking_id' => $bookingId,
                        'show_id' => $showId,
                        'seat_number' => $seat,
                        'price' => $show['ticket_price'] ?? $this->ticketPrice,
                    ]);
                }
                
                // Commit transaction
                $db->transCommit();
                
                // Get booking details for confirmation
                $booking = $this->bookingModel->find($bookingId);
                
                if ($isAjax) {
                    return $this->response->setJSON([
                        'success' => true,
                        'redirect' => site_url("booking/confirmation/{$booking['booking_number']}")
                    ]);
                }
                
                return redirect()->to("booking/confirmation/{$booking['booking_number']}");
                
            } catch (\Exception $e) {
                $db->transRollback();
                throw $e;
            }
            
        } catch (\Exception $e) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => $e->getMessage(),
                    'reload' => true
                ]);
            }
            
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    
    /**
     * Show booking confirmation
     */
    public function confirmation($bookingNumber)
    {
        $booking = $this->bookingModel->select('bookings.*, movies.title as movie_title, shows.show_time, cinemas.name as cinema_name, screens.name as screen_name')
                                    ->join('shows', 'shows.id = bookings.show_id')
                                    ->join('movies', 'movies.id = shows.movie_id')
                                    ->join('screens', 'screens.id = shows.screen_id')
                                    ->join('cinemas', 'cinemas.id = screens.cinema_id')
                                    ->where('bookings.booking_number', $bookingNumber)
                                    ->first();
        
        if (!$booking) {
            return redirect()->to('/booking')->with('error', 'Booking not found.');
        }
        
        // Get booked seats
        $bookingSeatModel = new \App\Models\BookingSeatModel();
        $seats = $bookingSeatModel->where('booking_id', $booking['id'])->findAll();
        
        $data = [
            'booking' => $booking,
            'seats' => $seats
        ];
        
        return view('templates/header', ['title' => 'Booking Confirmation'])
             . view('customer/booking/confirmation', $data)
             . view('templates/footer');
    }
    
    /**
     * Download ticket as PDF
     */
    public function downloadTicket($bookingNumber)
    {
        $booking = $this->bookingModel->select('bookings.*, movies.title as movie_title, shows.show_time, cinemas.name as cinema_name, screens.name as screen_name')
                                    ->join('shows', 'shows.id = bookings.show_id')
                                    ->join('movies', 'movies.id = shows.movie_id')
                                    ->join('screens', 'screens.id = shows.screen_id')
                                    ->join('cinemas', 'cinemas.id = screens.cinema_id')
                                    ->where('bookings.booking_number', $bookingNumber)
                                    ->first();
        
        if (!$booking) {
            return redirect()->to('/booking')->with('error', 'Booking not found.');
        }
        
        // Get booked seats
        $bookingSeatModel = new \App\Models\BookingSeatModel();
        $seats = $bookingSeatModel->where('booking_id', $booking['id'])->findAll();
        
        $data = [
            'booking' => $booking,
            'seats' => $seats
        ];
        
        // In a real app, you would use a PDF library like TCPDF or Dompdf here
        // For now, we'll just render a view that can be printed
        return view('customer/booking/ticket_pdf', $data);
    }
}
