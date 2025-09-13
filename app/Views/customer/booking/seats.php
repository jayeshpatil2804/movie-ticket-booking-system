<?= $this->section('css') ?>
    <link rel="stylesheet" href="<?= base_url('assets/css/seat-selection.css') ?>">
    <style>
        .screen {
            height: 20px;
            background: #333;
            width: 70%;
            margin: 0 auto 30px;
            border-radius: 50% / 20%;
            box-shadow: 0 10px 20px rgba(0,0,0,0.3);
        }
        
        .screen-preview {
            color: #666;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        
        .seat {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 35px;
            height: 35px;
            margin: 0 2px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            font-weight: bold;
            transition: all 0.2s ease;
        }
        
        .seat.available {
            background-color: #e9ecef;
            color: #495057;
            border: 1px solid #dee2e6;
        }
        
        .seat.available:hover {
            background-color: #c6cbd1;
            transform: scale(1.1);
        }
        
        .seat.selected {
            background-color: #28a745;
            color: white;
            border: 1px solid #28a745;
        }
        
        .seat.booked {
            background-color: #dc3545;
            color: white;
            cursor: not-allowed;
            opacity: 0.6;
        }
        
        .seat-gap {
            width: 20px;
            display: inline-block;
        }
        
        .row-label {
            width: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin: 0 5px;
        }
        
        .legend {
            margin-top: 20px;
        }
        
        .legend-item {
            display: inline-flex;
            align-items: center;
        }
    </style>
<?= $this->endSection() ?>

<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('booking') ?>">Movies</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url("booking/shows/{$show['movie_id']}") ?>"><?= esc($show['movie_title']) ?></a></li>
            <li class="breadcrumb-item active" aria-current="page">Select Seats</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Select Your Seats</h5>
                </div>
                <div class="card-body">
                    <div class="screen-container text-center mb-5">
                        <div class="screen-label">Screen This Way</div>
                        <div class="screen"></div>
                    </div>

                    <div class="seat-map">
                        <?php
                        $rows = range('A', 'J');
                        $cols = range(1, 10);
                        
                        foreach ($rows as $row): ?>
                            <div class="seat-row">
                                <div class="row-label"><?= $row ?></div>
                                <?php foreach ($cols as $col): 
                                    $seatId = $row . $col;
                                    $isBooked = in_array($seatId, $bookedSeats);
                                    $statusClass = $isBooked ? 'booked' : 'available';
                                ?>
                                    <div class="seat <?= $statusClass ?>" 
                                         data-seat="<?= $seatId ?>"
                                         <?= $isBooked ? 'title="Already Booked"' : '' ?>>
                                        <?= $col ?>
                                    </div>
                                    <?php if ($col == 5): ?>
                                        <div class="seat-gap"></div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <div class="row-label"><?= $row ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="legend mt-4">
                        <div class="d-flex justify-content-center">
                            <div class="legend-item">
                                <div class="seat available"></div>
                                <span>Available</span>
                            </div>
                            <div class="legend-item ml-3">
                                <div class="seat selected"></div>
                                <span>Selected</span>
                            </div>
                            <div class="legend-item ml-3">
                                <div class="seat booked"></div>
                                <span>Booked</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Booking Summary</h5>
                </div>
                <div class="card-body">
                    <h5><?= esc($show['movie_title']) ?></h5>
                    <p class="text-muted mb-2">
                        <i class="far fa-calendar-alt"></i> 
                        <?= date('D, M j, Y', strtotime($show['show_time'])) ?>
                        <br>
                        <i class="far fa-clock"></i> 
                        <?= date('g:i A', strtotime($show['show_time'])) ?>
                        <br>
                        <i class="fas fa-video"></i> 
                        <?= esc($show['screen_name']) ?>
                        <br>
                        <i class="fas fa-map-marker-alt"></i> 
                        <?= esc($show['cinema_name']) ?>
                    </p>
                    <hr>
                    
                    <div class="selected-seats mb-3">
                        <h6>Selected Seats</h6>
                        <div id="selectedSeatsList" class="text-muted">No seats selected</div>
                    </div>
                    
                    <div class="price-details">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tickets (<span id="ticketCount">0</span>)</span>
                            <span>₹<span id="ticketAmount">0</span></span>
                        </div>
                        <div class="d-flex justify-content-between font-weight-bold">
                            <span>Total Amount</span>
                            <span>₹<span id="totalAmount">0</span></span>
                        </div>
                    </div>
                    
                    <button id="proceedToPay" class="btn btn-primary btn-block mt-4" disabled>
                        Proceed to Pay
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hidden form for submission -->
<form id="bookingForm" action="<?= base_url('booking/process') ?>" method="post" style="display: none;">
    <input type="hidden" name="show_id" value="<?= $show['id'] ?>">
    <input type="hidden" name="seats[]" id="seatsInput">
    <?= csrf_field() ?>
</form>

<style>
    .screen {
        height: 20px;
        background: #333;
        width: 70%;
        margin: 0 auto 30px;
        border-radius: 50% / 20%;
        box-shadow: 0 10px 20px rgba(0,0,0,0.3);
    }
    
    .screen-preview {
        color: #666;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 2px;
    }
    
    .seat {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 35px;
        height: 35px;
        margin: 0 2px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 12px;
        font-weight: bold;
        transition: all 0.2s ease;
    }
    
    .seat.available {
        background-color: #e9ecef;
        color: #495057;
        border: 1px solid #dee2e6;
    }
    
    .seat.available:hover {
        background-color: #c6cbd1;
        transform: scale(1.1);
    }
    
    .seat.selected {
        background-color: #28a745;
        color: white;
        border: 1px solid #28a745;
    }
    
    .seat.booked {
        background-color: #dc3545;
        color: white;
        cursor: not-allowed;
        opacity: 0.6;
    }
    
    .seat-gap {
        width: 20px;
        display: inline-block;
    }
    
    .row-label {
        width: 20px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin: 0 5px;
    }
    
    .legend {
        margin-top: 20px;
    }
    
    .legend-item {
        display: inline-flex;
        align-items: center;
        margin-right: 15px;
    }
    
    .legend .seat {
        margin-right: 5px;
        cursor: default;
    }
    
    .legend .seat:hover {
        transform: none;
    }
    
    #selectedSeatsList {
        min-height: 24px;
    }
    
    .price-details {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        margin-top: 20px;
    }
    
    .seat-map-container {
        max-width: 600px;
        margin: 0 auto;
        overflow-x: auto;
    }
    
    .seat-row {
        white-space: nowrap;
    }
