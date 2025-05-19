# Job Board API

A flexible REST API for managing a job board system. It includes features for managing companies, candidates, job listings, and applications, with optimized performance.


## Features
- Company and Candidate Registration
- Secure Authentication
- Job Listing Management (Create, Update, Delete, and View)
- Candidate Applications
- Public Job Listings with Filters (keyword, location, remote status)
- Caching for better performance
- Error handling and validation

---

## Setup Instructions

### 1. Clone the Repository
```bash
git clone https://github.com/nnahgodspower122/job-board-api.git
cd job-board-api
```

### 2. Install Dependencies
Make sure you have [Composer](https://getcomposer.org/) installed, then run:
```bash
composer install
```

### 3. Set Up Environment Variables
Copy the `.env.example` file to create a `.env` file and configure the following:
```bash
cp .env.example .env
```
Update the `.env` file with your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=job_board
DB_USERNAME=your_username
DB_PASSWORD=your_password

APP_URL=http://127.0.0.1:8000
CACHE_DRIVER=file
```

### 4. Generate Application Key
```bash
php artisan key:generate
```

### 5. Run Database Migrations
```bash
php artisan migrate
```

### 7. Serve the Application
Run the following command to start the local development server:
```bash
php artisan serve
```

The API will be available at `http://127.0.0.1:8000`.



## Brief Explanation of Design Choices

### 1. Eloquent ORM
Used Laravel's Eloquent ORM for database interactions, ensuring clean and maintainable code.

### 2. Request Validation**
Used Laravel's `FormRequest` classes for input validation to centralize and simplify validation logic.

### 3. Authentication
- Used Laravel's built-in authentication system for secure registration and login processes.
- Passwords are hashed using `bcrypt`.

### 4. Caching
- Implemented caching for public job listings to reduce database load and improve performance. Cached results are refreshed every 5 minutes.

### 5. Modular Controller Design**
Each resource (e.g., Job, Company, Candidate) has its own controller, following the single responsibility principle.

### 6. Pagination
Incorporated pagination for job listings to improve performance and allow for efficient data retrieval when dealing with large datasets.

### 7. Error Handling
Used a centralized error response structure (`HttpResponses` trait) to ensure consistency in API responses.


## Assumptions

1. Public Listings:
   - Only jobs that have a `published_at` timestamp are considered public.
   - Filters like `location`, `is_remote`, and `keyword` apply only to public jobs.

2. Database Design:
   - Each job can have multiple applications.
   - A company can post multiple jobs.


## Improvements

### 1. API Documentation
- Add Swagger or Postman documentation for better developer onboarding.

### 2. Testing
- Improve test coverage with unit tests, feature tests, and integration tests.

### 3. Notifications
- Implement email or in-app notifications for candidates and companies (e.g., when a candidate applies for a job).

### 4. Role-Based Access Control
- Introduce roles (e.g., admin, company, candidate) to manage permissions more effectively.


## Contributing
1. Fork the repository.
2. Create a new feature branch (`git checkout -b feature-name`).
3. Commit your changes (`git commit -m 'Add new feature'`).
4. Push to the branch (`git push origin feature-name`).
5. Create a pull request.

