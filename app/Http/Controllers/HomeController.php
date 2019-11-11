<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
      // $client = new \GuzzleHttp\Client();
      // $results = $client->request('GET', 'https://jsonplaceholder.typicode.com/posts');
      // $posts = json_decode($results->getBody()->getContents());

      // foreach ($posts as  $post) {
      //   // code...
      //   dd($post);
      // }
        return view('home');
    }
    // public function deletePost($id)
    // {
    //   dd($id);
    // }
}
