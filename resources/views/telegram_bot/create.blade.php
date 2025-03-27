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
                                <li class="breadcrumb-item">Telegram Bot</li>
                                <li class="breadcrumb-item">Create</li>
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
                    <form enctype="multipart/form-data" @if (isset($telegram_bot)) method="post" action="{{ route('telegram_bot.update',$telegram_bot) }}" @else method="post" action="{{ route('telegram_bot.store') }}" @endif>
                        @csrf
                        <div class="col-lg-12 col-12">
                            <div class="form-group">
                                <label class="col-form-label">Telegram Username</label>
                                <input class="form-control" type="text" name="bot_name" placeholder="abc_bot" value="{{$telegram_bot->bot_name??''}}" required>
                            </div>
                            <div class="form-group">
                                <label class="col-form-label">Token</label>
                                <input class="form-control" type="text" name="bot_token" placeholder="token.." value="{{$telegram_bot->bot_token??''}}" required>
                            </div>
                            <div class="form-group">
                                <label class="col-form-label">Message Id (fill in to priority)</label>
                                <input class="form-control" type="text" name="reply_message_by_message_id" placeholder="messageId.." value="{{$telegram_bot->reply_message_by_message_id??''}}">
                            </div>
                            <div class="form-group">
                                <label class="col-form-label">Telegram Id (Where Message Id From)</label>
                                <input class="form-control" type="text" name="reply_message_from_telegram_id" placeholder="telegramId.." value="{{$telegram_bot->reply_message_from_telegram_id??''}}">
                            </div>
                            <div class="form-group">
                                <label class="col-form-label">Attachment</label>
                                <input class="form-control" type="file" name="file_attachment" accept="image/*">
                            @if(isset($telegram_bot)&&$telegram_bot->reply_message_path!=null)
                                <br>
                                <a href="{{ asset('storage/' . $telegram_bot->reply_message_path) }}" target="_blank" style="color:blue">Preview Uploaded</a>
                            @endif
                            </div>
                            <div class="form-group">
                                <label class="col-form-label">Content</label>
                                <textarea class="form-control" name="reply_message" rows="6">{{$telegram_bot->reply_message??''}}</textarea>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Button Text 1</label>
                                        <input class="form-control" type="text" name="button_text_1" placeholder="text.." value="{{$telegram_bot->button_text_1??''}}">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Button Link 1</label>
                                        <input class="form-control" type="text" name="button_link_1" placeholder="link.." value="{{$telegram_bot->button_link_1??''}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Button Text 2</label>
                                        <input class="form-control" type="text" name="button_text_2" placeholder="text.." value="{{$telegram_bot->button_text_2??''}}">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Button Link 2</label>
                                        <input class="form-control" type="text" name="button_link_2" placeholder="link.." value="{{$telegram_bot->button_link_2??''}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Button Text 3</label>
                                        <input class="form-control" type="text" name="button_text_3" placeholder="text.." value="{{$telegram_bot->button_text_3??''}}">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Button Link 3</label>
                                        <input class="form-control" type="text" name="button_link_3" placeholder="link.." value="{{$telegram_bot->button_link_3??''}}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="mt-2 btn btn-primary float-right" style="margin-right:10px">Submit</button>
                        <a href="{{route('telegram_bot.index')}}" class="mt-2 btn btn-warning float-right" style="margin-right:10px">Back</a>
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
