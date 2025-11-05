FROM mcr.microsoft.com/devcontainers/php:1-8.2-bookworm
# All necessary PHP dependnencies
RUN apt update && \
    apt install -y \
        libpng-dev libjpeg-dev libfreetype6-dev \
        libldap2-dev \
        build-essential autoconf pkg-config && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd mysqli ldap && \
    rm -rf /var/lib/apt/lists/*
# Enable Xdebug
RUN XDEBUG_INI="/usr/local/etc/php/conf.d/xdebug.ini" && \
    mkdir -p "$(dirname "$XDEBUG_INI")" && \
    if ! php -m | grep -qi xdebug; then \
        docker-php-ext-install xdebug; \
    fi && \
    if [ ! -f "$XDEBUG_INI" ]; then \
        echo "xdebug.mode=off" > "$XDEBUG_INI" && \
        echo "xdebug.start_with_request=no" >> "$XDEBUG_INI" && \
        echo "xdebug.client_host=host.docker.internal" >> "$XDEBUG_INI" && \
        echo "xdebug.client_port=9003" >> "$XDEBUG_INI"; \
    else \
        sed -i '/^zend_extension/d' "$XDEBUG_INI"; \
        grep -q "xdebug.mode" "$XDEBUG_INI" || echo "xdebug.mode=off" >> "$XDEBUG_INI"; \
        grep -q "xdebug.start_with_request" "$XDEBUG_INI" || echo "xdebug.start_with_request=no" >> "$XDEBUG_INI"; \
        grep -q "xdebug.client_host" "$XDEBUG_INI" || echo "xdebug.client_host=host.docker.internal" >> "$XDEBUG_INI"; \
        grep -q "xdebug.client_port" "$XDEBUG_INI" || echo "xdebug.client_port=9003" >> "$XDEBUG_INI"; \
    fi

# Optional: confirm extensions are active for debugging
RUN php -m | grep -E 'mysqli|ldap|gd'
