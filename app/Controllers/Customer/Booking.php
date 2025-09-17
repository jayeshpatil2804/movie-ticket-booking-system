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
        helper(['form', 'url', 'text']);
    }

    /**
     * Dummy payment page
     */
    public function payment($bookingNumber)
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

        // If already completed, go to confirmation
        if ($booking['payment_status'] === 'completed') {
            return redirect()->to("/booking/confirmation/{$bookingNumber}");
        }

        // Seats for summary
        $bookingSeatModel = new \App\Models\BookingSeatModel();
        $seats = $bookingSeatModel->where('booking_id', $booking['id'])->findAll();

        return view('templates/header', ['title' => 'Payment'])
             . view('customer/booking/payment', [
                 'booking' => $booking,
                 'seats' => $seats,
             ])
             . view('templates/footer');
    }

    /**
     * Dummy payment confirmation (marks booking as confirmed)
     */
    public function paymentConfirm($bookingNumber)
    {
        $booking = $this->bookingModel->where('booking_number', $bookingNumber)->first();
        if (!$booking) {
            return redirect()->to('/booking')->with('error', 'Booking not found.');
        }

        // Update booking status to confirmed and payment_status to completed
        $this->bookingModel->update($booking['id'], [
            'status' => 'confirmed',
            'payment_status' => 'completed',
        ]);

        return redirect()->to("/booking/confirmation/{$bookingNumber}");
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
        
        // Get featured movies with all necessary fields
        $featuredMovies = $this->movieModel->select('*')
            ->where('is_featured', 1)
            ->orderBy('release_date', 'DESC')
            ->findAll(4);
            
        // Get now showing movies with all necessary fields
        $nowShowing = $statusColumnExists 
            ? $this->movieModel->select('*')
                ->where('status', 'now_showing')
                ->orderBy('release_date', 'DESC')
                ->findAll(8)
            : $this->movieModel->select('*')
                ->orderBy('release_date', 'DESC')
                ->findAll(8);
                
        // Get coming soon movies with all necessary fields
        $comingSoon = [];
        if ($statusColumnExists) {
            $comingSoon = $this->movieModel->select('*')
                ->where('status', 'coming_soon')
                ->orderBy('release_date', 'ASC')
                ->findAll(4);
        }
        
        $data = [
            'featuredMovies' => $featuredMovies,
            'nowShowing' => $nowShowing,
            'comingSoon' => $comingSoon,
            'pageTitle' => 'Movie Ticket Booking - Book Your Favorite Movies Online'
        ];
        
        return view('templates/header', [
                'title' => 'Movie Ticket Booking',
                'styles' => [
                    'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css',
                    '/assets/css/homepage.css'
                ]
            ])
            . view('customer/booking/movies', $data)
            . view('templates/footer', [
                'scripts' => [
                    'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js',
                    '/assets/js/homepage.js'
                ]
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

        $shows = $this->showModel->select('shows.*, cinemas.name as cinema_name, screens.name as screen_name, shows.ticket_price as price')
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
        $show = $this->showModel->select('shows.*, movies.title as movie_title, movies.poster_url, screens.name as screen_name, cinemas.name as cinema_name, screens.capacity')
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
            // CSRF is handled by CI filters; proceed to validate input
            $showId = $this->request->getPost('show_id');
            $seatsJson = $this->request->getPost('seats');
            $seats = is_string($seatsJson) ? json_decode($seatsJson, true) : [];
            
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
                // Create booking (pending until dummy payment success)
                $bookingData = [
                    'user_id' => session()->get('user_id'),
                    'show_id' => $showId,
                    'booking_number' => 'BK' . time() . rand(1000, 9999),
                    'total_amount' => count($seats) * ($show['ticket_price'] ?? $this->ticketPrice),
                    'status' => 'pending',
                    'payment_status' => 'pending',
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
                
                // Get booking details for next step
                $booking = $this->bookingModel->find($bookingId);
                
                if ($isAjax) {
                    return $this->response->setJSON([
                        'success' => true,
                        'redirect' => site_url("booking/payment/{$booking['booking_number']}")
                    ]);
                }
                
                return redirect()->to("booking/payment/{$booking['booking_number']}");
                
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