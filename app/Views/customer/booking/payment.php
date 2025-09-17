<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">Payment (Dummy)</h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h5 class="mb-2">Booking Summary</h5>
                        <p class="mb-1"><strong>Movie:</strong> <?= esc($booking['movie_title']) ?></p>
                        <p class="mb-1"><strong>Cinema:</strong> <?= esc($booking['cinema_name']) ?> | <strong>Screen:</strong> <?= esc($booking['screen_name']) ?></p>
                        <p class="mb-1"><strong>Showtime:</strong> <?= date('D, M j, Y g:i A', strtotime($booking['show_time'])) ?></p>
                        <p class="mb-1"><strong>Booking #:</strong> <?= esc($booking['booking_number']) ?></p>
                    </div>

                    <div class="table-responsive mb-3">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Seat</th>
                                    <th class="text-end">Price (₹)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $total = 0; foreach ($seats as $seat): $total += (float)$seat['price']; ?>
                                    <tr>
                                        <td><?= esc($seat['seat_number']) ?></td>
                                        <td class="text-end"><?= number_format($seat['price'], 0) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr class="table-active">
                                    <th>Total</th>
                                    <th class="text-end"><?= number_format($total, 0) ?></th>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="alert alert-info">
                        This is a demo payment step. Clicking the button below will simulate a successful payment.
                    </div>

                    <a href="<?= base_url('booking/payment/confirm/' . $booking['booking_number']) ?>" class="btn btn-success btn-lg w-100">
                        Pay Now (Dummy)
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
