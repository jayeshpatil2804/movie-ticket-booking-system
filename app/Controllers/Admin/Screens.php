<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ScreenModel;
use App\Models\CinemaModel;
use CodeIgniter\API\ResponseTrait;


class Screens extends BaseController
{
    use ResponseTrait;

    protected $screenModel;
    protected $cinemaModel;

    public function __construct()
    {
        $this->screenModel = new ScreenModel();
        $this->cinemaModel = new CinemaModel();
    }

    public function index()
    {
        $data = [
            'screens' => $this->screenModel->getScreensWithDetails(),
        ];
        echo view('templates/admin_header');
        echo view('admin/screens/list', $data);
        echo view('templates/footer');
    }

    public function create()
    {
        $data = [
            'cinemas' => $this->cinemaModel->findAll(),
        ];
        echo view('templates/admin_header');
        echo view('admin/screens/form', $data);
        echo view('templates/footer');
    }

    public function store()
    {
        $data = $this->request->getPost();
        if ($this->screenModel->insert($data)) {
            return redirect()->to('admin/screens')->with('success', 'Screen added successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to add screen.');
        }
    }

    public function edit(int $id)
    {
        $data = [
            'screen' => $this->screenModel->find($id),
            'cinemas' => $this->cinemaModel->findAll(),
        ];
        echo view('templates/admin_header');
        echo view('admin/screens/form', $data);
        echo view('templates/footer');
    }

    public function update(int $id)
    {
        $data = $this->request->getPost();
        if ($this->screenModel->update($id, $data)) {
            return redirect()->to('admin/screens')->with('success', 'Screen updated successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to update screen.');
        }
    }

    public function delete(int $id)
    {
        $this->screenModel->delete($id);
        return redirect()->to('admin/screens')->with('success', 'Screen deleted successfully.');
    }
    /**
     * API endpoint to get screens for a specific cinema.
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function getScreensByCinema(int $cinemaId)
    {
        $screens = $this->screenModel->where('cinema_id', $cinemaId)->findAll();
        return $this->respond($screens);
    }
}
