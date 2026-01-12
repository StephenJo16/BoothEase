#!/bin/bash
set -e

# Build Vite assets if manifest.json doesn't exist
if [ ! -f "public/build/manifest.json" ]; then
  echo "Building Vite assets..."
  npm run build
fi

# Start php-fpm
exec php-fpm
