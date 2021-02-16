<!--/* Copyright © 2020 Chien-Yu Lin. All rights reserved.*/-->
<nav class="navbar navbar-inverse" style="margin-bottom:0">
    <div class="container">
        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Branding Image -->
            <a style="font-size:15px;color:#fff" class="navbar-brand" href="{{ url('/') }}">
                &nbsp<span>沏啡</span>
                <!--{{ config('app.name', '沏啡') }}-->
                <span style="font-size:0.7em;color:#deedfb">chiefe coffee</span>
            </a>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->

            <ul class="nav navbar-nav">
                <li>
                <form name="form1" action="/search" method="post">
                {!! csrf_field() !!}
                <div class="searchdiv" media="screen and (min-width: 400px) and (max-width: 700px)">
                &nbsp<input class="searching" type="text" name="keyword" placeholder=" Search">
                    <a href='javascript:document.form1.submit()' class="search-btn" style="text-decoration: none;">
                    <i class="fas fa-search"></i>
                    </a>
                </div>
                </form>
                </li>
                <li><a href="/posts">&nbsp首頁</a></li>
                <li><a href="/type0">&nbsp器具</a></li>
                <li><a href="/type1">&nbsp飲品</a></li>   
                <li><a href="/type2">&nbsp咖啡豆</a></li>
                <li><a href="/about">&nbsp關於我們</a></li>
              
            </ul>
            
            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
            
            
                <!-- Authentication Links -->
                @if (Auth::guest())
                    <li><a href="{{ route('login') }}">&nbsp登入</a></li>
                    <li><div class="register-button" onclick="location.href='{{ route('register') }}';">加入會員</div></li>
                @else
                    <li>
                    <div style="margin:auto">
                    <!--<div style="position:absolute;background-color:red;border-radius:50%;height:13px;width:13px;left:30px;top:12px;color:#fff;text-align:center"><span  style="font-size:2px">1</span></div>-->
                    <div>
                        <a href="/cart">
                        &nbsp<i class="fas fa-shopping-cart" style="color:#deedfb;margin:18px"></i></a>
                    </div>
                    </div>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                        &nbsp{{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <!--<li><a href="/send/{{ Auth::user()->id }}">我的清單</a></li>-->
                            <li><a href="/cart">我的清單</a></li>
                            <li><a href="/myorder">我的訂單</a></li>
                            @if(Auth::user()->privilege=='sa_admin')
                            <li><a href="/order">出貨管理</a></li>
                            <li><a href="/dashboard">品項管理</a></li>
                            <li><a href="/bulletin">公告管理</a></li>
                            <li><a href="/rules">關於我們</a></li>
                            <li><a href="/account">帳戶管理</a></li>
                            <li><a href="/carousel">輪播卡片</a></li>
                            
                            @endif
                            <li>
                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                              document.getElementById('logout-form').submit();">
                                    登出
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </li>
                    
                @endif
            </ul>
        </div>
    </div>
</nav>