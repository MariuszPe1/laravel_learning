@extends('layouts.app')

@section('content')
    <h1>{{$title}}</h1>
    @if(count($services) > 0)
        <ul class="list-group">
            @foreach($services as $i)
                <li class="list-group-item"> {{$i}} </li>
            @endforeach
        </ul>
    @endif
@endsection
