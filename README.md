# Takeaway Mailer

The application consists of the following modules: 
 - JSON API over HTTP - receives requests for async mail sending and adds them to a queue
 - Command handler - same as the JSON API but CLI
 - Queue system - tries to send the message through the available mail service providers

The lifecycle of a message is the following: 

 - After receiving an API or CLI request a Takeawaymailer/Message object is created and validated.
 - An UUID is generated and stored with the Message - that way even if no reference_id is passed, a single message could still be traced throughout the logs.
 - A job is dispatched in the Laravel queue system
 - When the worker picks the job it tries to send the email throu a provider picked by the ProviderFactory. The factory suggests alternative provider based on a $retry parameter passed to it. As a retry parameter the job attempt (comes from Laravel) is passed.
 - If there is an error, the next time the queue worker tries to process the job another provider (if more than one are available) will be used.
 
 The following events are logged:
 - Once a Message object is created
 - Once a Message is added to the queue
 - When there is an attempt to send the message
 - When the message is sent
 - When error occurs with the provider communication
 - Other errors logged by Laravel

### Adding Providers

To add a new Provider:
- Add a driver class extending App\TakeawayMailer\Provider in /src/app/TakeawayMailer/Providers/
- Add the provider class in App\TakeawayMailer\ProviderFactory::$available_providers

### The JSON API

Built with basic controller and routing provided by Laravel.

Available methods:

```
Base Url http://127.0.0.1/api 
POST /message
Parameters:
    - subject (*requred*) - Subject for the message
    - body (*required*) - Body of the message
    - to[] (*required*) - An array of valid email addresses
    - reference_id (optional) - A custom id/string for tracability
```

### Worker

The application uses Laravel's built in queue system. 
A database is used for the queue driver.

---

### How to setup the whole application

To run the application using docker at least 3 containers are needed

- for the database
- for the API 
- for a queue worker

More API and worker containers can be started if needed

To run the whole thing:

```
# clone the repo
git clone xxxxxxxxx

# create a docker network
docker network create takeaway_mailer_network_0

# start a db container
docker run --network takeaway_mailer_network_0 --name db_0 -e MYSQL_ROOT_PASSWORD=16783b3dc9fc -e MYSQL_DATABASE=takeawaymailer -d mariadb/server:10.3

# build the app image
docker build -t takeawaymailer/app -f ./app.Dockerfile .

# start an api server container 
docker run -dit -p 127.0.0.1:8000:8000 --name api --network takeaway_mailer_network_0 --entrypoint "/bin/bash" takeawaymailer/app -c "php artisan migrate && php artisan serve --host=0.0.0.0"

# start a queue worker container
docker run -dit --name worker0 --network takeaway_mailer_network_0 --entrypoint "/bin/bash" takeawaymailer/app -c "php artisan migrate && php artisan queue:work"
```

### Sending a message

Once you have all three containers running you should be able to send a message.

HTTP
```
curl -X POST --data "subject=Test%20Subject&body=Test%20Body&to[]=ehwas503@gmail.com" 127.0.0.1:8000/api/message
```

CLI
```
# start a shell in the api container
docker exec -it api /bin/bash
# and in the shell inside the container
php artisan message:send {subject} {body} {to} {reference_id=?}
```

### Running unit tests

To run the tests:

```
cd src
php artisan test
```