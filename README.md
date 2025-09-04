# Movie Ticket Booking System

This is a simple, full-stack movie ticket booking system built with PHP and the CodeIgniter 4 framework.

## Features

- **User Authentication:** Secure registration and login for both customers and administrators.
- **Admin Panel:**
    - CRUD (Create, Read, Update, Delete) functionality for Movies.
    - CRUD for Shows (linking movies to screens).
    - Role-based access control to protect admin routes.
- **Customer Side:**
    - Browse available movie shows.
    - Select seats and book tickets for a show.
    - View booking history.
- **Technology Stack:**
    - **Backend:** PHP, CodeIgniter 4
    - **Database:** MySQL
    - **Frontend:** Bootstrap (for a clean, responsive UI)

## Setup and Installation

1.  **Clone the repository:**
    ```bash
    git clone [https://github.com/your-username/movie-booking-system.git](https://github.com/your-username/movie-booking-system.git)
    cd movie-booking-system
    ```

2.  **Install PHP dependencies:**
    ```bash
    composer install
    ```

3.  **Database Configuration:**
    -   Copy the `.env.example` file to `.env`: `cp .env.example .env`
    -   Open the new `.env` file and set your database credentials (`database.default.username`, `database.default.password`, etc.).
    -   Create the database in MySQL: `CREATE DATABASE movie_booking_db;`

4.  **Run Migrations:**
    ```bash
    php spark migrate
    ```

5.  **Create the Admin User:**
    -   For a quick start, run the one-time script to create the default admin user.
    -   Access the following URL in your browser: `http://localhost:8080/auth/createAdminUser`
    -   **Important:** The function will output the admin password. **Delete or comment out this route in production!**
    -   Admin Login: `admin@example.com` / `admin_password`

6.  **Run the application:**
    ```bash
    php spark serve
    ```
    -   Visit `http://localhost:8080` to see the application.

## Usage

-   **Admin:** Log in with the admin credentials. You can then access the `admin/dashboard` to manage movies and shows.
-   **Customer:** Register a new account or log in. You can browse shows on the homepage and proceed to book tickets.

## License

This project is open source and available under the MIT License.