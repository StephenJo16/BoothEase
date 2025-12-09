FROM nginx:1.25-alpine

# Work inside docker/nginx during build
WORKDIR /var/www

# Copy nginx config from the same folder as this Dockerfile
COPY nginx.conf /etc/nginx/conf.d/default.conf
