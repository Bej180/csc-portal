# Laravel AngularJS Application

This project is a web application built using `Laravel` for the backend and `AngularJS` for the frontend. The app is designed for the **Computer Science Department** of the **Federal University of Technology Owerri**. It facilitates the uploading and manipulation of results by `staff` and `lecturers`, allows `students` to check their `results`, `CGPA`, and `academic progress`, and enables `admins` to `audit`, `manage staff`, and handle `student` management tasks.

# Description

This web application supports the following user roles and their respective abilities/functionalities:

- **Admins**: *(A Super User who)* can `audit processes`, manage `staff`, `students`, `courses`, `enrollments`, `resources` and much more.
- **Lecturers Rank**: *(A Staff who)* can `upload` and `manipulate student results`.
- **HOD Rank**: *(A Staff who)* Can perform the task of Lecturers above and as well `Approve the results` uploaded by Staff. This Role is above ordinary lecturers 
- **Class Advisors Rank**: *(A Staff who)* can `add results` of the class(es) of students assigned to them and can perform the task of Lecturers as stated above.
- **Technologists Rank**: *(A Staff who)* Can `Upload lab score` and `mark attendance` online.
- **Student**: *(A STUDENT who)* can `enroll for courses`, `check results`, `CGPA`, and `academic progress`.

Note: **HOD** can be a `lecturer`, or a `class advisor`, likewise a **lecturer** could be a `HOD` or `class advisor`. Same as a **Class Advisor** who could be a `lecturer` or `HOD`. But a **technologist** can't hold other `ranks`.

## Table of Contents

- [Prerequisites](#prerequisites)
- [Installation](#installation)
  - [Backend (Laravel)](#backend-laravel)
    - [INITIAL USERS DETAILS](#initial-users-details)
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
    git clone https://github.com/Bej180/csc-portal.git
    cd csc-portal
    ```

    **NOTE:** Ensure that `csc-portal` directory is existings, else create one by using the command:
    ```sh
    mkdir csc-portal
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

    This command generates `APP_KEY` and add it in `.env`

5. **Run database migrations:**

    

    Ensure that the database details are declared in the `.env` or copy the code and paste it in your terminal:

    ```sh
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=new_school
    DB_USERNAME=root
    DB_PASSWORD=
    ```

    Then run:
    ```sh
    php artisan migrate
    ```

    OR Run the code below to automatically create the database, tables and populate the initial user accounts (`admin`, `student`, `staff`).
    ```sh
    create database new_school
    php artisan migrate:refresh --seed
    ```

    *NOTE:* Ensure that your MySQL Server has been started
    
## INITIAL USERS DETAILS
    **Administrator Account Details**
    email: `admin@cscfuto.com` password: `admin`

    **Lecturer Account**
    email: `lecturer@cscfuto.com` password: `lecturer`

    **Class Advisor Account**
    email: `advisor@cscfuto.com` password: `lecturer`

    **HOD Account**
    email: `hod@cscfuto.com` password: `hod`

    **STUDENTS Account**
    email: `student1@cscfuto.com` password: `student1`
    email: `student2@cscfuto.com` password: `student2`

### Frontend (AngularJS)

**Install Node.js dependencies:**

    ```sh
    npm install
    ```

## Running the Application

1. **Start the Laravel development server:**

    ```sh
    php artisan serve
    ```

    The server will start at `http://localhost:8000`.
    You should see your application running.


3. **Start MySQL Server:**

    Ensure your MySQL server is running. If you start it using mysqld, you can start it by executing:
    
    ```sh
    mysqld
    ```

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
