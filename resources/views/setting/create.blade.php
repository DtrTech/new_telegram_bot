@extends('layouts.app')

@section('content')
<div class="middle-content container-xxl p-0">

    <!--  BEGIN BREADCRUMBS  -->
    <div class="secondary-nav">
        <div class="breadcrumbs-container" data-page-heading="Analytics">
            <header class="header navbar navbar-expand-sm">
                <a href="javascript:void(0);" class="btn-toggle sidebarCollapse" data-placement="bottom">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                </a>
                <div class="d-flex breadcrumb-content">
                    <div class="page-header">
                        <div class="page-title">
                        </div>
                        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">{{ isset($setting) ? 'Edit' : 'Create' }} Setting</li>
                            </ol>
                        </nav>
        
                    </div>
                </div>
            </header>
        </div>
    </div>
    <div class="row layout-top-spacing">
        <div id="basic" class="col-lg-6 layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>Settings</h4>
                        </div>                 
                    </div>
                </div>
                <div class="widget-content widget-content-area" style="padding: 16px 15px">

                    <div class="row" >
                    <form enctype="multipart/form-data" @if (isset($setting)) method="post" action="{{ route('setting.update',$setting) }}" @else method="post" action="{{ route('setting.store') }}" @endif>
                        @csrf
                        <div class="col-lg-12 col-12">
                            <div class="form-group">
                                <label class="col-form-label">Title</label>
                                <input class="form-control" type="text" name="title" placeholder="Title" value="{{ $setting->title ?? '' }}" required>
                            </div>
                            <div class="form-group">
                                <label class="col-form-label">Key</label>
                                <input class="form-control" type="text" name="title" placeholder="key" value="{{ $setting->key ?? '' }}" disabled>
                            </div>
                            @if ($setting->key == 'remove_link')
                                <div class="form-group">
                                    <label class="col-form-label">Value</label>
                                    <select name="value" class="form-control select2">
                                        <option value="Yes" {{ isset($setting) && $setting->value == 'Yes' ? 'selected' : '' }}>Yes</option>
                                        <option value="No" {{ isset($setting) && $setting->value == 'No' ? 'selected' : '' }}>No</option>
                                    </select>
                                </div>
                            @else
                                <div class="form-group">
                                    <label class="col-form-label">Value
                                        @if (isset($setting) && $setting->key == 'telegram_id')
                                            (Separate using <strong>,</strong> and without spacing in between)
                                        @elseif (isset($setting) && $setting->key == 'filter_word')
                                            (Separate using <strong>|</strong> and without spacing in between)
                                        @endif
                                    </label>
                                    <textarea class="form-control" type="text" name="value" placeholder="value">{{ $setting->value ?? '' }}</textarea>
                                </div>
                            @endif
                        </div>

                        <button type="submit" class="mt-2 btn btn-primary float-right" style="margin-right:10px">Submit</button>
                        <a href="{{route('setting.index')}}" class="mt-2 btn btn-warning float-right" style="margin-right:10px">Back</a>
                    </form>
                    </div>
                </div> 
            </div>
        </div>
    </div>
</div>
    <!-- end: page -->
</section>
@endsection

@section('page-js')
    <script src="{{ asset('porto-assets/vendor/select2/js/select2.js') }}"></script>
    <script src="{{ asset('porto-assets/js/theme.init.js') }}"></script>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $("#message_type").bind('change', function() {
                console.log($(this).find(":selected").val() == 'Groups');
                if ($(this).find(":selected").val() == 'Groups') {
                    $('#selectGroups').show();
                    $('#selectUsers').hide();
                } else if ($(this).find(":selected").val() == 'Users') {
                    $('#selectUsers').show();
                    $('#selectGroups').hide();
                } else {
                    $('#selectUsers').hide();
                    $('#selectGroups').hide();
                }
            });
        });
    </script>
@endsection

