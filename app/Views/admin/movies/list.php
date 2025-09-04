<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manage Movies</h2>
        <a href="<?= base_url('admin/movies/create') ?>" class="btn btn-primary">Add New Movie</a>
    </div>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>Title</th>
                <th>Director</th>
                <th>Duration (mins)</th>
                <th>Release Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($movies as $movie): ?>
            <tr>
                <td><?= esc($movie['title']) ?></td>
                <td><?= esc($movie['director']) ?></td>
                <td><?= esc($movie['duration_minutes']) ?></td>
                <td><?= esc($movie['release_date']) ?></td>
                <td>
                    <a href="<?= base_url('admin/movies/edit/' . $movie['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="<?= base_url('admin/movies/delete/' . $movie['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this movie?');">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>