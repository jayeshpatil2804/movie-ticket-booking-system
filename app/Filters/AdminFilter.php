<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminFilter implements FilterInterface
{
    /**
     * Do whatever you want here
     *
     * @param RequestInterface  $request
     * @param array|null        $arguments
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url('auth/login'))->with('error', 'You must be logged in to access the admin panel.');
        }

        // Check if user has admin role
        if (session()->get('user_role') !== 'admin') {
            return redirect()->to(base_url('/'))->with('error', 'Access denied. You do not have administrator privileges.');
        }
    }

    /**
     * Allows us to do some processing after the controller is executed
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed after controller execution
    }
}