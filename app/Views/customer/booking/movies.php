<!-- Hero Section -->
<div class="hero-section position-relative mb-5">
    <div class="container py-5">
        <div class="row align-items-center min-vh-50">
            <div class="col-lg-6 text-white">
                <h1 class="display-4 fw-bold mb-3">Book Your Movie Tickets Online</h1>
                <p class="lead mb-4">Experience the magic of cinema with the latest blockbusters in theaters near you.</p>
                <a href="#now-showing" class="btn btn-primary btn-lg px-4 me-2">Book Now</a>
                <a href="#coming-soon" class="btn btn-outline-light btn-lg px-4">Coming Soon</a>
            </div>
        </div>
    </div>
    <div class="hero-overlay"></div>
</div>

<!-- Featured Movies -->
<?php if (!empty($featuredMovies)): ?>
<div class="container mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="section-title">Featured Movies</h2>
        <a href="#" class="btn btn-outline-primary">View All</a>
    </div>
    <div class="row g-4">
        <?php foreach ($featuredMovies as $movie): ?>
        <div class="col-md-3">
            <div class="movie-card">
                <div class="movie-poster">
                    <?php if (!empty($movie['poster_url'])): ?>
                        <img src="<?= esc($movie['poster_url']) ?>" class="img-fluid rounded" alt="<?= esc($movie['title']) ?>">
                    <?php else: ?>
                        <div class="no-poster d-flex align-items-center justify-content-center">
                            <i class="fas fa-film fa-3x text-muted"></i>
                        </div>
                    <?php endif; ?>
                    <div class="movie-overlay">
                        <a href="<?= base_url("booking/shows/{$movie['id']}") ?>" class="btn btn-primary">Book Now</a>
                        <?php if (!empty($movie['trailer_url'])): ?>
                            <a href="<?= $movie['trailer_url'] ?>" class="btn btn-outline-light" target="_blank">
                                <i class="fas fa-play me-1"></i> Trailer
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="movie-info mt-3">
                    <h5 class="movie-title mb-1"><?= esc($movie['title']) ?></h5>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge bg-primary"><?= $movie['rating'] ?? 'PG-13' ?></span>
                        <div class="text-muted">
                            <i class="far fa-clock me-1"></i> 
                            <?= floor(($movie['duration_minutes'] ?? 120) / 60) ?>h <?= ($movie['duration_minutes'] ?? 120) % 60 ?>m
                        </div>
                    </div>
                    <div class="genre mt-2">
                        <?php 
                        $genres = explode(',', $movie['genre'] ?? 'Action, Adventure'); 
                        foreach (array_slice($genres, 0, 2) as $genre): 
                        ?>
                            <span class="badge bg-light text-dark me-1"><?= trim($genre) ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- Now Showing -->
<div id="now-showing" class="container mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="section-title">Now Showing</h2>
        <a href="#" class="btn btn-outline-primary">View All</a>
    </div>
    <div class="row g-4">
        <?php if (!empty($nowShowing)): ?>
            <?php foreach ($nowShowing as $movie): ?>
            <div class="col-md-3">
                <div class="movie-card">
                    <div class="movie-poster">
                        <?php if (!empty($movie['poster_url'])): ?>
                            <img src="<?= esc($movie['poster_url']) ?>" class="img-fluid rounded" alt="<?= esc($movie['title']) ?>">
                        <?php else: ?>
                            <div class="no-poster d-flex align-items-center justify-content-center">
                                <i class="fas fa-film fa-3x text-muted"></i>
                            </div>
                        <?php endif; ?>
                        <div class="movie-overlay">
                            <a href="<?= base_url("booking/shows/{$movie['id']}") ?>" class="btn btn-primary">Book Now</a>
                        </div>
                    </div>
                    <div class="movie-info mt-3">
                        <h5 class="movie-title mb-1"><?= esc($movie['title']) ?></h5>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-success">Now Showing</span>
                            <div class="text-muted small">
                                <?= floor(($movie['duration_minutes'] ?? 120) / 60) ?>h <?= ($movie['duration_minutes'] ?? 120) % 60 ?>m
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info">No movies currently showing. Please check back soon!</div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Coming Soon -->
<?php if (!empty($comingSoon)): ?>
<div id="coming-soon" class="container mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="section-title">Coming Soon</h2>
        <a href="#" class="btn btn-outline-primary">View All</a>
    </div>
    <div class="row g-4">
        <?php foreach ($comingSoon as $movie): ?>
        <div class="col-md-3">
            <div class="movie-card">
                <div class="movie-poster">
                    <?php if (!empty($movie['poster_url'])): ?>
                        <img src="<?= esc($movie['poster_url']) ?>" class="img-fluid rounded" alt="<?= esc($movie['title']) ?>">
                    <?php else: ?>
                        <div class="no-poster d-flex align-items-center justify-content-center">
                            <i class="fas fa-film fa-3x text-muted"></i>
                        </div>
                    <?php endif; ?>
                    <div class="movie-overlay">
                        <button class="btn btn-secondary" disabled>Coming Soon</button>
                        <?php if (!empty($movie['trailer_url'])): ?>
                            <a href="<?= $movie['trailer_url'] ?>" class="btn btn-outline-light mt-2" target="_blank">
                                <i class="fas fa-play me-1"></i> Watch Trailer
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="movie-info mt-3">
                    <h5 class="movie-title mb-1"><?= esc($movie['title']) ?></h5>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge bg-warning text-dark">Coming Soon</span>
                        <div class="text-muted small">
                            <?= !empty($movie['release_date']) ? date('M d, Y', strtotime($movie['release_date'])) : 'Coming Soon' ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<style>
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: none;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    .card-img-top {
        height: 400px;
        object-fit: cover;
    }
    
    .card-footer {
        border-top: none;
        background-color: #f8f9fa;
    }
    
    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }
    
    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #0056b3;
    }
</style>
