cd laradock
docker-compose up -d nginx mysql workspace phpmyadmin  php-worker
docker-compose exec workspace bash 


http://localhost
http://127.0.0.1:8081

127.0.0.1 mysql default secret


Запуск тестов 

php artisan test
