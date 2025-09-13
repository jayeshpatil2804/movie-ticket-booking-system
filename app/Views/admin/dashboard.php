<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">Dashboard</h1>
                <div>
                    <span class="badge bg-primary">Admin</span>
                </div>
            </div>
            <p class="text-muted">Welcome back! Here's what's happening with your movie booking system.</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Movies</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalMovies ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-film fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Shows</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalShows ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-ticket-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Bookings</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalBookings ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Revenue (This Month)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">₹<?= number_format(rand(50000, 200000)) ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-rupee-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Recent Movies -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Movies</h6>
                    <a href="<?= base_url('admin/movies') ?>" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Poster</th>
                                    <th>Title</th>
                                    <th>Release Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentMovies as $movie): ?>
                                <tr>
                                    <td>
                                        <?php if (!empty($movie['poster_url'])): ?>
                                            <img src="<?= esc($movie['poster_url']) ?>" alt="<?= esc($movie['title']) ?>" style="width: 50px; height: 75px; object-fit: cover;">
                                        <?php else: ?>
                                            <div class="bg-light d-flex align-items-center justify-content-center" style="width: 50px; height: 75px;">
                                                <i class="fas fa-film text-muted"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= esc($movie['title']) ?></td>
                                    <td><?= !empty($movie['release_date']) ? date('M d, Y', strtotime($movie['release_date'])) : 'N/A' ?></td>
                                    <td>
                                        <span class="badge bg-<?= $movie['status'] === 'now_showing' ? 'success' : ($movie['status'] === 'coming_soon' ? 'info' : 'secondary') ?>">
                                            <?= ucfirst(str_replace('_', ' ', $movie['status'])) ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Bookings -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Bookings</h6>
                    <a href="#" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Booking #</th>
                                    <th>Movie</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($recentBookings)): ?>
                                    <?php foreach ($recentBookings as $booking): ?>
                                    <tr>
                                        <td>#<?= $booking['booking_number'] ?></td>
                                        <td><?= esc($booking['movie_title']) ?></td>
                                        <td><?= date('M d, Y', strtotime($booking['created_at'])) ?></td>
                                        <td>
                                            <span class="badge bg-<?= $booking['status'] === 'confirmed' ? 'success' : 'warning' ?>">
                                                <?= ucfirst($booking['status']) ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center">No recent bookings found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="<?= base_url('admin/movies/create') ?>" class="btn btn-primary btn-block">
                                <i class="fas fa-plus-circle me-2"></i>Add New Movie
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="<?= base_url('admin/shows/create') ?>" class="btn btn-success btn-block">
                                <i class="fas fa-plus-circle me-2"></i>Schedule Show
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="#" class="btn btn-info btn-block disabled">
                                <i class="fas fa-chart-line me-2"></i>View Reports
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="#" class="btn btn-warning btn-block disabled">
                                <i class="fas fa-cog me-2"></i>Settings
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom CSS for Dashboard -->
<style>
    .card {
        border: none;
        border-radius: 10px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
    
    .border-left-primary {
        border-left: 0.25rem solid #4e73df !important;
    }
    
    .border-left-success {
        border-left: 0.25rem solid #1cc88a !important;
    }
    
    .border-left-info {
        border-left: 0.25rem solid #36b9cc !important;
    }
    
    .border-left-warning {
        border-left: 0.25rem solid #f6c23e !important;
    }
    
    .text-primary {
        color: #4e73df !important;
    }
    
    .text-success {
        color: #1cc88a !important;
    }
    
    .text-info {
        color: #36b9cc !important;
    }
    
    .text-warning {
        color: #f6c23e !important;
    }
    
    .bg-primary {
        background-color: #4e73df !important;
    }
    
    .bg-success {
        background-color: #1cc88a !important;
    }
    
    .bg-info {
        background-color: #36b9cc !important;
    }
    
    .bg-warning {
        background-color: #f6c23e !important;
    }
</style>