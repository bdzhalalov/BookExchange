# Шаг 1: Установка Node.js и сборка приложения
FROM node:16-alpine AS node_builder

# Устанавливаем рабочую директорию
WORKDIR /app

# Устанавливаем зависимости для Node.js
RUN apk add --no-cache bash curl libstdc++

# Копируем package.json и package-lock.json для установки зависимостей
COPY package*.json ./

# Устанавливаем зависимости
RUN npm install

# Шаг 2: Установка PHP и перенос артефактов из Node.js
FROM php:8.1-fpm-alpine

# Устанавливаем рабочую директорию
WORKDIR /var/www/app

# Устанавливаем системные зависимости
RUN apk update && apk add --no-cache \
    libstdc++ \
    libpq-dev && \
    docker-php-ext-install pdo pdo_pgsql pgsql

COPY . /var/www/app

# Устанавливаем Node.js и npm
COPY --from=node_builder /usr/local/bin/node /usr/local/bin/node
COPY --from=node_builder /usr/local/bin/npm /usr/local/bin/npm
COPY --from=node_builder /usr/local/lib/node_modules /usr/local/lib/node_modules

# Создаём симлинки для npm и npx
RUN ln -sf /usr/local/lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm \
    && ln -sf /usr/local/lib/node_modules/npm/bin/npx-cli.js /usr/local/bin/npx

# Устанавливаем Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
