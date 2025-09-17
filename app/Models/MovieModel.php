<?php

namespace App\Models;

use CodeIgniter\Model;

class MovieModel extends Model
{
    protected $table = 'movies';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'title', 'description', 'director', 'duration_minutes', 'release_date',
        'poster_url', 'backdrop_url', 'trailer_url', 'language', 'certification', 'genre',
        'status', 'is_featured'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'title'            => 'required|min_length[3]|max_length[255]',
        'description'      => 'permit_empty',
        'director'         => 'permit_empty|max_length[255]',
        'duration_minutes' => 'required|integer',
        'release_date'     => 'permit_empty|valid_date',
        'poster_url'       => 'permit_empty|valid_url_strict',
        'backdrop_url'     => 'permit_empty|valid_url_strict',
        'trailer_url'      => 'permit_empty|valid_url_strict',
        'language'         => 'permit_empty|max_length[50]',
        'certification'    => 'permit_empty|max_length[10]',
        'genre'            => 'permit_empty|max_length[255]',
        'status'           => 'permit_empty|in_list[now_showing,coming_soon]',
        'is_featured'      => 'permit_empty|in_list[0,1]',
    ];
}