# fantastic-blog

Setup

1. composer install
2. setup database (env file) and then:
  - php bin/console doctrine:database:create
  - php bin/console doctrine:migrations:migrate
  - php bin/console doctrine:fixtures:load
