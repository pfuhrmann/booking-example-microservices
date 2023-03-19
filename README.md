# Solution Notes

Example implementation of microservices in the monorepo. We are utilizing Laravel for reservations service
and Node.js for emails service. Everything is brought together using `docker compose` plugin and internal networking.

Few general notes of technologies and implementation decisions:

- PHP 8.2 with Laravel 10 and PHPUnit 10
- Node 18 (LTS) with Typescript
- RabbitMQ for the async jobs between services
- Redis for caching in Laravel
- implemented full test suite for the Reservations API (CRU actions)
- added xDebug to php-fpm container for the PHP tests coverage generation
- added more helper containers to `docker-compose.yaml` (see more details below)

## Requirements

- `docker`
- `docker compose` plugin

## Spin up docker compose

Run the following to start the necessary local services

```bash
docker compose up -d --build app
````

## Install dependencies and manage projects

### Reservation service

Install reservations service dependencies (composer & npm)

```bash
docker compose run --rm composer install
docker compose run --rm npm-reservations install
```

Run the DB migrations

```bash
docker compose run --rm artisan migrate
```

### Emails service

Install npm dependencies

```bash
docker compose run --rm npm-emails install
```

Build the needed scripts (ts => js)

```bash
docker compose run --rm npm-emails run build
```

Start the queue worker using CLI script

```bash
docker compose run --rm npm-emails run worker
```

You should see the message similar to

```bash
> emails-service@0.0.1 worker
> node src/index.js

Listening...
...
```

> **Warning**: If you see following error:
    `Error: Channel closed by server: 404 (NOT-FOUND) with message "NOT_FOUND - no queue 'reservations' in vhost '/'`.
    This means, at least one `PATCH /api/reservations/<reservation_id>` HTTP request must be made which will create the queue.
    See below for details about "Reservations API".

## Reservations API examples

Create a new reservation

```bash
curl --location 'http://localhost:8080/api/reservations' \
--header 'Accept: application/json' \
--header 'Content-Type: application/json' \
--data '{
    "date_arrival": "2023-03-19",
    "date_departure": "2023-03-20",
    "time_arrival": "18:00",
    "time_departure": "12:00"
}
```

Update the specific reservation by ID.

> **Note**: This call triggers a new RMQ message into `reservations` queue, once the reservation was updated.

```bash
curl --location --request PATCH 'http://localhost:8080/api/reservations/<reservation_id>' \
--header 'Accept: application/json' \
--header 'Content-Type: application/json' \
--data '{
    "date_arrival": "2023-03-19",
    "date_departure": "2023-03-21",
    "time_arrival": "18:00",
    "time_departure": "10:00",
    "status": "paid"
}'
```

Get all the reservations

```bash
curl --location --request GET 'http://localhost:8080/api/reservations' \
--header 'Accept: application/json' \
--header 'Content-Type: application/json'
```

Get a single reservation by ID

```bash
curl --location 'http://localhost:8080/api/reservations/<reservation_id>' \
--header 'Accept: application/json'
```

## Tests

Run the laravel tests including generating the coverage

```bash
docker-compose run --rm composer test:coverage-html
```

Check the coverage by opening `./laravel/output/code-coverage/index.html` file.

## Future improvements

- Fixing the RMQ message serialization from Laravel (spent too much time on this, run out of time)
- API auto-generation with `knuckleswtf/scribe`
- Auth for the Reservations API (Auth0 or Okta)
- Writing more tests
- Linting (using super-linter)
- Create K8s manifests for the services (Helm or Pulumi or Kustomize etc.)
- CI/CD
