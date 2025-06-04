# CPS-League-API - Main branch

## Guide for running the project:
### first make sure you are running docker on your system.
### Next you will have to go into infrastructure and make docker run:
* cd. \infrastructure\
* docker compose up -d
### Next you will have to go back and run:
* cd..

### and then open cps - league - api folder:
* cd. \CPS-League-API\
### next you will have to change the riot api key under the environment file: .env. which is available for 24hours. if you don't have an api key:
### Option 1. ask one of the group participant.
### Option 2. Make your own profile.
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

# Things that don't work:
## The Update button on the webiste does not pull down new data when pressed
## Some places in our HTTP request, we use "withoutVerifying" we need to do this because we don't have SSL certificates.