@extends('layouts.app')

@section('content')
<section role="main" class="content-body">
    <header class="page-header">
        <h2>Send Message</h2>
    </header>

    @include('layouts.flash-message')

    <!-- start: page -->
    <div class="row d-flex justify-content-center">
        <div class="col-lg-6 mb-3">
            <section class="card">
                <form class="theme-form mega-form" enctype="multipart/form-data" @if (isset($send_message)) method="post" action="{{ route('send_message.update',$send_message) }}" @else method="post" action="{{ route('send_message.store') }}" @endif>
                    @csrf
                    <div class="card-body">
                        <h6>Send Message</h6>
                        <div class="mb-3">
                            <label class="col-form-label">Bot</label>
                            <select class="form-control" name="telegram_bot_id" id="telegram_bot_id" required>
                                <option value="">-- Select Bot --</option>
                                @foreach ($telegram_bots as $telegram_bot)
                                    <option value="{{ $telegram_bot->id }}">{{ $telegram_bot->bot_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="col-form-label">Title</label>
                            <input class="form-control" type="text" name="title" placeholder="Title" value="{{ $send_message->title ?? '' }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="col-form-label">Schedule Send Time (Leave blank to send upon submit)</label>
                            <input class="form-control" type="datetime-local" name="schedule_send_time" value="{{ $send_message->schedule_send_time ?? '' }}">
                        </div>
                        <div class="mb-3">
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
                        <div class="mb-3">
                            <label class="col-form-label">Message Id (fill in to priority)</label>
                            <input class="form-control" type="text" name="message_id" placeholder="Message Id" value="{{ $send_message->message_id ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label class="col-form-label">Telegram Id (Where Message Id From)</label>
                            <input class="form-control" type="text" name="telegram_id" placeholder="Telegram Id" value="{{ $send_message->telegram_id ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label class="col-form-label">Attachment</label>
                            <input class="form-control" type="file" name="file_attachment" accept="image/*">
                            @if(isset($send_message) && $send_message->message_path != null)
                                <br>
                                <a href="{{ asset('storage/' . $send_message->message_path) }}" target="_blank" style="color:blue">Preview Uploaded</a>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label class="col-form-label">Message</label>
                            <textarea class="form-control" name="message" rows="6">{{ $send_message->message ?? '' }}</textarea>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <a href="{{route('send_message.index')}}" class="btn btn-secondary">Back</a>
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

