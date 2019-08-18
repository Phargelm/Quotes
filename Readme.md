# Quotes
#### Overview
Quotes is an application that is used to display historical quotes for the company by the date range in the table format as well as in chart.
#### Setup
This application is containerized using Docker. In order to setup development environment the latest versions of `docker` and `docker-compose` are required. All commands bellow assumes that your present directory - is the project root. After pulling repository, execute:
```
cd devops && ./start.sh
```
In order to test if everyting is Ok, open a browser, and try to navigate:
```
http://localhost:8181/
```
You should see a "Stock Quotes" form.
Explore the file `devops/.env`. Feel free to change any default environment variables in this file if you need it.

#### Attention (!). In order to make email sending works the correct smtp server credentials should be specified in `devops/.env`.
```
MAILER_DSN=smtp://user:pass@smtp.example.com
```
After changes in `devops/.env` restart docker containers:
```
cd devops && docker-compose down && ./start.sh
```
#### Database
In order to avoid parsing csv file on each request in order to get companies symbols the local database is used to persist parsed data. After setup all actual companies data imported from NASDAQ server to the local database automaticaly. Thus, we can parse csv only once and persist for future requests. More over, we can refresh companies data in local db, by executing command:
```
docker exec -it <php_container_id> php bin/console app:import-companies
```
(placeholder `<php_container_id>` need to be replaced by your actual php container id. To figure out id, check the list of your containers: `docker ps`)

Therefore no redeploy is needed in order to actualize the list of companies symbols.
#### Test
In order to run functional tests execute:
```
docker exec -it <php_container_id> composer test
```