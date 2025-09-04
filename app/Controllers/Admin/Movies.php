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
            'duration_minutes' => $this->request->getPost('duration_minutes'),
            'release_date'     => $this->request->getPost('release_date'),
            'poster_url'       => $this->request->getPost('poster_url'),
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
            'duration_minutes' => $this->request->getPost('duration_minutes'),
            'release_date'     => $this->request->getPost('release_date'),
            'poster_url'       => $this->request->getPost('poster_url'),
        ]);

        return redirect()->to(base_url('admin/movies'))->with('success', 'Movie updated successfully.');
    }

    public function delete($id)
    {
        $this->movieModel->delete($id);
        return redirect()->to(base_url('admin/movies'))->with('success', 'Movie deleted successfully.');
    }
}