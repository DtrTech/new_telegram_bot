@extends('layouts.app')

@section('content')
<style>
.text-wrap {
    word-wrap: break-word;
    white-space: normal;
}
</style>
<section role="main" class="content-body">
    <header class="page-header">
        <h2>{{$telegram_user->telegram_id??''}}</h2>
    </header>

    @include('layouts.flash-message')

    <!-- start: page -->
    <div class="row">
        <div class="col-lg-12 mb-3">
            <section class="card">
                <div class="card-body">
                    <table class="table table-bordered table-striped mb-0" id="datatable_all">
                        <thead>
                            <tr>
                                <th width="10%">Message Id</th>
                                <th width="20%">Datetime</th>
                                <th width="10%">Message at</th>
                                <th>Text</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @foreach($telegram_user->messages as $s)
                                <tr>
                                    <td>{{$s->message_id??''}}</td>
                                    <td>{{$s->message_time??''}}</td>
                                    <td>{{$s->chat_type??''}}</td>
                                    <td class="text-wrap">{{$s->text??''}}</td>
                                </tr>
                            @endforeach --}}
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
    <!-- end: page -->
</section>
@endsection

@section('page-js')
    <script src="{{ asset('porto-assets/vendor/select2/js/select2.js') }}"></script>
    <script src="{{ asset('porto-assets/vendor/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('porto-assets/vendor/datatables/media/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('porto-assets/vendor/datatables/extras/TableTools/Buttons-1.4.2/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('porto-assets/vendor/datatables/extras/TableTools/Buttons-1.4.2/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('porto-assets/vendor/datatables/extras/TableTools/Buttons-1.4.2/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('porto-assets/vendor/datatables/extras/TableTools/Buttons-1.4.2/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('porto-assets/vendor/datatables/extras/TableTools/JSZip-2.5.0/jszip.min.js') }}"></script>
    <script src="{{ asset('porto-assets/vendor/datatables/extras/TableTools/pdfmake-0.1.32/pdfmake.min.js') }}"></script>
    <script src="{{ asset('porto-assets/vendor/datatables/extras/TableTools/pdfmake-0.1.32/vfs_fonts.js') }}"></script>
@endsection
@section('scripts')
    <script src="{{ asset('porto-assets/js/examples/examples.datatables.default.js') }}"></script>
    <script src="{{ asset('porto-assets/js/examples/examples.datatables.row.with.details.js') }}"></script>
    <script src="{{ asset('porto-assets/js/examples/examples.datatables.tabletools.js') }}"></script>
    <script>
    $(document).ready(function() {
        var telegram_user_id = "<?php echo $telegram_user->id; ?>";
        $('#datatable_all').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "{{ url('telegram_user/load_message') }}/" + telegram_user_id,
                "type": "GET"
            },
            "columns": [
              { "data": "message_id" },
              { "data": "message_time" },
              { "data": "chat_type" },
              { "data": "text" },
            ]
        });
    });
    </script>
@endsection
