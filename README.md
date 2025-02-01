# Movie Recommendation System
A Laravel-based movie recommendation system that suggests movies based on user interests using KNN (Euclidean & Manhattan Distance) and K-Means clustering.

## Required Configuration
- **PHP** >= 8.1
- **Laravel** 10

## Installation
Follow these steps to set up the project:

**Clone the Repository**:
```bash
git clone <repository-url>
cd <project-directory>
```

**Install Dependencies**:
Run the following commands to install the required packages:
```bash
composer update
composer install
```

**Generate Application Key**:
Generate the Laravel application key: This will update the APP_KEY in your .env file.
```bash
php artisan key:generate
```

**Set Up Storage Link**:
Create a symbolic link for the storage directory:
```bash
php artisan storage:link
```

**Configure Database**:
Update your .env file with your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

**Run Migrations**:
Initialize the database by running migrations:
```bash
php artisan migrate
```

To seed the database with initial data, use:
```bash
php artisan migrate:refresh --seed
```

## Commands

**Create a Controller with CRUD Operations**:
To generate a controller with CRUD methods, use:
```bash
php artisan make:controller CastController -r
```

**Create a Model with Migration**:
To create a model along with its migration file, use:
```bash
php artisan make:model Cast -m
```

## Project Features

1. **Multi-Authentication System**
   - Admin, Staff, and User roles are implemented.
   - Laravel Breeze is used for authentication scaffolding.
   - Email Verification is enabled for users.
   - Login is available for all roles, but Registration is only for users.

2. **Admin CRUD Operations**
   - Admins can perform CRUD operations for Users and Staff.

3. **CRUD Management**
   - The following CRUD operations are managed:
     - Cast CRUD
     - Genre CRUD
     - Production Company CRUD
     - Language CRUD
     - Director CRUD
     - Movie CRUD
     - Country CRUD
   - A Dashboard is also included.

4. **User Profile and Interest**
   - Users can manage their profiles and interests.

5. **Super Admin and Support System**
   - A Super Admin role has been added.
   - The support system has been updated.

6. **Recommendation System**
   - Recommendations are based on user interests.
   - KNN (K-Nearest Neighbors) algorithm is used for recommendations:
     - Euclidean Distance
     - Manhattan Distance
     - KMeans with Euclidean Distance
     - KMeans with Manhattan Distance

## Contributing
If you'd like to contribute to this project, please follow these steps:
1. Fork the repository.
2. Create a new branch for your feature or bugfix.
3. Submit a pull request.

## License
This project is open-source and available under the MIT License.

## Support
For any issues or questions, please contact the development team or open an issue on the repository.

