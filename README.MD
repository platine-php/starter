## PLATINE STARTER
**Platine Starter** is a `Platine Framework` based simple starter project. Most of the commonly needed features of an 
application like `Authentication`, `Authorisation`, `User` and `Role management`, `Application Backend`, are available here. 
It is modular, so you may use this project as a base and build your own application.  
The `Platine Starter` comes with several features which are the most common in almost all applications. It is a template project which means it is intended to build in a way that it can be used for other projects.
It is a modular application, and some modules are installed by default. It will be helpful to use it as a base for future applications.

[![Project Status](http://opensource.box.com/badges/active.svg)](http://opensource.box.com/badges)
[![Latest Stable Version](https://poser.pugx.org/platine-php/starter/v/stable)](https://packagist.org/packages/platine-php/starter)
[![Latest Unstable Version](https://poser.pugx.org/platine-php/starter/v/unstable)](https://packagist.org/packages/platine-php/starter)
[![Total Downloads](https://poser.pugx.org/platine-php/starter/downloads)](https://packagist.org/packages/platine-php/starter)
[![License](https://poser.pugx.org/platine-php/starter/license)](https://packagist.org/packages/platine-php/starter)  
![Tests](https://github.com/platine-php/starter/actions/workflows/ci.yml/badge.svg)
[![Quality Score](https://img.shields.io/scrutinizer/g/platine-php/starter.svg?style=flat-square)](https://scrutinizer-ci.com/g/platine-php/starter)
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/c54c59491a1b4ef09c8d946d76dad729)](https://app.codacy.com/gh/platine-php/starter/dashboard?utm_source=gh&utm_medium=referral&utm_content=&utm_campaign=Badge_grade)
[![Codacy Badge](https://app.codacy.com/project/badge/Coverage/c54c59491a1b4ef09c8d946d76dad729)](https://app.codacy.com/gh/platine-php/starter/dashboard?utm_source=gh&utm_medium=referral&utm_content=&utm_campaign=Badge_coverage)

### Requirements 
- Any web server **(Apache, nginx, lighttp, etc.)**
- **PHP >= 7.4**, **PHP 8** 

### Installation
Follow the steps mentioned below to install and run the project. 
1. Create project
```bash
composer create-project platine-php/starter
```
2. Create `.env` file by copying the `.env.example`. You may use the command to do that 
```bash
cp .env.example .env
```
3. Update the database, application URL and others information in `.env` file.
```ini
# Application
PL_APP_URL=http://localhost/starter/public/

# Database
PL_DB_DRIVER=mysql
PL_DB_NAME=db_platine_starter
PL_DB_HOST=127.0.0.1
PL_DB_PORT=3306
PL_DB_USER=root
PL_DB_PASSWORD=
```
4. Run the command to execute migration 
```bash
php platine migration:init
php platine migration:migrate
```
5. Run the command to insert demo data
```bash
php platine seed:exec
```
6. You may create a virtualhost entry to access the application or run `php platine server` from the project root and visit `http://127.0.0.1:8080`.

### Demo
Use the following account credentials to access the application backend:
```
Username: admin
Password: admin
```

### License
MIT License See [LICENSE.MD](LICENSE.MD)

### Resources 
 - [Documentation](https://docs.platine-php.com)