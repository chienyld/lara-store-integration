@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div>
            <div class="panel panel-default">
                <div class="panel-heading">訂單狀況</div>

                <div class="panel-body">
                    <!--<h3>Borrows</h3>-->
                    @if($borrows)
                        <table class="table table-striped">
                            <tr>
                                <th>id</th>
                                <th>會員</th>
                                <th>品項</th>
                                <th>數量</th>
                                <th>狀態</th>
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
                                    <td>
                                    <div class="row">
                                    <div class="col-lg-6">
                                    <verify-status2 datastatus="{{ $borrow->status }}"></verify-status2>
                                    </div>
                                    <div class="col-lg-6">
                                        @if(Auth::user()->privilege=='sa_admin')
                                        <verify-status token="{{ csrf_token() }}" datastatus="{{ $borrow->status }}" dataid="{{ $borrow->id }}" dataitem="{{ $borrow->borrow_id }}" dataqty="{{ $borrow->qty }}"></verify-status>
                                        @else
                                        <div v-if="{{$borrow->status}}">器材已完成歸還，感謝使用本系統</div>
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
                        <p>You have no borrows</p>
                    @endif
                    {{$borrows->links()}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
