# Country Currency & Exchange API

A RESTful API that fetches country data from external APIs, stores it in a database, and provides CRUD operations with currency exchange rates and GDP calculations.

## 🚀 Features

-   Fetch country data from REST Countries API
-   Get real-time exchange rates from ER API
-   Calculate estimated GDP based on population and exchange rates
-   CRUD operations for country data
-   Filtering and sorting capabilities
-   Automatic image generation with summary statistics
-   MySQL database persistence

## 🛠️ Tech Stack

-   **Backend**: Laravel 10+
-   **Database**: MySQL
-   **Image Processing**: Intervention Image v3+
-   **HTTP Client**: Guzzle

## 📋 Prerequisites

-   PHP 8.1+
-   Composer
-   MySQL 5.7+
-   Laravel 10+

## ⚙️ Installation

1. **Clone the repository**

    ```bash
    git clone https://github.com/ifeoseni/country-task
    cd country-task
    ```

2. **Install PHP dependencies**

    ```bash
    composer install
    ```

3. **Create environment file**

    ```bash
    cp .env.example .env
    ```

4. **Generate application key**

    ```bash
    php artisan key:generate
    ```

5. **Configure database**
   Edit `.env` file with your database credentials:

    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=country_api
    DB_USERNAME=your_username
    DB_PASSWORD=your_password
    ```

6. **Run database migrations**

    ```bash
    php artisan migrate
    ```

7. **Install Intervention Image**
    ```bash
    composer require intervention/image
    ```

## 🚀 Running the Application

1. **Start the development server**

    ```bash
    php artisan serve
    ```

2. **Access the API**
   The API will be available at: `http://127.0.0.1:8000`

## 📚 API Endpoints

### POST `/countries/refresh`

Fetches latest country data and exchange rates from external APIs and updates the database.

**Response:**

```json
{
    "message": "Countries refreshed successfully",
    "processed": 250,
    "last_refreshed_at": "2025-10-27T19:30:11Z"
}
```

### GET `/countries`

Retrieve all countries with optional filtering and sorting.

**Query Parameters:**

-   `region` - Filter by region (e.g., Africa, Europe)
-   `currency` - Filter by currency code (e.g., USD, EUR)
-   `sort` - Sort by GDP (`gdp_desc`)

**Examples:**

-   `GET /countries` - All countries
-   `GET /countries?region=Africa` - African countries
-   `GET /countries?currency=USD` - Countries using USD
-   `GET /countries?sort=gdp_desc` - Countries sorted by GDP descending

**Response:**

```json
[
    {
        "id": 1,
        "name": "Nigeria",
        "capital": "Abuja",
        "region": "Africa",
        "population": 206139589,
        "currency_code": "NGN",
        "exchange_rate": 1600.23,
        "estimated_gdp": 25767448125.2,
        "flag_url": "https://flagcdn.com/ng.svg",
        "last_refreshed_at": "2025-10-27T19:30:11Z"
    }
]
```

### GET `/countries/{name}`

Get a specific country by name (case-insensitive).

**Example:** `GET /countries/nigeria`

**Response:** Same as above (single object)

### DELETE `/countries/{name}`

Delete a country record by name.

**Example:** `DELETE /countries/nigeria`

**Response:**

```json
{
    "message": "Country deleted"
}
```

### GET `/status`

Get API status including total countries and last refresh timestamp.

**Response:**

```json
{
    "total_countries": 250,
    "last_refreshed_at": "2025-10-27T19:30:11Z"
}
```

### GET `/countries/image`

Get a generated summary image showing total countries and top 5 by GDP.

**Response:** PNG image or JSON error if image not found.

## 🔧 External APIs Used

-   **Countries Data**: `https://restcountries.com/v2/all?fields=name,capital,region,population,flag,currencies`
-   **Exchange Rates**: `https://open.er-api.com/v6/latest/USD`

## 💾 Data Model

### Country Table Schema

```sql
id                   | auto-increment
name                 | string (required, unique)
capital              | string (nullable)
region               | string (nullable)
population           | integer (required)
currency_code        | string (nullable)
exchange_rate        | decimal(18,6) (nullable)
estimated_gdp        | decimal(30,6) (nullable)
flag_url             | string (nullable)
last_refreshed_at    | timestamp (nullable)
created_at           | timestamp
updated_at           | timestamp
```

## 🧮 Business Logic

### GDP Calculation

```php
estimated_gdp = population × random(1000–2000) ÷ exchange_rate
```

### Currency Handling

-   If a country has multiple currencies, only the first one is stored
-   If no currencies exist: `currency_code = null`, `exchange_rate = null`, `estimated_gdp = 0`
-   If currency not found in exchange API: `exchange_rate = null`, `estimated_gdp = null`

### Refresh Logic

-   Matches existing countries by name (case-insensitive)
-   Updates all fields including recalculated GDP with new random multiplier
-   Generates new summary image after refresh

## 🐛 Error Handling

### Standard Error Responses

```json
{
    "error": "Error message",
    "details": "Additional details"
}
```

### HTTP Status Codes

-   `200` - Success
-   `400` - Validation failed
-   `404` - Country not found
-   `500` - Internal server error
-   `503` - External API unavailable

## 🧪 Testing

### Manual Testing Sequence

1. Refresh data: `POST /countries/refresh`
2. Check status: `GET /status`
3. List countries: `GET /countries`
4. Filter examples: `GET /countries?region=Africa&sort=gdp_desc`
5. Get specific country: `GET /countries/nigeria`
6. View summary image: `GET /countries/image`

## 📁 Project Structure

```
app/
├── Http/
│   └── Controllers/
│       └── Api/
│           └── CountriesController.php
├── Models/
│   └── Country.php
└── Services/
    └── ExternalApiService.php
database/
└── migrations/
    └── 2025_10_25_075840_create_countries_table.php
routes/
└── api.php
```

## 🔒 Environment Variables

```env
APP_NAME="Country Currency API"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=country_api
DB_USERNAME=root
DB_PASSWORD=

CACHE_DRIVER=file
QUEUE_CONNECTION=sync
```

## 🚀 Deployment

### Production Requirements

-   PHP 8.1+ with extensions: `curl`, `gd`, `mbstring`, `xml`, `json`
-   MySQL 5.7+ or PostgreSQL
-   Web server (Apache/Nginx)
-   SSL certificate

### Deployment Steps

1. Set `APP_ENV=production`
2. Set `APP_DEBUG=false`
3. Run `php artisan config:cache`
4. Run `php artisan route:cache`
5. Configure web server rewrite rules
6. Set up database and run migrations

## 🆘 Troubleshooting

### Common Issues

1. **Intervention Image not found**

    ```bash
    composer require intervention/image
    ```

2. **Database connection failed**

    - Check `.env` database credentials
    - Ensure MySQL server is running
    - Run `php artisan migrate`

3. **External APIs failing**

    - Check internet connection
    - Verify API endpoints are accessible
    - Check Laravel logs in `storage/logs/laravel.log`

4. **Image generation failing**
    - Ensure GD extension is enabled in PHP
    - Check storage permissions: `chmod -R 775 storage/`

### Debug Mode

Enable debug mode in `.env` for detailed errors:

```env
APP_DEBUG=true
```

## 📄 License

This project is licensed under the MIT License.

---

**Note**: This API is designed for educational purposes and uses estimated GDP calculations. For production financial applications, use official economic data sources.

## 📞 Support

For issues and questions:

1. Check the Laravel logs: `storage/logs/laravel.log`
2. Verify all dependencies are installed
3. Ensure database migrations have run successfully
4. Test external API connectivity

---

_Last Updated: October 2025_
