@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div>
            <div class="panel panel-default">

                <div class="panel-body">
                    <!--<h3>Borrows</h3>-->
                    @if($borrows)
                    <h4>訂單編號 : {{$borrows[0]->order_id}}</h4>
                    <h4>會員名稱 : {{$borrows[0]->user_name}}</h4>
                        <table class="table table-striped">
                            <tr>
                                <th>品項</th>
                                <th>金額</th>
                                <th>數量</th>
                                
                            </tr>
                            <?php $sum = 0;
                            foreach($borrows as $borrow){
                                $sum += $borrow->depositamt*$borrow->qty;
                                } ?>
                            @foreach($borrows as $borrow)   
                                               
                                <div class="row">
                                <tr>
                                    {!! csrf_field() !!}
                                    <!--<td>{{$borrow->order_id}}</td>
                                    <td>{{$borrow->user_name}}</td>-->
                                    <td>{{$borrow->name}}</td>
                                    <td>{{$borrow->depositamt}}</td>
                                    <td>{{$borrow->qty}}</td>                                 
                                </tr>
                                </div>                         
                                
                            @endforeach
                        </table>
                          
                        <h4>小計 : {{$sum}}</h4>
                        @if($order->shipping==0)
                        <h4>運費 : 0</h4>
                        @else
                        <h4>運費 : 60</h4>
                        @endif
                        <h3>總金額 : {{$order->total}}</h3>
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
