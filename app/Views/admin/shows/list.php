<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manage Shows</h2>
        <a href="<?= base_url('admin/shows/create') ?>" class="btn btn-primary">Add New Show</a>
    </div>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>Movie</th>
                <th>Cinema</th>
                <th>Screen</th>
                <th>Show Time</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($shows as $show): ?>
            <tr>
                <td><?= esc($show['movie_title']) ?></td>
                <td><?= esc($show['cinema_name']) ?></td>
                <td><?= esc($show['screen_name']) ?></td>
                <td><?= date('F d, Y h:i A', strtotime(esc($show['show_time']))) ?></td>
                <td>$<?= esc($show['price']) ?></td>
                <td>
                    <a href="<?= base_url('admin/shows/edit/' . $show['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="<?= base_url('admin/shows/delete/' . $show['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this show?');">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>