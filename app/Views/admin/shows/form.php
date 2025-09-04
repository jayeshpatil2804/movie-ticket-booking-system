<div class="container mt-4">
    <h2><?= isset($show) ? 'Edit Show' : 'Add New Show' ?></h2>
    <hr>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>
    <form action="<?= isset($show) ? base_url('admin/shows/update/' . $show['id']) : base_url('admin/shows/store') ?>" method="post">
        <div class="mb-3">
            <label for="movie_id" class="form-label">Select Movie</label>
            <select class="form-control" id="movie_id" name="movie_id" required>
                <option value="">-- Select a Movie --</option>
                <?php foreach ($movies as $movie): ?>
                    <option value="<?= $movie['id'] ?>" <?= isset($show) && $show['movie_id'] == $movie['id'] ? 'selected' : '' ?>>
                        <?= $movie['title'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="cinema_id" class="form-label">Select Cinema</label>
            <select class="form-control" id="cinema_id" name="cinema_id" required>
                <option value="">-- Select a Cinema --</option>
                <?php foreach ($cinemas as $cinema): ?>
                    <option value="<?= $cinema['id'] ?>" <?= isset($show) && $show['cinema_id'] == $cinema['id'] ? 'selected' : '' ?>>
                        <?= $cinema['name'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="screen_id" class="form-label">Select Screen</label>
            <select class="form-control" id="screen_id" name="screen_id" required <?= isset($show) ? '' : 'disabled' ?>>
                <option value="">-- Select a Screen --</option>
                <?php if (isset($show)): ?>
                    <?php foreach ($screens as $screen): ?>
                        <option value="<?= $screen['id'] ?>" <?= $show['screen_id'] == $screen['id'] ? 'selected' : '' ?>>
                            <?= $screen['name'] ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="show_time" class="form-label">Show Time</label>
            <input type="datetime-local" class="form-control" id="show_time" name="show_time" value="<?= isset($show) ? date('Y-m-d\TH:i', strtotime($show['show_time'])) : '' ?>" required>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Ticket Price</label>
            <input type="number" step="0.01" class="form-control" id="price" name="price" value="<?= isset($show) ? $show['price'] : '' ?>" required min="0">
        </div>
        <button type="submit" class="btn btn-primary"><?= isset($show) ? 'Update' : 'Save' ?></button>
        <a href="<?= base_url('admin/shows') ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const cinemaSelect = document.getElementById('cinema_id');
    const screenSelect = document.getElementById('screen_id');

    cinemaSelect.addEventListener('change', async () => {
        const cinemaId = cinemaSelect.value;
        screenSelect.innerHTML = '<option value="">-- Select a Screen --</option>';
        screenSelect.disabled = true;

        if (cinemaId) {
            try {
                const response = await fetch(`<?= base_url('api/screens-by-cinema/') ?>${cinemaId}`);
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                const screens = await response.json();
                
                screens.forEach(screen => {
                    const option = document.createElement('option');
                    option.value = screen.id;
                    option.textContent = screen.name;
                    screenSelect.appendChild(option);
                });
                screenSelect.disabled = false;
            } catch (error) {
                console.error('Fetch error:', error);
                alert('Failed to load screens for the selected cinema.');
            }
        }
    });
});
</script>