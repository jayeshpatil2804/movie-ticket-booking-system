<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('booking') ?>">Movies</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= esc($movie['title']) ?></li>
        </ol>
    </nav>
    
    <div class="row">
        <div class="col-md-4 mb-4">
            <?php if (!empty($movie['poster_url'])): ?>
                <img src="<?= esc($movie['poster_url']) ?>" class="img-fluid rounded" alt="<?= esc($movie['title']) ?>">
            <?php else: ?>
                <div class="text-center py-5 bg-light rounded">
                    <i class="fas fa-film fa-5x text-muted"></i>
                </div>
            <?php endif; ?>
            
            <div class="mt-3">
                <h4><?= esc($movie['title']) ?></h4>
                <p class="text-muted">
                    <i class="fas fa-clock"></i> <?= floor($movie['duration_minutes'] / 60) ?>h <?= $movie['duration_minutes'] % 60 ?>m
                    <?php if (!empty($movie['release_date'])): ?>
                        <br><i class="far fa-calendar-alt"></i> <?= date('M d, Y', strtotime($movie['release_date'])) ?>
                    <?php endif; ?>
                </p>
                <p><?= esc($movie['description']) ?></p>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Select Showtime</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($shows)): ?>
                        <div class="alert alert-info">No shows available for this movie. Please check back later.</div>
                    <?php else: ?>
                        <?php 
                        // Group shows by date
                        $showsByDate = [];
                        foreach ($shows as $show) {
                            $date = date('Y-m-d', strtotime($show['show_time']));
                            $showsByDate[$date][] = $show;
                        }
                        ?>
                        
                        <ul class="nav nav-tabs" id="showDatesTab" role="tablist">
                            <?php $first = true; foreach ($showsByDate as $date => $dateShows): ?>
                                <li class="nav-item">
                                    <a class="nav-link <?= $first ? 'active' : '' ?>" id="date-<?= $date ?>-tab" data-bs-toggle="tab"
                                       href="#date-<?= $date ?>" role="tab" aria-controls="date-<?= $date ?>"
                                       <?= $first ? 'aria-selected="true"' : 'aria-selected="false"' ?>>
                                        <?= date('D, M j', strtotime($date)) ?>
                                    </a>
                                </li>
                                <?php $first = false; ?>
                            <?php endforeach; ?>
                        </ul>
                        
                        <div class="tab-content mt-3" id="showDatesTabContent">
                            <?php $first = true; foreach ($showsByDate as $date => $dateShows): ?>
                                <div class="tab-pane fade <?= $first ? 'show active' : '' ?>" id="date-<?= $date ?>" 
                                     role="tabpanel" aria-labelledby="date-<?= $date ?>-tab">
                                    
                                    <?php 
                                    // Group shows by cinema
                                    $showsByCinema = [];
                                    foreach ($dateShows as $show) {
                                        $showsByCinema[$show['cinema_name']][] = $show;
                                    }
                                    ?>
                                    
                                    <?php foreach ($showsByCinema as $cinemaName => $cinemaShows): ?>
                                        <div class="mb-4">
                                            <h6 class="text-muted"><?= esc($cinemaName) ?></h6>
                                            <div class="d-flex flex-wrap">
                                                <?php foreach ($cinemaShows as $show): ?>
                                                    <a href="<?= base_url("booking/seats/{$show['id']}") ?>" class="btn btn-outline-danger m-1 px-3 py-2 d-flex align-items-center gap-2">
                                                        <span class="fw-bold"><?= date('g:i A', strtotime($show['show_time'])) ?></span>
                                                        <span class="badge bg-danger-subtle text-danger border border-danger ms-2">₹<?= number_format($show['price'] ?? 200, 0) ?></span>
                                                        <small class="d-block text-muted w-100 text-start lh-1"><?= esc($show['screen_name']) ?></small>
                                                    </a>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <?php $first = false; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .nav-tabs .nav-link {
        color: #495057;
        border: 1px solid transparent;
        border-top-left-radius: 0.25rem;
        border-top-right-radius: 0.25rem;
        padding: 0.5rem 1rem;
    }
    
    .nav-tabs .nav-link.active {
        color: #dc3545;
        background-color: #fff;
        border-color: #dee2e6 #dee2e6 #fff;
        font-weight: 600;
    }
    
    .btn-outline-primary, .btn-outline-danger {
        min-width: 100px;
    }
    
    .btn-outline-danger:hover {
        background-color: #dc3545;
        color: #fff;
    }
</style>

<script>
    // Enable Bootstrap 5 tabs
    document.addEventListener('DOMContentLoaded', function() {
        var triggerTabList = [].slice.call(document.querySelectorAll('#showDatesTab a[data-bs-toggle="tab"]'));
        triggerTabList.forEach(function (triggerEl) {
            new bootstrap.Tab(triggerEl);
        });
    });
</script>
