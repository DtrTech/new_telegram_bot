<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.head')
    @include('layouts.css')
    @if(!env('APP_LOCAL', false))
        <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests" />
    @endif
</head>

<body class="layout-boxed">
    <div id="load_screen"> <div class="loader"> <div class="loader-content">
        <div class="spinner-grow align-self-center"></div>
    </div></div></div>

    <!--  BEGIN NAVBAR  -->
    @include('layouts.navtop')

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">

        <div class="overlay"></div>
        <div class="search-overlay"></div>

            @include('layouts.sidebar')
        <div id="content" class="main-content">
            <div class="layout-px-spacing">

                @include('layouts.flash-message')
                @yield('content')
                

            </div>
            @include('layouts.footer')
        </div>
    </div>
    
    <div class="modal" id="passwordModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form enctype="multipart/form-data" method="post" action="{{ route('change_password') }}">
                <div class="modal-header">
                    <h5 class="modal-title"><b style="color:orange">Change Password</b></h5>
                    <a class="btn-close" onclick="closePassModal()" aria-label="Close"></a>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="mb-3">
                        <label class="col-form-label">Password</label>
                        <input class="form-control" type="password" name="new_password">
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label">Password</label>
                        <input class="form-control" type="password" name="new_password_confirmation">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Confirm</button>
                    <a class="btn btn-default" onclick="closePassModal()">Close</a>
                </div>
                </form>
            </div>
        </div>
    </div>

    @include('layouts.script')

    <script>
        function openPassModal(){
            $("#passwordModal").show();
        }
        function closePassModal(){
            $("#passwordModal").hide();
        }

    </script>


</body>
</html>
