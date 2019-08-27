@extends('layouts.app')

@section('content')
	<button class="btn btn-default"><a href="{{url('/posts')}}">Go Back</a></button>
	<h1>{{$post->title}}</h1>
	<img style="width: 100%" src="{{asset('/storage/cover_image/'.$post->cover_image)}}" /><hr/>
	<div>
		{!!$post->body!!}<!--do this way if want avoid htnml tags of the actual texts-->
	</div>
	<hr/>
	<small>Written on {{$post->created_at}} by {{$post->user->name}}</small>
	<hr/>
	@if(!Auth::guest())
		@if(Auth::user()->id==$post->user_id)
			<button class="btn btn-default"><a href="{{url('/posts/'.$post->id.'/edit')}}">Edit</a></button>

			{!! Form::open(['action'=>['PostsController@destroy', $post->id],'method'=>'POST', 'class'=> 'float-right'])!!}
				{!! Form::hidden('_method','DELETE')!!}
				{!! Form::submit('Delete',['class'=>'btn btn-danger'])!!}
			{!! Form::close() !!}
		@endif
	@endif
	
@endsection