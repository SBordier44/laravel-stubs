# Laravel Stubs

This repository contains customized default laravel stubs.

## Installation
```shell
composer require --dev sbordier44/larastubs
```

Add these lines in your composer.json for publish automatically updated stubs
```json
{
    "scripts": {
        "post-update-cmd": [
            "@php artisan publish:stubs --force"
        ]
    }
}
```
