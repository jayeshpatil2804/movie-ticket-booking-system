<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\I18n\Time;

class BookingModel extends Model
{
    protected $table = 'bookings';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'user_id', 
        'show_id', 
        'booking_number',
        'total_amount',
        'status',
        'payment_status'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation rules
    protected $validationRules = [
        'user_id' => 'permit_empty|integer',
        'show_id' => 'required|integer',
        'booking_number' => 'required|string|max_length[50]',
        'total_amount' => 'required|numeric',
        'status' => 'required|in_list[pending,confirmed,completed,cancelled]',
        'payment_status' => 'required|in_list[pending,completed,failed,refunded]'
    ];

    /**
     * Get all bookings for a user with details
     */
    public function getBookingsWithDetails($userId = null)
    {
        $builder = $this->select('bookings.*, 
                                movies.title as movie_title, 
                                shows.show_time, 
                                cinemas.name as cinema_name, 
                                screens.name as screen_name,
                                COUNT(booked_seats.id) as total_seats')
                    ->join('shows', 'shows.id = bookings.show_id')
                    ->join('movies', 'movies.id = shows.movie_id')
                    ->join('screens', 'screens.id = shows.screen_id')
                    ->join('cinemas', 'cinemas.id = screens.cinema_id')
                    ->join('booked_seats', 'booked_seats.booking_id = bookings.id', 'left')
                    ->groupBy('bookings.id')
                    ->orderBy('bookings.created_at', 'DESC');

        if ($userId) {
            $builder->where('bookings.user_id', $userId);
        }

        return $builder->findAll();
    }

    /**
     * Get booking details by booking number
     */
    public function getBookingByNumber($bookingNumber)
    {
        return $this->select('bookings.*, 
                           movies.title as movie_title, 
                           shows.show_time, 
                           cinemas.name as cinema_name, 
                           screens.name as screen_name')
                   ->join('shows', 'shows.id = bookings.show_id')
                   ->join('movies', 'movies.id = shows.movie_id')
                   ->join('screens', 'screens.id = shows.screen_id')
                   ->join('cinemas', 'cinemas.id = screens.cinema_id')
                   ->where('bookings.booking_number', $bookingNumber)
                   ->first();
    }

    /**
     * Get booked seats for a show
     */
    public function getBookedSeats($showId)
    {
        return $this->db->table('booked_seats')
                       ->select('seat_number')
                       ->where('show_id', $showId)
                       ->get()
                       ->getResultArray();
    }

    /**
     * Check if seats are available for booking
     */
    public function areSeatsAvailable($showId, array $seats)
    {
        $bookedSeats = $this->db->table('booked_seats')
                               ->select('seat_number')
                               ->where('show_id', $showId)
                               ->whereIn('seat_number', $seats)
                               ->get()
                               ->getResultArray();

        return empty($bookedSeats);
    }

    /**
     * Create a new booking with seats
     */
    public function createBooking(array $bookingData, array $seats, $ticketPrice)
    {
        $this->db->transStart();

        // Generate unique booking number
        $bookingNumber = 'BK' . strtoupper(uniqid());
        
        $bookingData['booking_number'] = $bookingNumber;
        $bookingData['total_amount'] = count($seats) * $ticketPrice;
        $bookingData['status'] = 'confirmed';
        $bookingData['payment_status'] = 'completed';

        // Insert booking
        $this->insert($bookingData);
        $bookingId = $this->getInsertID();

        // Insert booked seats
        $bookedSeats = [];
        foreach ($seats as $seat) {
            $bookedSeats[] = [
                'booking_id' => $bookingId,
                'show_id' => $bookingData['show_id'],
                'seat_number' => $seat,
                'price' => $ticketPrice,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }

        if (!empty($bookedSeats)) {
            $this->db->table('booked_seats')->insertBatch($bookedSeats);
        }

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return false;
        }

        return $bookingNumber;
    }

    /**
     * Generate a unique booking number
     */
    public function generateBookingNumber()
    {
        return 'BK' . strtoupper(uniqid());
    }
}