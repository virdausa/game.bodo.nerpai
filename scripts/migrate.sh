#!/bin/bash

# Run the Laravel migration command
php artisan migrate --path=./database/migrations/tenant

# Check if the command was successful
if [ $? -eq 0 ]; then
  echo "Migrate successfuly successfully."
else
  echo "Failed to migrate."
  exit 1
fi
