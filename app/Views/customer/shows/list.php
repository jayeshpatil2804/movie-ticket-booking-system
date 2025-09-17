<div class="container mt-4">
    <h2>Available Shows</h2>
    <hr>
    <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php foreach($shows as $show): ?>
            <div class="col">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title"><?= esc($show['movie_title']) ?></h5>
                        <p class="card-text">
                            <strong>Cinema:</strong> <?= esc($show['cinema_name']) ?><br>
                            <strong>Screen:</strong> <?= esc($show['screen_name']) ?><br>
                            <strong>Time:</strong> <?= date('F d, Y h:i A', strtotime(esc($show['show_time']))) ?><br>
                            <strong>Price:</strong> $<?= esc($show['price']) ?>
                        </p>
                        <a href="<?= base_url('booking/seats/' . $show['id']) ?>" class="btn btn-primary">Book Now</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>