@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div>
            <div class="panel panel-default">
                <div class="panel-heading">訂單詳情</div>

                <div class="panel-body">
                    <!--<h3>Borrows</h3>-->
                    @if($borrows)
                        <table class="table table-striped">
                            <tr>
                                <th>id</th>
                                <th>會員</th>
                                <th>品項</th>
                                <th>數量</th>
                                <th></th>
                                
                            </tr>
                            @foreach($borrows as $borrow)                       
                                <div class="row">
                                <tr>
                                    {!! csrf_field() !!}
                                    <td>{{$borrow->order_id}}</td>
                                    <td>{{$borrow->user_name}}</td>
                                    <td>{{$borrow->name}}</td>
                                    <td>{{$borrow->qty}}</td>                                 
                                </tr>
                                </div>                         
                                
                            @endforeach
                        </table>
                    @else
                        <p>You have no borrows</p>
                    @endif
                    {{$borrows->links()}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
