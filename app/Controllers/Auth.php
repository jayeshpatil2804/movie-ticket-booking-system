<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class Auth extends Controller
{
    public function __construct()
    {
        helper(['form', 'url']);
    }

    public function login()
    {
        $data = [
            'validation' => \Config\Services::validation()
        ];
        echo view('templates/header');
        echo view('auth/login', $data);
        echo view('templates/footer');
    }

    public function processLogin()
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $userModel = new UserModel();
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $userModel->where('email', $email)->first();

        if ($user && password_verify($password, $user['password'])) {
            $session = session();
            $sessionData = [
                'user_id'    => $user['id'],
                'user_name'  => $user['name'],
                'user_email' => $user['email'],
                'user_role'  => $user['role'],
                'isLoggedIn' => true,
            ];
            $session->set($sessionData);

            if ($user['role'] === 'admin') {
                return redirect()->to(base_url('admin/dashboard'));
            } else {
                return redirect()->to(base_url('/'));
            }
        } else {
            return redirect()->back()->withInput()->with('error', 'Invalid email or password.');
        }
    }

    public function register()
    {
        $data = [
            'validation' => \Config\Services::validation()
        ];
        echo view('templates/header');
        echo view('auth/register', $data);
        echo view('templates/footer');
    }

    public function processRegister()
    {
        $rules = [
            'name'     => 'required|min_length[3]|max_length[255]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'pass_confirm' => 'required|matches[password]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $userModel = new UserModel();
        $data = [
            'name'     => $this->request->getPost('name'),
            'email'    => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'role'     => 'customer'
        ];

        $userModel->save($data);

        return redirect()->to(base_url('auth/login'))->with('success', 'Registration successful! You can now log in.');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('auth/login'))->with('success', 'You have been logged out.');
    }

    public function createAdminUser()
    {
        $userModel = new UserModel();
        // Check if admin user already exists to prevent duplicates
        if ($userModel->where('email', 'admin@example.com')->first() === null) {
            $plaintext_password = 'admin_password';
            $data = [
                'name'     => 'Admin User',
                'email'    => 'admin@example.com',
                'password' => $plaintext_password,
                'role'     => 'admin'
            ];
            $userModel->save($data);

            $admin_user = $userModel->where('email', 'admin@example.com')->first();
            $hashed_password = $admin_user['password'];

            echo "<h3>Admin User Created Successfully!</h3>";
            echo "Username: admin@example.com <br>";
            echo "Plaintext Password (for demo): {$plaintext_password} <br>";
            echo "Hashed Password: {$hashed_password} <br>";
            echo "Please remove this function in a production environment.";
        } else {
            echo "Admin user already exists.";
        }
    }
}