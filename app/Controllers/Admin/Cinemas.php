<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CinemaModel;

class Cinemas extends BaseController
{
    protected $cinemaModel;

    public function __construct()
    {
        $this->cinemaModel = new CinemaModel();
    }

    public function index()
    {
        $data = [
            'cinemas' => $this->cinemaModel->findAll(),
        ];
        echo view('templates/admin_header');
        echo view('admin/cinemas/list', $data);
        echo view('templates/footer');
    }

    public function create()
    {
        echo view('templates/admin_header');
        echo view('admin/cinemas/form');
        echo view('templates/footer');
    }

    public function store()
    {
        $data = $this->request->getPost();
        if ($this->cinemaModel->insert($data)) {
            return redirect()->to('admin/cinemas')->with('success', 'Cinema added successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to add cinema.');
        }
    }

    public function edit(int $id)
    {
        $data = [
            'cinema' => $this->cinemaModel->find($id),
        ];
        echo view('templates/admin_header');
        echo view('admin/cinemas/form', $data);
        echo view('templates/footer');
    }

    public function update(int $id)
    {
        $data = $this->request->getPost();
        if ($this->cinemaModel->update($id, $data)) {
            return redirect()->to('admin/cinemas')->with('success', 'Cinema updated successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to update cinema.');
        }
    }

    public function delete(int $id)
    {
        $this->cinemaModel->delete($id);
        return redirect()->to('admin/cinemas')->with('success', 'Cinema deleted successfully.');
    }
}