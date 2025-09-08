FROM php:8.3-fpm

# Bazowe narzędzia
RUN apt-get update && \
    DEBIAN_FRONTEND=noninteractive apt-get install -y --no-install-recommends \
      apt-utils vim curl debconf subversion git apt-transport-https \
      build-essential locales acl mailutils wget zip unzip \
      gnupg zlib1g-dev sudo pkg-config && \
    rm -rf /var/lib/apt/lists/*

# GD (JPEG + FreeType)
RUN apt-get update && \
    DEBIAN_FRONTEND=noninteractive apt-get install -y --no-install-recommends \
      libfreetype6-dev libjpeg62-turbo-dev libpng-dev \
      libxml2-dev libzip-dev graphviz && \
    docker-php-source extract && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j"$(nproc)" gd && \
    docker-php-source delete && \
    rm -rf /var/lib/apt/lists/*

# PDO MySQL + ZIP
RUN docker-php-source extract && \
    docker-php-ext-install -j"$(nproc)" pdo_mysql zip && \
    docker-php-source delete

# >>> KLUCZOWE DLA intl: libicu-dev <<<
RUN apt-get update && \
    DEBIAN_FRONTEND=noninteractive apt-get install -y --no-install-recommends libicu-dev && \
    rm -rf /var/lib/apt/lists/*

# Intl
RUN docker-php-source extract && \
    docker-php-ext-configure intl && \
    docker-php-ext-install -j"$(nproc)" intl && \
    docker-php-source delete

# php.ini i pool
COPY php.ini /usr/local/etc/php/php.ini
COPY php-fpm-pool.conf /usr/local/etc/php/pool.d/www.conf

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --version=2.6.6 && \
    mv composer.phar /usr/local/bin/composer

# Symfony CLI
RUN wget https://get.symfony.com/cli/installer -O - | bash && \
    mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

# Xdebug
RUN pecl install xdebug && docker-php-ext-enable xdebug && \
    { \
      echo "error_reporting = E_ALL"; \
      echo "display_startup_errors = On"; \
      echo "display_errors = On"; \
    } > /usr/local/etc/php/conf.d/error_reporting.ini && \
    { \
      echo "xdebug.mode=develop,coverage,debug,trace"; \
      echo "xdebug.start_with_request=trigger"; \
      echo "xdebug.discover_client_host=1"; \
      echo "xdebug.client_port=9003"; \
      echo 'xdebug.idekey="PHPSTORM"'; \
      echo "xdebug.var_display_max_children=128"; \
      echo "xdebug.var_display_max_data=512"; \
      echo "xdebug.var_display_max_depth=3"; \
    } > /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Narzędzia developerskie (jeśli potrzebne do pecl/kompilacji)
RUN apt-get update && DEBIAN_FRONTEND=noninteractive apt-get install -y --no-install-recommends \
      gcc g++ make && \
    rm -rf /var/lib/apt/lists/*

# Node.js LTS (poprawione – skrypt musi być wykonany)
RUN curl -fsSL https://deb.nodesource.com/setup_lts.x | bash - && \
    DEBIAN_FRONTEND=noninteractive apt-get install -y --no-install-recommends nodejs && \
    rm -rf /var/lib/apt/lists/*

# Użytkownik dev
RUN groupadd -g 999 dev && \
    useradd -m -d /home/dev -g dev dev && \
    passwd -d dev && \
    echo "dev ALL=(ALL) ALL" > /etc/sudoers

# Locale
RUN echo "en_US.UTF-8 UTF-8" > /etc/locale.gen && \
    echo "fr_FR.UTF-8 UTF-8" >> /etc/locale.gen && \
    echo "pl_PL.UTF-8 UTF-8" >> /etc/locale.gen && \
    locale-gen

WORKDIR /home/wwwroot/

EXPOSE 9000
CMD ["php-fpm"]
