# SnappySnaps

A Laravel-based application for managing and searching stores and postcodes.

## Getting Started

Follow these steps to set up the application locally using Docker.

### 1. Environment Setup

Clone the repository and copy the environment file:

```shell
cp .env.example .env
```

### 2. Start Application

Start the Docker containers:

```shell
docker-compose up -d
```

### 3. Install Dependencies

Install PHP and JavaScript dependencies:

```shell
docker-compose exec web composer install
docker-compose exec web npm install
```

### 4. Initialize Application

Run the setup commands to prepare the database and search index:

```shell
docker-compose exec web php artisan key:generate
docker-compose exec web php artisan migrate:fresh --seed
docker-compose exec web php artisan import:postcodes
docker-compose exec web php artisan scout:import "App\Models\Store"
docker-compose exec web php artisan scout:import "App\Models\Postcode"
```

## Common Commands

### Development

- **Run Tests:** `docker-compose exec web pest`
- **Lint Code:** `docker-compose exec web pint`
- **Frontend Dev:** `docker-compose exec web npm run dev`

### Code Generation

- **Generate TypeScript Types:**
  ```shell
  docker-compose exec web php artisan typescript:transform
  ```

- **Generate Routes/Functions:**
  ```shell
  docker-compose exec web php artisan wayfinder:generate --with-form
  ```

### Search (Elasticsearch)

- **Check Index Status:**
  ```shell
  docker-compose exec web curl -X GET "elastic:9200/_cat/indices?v"
  ```
- **Search Indices:**
  ```shell
  docker-compose exec web curl -X GET "elastic:9200/stores/_search"
  docker-compose exec web curl -X GET "elastic:9200/postcodes/_search"
  ```
