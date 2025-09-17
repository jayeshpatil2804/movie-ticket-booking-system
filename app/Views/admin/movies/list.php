<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manage Movies</h2>
        <a href="<?= base_url('admin/movies/create') ?>" class="btn btn-primary">Add New Movie</a>
    </div>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <table class="table table-striped table-hover align-middle">
        <thead>
            <tr>
                <th>Poster</th>
                <th>Title</th>
                <th>Lang</th>
                <th>Cert</th>
                <th>Status</th>
                <th>Featured</th>
                <th>Duration</th>
                <th>Release</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($movies as $movie): ?>
            <tr>
                <td style="width:72px;">
                    <?php if (!empty($movie['poster_url'])): ?>
                        <img src="<?= esc($movie['poster_url']) ?>" alt="poster" class="img-thumbnail" style="width:60px;height:60px;object-fit:cover;">
                    <?php else: ?>
                        <span class="text-muted">N/A</span>
                    <?php endif; ?>
                </td>
                <td>
                    <div class="fw-semibold"><?= esc($movie['title']) ?></div>
                    <small class="text-muted">Dir: <?= esc($movie['director'] ?? '-') ?></small>
                </td>
                <td><?= esc($movie['language'] ?? '-') ?></td>
                <td><?= esc($movie['certification'] ?? '-') ?></td>
                <td>
                    <?php $status = $movie['status'] ?? 'coming_soon'; ?>
                    <span class="badge bg-<?= $status === 'now_showing' ? 'success' : 'secondary' ?>"><?= ucwords(str_replace('_',' ',$status)) ?></span>
                </td>
                <td><?= ((int)($movie['is_featured'] ?? 0) === 1) ? '<span class="badge bg-warning text-dark">Yes</span>' : '<span class="badge bg-light text-dark">No</span>' ?></td>
                <td><?= esc($movie['duration_minutes']) ?></td>
                <td><?= !empty($movie['release_date']) ? date('Y-m-d', strtotime($movie['release_date'])) : '-' ?></td>
                <td class="d-flex gap-2">
                    <a href="<?= base_url('admin/movies/edit/' . $movie['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="<?= base_url('admin/movies/delete/' . $movie['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this movie?');">Delete</a>
                    <form action="<?= base_url('admin/movies/toggle-featured/' . $movie['id']) ?>" method="post" class="d-inline">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-sm <?= ((int)($movie['is_featured'] ?? 0) === 1) ? 'btn-outline-warning' : 'btn-outline-secondary' ?>">
                            <?= ((int)($movie['is_featured'] ?? 0) === 1) ? 'Unfeature' : 'Feature' ?>
                        </button>
                    </form>
                    <form action="<?= base_url('admin/movies/toggle-status/' . $movie['id']) ?>" method="post" class="d-inline">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-sm btn-outline-primary">
                            Toggle Status
                        </button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>