@extends('layouts.app')

@section('content')
    <h2>借用規則</h2>
        
    {!! Form::open(['action' => 'App\Http\Controllers\RulesController@edit', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
        <div class="form-group">
            {{Form::label('content', 'Content')}}
            {{Form::textarea('content', '', ['id' => 'article-ckeditor', 'class' => 'form-control', 'placeholder' => '借用規則'])}}
        </div>
        {{Form::submit('更新規則', ['class'=>'btn btn-primary'])}}
    {!! Form::close() !!}

    

@endsection