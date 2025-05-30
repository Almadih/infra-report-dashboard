# Infra Report

## Overview

The Infra Report is a web application designed to facilitate the reporting, tracking, and visualization of various types of damages and incidents. It provides users with an intuitive interface to submit reports, view heatmaps, and monitor the status of reported issues. The goal of this project is to empower communities and organizations in Sudan by providing a centralized platform for damage assessment and situational awareness.

## Goals

- Enable users to submit detailed reports about damages or incidents.
- Provide aggregated visualization of reports on maps and heatmaps.
- Track the status and severity of reported issues.
- Facilitate efficient management and response by administrators and stakeholders.

## Getting Started

### Prerequisites

- PHP (compatible version for Laravel framework)
- Composer
- Node.js and npm
- A database server (PostgreSQL with PostGIS extension)

### Installation

1. Clone the repository:

    ```bash
    git clone <repository-url>
    cd dashboard
    ```

2. Install PHP dependencies using Composer:

    ```bash
    composer install
    ```

3. Install JavaScript dependencies:

    ```bash
    npm install
    ```

4. Copy the example environment file and configure your environment variables:

    ```bash
    cp .env.example .env
    ```

    Update `.env` with your database credentials and other necessary configuration.

5. Generate the application key:

    ```bash
    php artisan key:generate
    ```

6. Run database migrations and seeders:

    ```bash
    php artisan migrate --seed
    ```

7. Build frontend assets:

    ```bash
    npm run build
    ```

8. Start the development server:
    ```bash
    php artisan serve
    ```
    The application will be accessible at `http://localhost:8000`.

### Usage

- Register or log in to the application.
- View reports and heatmaps on the dashboard.
- Manage reports and monitor their status.

## Contributing

Contributions are welcome! Please fork the repository and submit pull requests for any improvements or bug fixes.

## License

This project is licensed under the MIT License.
