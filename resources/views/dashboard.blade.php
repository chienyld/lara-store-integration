@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">主控板</div>

                <div class="panel-body">
                    <a href="/posts/create" class="btn btn-primary">新增項目</a>
                    <h3>品項管理</h3>
                    @if($posts)
                        <table class="table table-striped">
                            <tr>
                                <th>品項</th>
                                <th>總數</th>
                                <th>剩餘</th>
                                <th></th>
                                <th></th>
                            </tr>
                            @foreach($posts as $post)
                                <tr>
                                    <td>{{$post->title}}</td>
                                    <td>{{$post->total}}</td>
                                    <td>{{$post->inventory}}</td>
                                    <td><a href="/posts/{{$post->id}}/edit" class="btn btn-default">編輯</a></td>
                                    <td>
                                        {!!Form::open(['action' => ['App\Http\Controllers\PostsController@destroy', $post->id], 'method' => 'POST', 'class' => 'pull-right'])!!}
                                            {{Form::hidden('_method', 'DELETE')}}
                                            {{Form::submit('刪除', ['class' => 'btn btn-danger'])}}
                                        {!!Form::close()!!}
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    @else
                        <p>目前什麼都木有</p>
                    @endif
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
