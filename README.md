# CPS-League-API

## Change "your_api_key_here" to the current API key in the .env file.
### If you don't, it will display "summoner not found" as the application cannot search.
## When changing the API, you probably need to run the following 4 commands: 
* php artisan config:clear
* php artisan cache:clear
* php artisan config:cache
* php artisan serve

### If migrations have not been run, it won't be able to fetch data from the database models - Therefore, it would also be a good idea to run:
* php artisan migrate:fresh
### If any changes have been made, or if the database models have more than 500 lines - This looks like the maximum amount of lines.

## We know "withoutVerifying" is not the correct way to call our HTTP method responses, but we had trouble making all of our systems run the code, so we went with it.