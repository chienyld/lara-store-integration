@extends('layouts.app')

@section('content')
<div  class="postshow">
    <a href="/posts" class="btn btn-default">Go Back</a>
    <div class="container">
    <div class="row" style="padding:20px">
        <div class="col-lg-4 col-md-12" style="margin-top:30px;padding:15px">
            <img style="width:100%" src="/storage/cover_images/{{$post->cover_image}}">
        </div>
        
        <div class="showboard col-lg-8 col-md-12">
            <div style="padding:20px;margin-top:10px">  
                <div><h1>{{$post->title}}</h1></div><br>                
                <div style="font-size:1em;color:#0f4c81;right:1px">
                    價格 ${!!$post->deposit!!}
                </div>
                <div style="font-size:0.8em;color:#ABABAB">
                    剩餘 <b> {!!$post->inventory!!} </b> 
                </div>
                <div style="font-size:0.8em" style="min-height:80px;word-wrap: break-word;">
                    {!!$post->body!!}
                </div>
            </div>
        </div>
    </div>
    <form action="{{ url('/cart') }}" method="POST">
                {!! csrf_field() !!}
                @auth
                
                @endauth
                <input type="hidden" name="inventory" value="{{$post->inventory}}">
                <input type="hidden" name="id" value="{{$post->id}}">
                <input type="hidden" name="name" value="{{$post->title}}">
                <input type="hidden" name="price" value="{{$post->deposit}}">
                <div class="row">
                <amount-input min="1" max="{{$post->inventory}}"></amount-input>
                </div>
                </form>
    </div>
    @if(!Auth::guest())
        @if(Auth::user()->id == $post->user_id)
            <a href="/posts/{{$post->id}}/edit" class="btn btn-default">Edit</a>

            {!!Form::open(['action' => ['App\Http\Controllers\PostsController@destroy', $post->id], 'method' => 'POST', 'class' => 'pull-right'])!!}
                {{Form::hidden('_method', 'DELETE')}}
                {{Form::submit('Delete', ['class' => 'btn btn-danger'])}}
            {!!Form::close()!!}
        @endif
    @endif
</div>
<br><br>  
@endsection