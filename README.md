# Вступление

Библиотека позволяет отследить запросы с нейронкам и отобразить полезную информацию.

# Установка

##### Установка зависимости

```shell 
composer require darkslight2/ai-request-monitoring
```

##### Публикация стилей

```shell 
php artisan vendor:publish --tag=ai-monitor-assets
```

##### Миграции

```shell 
php artisan migrate
```

# Использование

Для открытия страницы мониторинга используйте адрес `https://<domain>/ai-monitor/dashboard`

# Окружение

#### Переменные окружения

```dotenv
# Влючить/Отключить мониторинг
AI_MONITORING_ENABLED=[true|false]
```
