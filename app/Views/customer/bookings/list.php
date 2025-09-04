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
                        <h5 class="mb-1"><?= esc($booking['title']) ?></h5>
                        <small class="text-muted"><?= date('F d, Y h:i A', strtotime(esc($booking['show_time']))) ?></small>
                    </div>
                    <p class="mb-1">
                        <strong>Booking ID:</strong> <?= esc($booking['id']) ?><br>
                        <strong>Cinema:</strong> <?= esc($booking['cinema_name']) ?>, <strong>Screen:</strong> <?= esc($booking['screen_name']) ?><br>
                        <strong>Total Price:</strong> $<?= esc($booking['total_price']) ?>
                    </p>
                    <small class="text-muted">Booked on: <?= date('F d, Y', strtotime(esc($booking['booking_date']))) ?></small>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>