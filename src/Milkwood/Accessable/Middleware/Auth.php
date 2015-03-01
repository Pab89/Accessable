<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class Auth {

	/**
	 * The Guard implementation.
	 *
	 * @var Guard
	 */
	protected $auth;

	/**
	 * Create a new filter instance.
	 *
	 * @param  Guard  $auth
	 * @return void
	 */
	public function __construct(Guard $auth)
	{
		$this->auth = $auth;
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{

		// Påsæt nøvendige class vars til senere brug
		$this->next = $next;
		$this->request = $request;
		$this->redirectPath = false;
		$this->action = $this->request->route()->getAction();
		
		// Check om brugeren overhovedet er logget ind
		if( $this->simpleAuth() ){
			// Hvis brugeren er logget ind foretag så advancedCheck
			$this->advancedAuth();
		}

		return $this->returnThis();
	}

	// Gær de forskellige restrictioner der må være
	public function advancedAuth(){
		
		if( ! $this->auth->user()->hasAccessToAction(false,true)){
			$this->redirectPath = '/';
		}
		
	
	}


	// Bare check om brugeren er logget ind eller ej
	public function simpleAuth(){
	
		if ($this->auth->guest())
		{
			$this->redirectPath = 'auth/login';
			return false;
		}

		return true;
	
	}

	// Hvis der er nul fejl lad da brugeren kommer videre eller redirect il $this->redirectPath
	public function returnThis(){

		if( $this->redirectPath ){

			if ($this->request->ajax()){

				return response('Unauthorized.', 401);
			}else{
				return redirect($this->redirectPath);

			}

		}
	
		$next = $this->next;
		return $next($this->request);
	
	}

}
