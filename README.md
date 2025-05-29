# CPS-League-API

## Guide for running the project:
### first make sure you are running docker on your system.
### Next you will have to go into infrastructure and make docker run:
* cd. \infrastructure\
* docker compose up -d
### Next you will have to go back and run:
* cd..

### and then open cps - league - api folder:
* cd. \CPS-League-API\
### next you will have to change the riot api key under the environment file: .env. if you dont have an api key:
### ask one of the group participant for one and you will have access to it for 24 hours.
### next you will have to runt the following commands:
* php artisan config:clear
* php artisan cache:clear
* php artisan config:cache
* php artisan serve

### if it does not work try running:
* php artisan migrage:fresh
### and run the other 4 commands again
### after that the wep application should run and have access to everything.
### to test if you can see a profile try to write a profile into the search field
### A profile from one of the group members is provided here: KarateStrate-euw

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