## About this project 

Api to search songs, movies, television shows and ebooks concentrated on a single endpoint 
These resources are taken from https://itunes.apple.com
and of http://tvmaze.com

### Preparing our environment
```
require
- php 7.2.5 or higher
```

* Install php dependencies on ubuntu 20   
    
    `` sudo apt install php php-soap php-xml php-mbstring php-zip php-curl  ``   

* Install composer 
    
    `` sudo apt install composer (*) ``

* We need install git also:   
    
    `` sudo apt install git ``


(*) for install on another versions maybe you need use the official documentation https://getcomposer.org/



### Installing project

* clone this project  https://github.com/alextuadev/api_seeker   
`` $ git clone https://github.com/alextuadev/api_seeker ``
 
* move to directory   
  `` $ cd api_seeker ``
    
* install the  project dependencies  
`` ~/api_seeker$ composer install  ``


### Run the development server

To run a development server use
`` $ php artisan serve  ``


### Docs
For a full documentation use the next endpoint of the api    
    `` /api/documentation ``

### Testing the api
Run the following command on the root of project   

`` php artisan test  ``
  
