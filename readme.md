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
"Milkwood\CustomHelper\CustomHelperServiceProvider"
```

### Step 3: Publish Middleware

Via the console

```
php artisan vendor:publish
```

### Step 4: Add Actions To Check For

Add the rules you got for your user

```
protected $keysToCheckFor = ['reqRoles','forbiddenRoles','minAccessLevel','maxAccessLevel','moduleToAccess'];
```

### Step 5: Add The Trait To Class

Add the accessable trait to the class, example the user

```
use Milkwood\Accessable\Accessable;

class ExampleClass{
	
	use Accessable;

}
´´´

### Step 6: Create The Rules

See examples below

```
// Requirement functions

public function reqRoles($roles){

	return $this->sendOfAccess('userType', [ __FUNCTION__ => $roles]);

}

public function forbiddenRoles($roles){

	return $this->sendOfAccess('userType', [ __FUNCTION__ => $roles]);
	
}

public function minAccessLevel($accessLevel){

	return $this->sendOfAccess('userType', [ __FUNCTION__ => $accessLevel]);

}

public function maxAccessLevel($accessLevel){

	return $this->sendOfAccess('userType', [ __FUNCTION__ => $accessLevel]);

}

public function moduleToAccess($moduleName){

	return $this->sendOfAccess('company', [ __FUNCTION__ => $moduleName]);

}

// Requirement functions

public function reqRoles($roles){

	$roles = explode('|',$roles);
	if( in_array( $this->name , $roles) ){
		return true;
	}

	$this->error = 'Din bruger type har ikke adgang til den pågældende side';
	return false;

}

public function forbiddenRoles($roles){

	$roles = explode('|',$roles);
	if( ! in_array( $this->name , $roles) ){
		return true;
	}

	$this->error = 'Din bruger type er restrikteret fra den pågældende side';
	return false;
	
}

public function minAccessLevel($accessLevel){

	if( $this->access_level >= $accessLevel){
		return true;
	}

	$this->error = 'Din brugertype har for lav access til den pågældende side';
	return false;

}

public function maxAccessLevel($accessLevel){

	if( $this->access_level <= $accessLevel){
		return true;
	}

	$this->error = 'Din brugertype har for høj access til den pågældende side';
	return false;

}

public function moduleToAccess($moduleName){
	
	$modulesCompanyHasAccessTo = $this->modules->lists('name');

	if( in_array($moduleName, $modulesCompanyHasAccessTo)){
		return true;
	}

	$this->error = 'Din virksomhed har ikke adgang til dette modul';
	return false;

}
```