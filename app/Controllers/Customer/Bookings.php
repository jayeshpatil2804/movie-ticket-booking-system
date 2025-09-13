<?php

namespace App\Controllers\Customer;

use App\Controllers\BaseController;
use App\Models\BookingModel;
use App\Models\BookingSeatModel;
use App\Models\ShowModel;
use App\Models\ScreenModel; // Added ScreenModel for a more robust approach

class Bookings extends BaseController
{
    protected $bookingModel;
    protected $bookingSeatModel;
    protected $showModel;
    protected $screenModel;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
        $this->bookingSeatModel = new BookingSeatModel();
        $this->showModel = new ShowModel();
        $this->screenModel = new ScreenModel();
        helper(['form', 'url', 'email']);
    }

    /**
     * Display the seat booking page for a specific show.
     */
    public function create($showId)
    {
        // Fetch show details along with movie, cinema, and screen info
        $show = $this->showModel->select('shows.*, movies.title, movies.poster_url, screens.name as screen_name, screens.rows, screens.seats_per_row, cinemas.name as cinema_name')
            ->join('movies', 'movies.id = shows.movie_id')
            ->join('screens', 'screens.id = shows.screen_id')
            ->join('cinemas', 'cinemas.id = screens.cinema_id')
            ->find($showId);

        if (!$show) {
            // Corrected redirect URL to the home page
            return redirect()->to(base_url('/'))->with('error', 'The selected show is not available.');
        }

        // Fetch already booked seats for this specific show
        $bookedSeats = $this->bookingModel->select('booking_seats.seat_id')
            ->join('booking_seats', 'booking_seats.booking_id = bookings.id')
            ->where('bookings.show_id', $showId)
            ->findAll();
        
        // Extract seat IDs into a flat array for easy checking
        $bookedSeatIds = array_column($bookedSeats, 'seat_id');

        $data = [
            'show'          => $show,
            'bookedSeats'   => $bookedSeatIds,
        ];

        echo view('templates/header');
        echo view('customer/bookings/create', $data);
        echo view('templates/footer');
    }

    /**
     * Processes the booking submission.
     */
    public function processBooking()
    {
        $showId = $this->request->getPost('show_id');
        $seatIdsString = $this->request->getPost('seat_ids');
        $userId = session()->get('user_id');

        // Check for necessary data
        if (empty($seatIdsString) || empty($showId) || empty($userId)) {
            return redirect()->back()->with('error', 'No seats selected or invalid data.');
        }
        
        $seats = explode(',', $seatIdsString);
        $show = $this->showModel->find($showId);

        if (!$show) {
             return redirect()->back()->with('error', 'The selected show is no longer available.');
        }

        $numberOfSeats = count($seats);
        $totalPrice = $numberOfSeats * $show['price'];

        // --- Simulated Payment Gateway Integration ---
        // In a real-world application, you would integrate with a payment gateway here.
        // For this demo, we'll assume the payment is always successful.
        $paymentSuccess = true;
        
        if (!$paymentSuccess) {
            return redirect()->back()->with('error', 'Payment failed. Please try again.');
        }
        // --- End of Payment Simulation ---

        // Start a transaction for data integrity
        $this->bookingModel->db->transBegin();

        try {
            // 1. Create the new booking record
            $bookingData = [
                'user_id'      => $userId,
                'show_id'      => $showId,
                'total_price'  => $totalPrice,
                'booking_date' => date('Y-m-d H:i:s'),
            ];
            $this->bookingModel->save($bookingData);
            $bookingId = $this->bookingModel->insertID();

            // 2. Link seats to the new booking
            $bookingSeatsData = [];
            foreach ($seats as $seatId) {
                $bookingSeatsData[] = [
                    'booking_id' => $bookingId,
                    'seat_id'    => $seatId,
                ];
            }
            $this->bookingSeatModel->insertBatch($bookingSeatsData);

            // Commit the transaction
            $this->bookingModel->db->transCommit();
            
            // Send confirmation email
            $this->_sendConfirmationEmail($userId, $bookingId);

            return redirect()->to(base_url('my-bookings'))->with('success', 'Booking successful!');

        } catch (\Exception $e) {
            // Rollback if anything goes wrong
            $this->bookingModel->db->transRollback();
            return redirect()->back()->with('error', 'Booking failed: ' . $e->getMessage());
        }
    }

    /**
     * Display the user's list of bookings.
     */
    public function list()
    {
        $userId = session()->get('user_id');
        $data = [
            'bookings' => $this->bookingModel->getBookingsWithDetails($userId)
        ];
        echo view('templates/header');
        echo view('customer/bookings/list', $data);
        echo view('templates/footer');
    }
    
    /**
     * Simulates sending a confirmation email.
     */
    private function _sendConfirmationEmail($userId, $bookingId)
    {
        // For a real application, you would fetch user and booking data here
        // and use a library like CodeIgniter's Email service to send a real email.
        $email = \Config\Services::email();

        $email->setFrom(config('Email')->fromEmail, config('Email')->fromName);
        // In a real application, you'd get the user's email from the database
        $email->setTo('user@example.com'); 
        $email->setSubject('Booking Confirmation #' . $bookingId);
        $email->setMessage('Thank you for your booking! Your booking ID is: ' . $bookingId);

        // This will simulate sending the email.
        if ($email->send(false)) {
            log_message('info', 'Email for booking ' . $bookingId . ' sent successfully.');
        } else {
            log_message('error', 'Email for booking ' . $bookingId . ' failed to send.');
        }
    }
}