@extends('layouts.app')

@section('content')
<section role="main" class="content-body">
    <header class="page-header">
        <h2>{{ isset($setting) ? 'Edit' : 'Create' }} Setting</h2>
    </header>

    @include('layouts.flash-message')

    <!-- start: page -->
    <div class="row d-flex justify-content-center">
        <div class="col-lg-6 mb-3">
            <section class="card">
                <form class="theme-form mega-form" enctype="multipart/form-data" @if (isset($setting)) method="post" action="{{ route('setting.update',$setting) }}" @else method="post" action="{{ route('setting.store') }}" @endif>
                    @csrf
                    <div class="card-body">
                        <h6>Settings</h6>
                        <div class="mb-3">
                            <label class="col-form-label">Title</label>
                            <input class="form-control" type="text" name="title" placeholder="Title" value="{{ $setting->title ?? '' }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="col-form-label">Key</label>
                            <input class="form-control" type="text" name="title" placeholder="key" value="{{ $setting->key ?? '' }}" disabled>
                        </div>
                        @if ($setting->key == 'remove_link')
                            <div class="mb-3">
                                <label class="col-form-label">Value</label>
                                <select name="value" class="form-control select2">
                                    <option value="Yes" {{ isset($setting) && $setting->value == 'Yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="No" {{ isset($setting) && $setting->value == 'No' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                        @else
                            <div class="mb-3">
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
                    <div class="card-footer text-end">
                        <a href="{{route('setting.index')}}" class="btn btn-secondary">Back</a>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </section>
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

