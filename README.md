# Symfony Api Demo

### This App provides:
* authenticate users
* get list of cities
* get list of countries
* get nearest city by coords

### Stack:
* php 8.2
* symfony 6.4
* postgres
* docker

### How to run project:
* clone it from GitHub and enter into project dir:
```bash
git clone https://github.com/TutunaruStanislav/symfony-api-demo.git
```
```bash
cd symfony-api-demo
```

* create local configs for docker:
```bash
cp .docker/.env.dist .env
```
```bash
cp .docker/docker-compose.dev.yml docker-compose.yml
```

* set your credentials (db, etc..)

* install MAKE

* build containers:
```bash
make dc_build
```

* run containers:
```bash
make dc_up
```

* enter into container and install dependencies:
```bash
make app_bash
```
```bash
composer install --no-interaction
```

* apply db migrations and load dump with data into db:
```bash
make db_migrate
```
```bash
cat ./.docker/db/init/init_db_data.sql | docker exec -i <container_name> psql -U <POSTGRES_USER> -d <POSTGRES_DB>
```

* generate jwt tokens:
```bash
make jwt
```

### Service is available on:
http://localhost:8888

### Documentation:
http://localhost:8888/api/doc

### Demo user:
79000000082 / password

### Author:
Stanislav Tutunaru

### License:
This project is licensed under [MIT License](https://github.com/TutunaruStanislav/symfony-api-demo/blob/master/LICENSE)
