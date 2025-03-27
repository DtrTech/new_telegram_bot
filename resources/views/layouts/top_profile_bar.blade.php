<div class="header-right">
    <span class="separator"></span>

    <div id="userbox" class="userbox">
        <a href="#" data-bs-toggle="dropdown">
            <figure class="profile-picture">
                <img src="{{asset('porto-assets/img/!logged-user.jpg')}}" alt="User" class="rounded-circle"/>
            </figure>
            <div class="profile-info" >
                <span class="name">{{Auth::user()->username??''}}</span>
                <span class="role">{{Auth::user()->role_title??''}}</span>
            </div>

            <i class="fa custom-caret"></i>
        </a>

        <div class="dropdown-menu">
            <ul class="list-unstyled">
                <li class="divider"></li>
                <li>
                    <a role="menuitem" tabindex="-1" onclick="openPassModal()"><i class="bx bx-lock"></i>{{ __('message.topbar-changepassword') }}</a>
                </li>
                <li>
                    <a role="menuitem" tabindex="-1" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i class="bx bx-power-off"></i> {{ __('message.topbar-logout') }}</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
						@csrf
					</form>
                </li>
            </ul>
        </div>
    </div>
</div>
