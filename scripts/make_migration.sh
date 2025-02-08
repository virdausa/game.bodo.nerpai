#!/bin/bash

# Check if a name argument is provided
if [ -z "$1" ]; then
  echo "Usage: $0 <migration_name>"
  exit 1
fi

# Run the Laravel migration command
php artisan make:migration "$1" --path=./database/migrations/tenant

# Check if the command was successful
if [ $? -eq 0 ]; then
  echo "Migration '$1' created successfully."
else
  echo "Failed to create migration '$1'."
  exit 1
fi
