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
                                    <td>{{$order->total}}</td>
                                    <td>
                                    <div class="row">
                                    <div class="col-lg-6">
                                    <verify-status2 datastatus="{{ $order->status }}"></verify-status2>
                                    </div>
                                    <div class="col-lg-6">
                                        @if(Auth::user()->privilege=='sa_admin')
                                        <verify-status token="{{ csrf_token() }}" datastatus="{{ $order->status }}" dataid="{{ $order->id }}"></verify-status>
                                        @else
                                        <div v-if="{{$order->status}}">商品已出貨，請耐心等候</div>
                                        <div v-else>訂單準備中，請耐心等候</div>
                                        @endif
                                    </div>
                                    </div>
                                    </td>
                                    <td>
                                    <form name="form2" action="/send/lookup" method="post">
                                    {!! csrf_field() !!}
                                    <div class="searchdiv" media="screen and (min-width: 400px) and (max-width: 700px)">
                                        <input type="hidden" name="order_id" value="{{$order->order_id}}">
                                        <button type="submit" class="search-btn" style="border:none">
                                        <i class="fas fa-search"></i>
                                        </button>
                                        </a>
                                    </div>
                                    </form>
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
