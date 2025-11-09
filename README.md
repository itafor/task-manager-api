A simple RESTful API for a task management system.

The RESTful API can be tested in two ways.

Testing with a remote base URL (http://staging.evaltopup.com)

1. Inport the postman collection into your postman workspace
2. Test all the available endpoints with this base URL: http://staging.evaltopup.com

Testing Locally

1. Clone the Repository
   Open your terminal and run: git clone https://github.com/itafor/task-manager-api.git
   Then navigate into the project: cd task-manager-api
2. Install Dependencies: composer install
   If you get an error like composer not found, install Composer first:
3. Create the .env File:
   cp .env.example .env
   Update the your database details in .env

4. Generate Application Key
   php artisan key:generate (This will update the APP_KEY in your .env file automatically.)

5. Run database migrations: php artisan migrate
6. Run database seeder: php artisan db:seed. This will create dummy users and tasks
7. Start the Laravel built-in server: php artisan serve

8. Run feature tests for Register, Create task and viewTask details endpoint:
   php artisan test
   Running the above commmand will test the Register endpoint: /auth/register
   Create Task endpoint: /task/create and view Task details endpoint /task/details/:taskId

    You may decide to test each feature separately with this command: php artisan test --filter it_can_register_a_user
