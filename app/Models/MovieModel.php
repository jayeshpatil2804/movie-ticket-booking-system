<?php

namespace App\Models;

use CodeIgniter\Model;

class MovieModel extends Model
{
    protected $table = 'movies';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['title', 'description', 'director', 'duration_minutes', 'release_date', 'poster_url', 'status', 'is_featured'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'title'            => 'required|min_length[3]|max_length[255]',
        'description'      => 'required',
        'director'         => 'permit_empty|max_length[255]',
        'duration_minutes' => 'required|integer',
        'release_date'     => 'required|valid_date',
        'poster_url'       => 'permit_empty|valid_url_strict',
        'status'           => 'permit_empty|in_list[now_showing,coming_soon]',
        'is_featured'      => 'permit_empty|in_list[0,1]',
    ];
}