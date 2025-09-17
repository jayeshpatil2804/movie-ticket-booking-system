<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-success">
                <div class="card-header bg-success text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Booking Confirmed!</h4>
                        <div class="booking-number">#<?= esc($booking['booking_number']) ?></div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="alert alert-success">
                        <h5 class="alert-heading">Thank you for your booking!</h5>
                        <p class="mb-0">Your tickets have been booked successfully. A confirmation has been sent to your email.</p>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-4 text-center">
                            <?php if (!empty($booking['poster_url'])): ?>
                                <img src="<?= esc($booking['poster_url']) ?>" class="img-fluid rounded" alt="<?= esc($booking['movie_title']) ?>">
                            <?php else: ?>
                                <div class="text-center py-5 bg-light rounded">
                                    <i class="fas fa-film fa-4x text-muted"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-8">
                            <h3 class="mb-3"><?= esc($booking['movie_title']) ?></h3>
                            <div class="show-details">
                                <p class="mb-1">
                                    <i class="far fa-calendar-alt text-muted"></i> 
                                    <strong>Date:</strong> <?= date('D, M j, Y', strtotime($booking['show_time'])) ?>
                                </p>
                                <p class="mb-1">
                                    <i class="far fa-clock text-muted"></i> 
                                    <strong>Time:</strong> <?= date('g:i A', strtotime($booking['show_time'])) ?>
                                </p>
                                <p class="mb-1">
                                    <i class="fas fa-video text-muted"></i> 
                                    <strong>Screen:</strong> <?= esc($booking['screen_name']) ?>
                                </p>
                                <p class="mb-1">
                                    <i class="fas fa-map-marker-alt text-muted"></i> 
                                    <strong>Cinema:</strong> <?= esc($booking['cinema_name']) ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="ticket-details mb-4">
                        <h5 class="mb-3">Ticket Details</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Seat Number</th>
                                        <th class="text-right">Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $total = 0;
                                    foreach ($seats as $seat): 
                                        $total += (float)$seat['price'];
                                    ?>
                                        <tr>
                                            <td><?= esc($seat['seat_number']) ?></td>
                                            <td class="text-right">₹<?= number_format($seat['price'], 0) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr class="table-active">
                                        <th>Total</th>
                                        <th class="text-right">₹<?= number_format($total, 0) ?></th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="booking-actions mt-4">
                        <a href="<?= base_url('booking/ticket/' . $booking['booking_number']) ?>" class="btn btn-outline-primary" target="_blank">
                            <i class="fas fa-print"></i> Print Ticket
                        </a>
                        <a href="<?= base_url('booking') ?>" class="btn btn-primary">
                            <i class="fas fa-home"></i> Back to Home
                        </a>
                    </div>
                </div>
                <div class="card-footer text-muted">
                    <small>
                        <i class="fas fa-info-circle"></i> 
                        Please arrive at least 30 minutes before the showtime. Bring a valid ID and this confirmation.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .booking-number {
        background: rgba(255, 255, 255, 0.2);
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-weight: 500;
        font-size: 0.9rem;
    }
    
    .show-details p {
        margin-bottom: 0.5rem;
    }
    
    .ticket-details {
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 0.5rem;
    }
    
    .booking-actions {
        display: flex;
        gap: 1rem;
        justify-content: center;
        margin-top: 2rem;
    }
    
    .card {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        border: none;
    }
    
    .card-header {
        border-bottom: none;
    }
    
    @media print {
        .no-print {
            display: none !important;
        }
        
        .container {
            max-width: 100%;
        }
        
        .card {
            border: 1px solid #dee2e6;
            box-shadow: none;
        }
    }
</style>

<div class="container mt-4 no-print">
    <div class="alert alert-info">
        <h5>Need Help?</h5>
        <p class="mb-0">
            If you have any questions about your booking, please contact our customer support at 
            <a href="mailto:support@movietickets.com">support@movietickets.com</a> or call us at +91 1234567890.
        </p>
    </div>
</div>
