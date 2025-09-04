<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h1>Admin Dashboard</h1>
            <p>Welcome to the admin panel. Use the navigation links to manage the system.</p>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Manage Movies</h5>
                    <p class="card-text">Add, edit, and delete movie entries.</p>
                    <a href="<?= base_url('admin/movies') ?>" class="btn btn-primary">Go to Movies</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Manage Shows</h5>
                    <p class="card-text">Create and manage movie showtimes.</p>
                    <a href="<?= base_url('admin/shows') ?>" class="btn btn-primary">Go to Shows</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Manage Cinemas & Screens</h5>
                    <p class="card-text">Handle cinema and screen configurations.</p>
                    <a href="#" class="btn btn-secondary disabled">Coming Soon</a>
                </div>
            </div>
        </div>
    </div>
</div>