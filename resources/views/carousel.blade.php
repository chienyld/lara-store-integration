@extends('layouts.app')

@section('content')
    <h2>輪播設定</h2>
    <span>圖片比例為21:9</span>
        
    {!! Form::open(['action' => 'App\Http\Controllers\CarouselController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
    <label class="btn btn-primary">選擇圖片
        <div class="form-group" style="display:none;">
            {{Form::file('card_image')}}
        </div>
    </label>
        {{Form::submit('確認新增', ['class'=>'btn btn-primary'])}}
    {!! Form::close() !!}
<hr>
<div class="d-flex flex-wrap">
@if(isset($carousel)) 
    @foreach($carousel as $card)
    
    <div class="col-lg-4 col-sm-12">
        <img style="width:100%" src="/storage/card_image/{{$card->card_image}}"><br><br>
    
        {!! Form::open(['action' => ['App\Http\Controllers\CarouselController@update',$card->id], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
        <label class="btn btn-primary">更改圖片
        <div class="form-group" style="display:none;">
            {{Form::file('card_image')}}
            {{Form::hidden('_method','PUT')}}
        </div>
        </label><br><br>
        <div class="row">
        <div class="col-6">
        {{Form::submit('上傳更新', ['class'=>'btn btn-primary'])}}
        {!! Form::close() !!}
        </div>
        <div class="col-6"> 
        {!!Form::open(['action' => ['App\Http\Controllers\CarouselController@destroy', $card->id], 'method' => 'POST', 'class' => 'pull-right'])!!}
        {{Form::hidden('_method', 'DELETE')}}
        
        {{Form::submit('刪除', ['class' => 'btn btn-danger'])}}
        {!!Form::close()!!}
        </div>
        </div>
        <br><hr>
    </div>
    
    @endforeach
@endif
</div> 

    

@endsection