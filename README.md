# Discounts Microservice

A microservice that grants discounts to orders based on configurable rules.

## Getting Started

1. Install and configure the microservice
2. Run it on a server
3. Query with order data
4. Receive back order data with discounts applied

### Installation

Installation instructions are for Production. Adjust for Development where needed.
 
1. Check out the project
2. `composer install -o --no-dev --prefer-dist`
3. Inside `config/autoload`, copy `local.php.dist` to `local.php` and update settings. 
4. In that file, the `base_uri` for `\App\ApiClient::class` should point to the internal API Gateway URL (assuming the 
other API modules are segregated into their own microservices).

### Running (development)

You can run the microservice with Docker by simply running `docker-compose`:

`docker-compose up -d && docker-composer logs -f`

The microservice will be available at `localhost:8080`.

### Querying

A [Paw](https://paw.cloud) project has been included in `tests/api.paw`. You can use that project to see what's 
possible on the API and test some queries.

## API

This microservice exposes a REST+RPC API, with JSON. With more time (and few BC breaks), it can be upgraded to use a 
more robust REST framework such as [JSON API](http://jsonapi.org/) or [HAL](http://stateless.co/hal_specification.html).

### Structure
There are several modules that provide API endpoints in this project. Each module provides their own, so it should be 
easy to remove them and put them in their own microservices.

In fact, this project can be used as a template for other microservices by extracting all modules except the "App" 
module. Each microservice could then install itself on top of this template via Composer.

Modules are located in `./src`. 
* `App`: provides basic support for the microservice. Modules that end in `Api` are modules that provide API endpoints.
* `*Api`: each of these modules expose their API endpoints. See their `README.md` for details.
* `Discounts`: a domain model for discounts. For simplicy, it doesn't follow any specific methodologies (e.g. DDD). 
It's also the only module with an explicit `composer.json`, because it DEFINITELY belongs outside of this project.

### Endpoints
See the `README.md` on each module for endpoint docs.

### Swagger
In the future the API documentation could be automatically published in [Swagger](https://swagger.io/) format. For an 
example of how that would work, copy the contents of `./tests/swagger.json` and paste them in 
[editor.swagger.io](https://editor.swagger.io/)

## TODO
There are many TODO's in the code indicating possible future improvements or things that were deliberately left undone.
