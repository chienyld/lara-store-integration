<!--/* Copyright © 2020 Chien-Yu Lin. All rights reserved.*/-->
<nav class="navbar navbar-inverse">
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
                {{ config('app.name', '中山醫學大學學生會') }}
            </a>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->

            <ul class="nav navbar-nav">
                <li>
                <form name="form1" action="/search" method="post">
                <div class="searchdiv" media="screen and (min-width: 400px) and (max-width: 700px)">
                    <input class="searching" type="text" name="keyword" placeholder=" Search">
                    <a href='javascript:document.form1.submit()' class="search-btn" style="text-decoration: none;">
                    <i class="fas fa-search"></i>
                    </a>
                </div>
                </form>
                </li>
                <li><a href="/posts">首頁</a></li>
                <li><a href="/type0">器具</a></li>
                <li><a href="/type1">飲品</a></li>   
                <li><a href="/type2">咖啡豆</a></li>
                <li><a href="/about">關於我們</a></li>
              
            </ul>
            
            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
            
            
                <!-- Authentication Links -->
                @if (Auth::guest())
                    <li><a href="{{ route('login') }}">Login</a></li>
                    <li><a href="{{ route('register') }}">Register</a></li>
                @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <!--<li><a href="/send/{{ Auth::user()->id }}">我的清單</a></li>-->
                            <li><a href="/cart">我的清單</a></li>
                            @if(Auth::user()->privilege=='sa_admin')
                            <li><a href="/send">訂單狀況</a></li>
                            <li><a href="/dashboard">品項管理</a></li>
                            <li><a href="/bulletin">公告管理</a></li>
                            <li><a href="/rules">借用規則</a></li>
                            <li><a href="/account">帳戶管理</a></li>
                            
                            @endif
                            <li>
                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                              document.getElementById('logout-form').submit();">
                                    Logout
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