<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="text-center"><?= isset($movie) ? 'Edit Movie' : 'Add New Movie' ?></h3>
            </div>
            <div class="card-body">
                <?php if(isset($validation)): ?>
                    <div class="alert alert-danger"><?= $validation->listErrors() ?></div>
                <?php endif; ?>
                
                <?= form_open(isset($movie) ? 'admin/movies/update/' . $movie['id'] : 'admin/movies/store') ?>
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" id="title" value="<?= old('title', $movie['title'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" class="form-control" id="description" rows="3"><?= old('description', $movie['description'] ?? '') ?></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="language" class="form-label">Language</label>
                            <input type="text" name="language" class="form-control" id="language" value="<?= old('language', $movie['language'] ?? '') ?>" placeholder="e.g., Hindi">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="certification" class="form-label">Certification</label>
                            <input type="text" name="certification" class="form-control" id="certification" value="<?= old('certification', $movie['certification'] ?? '') ?>" placeholder="e.g., U/A">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="genre" class="form-label">Genre</label>
                        <input type="text" name="genre" class="form-control" id="genre" value="<?= old('genre', $movie['genre'] ?? '') ?>" placeholder="e.g., Action, Drama">
                    </div>
                    <div class="mb-3">
                        <label for="director" class="form-label">Director</label>
                        <input type="text" name="director" class="form-control" id="director" value="<?= old('director', $movie['director'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label for="duration_minutes" class="form-label">Duration (minutes)</label>
                        <input type="number" name="duration_minutes" class="form-control" id="duration_minutes" value="<?= old('duration_minutes', $movie['duration_minutes'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label for="release_date" class="form-label">Release Date</label>
                        <input type="date" name="release_date" class="form-control" id="release_date" value="<?= old('release_date', $movie['release_date'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label for="poster_url" class="form-label">Poster URL</label>
                        <input type="url" name="poster_url" class="form-control" id="poster_url" value="<?= old('poster_url', $movie['poster_url'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label for="backdrop_url" class="form-label">Backdrop URL</label>
                        <input type="url" name="backdrop_url" class="form-control" id="backdrop_url" value="<?= old('backdrop_url', $movie['backdrop_url'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label for="trailer_url" class="form-label">Trailer URL</label>
                        <input type="url" name="trailer_url" class="form-control" id="trailer_url" value="<?= old('trailer_url', $movie['trailer_url'] ?? '') ?>">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select">
                                <?php $status = old('status', $movie['status'] ?? 'coming_soon'); ?>
                                <option value="now_showing" <?= $status === 'now_showing' ? 'selected' : '' ?>>Now Showing</option>
                                <option value="coming_soon" <?= $status === 'coming_soon' ? 'selected' : '' ?>>Coming Soon</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3 d-flex align-items-end">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="is_featured" name="is_featured" <?= (int)old('is_featured', $movie['is_featured'] ?? 0) === 1 ? 'checked' : '' ?>>
                                <label class="form-check-label" for="is_featured">
                                    Featured on Homepage
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary"><?= isset($movie) ? 'Update Movie' : 'Save Movie' ?></button>
                    </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>