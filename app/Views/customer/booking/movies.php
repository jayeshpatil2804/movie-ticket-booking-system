<!-- Hero Section with Slider -->
<div id="mainCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
    <div class="carousel-inner">
        <?php if (!empty($featuredMovies)): ?>
            <?php foreach ($featuredMovies as $index => $movie): ?>
            <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                <div class="hero-banner" style="background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('<?= !empty($movie['backdrop_url']) ? esc($movie['backdrop_url']) : 'https://via.placeholder.com/1920x500?text=' . urlencode($movie['title']) ?>');">
                    <div class="container py-5">
                        <div class="row align-items-center min-vh-50">
                            <div class="col-lg-6 text-white">
                                <span class="badge bg-danger mb-2"><?= $movie['certification'] ?? 'UA' ?></span>
                                <h1 class="display-4 fw-bold mb-3"><?= esc($movie['title']) ?></h1>
                                <div class="d-flex flex-wrap gap-2 mb-3">
                                    <span class="badge bg-primary"><?= $movie['language'] ?? 'Hindi' ?></span>
                                    <span class="badge bg-secondary"><?= floor(($movie['duration_minutes'] ?? 150) / 60) ?>h <?= ($movie['duration_minutes'] ?? 150) % 60 ?>m</span>
                                    <span class="badge bg-info"><?= $movie['genre'] ?? 'Action, Drama' ?></span>
                                </div>
                                <p class="lead mb-4"><?= character_limiter($movie['description'] ?? 'An exciting movie experience', 150) ?></p>
                                <a href="<?= base_url("booking/shows/{$movie['id']}") ?>" class="btn btn-danger btn-lg px-4 me-2">
                                    <i class="fas fa-ticket-alt me-2"></i>Book Tickets
                                </a>
                                <?php if (!empty($movie['trailer_url'])): ?>
                                    <a href="<?= $movie['trailer_url'] ?>" class="btn btn-outline-light btn-lg px-4" target="_blank">
                                        <i class="fas fa-play me-2"></i>Watch Trailer
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>

<!-- Now Showing Section -->
<div class="container mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-uppercase border-bottom border-danger border-3 d-inline-block pb-2">Now Showing</h2>
        <a href="#" class="btn btn-outline-danger">View All <i class="fas fa-chevron-right ms-1"></i></a>
    </div>
    
    <div class="row g-4">
        <?php if (!empty($nowShowing)): ?>
            <?php foreach ($nowShowing as $movie): ?>
            <div class="col-md-3 col-6">
                <div class="movie-card h-100 d-flex flex-column">
                    <div class="movie-poster position-relative">
                        <?php if (!empty($movie['poster_url'])): ?>
                            <img src="<?= esc($movie['poster_url']) ?>" class="img-fluid rounded-3" alt="<?= esc($movie['title']) ?>">
                        <?php else: ?>
                            <div class="no-poster d-flex align-items-center justify-content-center bg-light" style="height: 300px;">
                                <i class="fas fa-film fa-4x text-muted"></i>
                            </div>
                        <?php endif; ?>
                        <div class="movie-overlay d-flex align-items-center justify-content-center">
                            <div class="d-flex gap-2">
                                <a href="<?= base_url("booking/shows/{$movie['id']}") ?>" class="btn btn-danger btn-sm px-3">
                                    <i class="fas fa-ticket-alt me-2"></i>Book Now
                                </a>
                                <?php if (!empty($movie['trailer_url'])): ?>
                                <a href="<?= esc($movie['trailer_url']) ?>" target="_blank" class="btn btn-outline-light btn-sm px-3">
                                    <i class="fas fa-play me-1"></i> Trailer
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="position-absolute top-0 end-0 m-2">
                            <span class="badge bg-danger"><?= $movie['certification'] ?? 'UA' ?></span>
                        </div>
                    </div>
                    <div class="movie-info p-3 flex-grow-1 d-flex flex-column">
                        <h5 class="fw-bold mb-2"><?= esc($movie['title']) ?></h5>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-primary"><?= $movie['language'] ?? 'Hindi' ?></span>
                            <span class="text-muted small">
                                <i class="far fa-clock me-1"></i>
                                <?= floor(($movie['duration_minutes'] ?? 150) / 60) ?>h <?= ($movie['duration_minutes'] ?? 150) % 60 ?>m
                            </span>
                        </div>
                        <div class="mt-auto">
                            <a href="<?= base_url("booking/shows/{$movie['id']}") ?>" class="btn btn-outline-danger w-100 mt-2">
                                Book Tickets
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <i class="fas fa-film fa-3x text-muted mb-3"></i>
                <p class="lead text-muted">No movies currently showing. Please check back later.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Coming Soon -->
<?php if (!empty($comingSoon)): ?>
<div id="coming-soon" class="container mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-uppercase border-bottom border-secondary border-3 d-inline-block pb-2">Coming Soon</h2>
    </div>
    <div class="row g-4">
        <?php foreach ($comingSoon as $movie): ?>
        <div class="col-md-3 col-6">
            <div class="movie-card h-100 d-flex flex-column">
                <div class="movie-poster position-relative">
                    <?php if (!empty($movie['poster_url'])): ?>
                        <img src="<?= esc($movie['poster_url']) ?>" class="img-fluid rounded-3" alt="<?= esc($movie['title']) ?>">
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
    /* Hero Banner */
    .hero-banner {
        background-size: cover;
        background-position: center;
        min-height: 500px;
        display: flex;
        align-items: center;
        padding: 60px 0;
    }
    
    /* Movie Card */
    .movie-card {
        transition: all 0.3s ease;
        border-radius: 10px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        border: 1px solid #eee;
    }
    
    .movie-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }
    
    .movie-poster {
        position: relative;
        overflow: hidden;
        background: #f8f9fa;
    }
    
    .movie-poster img {
        width: 100%;
        height: 350px;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    
    .movie-card:hover .movie-poster img {
        transform: scale(1.05);
    }
    
    .movie-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .movie-card:hover .movie-overlay {
        opacity: 1;
    }
    
    .movie-info {
        padding: 15px;
    }
    
    .movie-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #333;
    }
    
    /* Badges */
    .badge {
        font-weight: 500;
        padding: 0.35em 0.65em;
        font-size: 0.75em;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .hero-banner {
            min-height: 400px;
            padding: 40px 0;
        }
        
        .movie-poster img {
            height: 300px;
        }
    }
    
    @media (max-width: 576px) {
        .hero-banner {
            min-height: 350px;
        }
        
        .movie-poster img {
            height: 250px;
        }
    }
    
    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #0056b3;
    }
</style>
