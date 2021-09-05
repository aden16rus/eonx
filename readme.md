- Copy repository to local drive

```git clone https://github.com/aden16rus/eonx eonx_test```

- Build containers with docker-compose util

```cd ./eonx_test/docker_compose && docker-compose build```

- Run containers

```docker-compose up -d```

- change config if you get errors or conflicts with another projects and try again

```nano .env```

- add domain to hosts

```sudo echo '127.0.0.1 symfony.localhost' > /etc/hosts```

- when all components work properly default page will contains this copy of instruction on ```symfony.localhost:{choosen_port}```

- Go to php container

```docker exec -ti eonx-php sh```

- Create databases

```php bin/console d:d:c```

```php bin/console d:d:c --env=test```

- Run migrations

```php bin/console d:m:m```

```php bin/console d:m:m --env=test```

- Check tests

```php ./vendor/bin/phpunit```

- Run import customers

```php bin/console customers:import 100```

- Check api endpoints

```symfony.localhost:{choosen_port}/customers```

```symfony.localhost:{choosen_port}/customers/{customerId}```