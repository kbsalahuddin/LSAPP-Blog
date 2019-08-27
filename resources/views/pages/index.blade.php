@extends('layouts.app')
@section('content')
	<div class="jumbotron text-center">
        <h1>{{$title}}</h1>
        <p>Welcome to my Laravel Application</p>
        <p><a class="btn btn-primary btn-lg" role="button" href="{{url('/login')}}">Login</a>
           <a class="btn btn-success btn-lg" role="button" href="{{url('/register')}}">Register</a>
        </p>
    </div>
        
@endsection
