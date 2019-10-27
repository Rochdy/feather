# Weather console
``` Cool developers don't have to waste time and look out of the window to see the current weather conditions.```

## Build & Run The App
```You can replace cairo with any city! :D```
- Docker way:
    - Requirements:
        - docker
    - Build the image: ```docker build . -t roshdy-weather-app```
    - Run the command: ```docker run roshdy-weather-app php bin/console weather cairo```
    - You can also run the tests! ```docker run roshdy-weather-app ./vendor/bin/phpunit tests```
    
- Simple Way:
    - Requirements:
        - PHP >= 7.3
        - Composer
    - Install dependencies : ```composer install```
    - To run the command: ```php bin/console weather cairo```
    - to run the tests: ```./vendor/bin/phpunit tests```


## Used Packages
- ```symfony/console```: Creating command line app
- ```symfony/dependency-injection```: DI
- ```symfony/dotenv```: Manage the .env file
- ```phpunit/phpunit```: As a testing framework
