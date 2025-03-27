@if ($message = Session::get('success'))
<div class="alert alert-light-success alert-dismissible fade show border-0 mb-4 layout-top-spacing" role="alert"> 
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"> 
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close" data-bs-dismiss="alert"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
    </button> 
    {{ $message }}
</div>
@endif
  
@if ($message = Session::get('error'))
<div class="alert alert-light-danger alert-dismissible fade show border-0 mb-4 layout-top-spacing" role="alert"> 
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"> 
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close" data-bs-dismiss="alert"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
    </button> 
    {{ $message }}
</div>
@endif
   
@if ($message = Session::get('warning'))
<div class="alert alert-light-warning alert-dismissible fade show border-0 mb-4 layout-top-spacing" role="alert"> 
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"> 
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close" data-bs-dismiss="alert"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
    </button> 
    {{ $message }}
</div>
@endif
   
@if ($message = Session::get('info'))
<div class="alert alert-light-info alert-dismissible fade show border-0 mb-4 layout-top-spacing" role="alert"> 
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"> 
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close" data-bs-dismiss="alert"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
    </button> 
    {{ $message }}
</div>
@endif
  
@if ($errors->any())
<div class="alert alert-light-danger alert-dismissible fade show border-0 mb-4 layout-top-spacing" role="alert"> 
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"> 
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close" data-bs-dismiss="alert"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
    </button> 
    @foreach ($errors->all() as $error)
         {{$error}}<br>
     @endforeach
</div>
@endif