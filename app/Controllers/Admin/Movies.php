<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MovieModel;

class Movies extends BaseController
{
    protected $movieModel;

    public function __construct()
    {
        $this->movieModel = new MovieModel();
        helper(['form']);
    }

    public function index()
    {
        $data = [
            'movies' => $this->movieModel->findAll()
        ];
        echo view('templates/header');
        echo view('admin/movies/list', $data);
        echo view('templates/footer');
    }

    public function create()
    {
        $data = [
            'validation' => \Config\Services::validation()
        ];
        echo view('templates/header');
        echo view('admin/movies/form', $data);
        echo view('templates/footer');
    }

    public function store()
    {
        if (!$this->validate($this->movieModel->getValidationRules())) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $this->movieModel->save([
            'title'            => $this->request->getPost('title'),
            'description'      => $this->request->getPost('description'),
            'director'         => $this->request->getPost('director'),
            'language'         => $this->request->getPost('language'),
            'certification'    => $this->request->getPost('certification'),
            'genre'            => $this->request->getPost('genre'),
            'duration_minutes' => $this->request->getPost('duration_minutes'),
            'release_date'     => $this->request->getPost('release_date'),
            'poster_url'       => $this->request->getPost('poster_url'),
            'backdrop_url'     => $this->request->getPost('backdrop_url'),
            'trailer_url'      => $this->request->getPost('trailer_url'),
            'status'           => $this->request->getPost('status'),
            'is_featured'      => (int) $this->request->getPost('is_featured') === 1 ? 1 : 0,
        ]);

        return redirect()->to(base_url('admin/movies'))->with('success', 'Movie added successfully.');
    }

    public function edit($id)
    {
        $data = [
            'movie'      => $this->movieModel->find($id),
            'validation' => \Config\Services::validation()
        ];

        if (empty($data['movie'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Cannot find the movie item: ' . $id);
        }

        echo view('templates/header');
        echo view('admin/movies/form', $data);
        echo view('templates/footer');
    }

    public function update($id)
    {
        if (!$this->validate($this->movieModel->getValidationRules())) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $this->movieModel->update($id, [
            'title'            => $this->request->getPost('title'),
            'description'      => $this->request->getPost('description'),
            'director'         => $this->request->getPost('director'),
            'language'         => $this->request->getPost('language'),
            'certification'    => $this->request->getPost('certification'),
            'genre'            => $this->request->getPost('genre'),
            'duration_minutes' => $this->request->getPost('duration_minutes'),
            'release_date'     => $this->request->getPost('release_date'),
            'poster_url'       => $this->request->getPost('poster_url'),
            'backdrop_url'     => $this->request->getPost('backdrop_url'),
            'trailer_url'      => $this->request->getPost('trailer_url'),
            'status'           => $this->request->getPost('status'),
            'is_featured'      => (int) $this->request->getPost('is_featured') === 1 ? 1 : 0,
        ]);

        return redirect()->to(base_url('admin/movies'))->with('success', 'Movie updated successfully.');
    }

    public function delete($id)
    {
        $this->movieModel->delete($id);
        return redirect()->to(base_url('admin/movies'))->with('success', 'Movie deleted successfully.');
    }

    /**
     * Toggle featured flag (AJAX or normal request)
     */
    public function toggleFeatured($id)
    {
        $movie = $this->movieModel->find($id);
        if (!$movie) {
            return redirect()->back()->with('error', 'Movie not found');
        }
        $new = ((int)($movie['is_featured'] ?? 0) === 1) ? 0 : 1;
        $this->movieModel->update($id, ['is_featured' => $new]);
        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success' => true, 'is_featured' => $new]);
        }
        return redirect()->to(base_url('admin/movies'))->with('success', 'Featured updated');
    }

    /**
     * Toggle status between now_showing and coming_soon
     */
    public function toggleStatus($id)
    {
        $movie = $this->movieModel->find($id);
        if (!$movie) {
            return redirect()->back()->with('error', 'Movie not found');
        }
        $current = $movie['status'] ?? 'coming_soon';
        $new = $current === 'now_showing' ? 'coming_soon' : 'now_showing';
        $this->movieModel->update($id, ['status' => $new]);
        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success' => true, 'status' => $new]);
        }
        return redirect()->to(base_url('admin/movies'))->with('success', 'Status updated');
    }
}