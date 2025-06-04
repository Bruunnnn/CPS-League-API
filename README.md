# CPS-League-API - Peter's branch

## Guide for running the project:
### First, make sure you are running docker on your system.
You will need to run a local database through PostgreSQL called simple placeholder names:
* your_database
* your_username
* your_password
### You will have to go into the infrastructure directory and make docker run:
* cd .\infrastructure\
* docker compose up -d
  Then go back:
* cd..

### Then locate the Laravel directory:
* cd .\CPS-League-API\

You will have to change the riot api key under the environment file: .env. which is available for 24hours.
**if you don't have an api key:**
* Option 1. Ask one of the group participant.
* Option 2. Make your own profile.
### Now you will have to run the following commands:
* php artisan config:clear
* php artisan cache:clear
* php artisan config:cache
* php artisan serve

## DEBUGGING:
### If the migrations failed, try running:
* php artisan migrate:fresh

and run the other 4 commands again:
* php artisan config:clear
* php artisan cache:clear
* php artisan config:cache
* php artisan serve

Now the web application should be functional.
To test the service, try inputting a riotID from an existing account and hit *ENTER*
**If you don't know any riotID accounts, try running one of the following ID's through:
* brown#6969
* karateStrate#euw
*Others (non-group member riotIDs):*
* naayil#666
* Mundo#piggy
* thebausffs#cool


# Things that does not work:
* The Update button on the webiste does not function as a reload
* When doing HTTP requests, we have used "withoutVerifying," as some group-
* members machines could not verify SSL certificates. This is not usually something you should do, 
* but due to the scope of this project, and time constraints, we did not allocate time for debugging this.