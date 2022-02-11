#Top Movies
    
    1. Git clone application
    2. Enter into the directory
    3. Run: composer install
    4. Run: symfony server:start

    5. API endpoints in browser: 
        - Movie list: http://127.0.0.1:8000/api/movie/list
        - Movie list save: http://127.0.0.1:8000/api/movie/save-list
        - Movie saved list: http://127.0.0.1:8000/api/movie/saved-list

    6. Run this command with Cron if you want to refresh the database movies data:
        - php bin/console save-movies
    6.1 Run this command with parameter, for example (parameter is page number for the API):
        - php bin/console save-movies 2
        - php bin/console save-movies 3