@extends('layouts.app')

@section('content')
	<h1>Posts</h1>
	@if(count($posts)>0)
		@foreach($posts as $post)
			<div class="card bg-light card-body">
				<div class="row">
					<div class="col-md-4 col-sm-4">
						<img style="width: 100%" src="{{asset('/storage/cover_image/'.$post->cover_image)}}" />
					</div>
					<div class="col-md-8 col-sm-8">
						
						<h4><a href="{{url('/posts/'.$post->id)}}">{{$post->title}}</a></h4>
						<small>Written on {{$post->created_at}} by {{$post->user->name}}</small>
					</div>
				</div>
			</div>
		@endforeach
		<!--for pagination--> 
		{{$posts->links()}}
	@else
		<p>No Posts found</p>	
	@endif
@endsection