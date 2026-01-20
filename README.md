# Forum App

A community discussion platform built with Laravel, Filament, and Tailwind CSS.
It focuses on providing a clean, modern interface for both users and moderators.

## Features

- User registration and authentication
- Forum categories, boards, and topics
- Post creation with rich text editor
- User ranks and post counts
- Admin panel with Filament
- Quote functionality for replies
- User signatures
- Soft delete support
- Moderation system

## Tech Stack

- **Backend**: Laravel 12
- **Frontend**: Filament v4
- **Styling**: Tailwind CSS v4
- **Database**: MySQL
- **Testing**: Pest PHP v4

## Setup

1. Install dependencies:

```bash
composer install
npm install
```

2. Copy environment file:

```bash
cp .env.example .env
```

3. Configure database in `.env`

4. Run migrations:

```bash
php artisan migrate --seed
```

5. Run build:

```bash
npm run build
```

6. Serve application:

```bash
composer run dev
```

## Testing

Run all tests:

```bash
php artisan test
```

Run specific test file:

```bash
php artisan test tests/Feature/ExampleTest.php
```

Run with filter:

```bash
php artisan test --filter=testName
```

## Code Formatting

Format code with Pint:

```bash
vendor/bin/pint
```

## Admin Panel

Access the admin panel at `/` to manage:

- Categories
- Boards
- Topics
- Posts
- Users

## User Roles

- **Admin**: Full access to all features
- **Moderator**: Can moderate boards assigned to them
- **User**: Can post and reply
- **Newbie**: Default rank for new users
