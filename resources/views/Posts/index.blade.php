@extends ('layouts.app')

@section('content')
  <h1>Posts</h1>
  @if(count($posts) > 0)
    @foreach($posts as $i)
      
      {{-- <div class="card">
        <div class="card-body">
            <div class="card-header">{{$i->title}}</div>
          <small>Written on {{$i->created_at}} </small>
          <h5>{{$i->body}}</h5>
        </div>
      </div> --}}
      
      <div class="card text-secondary bg-light mb-3" >
        <div class="card-header">
          
          <h3><a href="/posts/{{$i->id}}"> {{$i->title}}</a></h3>
          <small>Written on {{$i->created_at}} by {{$i->user->name}}</small>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-2 col-sm-2">
              <img style="width:100%" src="/storage/cover_images/{{$i->cover_image}}">            
            </div>
            <div class="col-md-10 col-sm-10">
              <p class="card-text">{!!$i->body!!}</p>
            </div>
          </div>
        </div>
      </div>

    @endforeach
    {{$posts->links()}}
  @else
    <p> No posts found </p>
  @endif
@endsection