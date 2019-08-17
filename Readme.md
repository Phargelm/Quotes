# Quotes
#### Overview
Quotes is an application that is used to display historical quotes for the company by the date range in the table format as well as in chart.
#### Setup
This application is containerized using Docker. In order to setup development environment the latest versions of `docker` and `docker-compose` are required. After pulling repository, execute:
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
docker-compose down && ./start.sh
```
#### Test
In order to run functional tests execute:
```
docker exec -it <container_id> bin/phpunit
```
Placeholder `<php_container_id>` need to be replaced by your actual php container id. To figure out id, check the list of your containers:
```
docker ps
```