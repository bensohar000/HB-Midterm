# INF653 Midterm Project — REST API

Author: Harrison Bensouda

Live Project: https://hb-midterm.onrender.com/quotesapi/api/quotes/

## About

A PHP OOP REST API for quotes built with PDO and PostgreSQL. The API supports full CRUD operations for quotes, authors, and categories. It was built as part of the INF653 Back End Web Development course midterm project.

## Tech Stack
- PHP 8.1 (OOP)
- PostgreSQL
- PDO
- Apache
- Docker
- Render (deployment)

## Deployment

The application is deployed on Render using a Docker container running PHP 8.1 with Apache. The database is a **PostgreSQL** instance hosted on Render's free tier.

The project was containerized using a Dockerfile that installs the necessary PHP PostgreSQL extensions (pdo_pgsql) and configures Apache to serve the API. Database credentials are stored securely as environment variables in the Render dashboard and accessed in PHP using getenv().

## Database Structure

The API uses a PostgreSQL database named quotesdb with 3 tables:

- authors — id, author
- categories — id, category
- quotes — id, quote, author_id (FK), category_id (FK)

## Endpoints

### Quotes
| Method | URL | Description |
|--------|-----|-------------|
| GET | /quotesapi/api/quotes/ | Get all quotes |
| GET | /quotesapi/api/quotes/?id=1 | Get quote by id |
| GET | /quotesapi/api/quotes/?author_id=1 | Get quotes by author |
| GET | /quotesapi/api/quotes/?category_id=1 | Get quotes by category |
| GET | /quotesapi/api/quotes/?author_id=1&category_id=1 | Get quotes by author and category |
| POST | /quotesapi/api/quotes/ | Create a quote |
| PUT | /quotesapi/api/quotes/ | Update a quote |
| DELETE | /quotesapi/api/quotes/ | Delete a quote |

### Authors
| Method | URL | Description |
|--------|-----|-------------|
| GET | /quotesapi/api/authors/ | Get all authors |
| GET | /quotesapi/api/authors/?id=1 | Get author by id |
| POST | /quotesapi/api/authors/ | Create an author |
| PUT | /quotesapi/api/authors/ | Update an author |
| DELETE | /quotesapi/api/authors/ | Delete an author |

### Categories
| Method | URL | Description |
|--------|-----|-------------|
| GET | /quotesapi/api/categories/ | Get all categories |
| GET | /quotesapi/api/categories/?id=1 | Get category by id |
| POST | /quotesapi/api/categories/ | Create a category |
| PUT | /quotesapi/api/categories/ | Update a category |
| DELETE | /quotesapi/api/categories/ | Delete a category |

## Error Responses

| Message | Description |
|---------|-------------|
| { "message": "No Quotes Found" } | No quotes match the request |
| { "message": "author_id Not Found" } | Author does not exist |
| { "message": "category_id Not Found" } | Category does not exist |
| { "message": "Missing Required Parameters" } | Required fields missing from request |

## Local Development

To run this project locally you will need XAMPP installed.

1. Clone the repo into your XAMPP htdocs folder
2. Start Apache and MySQL in the XAMPP control panel
3. Import the database SQL into phpMyAdmin
4. Update config/Database.php with your local credentials
5. Visit http://localhost/quotesapi/api/quotes/ in your browser