</style>

<script>
<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/seat-selection.js') ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize ticket price from PHP
    const ticketPrice = <?= $ticketPrice ?>;
    
    // Set the ticket price in the DOM for the JS to use
    const priceElement = document.createElement('div');
    priceElement.className = 'ticket-price';
    priceElement.dataset.price = ticketPrice;
    priceElement.style.display = 'none';
    document.body.appendChild(priceElement);
    
    // Get all available seats
    const seats = document.querySelectorAll('.seat.available');
    const selectedSeatsList = document.getElementById('selectedSeatsList');
    const ticketCount = document.getElementById('ticketCount');
    const ticketAmount = document.getElementById('ticketAmount');
    const totalAmount = document.getElementById('totalAmount');
    const proceedBtn = document.getElementById('proceedToPay');
    const seatsInput = document.getElementById('seatsInput');
    const bookingForm = document.getElementById('bookingForm');
    
    const ticketPrice = <?= $ticketPrice ?>;
    let selectedSeats = [];
    
    // Initialize from session storage if available
    const storedSeats = sessionStorage.getItem(`selectedSeats_${<?= $show['id'] ?>}`);
    if (storedSeats) {
        selectedSeats = JSON.parse(storedSeats);
        updateSelectedSeats();
    }
    
    // Seat selection handler
    seats.forEach(seat => {
        seat.addEventListener('click', () => {
            const seatNumber = seat.getAttribute('data-seat');
            
            // Toggle seat selection
            const index = selectedSeats.indexOf(seatNumber);
            if (index === -1) {
                selectedSeats.push(seatNumber);
                seat.classList.add('selected');
            } else {
                selectedSeats.splice(index, 1);
                seat.classList.remove('selected');
            }
            
            // Update UI
            updateSelectedSeats();
            
            // Save to session storage
            sessionStorage.setItem(`selectedSeats_${<?= $show['id'] ?>}`, JSON.stringify(selectedSeats));
        });
    });
    
    // Update the selected seats display
    function updateSelectedSeats() {
        // Update selected seats list
        if (selectedSeats.length > 0) {
            selectedSeatsList.innerHTML = selectedSeats.join(', ');
            selectedSeatsList.classList.remove('text-muted');
        } else {
            selectedSeatsList.textContent = 'No seats selected';
            selectedSeatsList.classList.add('text-muted');
        }
        
        // Update counters
        const count = selectedSeats.length;
        const amount = count * ticketPrice;
        
        ticketCount.textContent = count;
        ticketAmount.textContent = amount.toFixed(2);
        totalAmount.textContent = amount.toFixed(2);
        
        // Enable/disable proceed button
        proceedBtn.disabled = count === 0;
    }
    
    // Handle form submission
    proceedBtn.addEventListener('click', async () => {
        if (selectedSeats.length === 0) return;
        
        // Disable button and show loading state
        proceedBtn.disabled = true;
        proceedBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';
        
        try {
            // Set the selected seats in the form
            seatsInput.value = JSON.stringify(selectedSeats);
            
            // Submit the form via AJAX
            const response = await fetch(bookingForm.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new URLSearchParams(new FormData(bookingForm))
            });
            
            const result = await response.json();
            
            if (result.success) {
                // Clear session storage on successful booking
                sessionStorage.removeItem(`selectedSeats_${<?= $show['id'] ?>}`);
                // Redirect to confirmation page
                window.location.href = result.redirect;
            } else {
                // Show error message
                alert(result.message || 'Failed to process booking. Please try again.');
                // Re-enable button
                proceedBtn.disabled = false;
                proceedBtn.textContent = 'Proceed to Pay';
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
            // Re-enable button
            proceedBtn.disabled = false;
            proceedBtn.textContent = 'Proceed to Pay';
        }
    });
    
    // Initialize any previously selected seats
    selectedSeats.forEach(seatNumber => {
        const seat = document.querySelector(`.seat[data-seat="${seatNumber}"]`);
        if (seat) seat.classList.add('selected');
    });
});
</script>
