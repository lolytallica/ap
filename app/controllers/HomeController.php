<?php

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function showWelcome()
	{
		return View::make('hello');
	}

    public function curlTest()
    {
       // $curl = new anlutro\cURL\cURL;

        //$response = $curl->get('http://www.google.com');
        $response = cURL::get('http://www.google.com');

        // easily build an url with a query string
     /*   $url = $curl->$curl->buildUrl('http://www.google.com', ['s' => 'curl']);
        $response = $curl->get($url);*/

        $url = cURL::buildUrl('http://www.google.com', ['s' => 'curl']);
        $response = cURL::get($url);


        // post() takes an array of POST data
       /* $url = $curl->buildUrl('http://api.myservice.com', ['api_key' => 'my_api_key']);
        $response = $curl->post($url, ['post' => 'data']); */

        $url = cURL::buildUrl('http://api.myservice.com', ['api_key' => 'my_api_key']);
        $response = cURL::post($url, ['post' => 'data']);

        // add "json" to the start of the method to post as JSON
        //$response = $curl->jsonPut($url, ['post' => 'data']);
        $response = cURL::jsonPut($url, ['post' => 'data']);

        // a response object is returned
        var_dump($response->code); // response status code (for example, '200 OK')
        echo $response->body;
        var_dump($response->headers); // array of headers
        var_dump($response->info); // array of curl info
    }

}