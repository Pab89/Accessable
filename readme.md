# Accessable
Laravel middleware for authfication

### Step 1: Install Through Composer

```
Composer require 'milkwood/accessable'
```

### Step 2: Add The ServiceProvider

Add the following lines to your service providers i Config/App.php and a CH facade
You are here adding both the service provider for CustomHelper which Accessable is dependent on aswell as it's own
You are also adding a facade for Customhelper

```
"Milkwood\Accessable\AccesableServiceProvider",
"Milkwood\CustomHelper\CustomHelperServiceProvider",

'CH' => 'Milkwood\CustomHelper\CH',
```

### Step 3: Publish Middleware

Via the console, to publish the middlewhere

```
php artisan vendor:publish
```

You can now use
```
['middleware' => 'auth]
```
In your routes

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
```

### Step 6: Create The Rules

See examples below of rules on the usertype below

```
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
```

See below for an example of company requirering modules

```
public function moduleToAccess($moduleName){
	
	$modulesCompanyHasAccessTo = $this->modules->lists('name');

	if( in_array($moduleName, $modulesCompanyHasAccessTo)){
		return true;
	}

	$this->error = 'Din virksomhed har ikke adgang til dette modul';
	return false;

}
```

See below how to send of the request to another class

```
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
```
