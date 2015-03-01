# Accessable
Laravel middleware for authfication

### Step 1: Install Through Composer

```
Composer require 'milkwood/accessable'
```

### Step 2: Add The ServiceProvider

Add the following line to your service providers i Config/App.php

```
"Milkwood\Accessable\AccesableServiceProvider"
```

### Step 3: Publish Middleware

Via the console

```
php artisan vendor:publish --merge
```

### Step 4: Add Actions To Check For

Add the rules you got for your user

```
protected $keysToCheckFor = ['reqRoles','forbiddenRoles','minAccessLevel','maxAccessLevel','moduleToAccess'];
```
