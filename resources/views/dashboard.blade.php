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
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
