<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;

class ApiController extends Controller
{
    public function login(Request $request)
    {
      if($request->email == null)
      {
        return response()->json([
          'status' => 0,
          'message' => 'Email is required'
        ]);
      }
      if($request->password == null)
      {
        return response()->json([
          'status' => 0,
          'message' => 'Password is required'
        ]);
      }

      //check if match
      if ($request->email == "bilal@gmail.com" && $request->password == "bilal123") {

        $authToken = Hash::make($request->email);
        //send data along with
        $client = new \GuzzleHttp\Client();
        $results = $client->request('GET', 'https://jsonplaceholder.typicode.com/posts');
        $posts = json_decode($results->getBody()->getContents());

        return response()->json([
          'status' => 1,
          'message' => 'Login successfully',
          'authToken' => $authToken,
          'posts' => $posts
        ]);
      }else {
        return response()->json([
          'status' => 0,
          'message' => 'Credentials do not match'
        ]);
      }
    }
    public function getPosts(Request $request)
    {
      if($request->header('authToken') == null)
      {
        return response()->json([
          'status' => 0,
          'message' => 'Please login first'
        ]);
      }
      // dd($request->header('authToken'));
      if(!Hash::check("bilal@gmail.com" , $request->header('authToken')))
      {
        return response()->json([
          'status' => 0,
          'message' => 'Invalid auth Token'
        ]);
      }
        //send data along with
        $client = new \GuzzleHttp\Client();
        $results = $client->request('GET', 'https://jsonplaceholder.typicode.com/posts');
        $posts = json_decode($results->getBody()->getContents());

        return response()->json([
          'status' => 1,
          'posts' => $posts
        ]);

    }
    public function getComments(Request $request,$id)
    {
      if($request->header('authToken') == null)
      {
        return response()->json([
          'status' => 0,
          'message' => 'Please login first'
        ]);
      }
      if(!Hash::check("bilal@gmail.com" , $request->header('authToken')))
      {
        return response()->json([
          'status' => 0,
          'message' => 'Invalid auth Token'
        ]);
      }
        //send data along with
        $client = new \GuzzleHttp\Client();
        $results = $client->request('GET', 'https://jsonplaceholder.typicode.com/posts/'.$id.'/comments');
        $comments = json_decode($results->getBody()->getContents());
         // dd($comments);
        return response()->json([
          'status' => 1,
          'comments' => $comments
        ]);

    }
    public function deletePost(Request $request , $id = null)
    {
      if($request->header('authToken') == null)
      {
        return response()->json([
          'status' => 0,
          'message' => 'Please login first'
        ]);
      }
      if($id == null)
      {
        return response()->json([
          'status' => 0,
          'message' => 'Id is required'
        ]);
      }
      if(!Hash::check("bilal@gmail.com" , $request->header('authToken')))
      {
        return response()->json([
          'status' => 0,
          'message' => 'Invalid auth Token'
        ]);
      }
        //send data along with
        $client = new \GuzzleHttp\Client();

        $results = $client->delete('https://jsonplaceholder.typicode.com/posts/'.$id);

        $status= json_decode($results->getStatusCode());
        if ($status == 200) {
          return response()->json([
            'status' => 1,
            'message' => "deleted successfully."
          ]);
        }else {
          return response()->json([
            'status' => 0,
            'message' => "Some thing went wrong."
          ]);
        }

    }
    public function deleteComment(Request $request , $commentId = null ,$postId = null)
    {
      // dd($id , $postId);
      if($request->header('authToken') == null)
      {
        return response()->json([
          'status' => 0,
          'message' => 'Please login first'
        ]);
      }
      if($commentId == null)
      {
        return response()->json([
          'status' => 0,
          'message' => 'Comment Id is required'
        ]);
      }
      if($postId == null)
      {
        return response()->json([
          'status' => 0,
          'message' => 'Post Id is required'
        ]);
      }
      if(!Hash::check("bilal@gmail.com" , $request->header('authToken')))
      {
        return response()->json([
          'status' => 0,
          'message' => 'Invalid auth Token'
        ]);
      }
        //send data along with
        $client = new \GuzzleHttp\Client();

        $results = $client->delete('https://jsonplaceholder.typicode.com/comments/'.$commentId);

        $status= json_decode($results->getStatusCode());
        if ($status == 200) {
          return response()->json([
            'status' => 1,
            'message' => "deleted successfully."
          ]);
        }else {
          return response()->json([
            'status' => 0,
            'message' => "Some thing went wrong."
          ]);
        }

    }
}
