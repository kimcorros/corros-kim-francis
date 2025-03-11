# Bakery Store Hours Management System

A web application for managing bakery opening hours with real-time status display and date checking capabilities.

## Features

- 🕒 **Configurable Opening Hours**
    - Set different schedules for each day
    - Manage lunch breaks and biweekly openings
    - Admin interface for schedule management
- 🟢 **Real-Time Store Status**
    - Current open/closed status indicator
    - Next opening time display
- 📅 **Date Picker Integration**
    - Check availability for specific dates
    - Human-readable time formatting
- 🔐 **Secure Admin Dashboard**
    - Authentication protected
    - Intuitive schedule configuration

## Tech Stack

- **Backend**: Laravel 12
- **Frontend**: Vue 3 + Inertia.js
- **Styling**: Tailwind CSS + shadcn/ui
- **Database**: MySQL
- **Environment**: Docker (Laravel Sail)

## Installation

```bash
cp .env.example .env

composer install
npm install

./vendor/bin/sail up -d
```

Then go to another terminal and go inside the docker container:

```bash
./vendor/bin/sail exec laravel.test sh
```

Inside the docker container, run the following:

```bash
php artisan migrate:fresh --seed

composer run dev
```

### Prerequisites

- Docker Desktop
- Node.js 18+
- PHP 8.2+
