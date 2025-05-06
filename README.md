# Order Management API

A full-featured, well-structured Order Management RESTful API built using Laravel, following **SOLID principles**, with proper **service layers**, **repository pattern**, and **Swagger API documentation**. Now fully **Dockerized** for easy local development.

---

## 🛠️ Features

-   Product Management: Add and list products
-   Order Management: Create, view, update, and delete orders
-   Order Items: Handle multiple items per order
-   Swagger (OpenAPI) documentation
-   Follows SOLID principles and clean architecture
-   Includes unit tests for services
-   Uses Laravel Resource for consistent API responses
-   Dockerized with PHP, MySQL, and Nginx

---

## 🐳 Docker Setup

### 1. Clone the repository

```bash
git clone https://github.com/your-username/order-management-api.git
cd order-management-api
```

### 2. Build and start containers

```bash
docker-compose up -d --build
```

### 3. Install dependencies inside container

Install dependency in container is done automaticaly but you can manually by following this steps

```bash
docker exec -it app bash
composer install
php artisan key:generate
php artisan migrate
exit
```

### 4. Access the app

-   API: `http://localhost:8000/api`
-   Swagger docs: `http://localhost:8000/api/documentation`
-   phpMyAdmin (if included): `http://localhost:8080`

---

## 🧾 Environment Configuration

Copy `.env.example`:

```bash
cp .env.example .env
```

Ensure the DB variables in `.env` match your Docker setup:

```
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=order_db
DB_USERNAME=root
DB_PASSWORD=root
```

---

## 🔌 API Endpoints

| Method | Endpoint     | Description          |
| ------ | ------------ | -------------------- |
| GET    | /products    | List all products    |
| POST   | /products    | Create a new product |
| GET    | /orders      | List all orders      |
| POST   | /orders      | Create a new order   |
| GET    | /orders/{id} | Show specific order  |
| PUT    | /orders/{id} | Update an order      |
| DELETE | /orders/{id} | Delete an order      |
| POST   | /order-items | Add item to order    |

---

## 📄 API Documentation (Swagger)

Visit:

```
http://localhost:8000/api/documentation
```

---

## 🧪 Running Tests

```bash
docker exec -it app bash
php artisan test
```

---

## 📁 Project Structure

```
app/
├── Http/
│   ├── Controllers/API/
│   ├── Requests/
│   ├── Resources/
├── Models/
├── Services/
├── Repositories/
│   ├── Interfaces/
│   ├── Implementations/
routes/
├── api.php
tests/
├── Unit/
docker-compose.yml
Dockerfile
```

---

## 👨‍💻 Author

Built by Valentin Niyonshuti.

### 🐳 Running with Docker

Ensure Docker and Docker Compose are installed.

```bash
docker-compose up --build
```

This will:

-   Build the Laravel API container with PHP 8.2
-   Spin up a MySQL 8 database
-   Run migrations automatically
-   Serve the app on `http://localhost:8009`

**docker-compose.yml**

```yaml
version: "3.8"

services:
    order:
        build:
            context: .
        ports:
            - "8009:8181"
        depends_on:
            - order-db
        networks:
            - order-network
        restart: always

    order-db:
        image: mysql:8
        environment:
            MYSQL_ALLOW_EMPTY_PASSWORD: true
            MYSQL_DATABASE: order_db
        volumes:
            - order:/var/lib/mysql
        networks:
            - order-network

networks:
    order-network:
        driver: bridge

volumes:
    order:
```

Remember to create docker volume

```
docker volume create order
```

## Future recommendation

### Authentication & Authorization

-   Add Laravel Sanctum or Passport for secure API access.

-   Role-based access control (Admin, Manager, User).

### Performance & Caching

Cache common queries like product lists or frequent orders.
