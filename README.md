# How to set up on Linux (tested on Ubuntu 26.04 lts)

## Set up environment
```docker compose up -d```

## Do migrations
```docker compose exec -it php bin/console doctrine:migrations:migrate```
```docker compose exec -it php bin/console doctrine:migrations:migrate --env=test```

## Load fixtures:
```docker compose exec -it php add-fixtures```
```docker compose exec -it php add-fixtures-test```

## Check api platform url to test api:
```http://localhost:8080/api```

## Execute unit and api tests:
```docker compose exec -it php composer tests```

## Execute api tests
```docker compose exec -it php composer api-tests```



