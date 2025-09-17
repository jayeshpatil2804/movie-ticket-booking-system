<div class="container mt-4">
    <h2>My Bookings</h2>
    <hr>
    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    
    <?php if (empty($bookings)): ?>
        <div class="alert alert-info">You have no bookings yet.</div>
    <?php else: ?>
        <div class="list-group">
            <?php foreach ($bookings as $booking): ?>
                <div class="list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1"><?= esc($booking['title'] ?? $booking['movie_title'] ?? 'Movie') ?></h5>
                        <small class="text-muted">Booked on: <?= date('M d, Y', strtotime(esc($booking['created_at'] ?? date('Y-m-d H:i:s')))) ?></small>
                    </div>
                    <p class="mb-1">
                        <strong>Booking #:</strong> <?= esc($booking['booking_number'] ?? $booking['id']) ?><br>
                        <?php if (!empty($booking['show_time'])): ?>
                        <strong>Showtime:</strong> <?= date('D, M j, Y g:i A', strtotime($booking['show_time'])) ?><br>
                        <?php endif; ?>
                        <strong>Cinema:</strong> <?= esc($booking['cinema_name'] ?? '-') ?>, <strong>Screen:</strong> <?= esc($booking['screen_name'] ?? '-') ?>
                        <br>
                        <strong>Status:</strong> <span class="badge bg-<?= ($booking['status'] ?? 'pending') === 'confirmed' ? 'success' : (($booking['status'] ?? 'pending') === 'cancelled' ? 'secondary' : 'warning') ?>"><?= ucfirst($booking['status'] ?? 'pending') ?></span>
                        <strong class="ms-2">Payment:</strong> <span class="badge bg-<?= ($booking['payment_status'] ?? 'pending') === 'completed' ? 'success' : (($booking['payment_status'] ?? 'pending') === 'failed' ? 'danger' : 'warning') ?>"><?= ucfirst($booking['payment_status'] ?? 'pending') ?></span>
                        <br>
                        <strong>Total Amount:</strong> ₹<?= number_format((float)($booking['total_amount'] ?? 0), 0) ?>
                    </p>
                    <?php if (!empty($booking['booking_date'])): ?>
                        <small class="text-muted">Booked on: <?= date('M d, Y', strtotime(esc($booking['booking_date']))) ?></small>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>