## The Force Track

A simple reporting system made with Laravel 9 and Vue 3.

Features:

- Browse issues using pagination and filter by status
- Add new issues and comments
- Update the issue status as admin
- Get e-mail notification for the issue status update
- Authentication and authorization system with password reset

## Instruction

1. Fill the "DOCKER_" variables in the .env file

2. Use Docker to prepare the environment

<code>docker compose up -d</code>

3. Enter the Docker container

<code>docker exec -it php /bin/bash</code>

4. Install dependencies

<code>composer install</code>

<code>npm install</code>

5. Insert default data to the database

<code>php artisan migrate --seed</code>

6. Insert test data to the database

<code>php artisan testDatabase:create</code>

7. Run application

<code>npm run dev</code>

8. Run queue

<code>php artisan queue:work --queue=emails;</code>

9. Login with any account from the users table, the password is "password"

## Screenshots

![Index page](/screenshots/1.png)

![Add issue](/screenshots/2.png)

![Issue details](/screenshots/3.png)

![Login page](/screenshots/4.png)