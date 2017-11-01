## Poker service website

### Steps to execute the env locally
0. `git clone --recursive https://github.com/jcescala/poker-service.git .`
1. `composer install` requires a functioning composer 
2. `cp .env.docker .env`
3. `cd laradock`
4. `cp env-example .env`
5. `docker-compose up -d nginx redis` requires a functioning docker and docker compose
6. `http://localhost` 