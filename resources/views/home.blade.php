@extends('layouts.app')

@section('pagespecificscripts')

<script >
$(function() {
  $('#loading').hide();
  // check login status
  $authToken = localStorage.getItem("authToken");
  // window.localStorage.setItem("authToken",$authToken);


  // console.log($authToken == "undefined");
  if ($authToken == "undefined" || $authToken == null) {
    $('#posts').hide();

  }else {
    $('#login').hide();
    $('#loading').show();

    $.ajaxSetup({
       headers:{
          'authToken': $authToken
       }
    });
    $.get('{{ route('getPosts') }}',
    function(data, status){
      if (data.status == 0) {
        alert(data.message);
        return;
      }
      if (data.status == 1) {

        $('#login').hide();
        $('#posts').show();
        $('.alert').show();

        $('#postsTable tbody').empty();
        $table = $('#postsTable tbody');

        $.each(data.posts, function (a, b) {
                  $table.append("<tr id="+b.id+"><td><a href='javascript:void(0)' onclick='getComments("+b.id+")'>"+b.title+"</a></td>"+
                  "<td>"+b.body+"</td>"+
                  "<td><a onclick='deletePost("+b.id+")' class='btn btn-danger'><i class='fa fa-trash'></i></a></td>"+
                  "</tr>");

              });
        $('#loading').hide();


      }else {
        alert('Something went wrong.');
      }
    });
  }
  $('.alert').hide();
});

function isEmail(email) {
  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  return regex.test(email);
}
function login()
{
  // hide if error displayed
  $('#emailError').hide();
  $('#passwordError').hide();
  $('#loading').hide();

  $email = $('#email').val();
  $password = $('#password').val();
  if (!isEmail($email)) {
    $('#emailError').text('Email is not valid');
    $('#emailError').show();
    return 0;
  }
  if ($password.length < 3) {
    $('#passwordError').text('Password is not valid');
    $('#passwordError').show();
    return 0;
  }
  $('#loading').show();

  $.post('{{ route('login') }}',
  {
    email: $email,
    password: $password
  },
  function(data, status){
    if (data.status == 0) {
      alert(data.message);
      return;
    }
    if (data.status == 1) {

      localStorage.setItem("authToken", data.authToken);
      $('#login').hide();
      $('#posts').show();
      $('.alert').show();

      $('#postsTable tbody').empty();
      $table = $('#postsTable tbody');

      $.each(data.posts, function (a, b) {

                $table.append("<tr id="+b.id+"><td><a href='javascript:void(0)' onclick='getComments("+b.id+")'>"+b.title+"</a></td>"+
                "<td>"+b.body+"</td>"+
                "<td><a onclick='deletePost("+b.id+")' class='btn btn-danger'><i class='fa fa-trash'></i></a></td>"+
                "</tr>");

            });
            $('#loading').hide();

    }else {
      alert('Something went wrong.');
    }
  });
}

function deletePost(id)
{
  $('#loading').show();
  $authToken = localStorage.getItem("authToken");
  $.ajaxSetup({
     headers:{
        'authToken': $authToken
     }
  });

  var url = '{{ route("deletePost", "id") }}';
      url = url.replace('id', id);
      $.get( url,
      function(data, status){
        if (data.status == 0) {
          alert(data.message);
          return;
        }
        if (data.status == 1) {
          console.log('deleted');
          $('#'+id).remove();
          $('#commentTable'+id).remove();
          $('#loading').hide();

        }else {
          alert('Something went wrong.');
        }
      });
}

