# Laravel AngularJS Application

This project is a web application built using Laravel for the backend and AngularJS for the frontend. This README provides instructions on how to set up and run the application, even if you have never used Laravel or AngularJS before.

## Table of Contents

- [Prerequisites](#prerequisites)
- [Installation](#installation)
  - [Backend (Laravel)](#backend-laravel)
  - [Frontend (AngularJS)](#frontend-angularjs)
- [Running the Application](#running-the-application)
- [Usage](#usage)
- [Contributing](#contributing)
- [License](#license)

## Prerequisites

Before you begin, ensure you have met the following requirements:

- A web server like Apache or Nginx (for production).
- [Git](https://git-scm.com/) installed on your machine.

### Installing Composer

Composer is a dependency manager for PHP. Follow these steps to install Composer:

#### On Windows

1. Download and run the [Composer-Setup.exe](https://getcomposer.org/Composer-Setup.exe) file.

#### On macOS

1. Open your terminal.
2. Run the following command:

    ```sh
    brew install composer
    ```

#### On Linux

1. Open your terminal.
2. Run the following commands:

    ```sh
    sudo apt update
    sudo apt install php-cli unzip
    curl -sS https://getcomposer.org/installer | php
    sudo mv composer.phar /usr/local/bin/composer
    ```

### Installing Node.js and npm

Node.js is a JavaScript runtime, and npm is the Node.js package manager. Follow these steps to install Node.js and npm:

#### On Windows and macOS

1. Download the installer from the [Node.js website](https://nodejs.org/).
2. Run the installer and follow the prompts.

#### On Linux

1. Open your terminal.
2. Run the following commands:

    ```sh
    sudo apt update
    sudo apt install nodejs npm
    ```

## Installation

### Backend (Laravel)

1. **Clone the repository:**

    ```sh
    git clone https://github.com/Albrite/csc-portal.git
    cd your-repo-name
    ```

2. **Install PHP dependencies:**

    ```sh
    composer install
    ```

3. **Set up the environment file:**

    ```sh
    cp .env.example .env
    ```

    Open the `.env` file and configure your database and other settings.

4. **Generate an application key:**

    ```sh
    php artisan key:generate
    ```

5. **Run database migrations:**

    ```sh
    php artisan migrate
    ```

### Frontend (AngularJS)

1. **Navigate to the AngularJS directory:**

    ```sh
    cd public/angularjs-app
    ```

2. **Install Node.js dependencies:**

    ```sh
    npm install
    ```

## Running the Application

1. **Start the Laravel development server:**

    ```sh
    php artisan serve
    ```

    The server will start at `http://localhost:8000`.

2. **Serve the AngularJS application:**

    Since AngularJS files are served as static files within the Laravel application, there's no need for a separate development server for AngularJS. Ensure that your AngularJS application files are in the `public/angularjs-app` directory.

3. **Open your browser and navigate to:**

    ```
    http://localhost:8000
    ```

    You should see your application running.

## Usage

Once the application is running, you can interact with the frontend through the AngularJS interface and the backend will handle API requests via Laravel.

### Example Pages

- **Home Page:** Provides an overview of the application.
- **User Management:** Allows you to manage users (e.g., create, read, update, delete users).
- **Settings:** Configure application settings.

## Contributing

If you would like to contribute to this project, please fork the repository and create a pull request. You can also open issues for any bugs or feature requests.

1. **Fork the repository**
2. **Create a new branch**

    ```sh
    git checkout -b feature-branch
    ```

3. **Make your changes and commit them**

    ```sh
    git commit -m "Description of your changes"
    ```

4. **Push to your branch**

    ```sh
    git push origin feature-branch
    ```

5. **Create a pull request**

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.
