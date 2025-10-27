# Laravel Cron & Queue Demo

A simple demonstration of Laravel's task scheduling (cron jobs) and queue system for cleaning up log files.

## Overview

This demo shows how to:
1. Create a Laravel **Job** to clean up log files
2. Create an **Artisan Command** to dispatch the job to a queue
3. **Schedule** the command to run automatically using Laravel's task scheduler

## Project Structure

```
app/
├── Console/
│   └── Commands/
│       └── CleanupLogsCommand.php    # Command to dispatch the job
├── Jobs/
│   └── CleanupLogs.php               # Job that cleans the log file
routes/
└── console.php                        # Scheduled tasks defined here
```

## Setup Instructions

### 1. Clone & Install Dependencies

```bash
git clone <repository-url>
cd cron-demo
composer install
cp .env.example .env
php artisan key:generate
```

### 2. Configure Queue Driver

For simplicity, this demo uses the **sync** driver (executes jobs immediately).

In your `.env` file, ensure it's set to:
```env
QUEUE_CONNECTION=sync
```

### 3. Test the Job Manually

You can manually dispatch the job to see it in action:

```bash
php artisan logs:cleanup
```

This will:
- Dispatch the `CleanupLogs` job to the queue
- The job will clear the contents of `storage/logs/laravel.log`

### 4. Schedule the Task (Cron Job)

The command is already scheduled in `routes/console.php` to run **daily at midnight**.

To test the scheduler locally:

```bash
php artisan schedule:work
```

Or run it once:

```bash
php artisan schedule:run
```

### 5. Production Setup

In production, add this cron entry to your server:

```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

This will check every minute if any scheduled tasks need to run.

## How It Works

### 1. **CleanupLogs Job** (`app/Jobs/CleanupLogs.php`)
- Implements `ShouldQueue` interface to make it queueable
- Clears the content of `storage/logs/laravel.log`
- Logs a message after cleanup

### 2. **CleanupLogsCommand** (`app/Console/Commands/CleanupLogsCommand.php`)
- Command signature: `logs:cleanup`
- Dispatches the `CleanupLogs` job to the queue
- Can be run manually or scheduled

### 3. **Task Scheduler** (`routes/console.php`)
- Schedules `logs:cleanup` command to run daily
- Uses Laravel's task scheduling API

## Queue Configuration

### Sync Driver (Default - Simplest)
- Jobs execute immediately, synchronously
- No queue worker needed
- Perfect for simple demos and testing

### Database Driver (Recommended for Production)
- Jobs are stored in the database
- Requires running `php artisan queue:work`
- Better for handling failures and retries

## Available Commands

```bash
# Manually run the cleanup command
php artisan logs:cleanup

# Test the scheduler (runs all due tasks)
php artisan schedule:run

# Run scheduler continuously (for local testing)
php artisan schedule:work

# List all scheduled tasks
php artisan schedule:list

# Run queue worker (if using database driver)
php artisan queue:work
```

## Testing

Generate some logs to test the cleanup:

```bash
# Create some log entries
php artisan tinker
>>> Log::info('Test log entry 1');
>>> Log::warning('Test log entry 2');
>>> Log::error('Test log entry 3');
>>> exit

# Check the log file
cat storage/logs/laravel.log

# Run cleanup
php artisan logs:cleanup

# Verify it's cleaned
cat storage/logs/laravel.log
```

## Requirements

- PHP >= 8.3
- Laravel >= 12.x
- Composer >= 2.6

## License

This is a demo project for educational purposes.
