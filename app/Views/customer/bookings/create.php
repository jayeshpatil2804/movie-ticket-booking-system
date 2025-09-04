<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h4>Book Tickets for <?= esc($show['movie_title']) ?></h4>
            <p class="mb-0"><strong>Cinema:</strong> <?= esc($show['cinema_name']) ?> | <strong>Screen:</strong> <?= esc($show['screen_name']) ?></p>
            <p><strong>Time:</strong> <?= esc(date('h:i A', strtotime($show['show_time']))) ?> | <strong>Price:</strong> $<?= esc($show['price']) ?></p>
        </div>
        <div class="card-body text-center">
            <h5>Select Your Seats</h5>
            <div class="seat-grid-container mt-3 p-3 border rounded">
                <?php
                $currentSeat = 1;
                $rows = $show['rows'];
                $seats_per_row = $show['seats_per_row'];
                ?>
                <?php for ($row = 1; $row <= $rows; $row++): ?>
                    <div class="seat-row d-flex justify-content-center mb-2">
                        <div class="row-label me-2">Row <?= chr(64 + $row) ?></div>
                        <?php for ($seat = 1; $seat <= $seats_per_row; $seat++): ?>
                            <?php
                            $seatId = $currentSeat;
                            $isBooked = in_array($seatId, $bookedSeats);
                            $seatClass = $isBooked ? 'seat-booked' : 'seat-available';
                            ?>
                            <div class="seat-box <?= $seatClass ?>" data-seat-id="<?= $seatId ?>" data-price="<?= esc($show['price']) ?>">
                                <?= $seat ?>
                            </div>
                            <?php $currentSeat++; ?>
                        <?php endfor; ?>
                    </div>
                <?php endfor; ?>
                <div class="screen-label mt-3 p-2 bg-dark text-white rounded">SCREEN</div>
            </div>
            <div class="seat-legend mt-4">
                <span class="d-inline-block p-2 rounded" style="background-color: #28a745;"></span> Available
                <span class="d-inline-block p-2 rounded ms-3" style="background-color: #dc3545;"></span> Booked
            </div>
        </div>
        <div class="card-footer d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Total Price: $<span id="total-price">0.00</span></h5>
            <form id="booking-form" action="<?= base_url('booking/process') ?>" method="post">
                <input type="hidden" name="show_id" value="<?= esc($show['id']) ?>">
                <input type="hidden" name="seat_ids" id="seat-ids">
                <button type="submit" class="btn btn-primary" id="confirm-booking-btn" disabled>Confirm Booking</button>
            </form>
        </div>
    </div>
</div>

<style>
    .seat-box {
        width: 40px;
        height: 40px;
        margin: 5px;
        border: 1px solid #ccc;
        border-radius: 5px;
        line-height: 40px;
        cursor: pointer;
        user-select: none;
    }
    .seat-available {
        background-color: #28a745;
        color: white;
    }
    .seat-booked {
        background-color: #dc3545;
        color: white;
        cursor: not-allowed;
    }
    .seat-selected {
        background-color: #007bff;
        color: white;
    }
    .seat-legend span {
        width: 25px;
        height: 25px;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const seatGrid = document.querySelector('.seat-grid-container');
    const totalPriceSpan = document.getElementById('total-price');
    const seatIdsInput = document.getElementById('seat-ids');
    const confirmBtn = document.getElementById('confirm-booking-btn');
    const selectedSeats = new Set();
    const pricePerSeat = <?= $show['price'] ?>;

    seatGrid.addEventListener('click', (event) => {
        const seat = event.target.closest('.seat-box');
        if (!seat || seat.classList.contains('seat-booked')) {
            return;
        }

        const seatId = seat.dataset.seatId;

        if (selectedSeats.has(seatId)) {
            selectedSeats.delete(seatId);
            seat.classList.remove('seat-selected');
        } else {
            selectedSeats.add(seatId);
            seat.classList.add('seat-selected');
        }

        updatePriceAndButton();
    });

    function updatePriceAndButton() {
        const total = selectedSeats.size * pricePerSeat;
        totalPriceSpan.textContent = total.toFixed(2);
        seatIdsInput.value = Array.from(selectedSeats).join(',');
        confirmBtn.disabled = selectedSeats.size === 0;
    }
});
</script>

<!-- This video provides an excellent guide on how to build a CRUD application with CodeIgniter 4, which is fundamental to managing your movies, cinemas, and screens. [CodeIgniter 4 CRUD Tutorial - Initial Set Up](https://www.youtube.com/watch?v=Ezvy2tLphJY) -->
<!-- http://googleusercontent.com/youtube_content/1 -->