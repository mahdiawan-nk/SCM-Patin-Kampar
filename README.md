# Kolam Budidaya API

This is a RESTful API for managing kolam budidaya (fish farming) data.

## Installation

1. Clone this repository
2. Run `composer install`
3. Run `php artisan migrate`
4. Run `php artisan db:seed`

## Usage

### Get all kolam budidaya

`GET /api/kolam-budidaya`

### Get a single kolam budidaya

`GET /api/kolam-budidaya/{id}`

### Create a new kolam budidaya

`POST /api/kolam-budidaya`

### Update a kolam budidaya

`PUT /api/kolam-budidaya/{id}`

### Delete a kolam budidaya

`DELETE /api/kolam-budidaya/{id}`

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.
