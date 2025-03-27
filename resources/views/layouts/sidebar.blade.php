@php
$currentRoute = request()->route()->getName();
@endphp
<div class="sidebar-wrapper sidebar-theme">

<nav id="sidebar">

	<div class="navbar-nav theme-brand flex-row  text-center">
		<div class="nav-logo">
			<div class="nav-item theme-logo">
				<a href="{{route('home')}}">
					<img src="../src/assets/img/logo.svg" class="navbar-logo" alt="logo">
				</a>
			</div>
			<div class="nav-item theme-text">
				<a href="{{route('home')}}" class="nav-link"> TELEGRAM </a>
			</div>
		</div>
		<div class="nav-item sidebar-toggle">
			<div class="btn-toggle sidebarCollapse">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-left"><polyline points="11 17 6 12 11 7"></polyline><polyline points="18 17 13 12 18 7"></polyline></svg>
			</div>
		</div>
	</div>
	<div class="shadow-bottom"></div>
	<ul class="list-unstyled menu-categories" id="accordionExample">
		<li class="menu {{ $currentRoute == 'home' ? 'active' : ''}}">
			<a href="{{route('home')}}" aria-expanded="false" class="dropdown-toggle">
				<div class="">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user">
					<path d="M12 2a5 5 0 0 1 5 5v2a5 5 0 0 1-10 0V7a5 5 0 0 1 5-5zm0 10a5 5 0 0 1 5 5v2a5 5 0 0 1-10 0v-2a5 5 0 0 1 5-5zm0 10a5 5 0 0 1-5-5h10a5 5 0 0 1-5 5z"></path>
				</svg>
					<span>Home</span>
				</div>
			</a>
		</li>
		<li class="menu {{ $currentRoute == 'telegram_user.index' ? 'active' : ''}}">
			<a href="{{route('telegram_user.index')}}" aria-expanded="false" class="dropdown-toggle">
				<div class="">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user">
					<path d="M12 2a5 5 0 0 1 5 5v2a5 5 0 0 1-10 0V7a5 5 0 0 1 5-5zm0 10a5 5 0 0 1 5 5v2a5 5 0 0 1-10 0v-2a5 5 0 0 1 5-5zm0 10a5 5 0 0 1-5-5h10a5 5 0 0 1-5 5z"></path>
				</svg>
					<span>Telegram User</span>
				</div>
			</a>
		</li>
		<li class="menu {{ $currentRoute == 'telegram_group.index' ? 'active' : ''}}">
			<a href="{{route('telegram_group.index')}}" aria-expanded="false" class="dropdown-toggle">
				<div class="">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bank">
					<path d="M21 12h-4V6H7v6H3"></path>
					<path d="M21 12h-4v6H7v-6H3"></path>
					<path d="M11 22v-6"></path>
					<path d="M21 12h-4V6H7v6H3"></path>
				</svg>
					<span>Telegram Group</span>
				</div>
			</a>
		</li>
		<li class="menu {{ $currentRoute == 'telegram_bot.index' ? 'active' : ''}}">
			<a href="{{route('telegram_bot.index')}}" aria-expanded="false" class="dropdown-toggle">
				<div class="">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-pie-chart"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path><path d="M22 12A10 10 0 0 0 12 2v10z"></path></svg>
					<span>Telegram Bot</span>
				</div>
			</a>
		</li>
		<li class="menu {{ $currentRoute == 'send_message.index' ? 'active' : ''}}">
			<a href="{{route('send_message.index')}}" aria-expanded="false" class="dropdown-toggle">
				<div class="">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-pie-chart"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path><path d="M22 12A10 10 0 0 0 12 2v10z"></path></svg>
					<span>Send Message</span>
				</div>
			</a>
		</li>
		<li class="menu {{ $currentRoute == 'setting.index' ? 'active' : ''}}">
			<a href="{{route('setting.index')}}" aria-expanded="false" class="dropdown-toggle">
				<div class="">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-pie-chart"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path><path d="M22 12A10 10 0 0 0 12 2v10z"></path></svg>
					<span>Telegram Bot</span>
				</div>
			</a>
		</li>

		
	</ul>
	
</nav>

</div>
<!--  END SIDEBAR  -->