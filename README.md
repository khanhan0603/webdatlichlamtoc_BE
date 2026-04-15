# Hair Salon Booking API (Backend)

Backend API for the Hair Salon Booking System.
The system allows users to view services, hairstyles, promotions, and book appointments at a salon.

### Technologies Used

* PHP
* Laravel Framework
* MySQL
* RESTful API
* Laravel Sanctum (Authentication)

---

# Project Structure

```
BE
├── app
├── bootstrap
├── config
├── database
├── public
├── resources
├── routes
└── storage
```

---

# Installation

### 1. Clone repository

```
git clone https://github.com/username/repository-name.git
```

### 2. Move to backend folder

```
cd BE
```

### 3. Install dependencies

```
composer install
```

### 4. Create environment file

```
cp .env.example .env
```

### 5. Generate application key

```
php artisan key:generate
```

### 6. Configure database

Open `.env` file and update database configuration:

```
DB_DATABASE=quanlylichlamtoc
DB_USERNAME=root
DB_PASSWORD=
```

### 7. Run migration and seed data

```
php artisan migrate --seed
```

### 8. Start the server

```
php artisan serve
```

Server will run at:

```
http://127.0.0.1:8000
```

---

# Default Account

Admin and Staff account is created automatically when running the seeder.

```
Email: admin@gmail.com
Password: 123456
```
```
Email: staff@gmail.com
Password: 123456
```

---

# Features

* User authentication
* Role-based authorization
* Service management
* Hairstyle management
* Promotion management
* Appointment booking system
* API for frontend integration

## Cloudflare R2 Storage Configuration

This project uses **Cloudflare R2** for storing images and video (such as hairstyle images or service images).

### 1. Create an R2 Bucket

1. Go to the Cloudflare Dashboard
2. Navigate to **R2 Object Storage**
3. Create a new bucket

Example bucket name:

```
quanlylamtoc
```

---

### 2. Create API Token

1. Go to **R2 → Manage R2 API Tokens**
2. Click **Create API Token**
3. Copy the following credentials:

```
Access Key ID
Secret Access Key
```

---

### 3. Configure `.env`

Open the `.env` file and add the following configuration:

```
AWS_ACCESS_KEY_ID=your_access_key
AWS_SECRET_ACCESS_KEY=your_secret_key
AWS_DEFAULT_REGION=auto
AWS_BUCKET=quanlylamtoc
AWS_ENDPOINT=https://your-account-id.r2.cloudflarestorage.com
AWS_URL=https://your-public-domain.r2.dev
AWS_USE_PATH_STYLE_ENDPOINT=true
```

Example:

```
AWS_ACCESS_KEY_ID=xxxxxxxx
AWS_SECRET_ACCESS_KEY=xxxxxxxx
AWS_DEFAULT_REGION=auto
AWS_BUCKET=quanlylamtoc
AWS_ENDPOINT=https://abc123.r2.cloudflarestorage.com
AWS_URL=https://pub-abc123.r2.dev
AWS_USE_PATH_STYLE_ENDPOINT=true
```

---

### 4. Important Note

The `.env` file contains sensitive credentials and **must not be pushed to GitHub**.

Only commit the `.env.example` file:

```
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=auto
AWS_BUCKET=
AWS_ENDPOINT=
AWS_URL=
AWS_USE_PATH_STYLE_ENDPOINT=true
```

