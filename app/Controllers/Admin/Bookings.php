<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BookingModel;

class Bookings extends BaseController
{
    protected $bookingModel;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
        helper(['form']);
    }

    public function index()
    {
        echo view('templates/header', ['title' => 'Manage Bookings']);
        echo view('admin/bookings/list');
        echo view('templates/footer');
    }

    // Return JSON list of bookings with joined details
    public function listData()
    {
        $builder = $this->bookingModel->select(
            'bookings.id, bookings.booking_number, bookings.status, bookings.payment_status, bookings.total_amount, bookings.created_at,' .
            'users.name as user_name, movies.title as movie_title, screens.name as screen_name, cinemas.name as cinema_name,' .
            'shows.show_time, GROUP_CONCAT(booked_seats.seat_number ORDER BY booked_seats.seat_number SEPARATOR ", ") as seats'
        )
        ->join('shows', 'shows.id = bookings.show_id')
        ->join('movies', 'movies.id = shows.movie_id')
        ->join('screens', 'screens.id = shows.screen_id')
        ->join('cinemas', 'cinemas.id = screens.cinema_id')
        ->join('users', 'users.id = bookings.user_id', 'left')
        ->join('booked_seats', 'booked_seats.booking_id = bookings.id', 'left')
        ->groupBy('bookings.id')
        ->orderBy('bookings.created_at', 'DESC');

        $data = $builder->findAll();
        return $this->response->setJSON(['data' => $data]);
    }

    // Return detailed information for a single booking (JSON)
    public function show($id)
    {
        $row = $this->bookingModel->select(
            'bookings.id, bookings.booking_number, bookings.status, bookings.payment_status, bookings.total_amount, bookings.created_at,' .
            'users.name as user_name, users.email as user_email,' .
            'movies.title as movie_title, movies.language, movies.certification,' .
            'screens.name as screen_name, cinemas.name as cinema_name,' .
            'shows.show_time, shows.ticket_price'
        )
        ->join('shows', 'shows.id = bookings.show_id')
        ->join('movies', 'movies.id = shows.movie_id')
        ->join('screens', 'screens.id = shows.screen_id')
        ->join('cinemas', 'cinemas.id = screens.cinema_id')
        ->join('users', 'users.id = bookings.user_id', 'left')
        ->where('bookings.id', $id)
        ->first();

        if (!$row) {
            return $this->response->setJSON(['success' => false, 'message' => 'Booking not found'])->setStatusCode(404);
        }

        // Fetch seats
        $seats = $this->bookingModel->db->table('booked_seats')
            ->select('seat_number')
            ->where('booking_id', $id)
            ->orderBy('seat_number', 'ASC')
            ->get()->getResultArray();
        $row['seats'] = array_map(fn($s) => $s['seat_number'], $seats);

        return $this->response->setJSON(['success' => true, 'data' => $row]);
    }

    // Confirm a booking (set status to confirmed)
    public function confirm($id)
    {
        $booking = $this->bookingModel->find($id);
        if (!$booking) {
            return $this->response->setJSON(['success' => false, 'message' => 'Booking not found'])->setStatusCode(404);
        }
        $this->bookingModel->update($id, [
            'status' => 'confirmed',
            'payment_status' => 'completed',
        ]);
        return $this->response->setJSON(['success' => true, 'message' => 'Booking confirmed successfully']);
    }

    // Update status via POST (pending, confirmed, completed, cancelled)
    public function updateStatus($id)
    {
        $status = $this->request->getPost('status');
        $allowed = ['pending', 'confirmed', 'completed', 'cancelled'];
        if (!in_array($status, $allowed, true)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid status'])->setStatusCode(400);
        }
        $booking = $this->bookingModel->find($id);
        if (!$booking) {
            return $this->response->setJSON(['success' => false, 'message' => 'Booking not found'])->setStatusCode(404);
        }
        $data = ['status' => $status];
        if (in_array($status, ['confirmed', 'completed'], true)) {
            $data['payment_status'] = 'completed';
        }
        $this->bookingModel->update($id, $data);
        return $this->response->setJSON(['success' => true, 'message' => 'Booking status updated successfully']);
    }

    // Delete (cancel) a booking
    public function delete($id)
    {
        $booking = $this->bookingModel->find($id);
        if (!$booking) {
            return $this->response->setJSON(['success' => false, 'message' => 'Booking not found'])->setStatusCode(404);
        }
        // Deleting booking will cascade to booked_seats if FK is set; otherwise, it will just remove the booking
        $this->bookingModel->delete($id);
        return $this->response->setJSON(['success' => true, 'message' => 'Booking deleted successfully']);
    }
}
