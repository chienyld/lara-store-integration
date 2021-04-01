@extends('layouts.app')

@section('content')
    @if($carousel)
    <splide>
            @foreach($carousel as $card)
        <splide-slide>
        <div style="border-radius:10px;overflow:hidden">
            <img style="width:100%" src="/storage/card_image/{{$card->card_image}}">
        </div>
        </splide-slide>
            @endforeach
    </splide>
    @endif
<div class="container" style="height:20%;overflow:hidden">

</div>
<br>

    @auth
    @if(! Auth::user()->active)
        <div class="well" style="background-color: #ff7a7a;color:#333333">
            <h4>此帳號可能已被停權。</h4>
        </div>  
    @endif
    @endauth 

    @if(isset($bulletin->content))
    <div class="well" >
        <div><h5>{{ $bulletin->content }}</h5></div>
    </div>  
    @endif

    <h2>項目</h2>
    <div class="scrolln d-flex flex-wrap" style="overflow-x: hidden">
    @if(count($posts) > 0)    
        @foreach($posts as $post)
        
            <div class="fixed-well col-lg-4 col-sm-6 col-xs-6" style="overflow: hidden;scrollbar-width: none;padding:0">
                <div style="margin:0;overflow: hidden">
                    <div class="col-12" style="padding:0">
                        <img style="width:100%" src="/storage/cover_images/thumb.{{$post->cover_image}}">
                    </div>
                    <div class="col-12">
                        <div style="padding:1vh">
                            <h2 id="item"><a href="/posts/{{$post->id}}">{{$post->title}}</a></h2>
                            <h3 id="inventory">${{$post->deposit}}</h3>
                            @if($post->inventory && $post->type!=1)
                            <h3 id="inventory">剩餘數量 <b> {!!$post->inventory!!} </b> </h3>
                            @endif
                            @if(!$post->inventory)
                            <h4 style="color:#df4c4c;font-size:1rem">缺貨中</h4>
                            @endif
                        </div>
                        <form action="{{ url('/cart') }}" method="POST">
                        
                        {!! csrf_field() !!}
                        @auth
                        
                        
                        <input type="hidden" name="inventory" value="{{$post->inventory}}">
                        <input type="hidden" name="id" value="{{$post->id}}">
                        <input type="hidden" name="name" value="{{$post->title}}">
                        <input type="hidden" name="price" value="{{$post->deposit}}">
                        @if($post->inventory && $post->type!=1)
                        <amount-input min="1" max="{{$post->inventory}}"></amount-input>
                        @endif
                        @if($post->type==1)
                        <div style="height:10vh"></div>
                        @endif
                        @endauth
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
        {{$posts->links()}}
    @else
        <p>No posts found</p>
    @endif
    </div>


@endsection