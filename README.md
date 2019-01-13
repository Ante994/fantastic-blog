# fantastic-blog

Setup

1. composer install

2a. setup database (env file) and then:
  - php bin/console doctrine:database:create
  - php bin/console doctrine:migrations:migrate
  - php bin/console doctrine:fixtures:load

(for testing)
2b. setup database (env file) and then:
  - php bin/console doctrine:database:create --env=test
  - php bin/console doctrine:schema:create --env=test
  - php bin/console doctrine:migrations:migrate --env=test
  - php bin/console doctrine:fixtures:load --env=test
