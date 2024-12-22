# Use the prebuilt Open Swoole image
FROM openswoole/swoole:php8.3

# Set working directory
WORKDIR /var/www

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy application code
COPY . /var/www

# Expose the Open Swoole application port
EXPOSE 8080

# Start the Open Swoole server
CMD ["php", "/var/www/public/index.php"]