/**
 * Seat Selection Script
 * Handles the interactive seat selection functionality
 */

document.addEventListener('DOMContentLoaded', function() {
    // DOM Elements
    const seatMap = document.querySelector('.seat-map');
    const seats = document.querySelectorAll('.seat:not(.booked)');
    const selectedSeatsList = document.getElementById('selectedSeatsList');
    const ticketCount = document.getElementById('ticketCount');
    const ticketAmount = document.getElementById('ticketAmount');
    const totalAmount = document.getElementById('totalAmount');
    const proceedBtn = document.getElementById('proceedToPay');
    const bookingForm = document.getElementById('bookingForm');
    const seatsInput = document.getElementById('seatsInput');
    
    // Configuration
    const ticketPrice = parseFloat(document.querySelector('.ticket-price').dataset.price) || 200;
    let selectedSeats = [];
    
    // Initialize from session storage if available
    const showId = document.querySelector('[data-show-id]')?.dataset.showId;
    const storedSeats = showId ? sessionStorage.getItem(`selectedSeats_${showId}`) : null;
    if (storedSeats) {
        selectedSeats = JSON.parse(storedSeats);
        updateSelectedSeats();
    }
    
    // Event Listeners
    if (seatMap) {
        seatMap.addEventListener('click', handleSeatClick);
    }
    
    if (proceedBtn) {
        proceedBtn.addEventListener('click', handleProceedToPay);
    }
    
    // Functions
    function handleSeatClick(e) {
        const seat = e.target.closest('.seat');
        
        // Ignore if not a seat or if already booked
        if (!seat || seat.classList.contains('booked')) return;
        
        const seatNumber = seat.getAttribute('data-seat');
        
        // Toggle seat selection
        if (seat.classList.contains('selected')) {
            // Deselect seat
            seat.classList.remove('selected');
            selectedSeats = selectedSeats.filter(seat => seat !== seatNumber);
        } else {
            // Select seat
            seat.classList.add('selected');
            selectedSeats.push(seatNumber);
            
            // Add animation class for visual feedback
            seat.classList.add('pulse');
            setTimeout(() => seat.classList.remove('pulse'), 300);
        }
        
        // Update UI
        updateSelectedSeats();
        
        // Save to session storage
        if (showId) {
            sessionStorage.setItem(`selectedSeats_${showId}`, JSON.stringify(selectedSeats));
        }
    }
    
    function updateSelectedSeats() {
        // Update selected seats list
        if (selectedSeats.length > 0) {
            selectedSeatsList.innerHTML = selectedSeats
                .sort((a, b) => a.localeCompare(b, undefined, {numeric: true, sensitivity: 'base'}))
                .join(', ');
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
        if (proceedBtn) {
            proceedBtn.disabled = count === 0;
        }
    }
    
    async function handleProceedToPay() {
        if (selectedSeats.length === 0) return;
        
        // Disable button and show loading state
        proceedBtn.disabled = true;
        const originalText = proceedBtn.innerHTML;
        proceedBtn.innerHTML = `
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            Processing...
        `;
        
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
                if (showId) {
                    sessionStorage.removeItem(`selectedSeats_${showId}`);
                }
                
                // Redirect to confirmation page
                window.location.href = result.redirect;
            } else {
                // Show error message
                showAlert(result.message || 'Failed to process booking. Please try again.', 'danger');
                
                // Re-enable button
                proceedBtn.disabled = false;
                proceedBtn.innerHTML = originalText;
                
                // If seats are no longer available, reload the page
                if (result.reload) {
                    setTimeout(() => window.location.reload(), 2000);
                }
            }
        } catch (error) {
            console.error('Error:', error);
            showAlert('An error occurred. Please try again.', 'danger');
            
            // Re-enable button
            proceedBtn.disabled = false;
            proceedBtn.innerHTML = originalText;
        }
    }
    
    function showAlert(message, type = 'info') {
        // Remove any existing alerts
        const existingAlert = document.querySelector('.alert');
        if (existingAlert) {
            existingAlert.remove();
        }
        
        // Create alert element
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.role = 'alert';
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        
        // Add to page
        const container = document.querySelector('.container') || document.body;
        container.prepend(alertDiv);
        
        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alertDiv);
            bsAlert.close();
        }, 5000);
    }
    
    // Initialize any previously selected seats
    selectedSeats.forEach(seatNumber => {
        const seat = document.querySelector(`.seat[data-seat="${seatNumber}"]`);
        if (seat) seat.classList.add('selected');
    });
    
    // Update the UI with initial values
    updateSelectedSeats();
});
