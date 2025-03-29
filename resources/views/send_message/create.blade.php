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
                                <li class="breadcrumb-item">Send Message</li>
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
                            <h4>Telegram Bot</h4>
                        </div>                 
                    </div>
                </div>
                <div class="widget-content widget-content-area" style="padding: 16px 15px">

                    <div class="row" >
                    <form enctype="multipart/form-data" @if (isset($send_message)) method="post" action="{{ route('send_message.update',$send_message) }}" @else method="post" action="{{ route('send_message.store') }}" @endif>
                        @csrf
                        <div class="col-lg-12 col-12">
                            <div class="form-group">
                                <label class="col-form-label">Bot</label>
                                <select class="form-control" name="telegram_bot_id" id="telegram_bot_id" required>
                                    <option value="">-- Select Bot --</option>
                                    @foreach ($telegram_bots as $telegram_bot)
                                        <option value="{{ $telegram_bot->id }}">{{ $telegram_bot->bot_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="col-form-label">Title</label>
                                <input class="form-control" type="text" name="title" placeholder="Title" value="{{ $send_message->title ?? '' }}" required>
                            </div>
                            <div class="form-group">
                                <label class="col-form-label">Schedule Send Time (Leave blank to send upon submit)</label>
                                <input class="form-control" type="datetime-local" name="schedule_send_time" value="{{ $send_message->schedule_send_time ?? '' }}">
                            </div>
                            <div class="form-group">
                                <label class="col-form-label">Type</label>
                                <select class="form-control" name="message_type" id="message_type" required>
                                    <option value="">-- Select Type --</option>
                                    <option value="All Groups">All Groups</option>
                                    <option value="Groups">Groups</option>
                                    <option value="All Users">All Users</option>
                                    <option value="Users">Users</option>
                                </select>
                            </div>
                            <div id="selectGroups" style="display: none;">
                                <label class="col-form-label">Select Groups</label>
                                <select class="select2 select2size form-control" name="groups[]" id="groupList" multiple data-plugin-selectTwo>
                                    <option value="">-- Select Groups --</option>
                                    @isset($telegram_groups)
                                        @foreach ($telegram_groups as $group)
                                            <option value="{{ $group->id }}">{{ $group->group_name }}</option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>
                            <div id="selectUsers" style="display: none;">
                                <label class="col-form-label">Select Users</label>
                                <select class="select2 select2size form-control" name="users[]" id="userList" multiple data-plugin-selectTwo>
                                    <option value="">-- Select Users --</option>
                                    @isset($telegram_users)
                                        @foreach ($telegram_users as $user)
                                            <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="col-form-label">Message Id (fill in to priority)</label>
                                <input class="form-control" type="text" name="message_id" placeholder="Message Id" value="{{ $send_message->message_id ?? '' }}">
                            </div>
                            <div class="form-group">
                                <label class="col-form-label">Telegram Id (Where Message Id From)</label>
                                <input class="form-control" type="text" name="telegram_id" placeholder="Telegram Id" value="{{ $send_message->telegram_id ?? '' }}">
                            </div>
                            <div class="form-group">
                                <label class="col-form-label">Attachment</label>
                                <input class="form-control" type="file" name="file_attachment" accept="image/*">
                                @if(isset($send_message) && $send_message->message_path != null)
                                    <br>
                                    <a href="{{ asset('storage/' . $send_message->message_path) }}" target="_blank" style="color:blue">Preview Uploaded</a>
                                @endif
                            </div>
                            <div class="form-group">
                                <label class="col-form-label">Message</label>
                                <textarea class="form-control" name="message" rows="6">{{ $send_message->message ?? '' }}</textarea>
                            </div>
                            
                            <div class="row">
                                <div class="col-lg-6 col-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Button Text 1</label>
                                        <input class="form-control" type="text" name="button_text_1" placeholder="text.." value="{{$send_message->button_text_1??''}}">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Button Link 1</label>
                                        <input class="form-control" type="text" name="button_link_1" placeholder="link.." value="{{$send_message->button_link_1??''}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Button Text 2</label>
                                        <input class="form-control" type="text" name="button_text_2" placeholder="text.." value="{{$send_message->button_text_2??''}}">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Button Link 2</label>
                                        <input class="form-control" type="text" name="button_link_2" placeholder="link.." value="{{$send_message->button_link_2??''}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Button Text 3</label>
                                        <input class="form-control" type="text" name="button_text_3" placeholder="text.." value="{{$send_message->button_text_3??''}}">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Button Link 3</label>
                                        <input class="form-control" type="text" name="button_link_3" placeholder="link.." value="{{$send_message->button_link_3??''}}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="mt-2 btn btn-primary float-right" style="margin-right:10px">Submit</button>
                        <a href="{{route('send_message.index')}}" class="mt-2 btn btn-warning float-right" style="margin-right:10px">Back</a>
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

            $('#telegram_bot_id').bind('change', function() {
                getGroupList($(this).val());
                getUserList($(this).val());
            });
        });

        function getGroupList(telegram_bot_id) {
            $.ajax({
                type: "get",
                url: "{{ url('telegram_bot/getGroupList') }}/" + telegram_bot_id,
                success: function (response) {
                    $(document.body).css({
                        'cursor': 'default'
                    });
                    markup = "";
                    $("#groupList").html(markup);
                    $.each(response, function(key, value) {
                        $("#groupList").append('<option value=' + key + '>' + value +'</option>');
                    });
                }
            });
        }

        function getUserList(telegram_bot_id) {
            $.ajax({
                type: "get",
                url: "{{ url('telegram_bot/getUserList') }}/" + telegram_bot_id,
                success: function (response) {
                    $(document.body).css({
                        'cursor': 'default'
                    });
                    markup = "";
                    $("#userList").html(markup);
                    $.each(response, function(key, value) {
                        $("#userList").append('<option value=' + key + '>' + value +'</option>');
                    });
                }
            });
        }
    </script>
@endsection

