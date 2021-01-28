@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">主控版</div>

                <div class="panel-body">
                    <h3>帳戶管理</h3>
                    @if($accounts)
                        <table class="table table-striped">
                            <tr>
                                <th>id</th>
                                <th>名稱</th>
                                <th>權限</th>
                                <th>action</th>
                                <th></th>
                            </tr>
                            @foreach($accounts as $account)
                                <tr>
                                    <td>{{$account->id}}</td>
                                    <td>{{$account->name}}</td>
                                    <td>{{$account->privilege}}</td>
                                    <td><user-type active="{{ $account->active }}" userid="{{ $account->id }}"></user-type></td>   
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
