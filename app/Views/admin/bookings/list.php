<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manage Bookings</h2>
    </div>

    <div id="alertBox" class="alert d-none" role="alert"></div>

    <table class="table table-striped table-hover align-middle" id="bookingsTable">
        <thead>
            <tr>
                <th>#</th>
                <th>Booking #</th>
                <th>User</th>
                <th>Movie</th>
                <th>Cinema / Screen</th>
                <th>Show Time</th>
                <th>Seats</th>
                <th>Total (₹)</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<script>
(function() {
    const tableBody = document.querySelector('#bookingsTable tbody');
    const alertBox = document.getElementById('alertBox');

    function showAlert(type, message) {
        alertBox.className = `alert alert-${type}`;
        alertBox.textContent = message;
        alertBox.classList.remove('d-none');
        setTimeout(() => alertBox.classList.add('d-none'), 3000);
    }

    async function fetchBookings() {
        const res = await fetch('<?= base_url('admin/bookings/list-data') ?>');
        const json = await res.json();
        renderRows(json.data || []);
    }

    function statusBadge(status) {
        const map = { pending: 'warning', confirmed: 'success', completed: 'primary', cancelled: 'secondary' };
        const cls = map[status] || 'light';
        return `<span class="badge bg-${cls}">${status.charAt(0).toUpperCase() + status.slice(1)}</span>`;
    }

    function renderRows(rows) {
        tableBody.innerHTML = '';
        rows.forEach((row, idx) => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${idx + 1}</td>
                <td>${row.booking_number}</td>
                <td>${row.user_name || '-'}</td>
                <td>${row.movie_title}</td>
                <td>${row.cinema_name} / ${row.screen_name}</td>
                <td>${formatDateTime(row.show_time)}</td>
                <td>${row.seats || '-'}</td>
                <td>${formatINR(row.total_amount)}</td>
                <td data-col="status">${statusBadge(row.status)}</td>
                <td>
                    <div class="btn-group btn-group-sm" role="group">
                        <button class="btn btn-outline-secondary js-view" data-id="${row.id}">View</button>
                        <button class="btn btn-outline-success" data-action="confirm" data-id="${row.id}" ${row.status === 'confirmed' ? 'disabled' : ''}>Confirm</button>
                        <button class="btn btn-outline-danger" data-action="delete" data-id="${row.id}">Delete</button>
                    </div>
                </td>
            `;
            tableBody.appendChild(tr);
        });
    }

    function formatINR(amount) {
        const formatter = new Intl.NumberFormat('en-IN', { maximumFractionDigits: 0 });
        return '₹' + formatter.format(Number(amount || 0));
    }

    function formatDateTime(dt) {
        const d = new Date(dt);
        return d.toLocaleString();
    }

    tableBody.addEventListener('click', async (e) => {
        const btn = e.target.closest('button');
        if (!btn) return;
        const id = btn.getAttribute('data-id');
        const action = btn.getAttribute('data-action');

        if (btn.classList.contains('js-view')) {
            // Open modal with booking details
            openViewModal(id);
            return;
        }

        if (action === 'delete') {
            if (!confirm('Are you sure you want to delete this booking?')) return;
            const res = await fetch(`<?= base_url('admin/bookings/delete') ?>/${id}`, { method: 'POST' });
            const json = await res.json();
            if (json.success) {
                showAlert('success', 'Booking deleted successfully');
                fetchBookings();
            } else {
                showAlert('danger', json.message || 'Failed to delete booking');
            }
        }

        if (action === 'confirm') {
            const res = await fetch(`<?= base_url('admin/bookings/confirm') ?>/${id}`, { method: 'POST' });
            const json = await res.json();
            if (json.success) {
                showAlert('success', 'Booking confirmed successfully');
                fetchBookings();
            } else {
                showAlert('danger', json.message || 'Failed to confirm booking');
            }
        }
    });

    // Initial load
    fetchBookings();

    // ------- View Modal -------
    const viewModalHtml = `
    <div class="modal fade" id="adminBookingViewModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Booking Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div id="adminBookingDetails">Loading...</div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>`;
    document.body.insertAdjacentHTML('beforeend', viewModalHtml);

    let adminViewModal;
    function ensureViewModal() {
        if (!adminViewModal) {
            const el = document.getElementById('adminBookingViewModal');
            adminViewModal = new bootstrap.Modal(el);
        }
        return adminViewModal;
    }

    async function fetchBookingDetails(id) {
        const res = await fetch('<?= base_url('admin/bookings/show') ?>/' + id);
        return res.json();
    }

    function renderDetails(data) {
        const seats = (data.seats || []).join(', ');
        const dt = new Date(data.show_time);
        return `
        <div class="row g-3">
          <div class="col-md-6">
            <div><strong>Booking #:</strong> ${data.booking_number}</div>
            <div><strong>User:</strong> ${data.user_name || '-'} (${data.user_email || '-'})</div>
            <div><strong>Status:</strong> ${data.status}</div>
            <div><strong>Payment:</strong> ${data.payment_status}</div>
          </div>
          <div class="col-md-6">
            <div><strong>Movie:</strong> ${data.movie_title}</div>
            <div><strong>Cinema/Screen:</strong> ${data.cinema_name} / ${data.screen_name}</div>
            <div><strong>Show Time:</strong> ${dt.toLocaleString()}</div>
            <div><strong>Ticket Price:</strong> ₹${Number(data.ticket_price || 0).toFixed(0)}</div>
          </div>
          <div class="col-12">
            <div><strong>Seats:</strong> ${seats || '-'}</div>
            <div><strong>Total Amount:</strong> ₹${Number(data.total_amount || 0).toFixed(0)}</div>
          </div>
        </div>`;
    }

    async function openViewModal(id) {
        const container = document.getElementById('adminBookingDetails');
        container.innerHTML = 'Loading...';
        try {
            const json = await fetchBookingDetails(id);
            if (json.success) {
                container.innerHTML = renderDetails(json.data);
                ensureViewModal().show();
            } else {
                showAlert('danger', json.message || 'Failed to load booking');
            }
        } catch (e) {
            showAlert('danger', 'Network error');
        }
    }
})();
</script>
