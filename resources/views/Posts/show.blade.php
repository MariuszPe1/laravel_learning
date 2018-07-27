@extends ('layouts.app')

@section('content')
<a href="/posts" class="btn btn-secondary mb-4">Go back</a>
  <h1>{{$post->title}}</h1>
  <img src="/storage/cover_images/{{$post->cover_image}}">
  <br><br>
  <div>
    {!!$post->body!!}
  </div>
  <hr>
<small>Written on  {{$post->created_at}} by {{$post->user->name}}</small>
  <hr>
  {{-- Sprawdzenie czy jesteś zalogowany --}}
  @if(!Auth::guest() && Auth::user()->id == $post->user_id)
  <a href="/posts/{{$post->id}}/edit" class="btn btn-secondary mt-4 mr-2">Edit Post</a>
  {!!Form::open(['action' =>['PostsController@destroy', $post->id], 'method' =>'POST', 'class' => 'float-right']) !!}
    {{Form::hidden('_method', 'DELETE')}}
    {{Form::submit('Delete', ['class' => 'btn btn-danger'])}}
  {!!Form::close()!!}
  @endif
@endsection