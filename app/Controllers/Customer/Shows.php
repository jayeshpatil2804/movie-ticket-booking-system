<?php

namespace App\Controllers\Customer;

use App\Controllers\BaseController;
use App\Models\ShowModel;

class Shows extends BaseController
{
    protected $showModel;

    public function __construct()
    {
        $this->showModel = new ShowModel();
    }

    public function index()
    {
        $data = [
            'shows' => $this->showModel->getShowsWithDetails()
        ];
        echo view('templates/header');
        echo view('customer/shows/list', $data);
        echo view('templates/footer');
    }

    public function details($id)
    {
        $show = $this->showModel->select('shows.*, movies.title as movie_title, movies.description, movies.poster_url, screens.name as screen_name, cinemas.name as cinema_name')
                                ->join('movies', 'movies.id = shows.movie_id')
                                ->join('screens', 'screens.id = shows.screen_id')
                                ->join('cinemas', 'cinemas.id = screens.cinema_id')
                                ->find($id);

        if (!$show) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Cannot find the show with ID: ' . $id);
        }

        $data = ['show' => $show];
        echo view('templates/header');
        echo view('customer/shows/details', $data);
        echo view('templates/footer');
    }
}