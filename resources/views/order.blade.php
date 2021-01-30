@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div>
            <div class="panel panel-default">
                <div class="panel-heading">訂單狀況</div>

                <div class="panel-body">
                    <!--<h3>Borrows</h3>-->
                    @if($orders)
                        <table class="table table-striped">
                            <tr>
                                <th>訂單</th>
                                <th>編號</th>
                                <th>會員</th>
                                <th>金額</th>
                                <th>狀態</th>
                                <th></th>
                                
                            </tr>
                            @foreach($orders as $order)                       
                                <div class="row">
                                <tr>
                                    {!! csrf_field() !!}
                                    <td>{{$order->order_id}}</td>
                                    <td>{{$order->user_name}}</td>
                                    <td>{{$order->name}}</td>
                                    <td>{{$order->total}}</td>
                                    <td>
                                    <div class="row">
                                    <div class="col-lg-6">
                                    <verify-status2 datastatus="{{ $order->status }}"></verify-status2>
                                    </div>
                                    <div class="col-lg-6">
                                        @if(Auth::user()->privilege=='sa_admin')
                                        <verify-status token="{{ csrf_token() }}" datastatus="{{ $order->status }}" dataid="{{ $order->id }}" dataitem="{{ $order->order_id }}" dataqty="{{ $order->total }}"></verify-status>
                                        @else
                                        <div v-if="{{$order->status}}">器材已完成歸還，感謝使用本系統</div>
                                        <div v-else>器材借用申請成功，請於填寫日期時段至學務處領取器材</div>
                                        @endif
                                    </div>
                                    </div>
                                    </td>
                                    
                                </tr>
                                </div>                         
                                
                            @endforeach
                        </table>
                    @else
                        <p>There is No Order</p>
                    @endif
                    {{$orders->links()}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
