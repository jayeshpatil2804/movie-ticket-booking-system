<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MovieModel;
use App\Models\ShowModel;
use App\Models\ScreenModel;
use App\Models\CinemaModel;

class Shows extends BaseController
{
    protected $movieModel;
    protected $showModel;
    protected $screenModel;
    protected $cinemaModel;

    public function __construct()
    {
        $this->movieModel = new MovieModel();
        $this->showModel = new ShowModel();
        $this->screenModel = new ScreenModel();
        $this->cinemaModel = new CinemaModel();
        helper(['form']);
    }

    public function index()
    {
        $data = [
            'shows' => $this->showModel->getShowsWithDetails(),
        ];
        echo view('templates/admin_header');
        echo view('admin/shows/list', $data);
        echo view('templates/footer');
    }

    public function create()
    {
        $data = [
            'movies' => $this->movieModel->findAll(),
            'cinemas' => $this->cinemaModel->findAll(),
            'validation' => \Config\Services::validation()
        ];
        echo view('templates/admin_header');
        echo view('admin/shows/form', $data);
        echo view('templates/footer');
    }

    public function edit(int $id)
    {
        $show = $this->showModel->getShowWithDetails($id);
        if (empty($show)) {
            return redirect()->to('admin/shows')->with('error', 'Show not found.');
        }

        $data = [
            'show' => $show,
            'movies' => $this->movieModel->findAll(),
            'cinemas' => $this->cinemaModel->findAll(),
            'screens' => $this->screenModel->where('cinema_id', $show['cinema_id'])->findAll(),
            'validation' => \Config\Services::validation()
        ];
        echo view('templates/admin_header');
        echo view('admin/shows/form', $data);
        echo view('templates/footer');
    }
    
    public function store()
    {
        if (!$this->validate($this->showModel->getValidationRules())) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $data = $this->request->getPost();
        if ($this->showModel->save($data)) {
            return redirect()->to('admin/shows')->with('success', 'Show added successfully.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to add show. Please check your inputs.');
        }
    }

    public function update(int $id)
    {
        if (!$this->validate($this->showModel->getValidationRules())) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $data = $this->request->getPost();
        if ($this->showModel->update($id, $data)) {
            return redirect()->to('admin/shows')->with('success', 'Show updated successfully.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to update show. Please check your inputs.');
        }
    }

    public function delete(int $id)
    {
        $this->showModel->delete($id);
        return redirect()->to('admin/shows')->with('success', 'Show deleted successfully.');
    }
}