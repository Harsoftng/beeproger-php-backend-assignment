<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Beeproger Todo App

This Todo App is the back-end part of the take home assignment from Beeproger. Find details below

### The beeproger assignment
As indicated during the job interview, we would like you to complete an assignment
for us. This will allow us to get a clear picture of your knowledge level.

### Here's what you should do:
Develop a To-do list and/or Shopping list for us. It should consist of a loss back-
and frontend, which communicate with each other.

### The characteristics of this To-do list are are:
- Shows a list of items;
- Being able to edit an item;
- A photo uploaded with the item;
- Being able to view the details of an item;
- The option to mark an item as complete;
- Being able to remove an item from the list;
- And make sure to always check for errors.

### The backend requirements:
- Laravel Framework - REST
- MySQL Database

### The frontend requirements:
At beeproger we work with the following frameworks/libraries/templating engines
- React
- Angular
- Ionic
- Blade
- Livewire

# Installation Instructions

### Technologies used
- Docker 
- Docker Compose
- Redis
- Mysql 
- Laravel 

### Installation
The backend is fully dockerized, and runs on port 1759. So the backend REST API Service will run at http://localhost:1759/api
- Download & Install Docker desktop from https://www.docker.com/products/docker-desktop/
- Navigate to the root directory of this project and run the following commands:
  - Run "docker-compose up -d" to setup all backend services and apps.
  - Run "docker exec -it bp_api sh" to enter the backend docker container shell and execute the following commands
      - php artisan optimize:clear
      - php artisan migrate
      - exit


## NB:
This project was done on holidays/vacation so i did not really have the time to complete the app. The only part remaining is writing automated tests for both the backend and frontend apps. If you have any questions or require more info running the app, please contact me on harsoftng@gmail.com.

Cheers 🥂😊
    
## License

This app and the Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
