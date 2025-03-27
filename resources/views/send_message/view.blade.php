@extends('layouts.app')

@section('content')
<section role="main" class="content-body">
    <header class="page-header">
        <h2>View Message</h2>
    </header>

    @include('layouts.flash-message')

    <!-- start: page -->
    <div class="row d-flex justify-content-center">
        <div class="col-lg-6 mb-3">
            <section class="card">
                <div class="card-body">
                    <h6>Send Message</h6>
                    <div class="mb-3">
                        <label class="col-form-label">Bot</label>
                        <select class="form-control" name="telegram_bot_id" id="telegram_bot_id" disabled>
                            <option value="">-- Select Bot --</option>
                            @foreach ($telegram_bots as $telegram_bot)
                                <option value="{{ $telegram_bot->id }}" {{ $sendMessage->telegram_bot_id == $telegram_bot->id ? 'selected' : '' }}>{{ $telegram_bot->bot_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label">Title</label>
                        <input class="form-control" type="text" name="title" placeholder="Title" value="{{ $sendMessage->title ?? '' }}" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label">Schedule Send Time</label>
                        <input class="form-control" type="datetime-local" name="schedule_send_time" value="{{ $sendMessage->schedule_send_time ?? '' }}" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label">Type</label>
                        <select class="form-control" name="message_type" id="message_type" disabled>
                            <option value="">-- Select Type --</option>
                            <option value="All Groups" {{ $sendMessage->message_type == 'All Groups' ? 'selected' : '' }}>All Groups</option>
                            <option value="Groups" {{ $sendMessage->message_type == 'Groups' ? 'selected' : '' }}>Groups</option>
                            <option value="All Users" {{ $sendMessage->message_type == 'All Users' ? 'selected' : '' }}>All Users</option>
                            <option value="Users" {{ $sendMessage->message_type == 'Users' ? 'selected' : '' }}>Users</option>
                        </select>
                    </div>
                    @if ($sendMessage->message_type == 'All Groups' || $sendMessage->message_type == 'Groups')
                        <div class="mb-3">
                            <label class="col-form-label">Selected Groups</label>
                            <ol>
                                @foreach ($sendMessage->sendMessageDetails as $detail)
                                    <li>{{ $detail->content->group_name }}</li>
                                @endforeach
                            </ol>
                        </div>
                    @else
                        <div class="mb-3">
                            <label class="col-form-label">Selected Users</label>
                            <ol>
                                @foreach ($sendMessage->sendMessageDetails as $detail)
                                    <li>{{ $detail->content->first_name }}  {{ $detail->content->last_name }}</li>
                                @endforeach
                            </ol>
                        </div>
                    @endif
                    <div class="mb-3">
                        <label class="col-form-label">Message Id (fill in to priority)</label>
                        <input class="form-control" type="text" name="message_id" placeholder="Message Id" value="{{ $sendMessage->message_id ?? '' }}" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label">Telegram Id (Where Message Id From)</label>
                        <input class="form-control" type="text" name="telegram_id" placeholder="Telegram Id" value="{{ $sendMessage->telegram_id ?? '' }}" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label">Attachment</label>
                        {{-- <input class="form-control" type="file" name="file_attachment" accept="image/*"> --}}
                        @if(isset($sendMessage) && $sendMessage->message_path != null)
                            <br>
                            <a href="{{ asset('storage/' . $sendMessage->message_path) }}" target="_blank" style="color:blue">Preview Uploaded</a>
                        @else
                            <p>-</p>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label">Message</label>
                        <textarea class="form-control" name="message" rows="6" disabled>{{ $sendMessage->message ?? '' }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label">Is Sent</label>
                        <p>{{ $sendMessage->is_sent ? 'Yes' : 'No' }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label">Updated At</label>
                        <p>{{ $sendMessage->updated_at ?? '' }}</p>
                    </div>
                </div>
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

