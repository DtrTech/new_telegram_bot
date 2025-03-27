@php
$currentRoute = request()->route()->getName();
@endphp
<div class="logo-container">
    <a href="/" class="logo">
        <img src="{{asset('porto-assets/img/ddlogo.png')}}" height="35" alt="Porto Admin" />
    </a>
    <button class="btn header-btn-collapse-nav d-lg-none" data-bs-toggle="collapse" data-bs-target=".header-nav">
        <i class="fas fa-bars"></i>
    </button>

    <!-- start: header nav menu -->
    <div class="header-nav collapse">
        <div class="header-nav-main header-nav-main-effect-1 header-nav-main-sub-effect-1 header-nav-main-square">
            <nav>
                <ul class="nav nav-pills" id="mainNav">
                    <li class="{{ $currentRoute == 'home' ? 'active' : ''}}">
                        <a class="nav-link" href="{{route('home')}}">
                        Home
                        </a>
                    </li>
                    <li class="{{ $currentRoute == 'telegram_user.index' ? 'active' : ''}}">
                        <a class="nav-link" href="{{route('telegram_user.index')}}">
                        Telegram User
                        </a>
                    </li>
                    <li class="{{ $currentRoute == 'telegram_group.index' ? 'active' : ''}}">
                        <a class="nav-link" href="{{route('telegram_group.index')}}">
                        Telegram Group
                        </a>
                    </li>
                    <li class="{{ $currentRoute == 'telegram_bot.index' ? 'active' : ''}}">
                        <a class="nav-link" href="{{route('telegram_bot.index')}}">
                        Telegram Bot
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>