function getComments(id)
{
  $('#loading').show();

  $authToken = localStorage.getItem('authToken');
  $.ajaxSetup({
     headers:{
        'authToken': $authToken
     }
  });
  var url = '{{ route("getComments", "id") }}';
      url = url.replace('id', id);
  $.get(url,
  function(data, status){
    if (data.status == 0) {
      alert(data.message);
      return;
    }
    if (data.status == 1) {

      $table = $('#'+id);
      console.log($table);
      $table.after("<tr id='newRowAdded'><td colspan='3'><table id='commentTable"+id+"'><thead><tr><td>Name</td><td>Email</td><td>Message</td><td>Action</td></tr></thead><tbody></tbody></table></td></tr>");
      $table = $("#commentTable"+id+" tbody");
      console.log($table);
      $.each(data.comments, function (a, b) {
                $table.append("<tr id='commentRow"+b.id+"'><td>"+b.name+"</td>"+
                "<td>"+b.email+"</td>"+
                "<td>"+b.body+"</td>"+
                "<td><a onclick='deleteComment("+b.id+")' class='btn btn-danger'><i class='fa fa-trash'></i></a></td>"+
                "</tr>");

            });
            $('#loading').hide();

    }else {
      alert('Something went wrong.');
    }
  });
}

function deleteComment(id)
{
  $('#loading').show();

  $authToken = localStorage.getItem("authToken");
  $.ajaxSetup({
     headers:{
        'authToken': $authToken
     }
  });
  $tableId = $('#commentRow'+id).closest('table').attr('id');
  $postId = $tableId.replace('commentTable','');
  console.log($postId);

  var url = '{{ route("deleteComment", ["commentId"=>"id1" ,"postId"=>"id2"]) }}';
      url = url.replace('id1', id);
      url = url.replace('id2', $postId);
      console.log('url');
      $.get( url,
      function(data, status){
        if (data.status == 0) {
          alert(data.message);
          return;
        }
        if (data.status == 1) {
          console.log('deleted');
          $('#commentRow'+id).remove();
          $('#loading').hide();

        }else {
          alert('Something went wrong.');
        }
      });
}

</script>


@endsection
@section('content')
  <style >
    .d-none{
      display:none;
    }
    .thead{
      background: #efefef;
    }
  </style>
  {{-- Messages --}}
  <div class="container">
    <div class="alert alert-info alert-dismissible fade show" role="alert">
      <strong>Welcome to Demo App.</strong>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>

  </div>
  <div id="loading" class="container mb-2">
    <button class="btn btn-primary" type="button" disabled>
      <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
      Loading...
    </button>
  </div>
  <div class="container bg-white">
      <div class="row justify-content-center">
 {{-- Table --}}
     <div class="col-md-12">

     <div class="card" id="posts">
       <div class="card-header">
          Posts
       </div>
       <div class="card-body">
        <div class="table-responsive" >
          <table id="postsTable" class="table table-hover">
            <thead class="thead">
              <tr>
                <td>Title</td>
                <td>Body</td>
                <td>Action</td>
              </tr>
            </thead>
            <tbody>

            </tbody>
          </table>
        </div>
        {{-- Pagination  --}}
        <nav aria-label="Page navigation example">
          <ul class="pagination justify-content-center">
            <li class="page-item">
              <a class="page-link" href="#" tabindex="-1">Previous</a>
            </li>
            <li class="page-item"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item">
              <a class="page-link" href="#">Next</a>
            </li>
          </ul>
        </nav>
      </div>
      </div>
      </div>
  {{-- Login --}}
          <div class="col-md-8" id="login">
              <div class="card">
                  <div class="card-header">{{ __('Login') }}</div>

                  <div class="card-body">

                          <div class="form-group row">
                              <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                              <div class="col-md-6">
                                  <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                      <span id="emailError" class="invalid-feedback alert-dismissible fade show" role="alert">
                                      </span>
                              </div>
                          </div>

                          <div class="form-group row">
                              <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                              <div class="col-md-6">
                                  <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                    <span id="passwordError" class="invalid-feedback" role="alert">
                                    </span>
                              </div>
                          </div>

                          <div class="form-group row mb-0">
                              <div class="col-md-8 offset-md-4">
                                  <button onclick="login()" type="submit" class="btn btn-primary " data-dismiss="alert">
                                      {{ __('Login') }}
                                  </button>


                              </div>
                          </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
@endsection
