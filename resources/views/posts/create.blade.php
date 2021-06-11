@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create Post</h1>
    {!! Form::open(['action' => 'App\Http\Controllers\PostsController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
        <div class="form-group">
            {{Form::label('title', '品項')}}
            {{Form::text('title', '', ['class' => 'form-control', 'placeholder' => 'Title'])}}
        </div>
        <div class="form-group">
            {{Form::label('deposit', '金額')}}
            {{Form::number('deposit', 'value', ['class' => 'form-control', 'placeholder' => 'security deposit'])}}
        </div>
        <div class="form-group">
            {{Form::label('inventory', '數量')}}
            {{Form::number('inventory', 'value', ['class' => 'form-control', 'placeholder' => 'inventory'])}}
        </div>
        <div class="form-group">
            {{Form::label('type', '類別')}}<br>
            {{Form::label('type', '醫療器材')}}
            {{Form::radio('type', '0', ['class' => 'form-control'])}}
            {{Form::label('type', '保健食品')}}
            {{Form::radio('type', '1', ['class' => 'form-control'])}}
            {{Form::label('type', '日常用品')}}
            {{Form::radio('type', '2', ['class' => 'form-control'])}}
            {{Form::label('type', '餐點飲品')}}
            {{Form::radio('type', '3', ['class' => 'form-control'])}}
        </div>
        <div class="form-group">
            {{Form::label('body', '內容')}}
            {{Form::textarea('body', '', ['id' => 'article-ckeditor', 'class' => 'form-control', 'placeholder' => 'Body Text'])}}
        </div>
        <label class="btn btn-primary">選擇圖片
        <div class="form-group" style="display:none">
            {{Form::file('cover_image')}}
        </div>
        </label><br><br>
        {{Form::submit('送出', ['class'=>'btn btn-primary'])}}
    {!! Form::close() !!}
</div>
@endsection