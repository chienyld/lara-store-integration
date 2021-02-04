@extends('layouts.app')

@section('content')
    <h2>輪播設定</h2>
        
    {!! Form::open(['action' => 'App\Http\Controllers\CarouselController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
        <div class="form-group">
            {{Form::file('card_image')}}
        </div>
        {{Form::submit('新增', ['class'=>'btn btn-primary'])}}
    {!! Form::close() !!}
<hr>
<div class="d-flex flex-wrap">
@if(isset($carousel)) 
    @foreach($carousel as $card)
    
    <div class="col-lg-4 col-sm-12">
        <img style="width:100%" src="/storage/card_image/{{$card->card_image}}"><hr>
    
        {!! Form::open(['action' => ['App\Http\Controllers\CarouselController@update',$card->id], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
        <div class="form-group">
            {{Form::file('card_image')}}
            {{Form::hidden('_method','PUT')}}
        </div>
        <div class="row">
        <div class="col-6">
        {{Form::submit('更新', ['class'=>'btn btn-primary'])}}
        {!! Form::close() !!}
        </div>
        <div class="col-6"> 
        {!!Form::open(['action' => ['App\Http\Controllers\CarouselController@destroy', $card->id], 'method' => 'POST', 'class' => 'pull-right'])!!}
        {{Form::hidden('_method', 'DELETE')}}
        
        {{Form::submit('刪除', ['class' => 'btn btn-danger'])}}
        {!!Form::close()!!}
        </div>
        </div>
        
    </div>
    <hr>
    @endforeach
@endif
</div> 

    

@endsection