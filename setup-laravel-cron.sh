#!/bin/bash

# Define the path to your Laravel project
LARAVEL_PROJECT_PATH="/var/www/trustpaybd_n_usr/data/www/trustpaybd.net"  # <-- change this to your real path

# Check if the directory exists
if [ ! -d "$LARAVEL_PROJECT_PATH" ]; then
  echo "Error: Directory $LARAVEL_PROJECT_PATH does not exist."
  exit 1
fi

# Define the cron job line
CRON_JOB="* * * * * cd $LARAVEL_PROJECT_PATH && php artisan schedule:run >> /dev/null 2>&1"

# Add cron job if it doesn't already exist
(crontab -l 2>/dev/null | grep -Fv "$CRON_JOB" ; echo "$CRON_JOB") | crontab -

echo "✅ Laravel scheduler cron job set successfully."
echo "ℹ️  Project Path: $LARAVEL_PROJECT_PATH"
echo "⏱  Cron set to run every minute."
