<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">Yii 2 Enhanced Project Template</h1>
    <br>
</p>

Yii 2 Enhanced Template is an improved skeleton of original Advanced Project Template 
[Yii 2](http://www.yiiframework.com/) application best for
developing complex Web applications with multiple tiers.

The template includes three tiers: front end, back end, and console, each of which
is a separate Yii application.

The template is designed to work in a team development environment. It supports
deploying the application in different environments.

Documentation is at [docs/guide/README.md](docs/guide/README.md).

[![Latest Stable Version](https://img.shields.io/packagist/v/yiisoft/yii2-app-advanced.svg)](https://packagist.org/packages/yiisoft/yii2-app-advanced)
[![Total Downloads](https://img.shields.io/packagist/dt/yiisoft/yii2-app-advanced.svg)](https://packagist.org/packages/yiisoft/yii2-app-advanced)
[![Build Status](https://travis-ci.com/yiisoft/yii2-app-advanced.svg?branch=master)](https://travis-ci.com/yiisoft/yii2-app-advanced)

DIRECTORY STRUCTURE
-------------------
Structure is a little bit different from original Yii2 Advanced Template, namely:
1. forms have been detached from models and contains separately in backend and frontend
2. models moved into core folder 
3. service layer has been realized in app
4. some services have been loaded on bootstrap level of app
5. asset domain is using for assets

```
backend
    assets/              contains application assets such as JavaScript and CSS
    bootstrap/           conteins backend bootstrap classes
    config/              contains backend configurations
    controllers/         contains Web controller classes
    forms/               conteins backend forms
    runtime/             contains files generated during runtime
    tests/               contains tests for backend application    
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
    widgets/             conteins backend widgets
common
    assets/              contains shared assets
    bootstrap/           conteins common bootstrap classes
    config/              contains shared configurations
    fixtures/            contains test fixtures
    mail/                contains view files for e-mails
    messages/            contains languages for multi language app
    runtime/             contains files generated during runtime
    tests/               contains tests for common classes    
    web/                 contains common Web resources
    widgets/             conteins common widgets
console
    config/              contains console configurations
    controllers/         contains console controllers (commands)
    migrations/          contains database migrations
    models/              contains console-specific model classes
    runtime/             contains files generated during runtime
core
    entities             contains model classes
    exceptions           contains exception classes
    helpers              conteins helpers
    repositories         conteins repositories for large classes
    search               conteins search models
    services             conteins services
environments/            contains environment-based overrides
frontend
    assets/              contains application assets such as JavaScript and CSS
    bootstrap/           conteins frontend bootstrap classes
    config/              contains frontend configurations
    controllers/         contains Web controller classes
    models/              contains frontend-specific model classes
    runtime/             contains files generated during runtime
    tests/               contains tests for frontend application
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
    widgets/             contains frontend widgets
vendor/                  contains dependent 3rd-party packages
```
Installation:
1. Clone github repository with link https://github.com/mrZema/yii2-advanced-template
2. Open a console terminal, and update dependencies, through command 
   php composer.phar update
3. In console terminal, execute the init command and select dev as environment.
   /path/to/php-bin/php /path/to/yii-application/init
4. Create a new database and adjust the components['db'] configuration in /path/to/yii-application/common/config/main-local.php accordingly.
5. Open a console terminal, apply migrations with command /path/to/php-bin/php /path/to/yii-application/yii migrate
6. Set root paths of your web server:
   for frontend /path/to/yii-application/frontend/web/ and using the URL http://name.domen/
   for backend /path/to/yii-application/backend/web/ and using the URL http://backend.name.domen/
   for assets /path/to/yii-application/common/web/ and using the URL http://assets.name.domen/
7. Open Frontend app with browser and create a first user. 
   Open DB and manually change auth-assignment for created User from User to Owner.
   Now you have access to backend and can ajust permissions for all roles.
