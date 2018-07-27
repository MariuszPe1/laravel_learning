@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">Dashboard</div>

        <div class="card-body">
          @if (session('status'))
            <div class="alert alert-success" role="alert">
              {{ session('status') }}
            </div>
          @endif
          <a href="/posts/create" class="btn btn-secondary mb-3">Create Post</a>
          <h3>Your blog posts</h3>
         
          @if(count($posts) > 0)
          <table class="table table-striped">
            <tr>
              <th>Title</th>
              <th></th>
              <th></th>
            </tr>            
            @foreach($posts as $i)
            <tr>
              <td>{{$i->title}}</td>
              <td><a href="/posts/{{$i->id}}/edit" class="btn btn-secondary">Edit</a></td>
              <td>  
                {!!Form::open(['action' =>['PostsController@destroy', $i->id], 'method' =>'POST', 'class' => 'float-right']) !!}
                  {{Form::hidden('_method', 'DELETE')}}
                  {{Form::submit('Delete', ['class' => 'btn btn-danger'])}}
                {!!Form::close()!!}
              </td>
            </tr>
            @endforeach
          </table>
          @else
          <p>You have no post</p>
          @endif

        </div>
      </div>
    </div>
  </div>
</div>
@endsection
