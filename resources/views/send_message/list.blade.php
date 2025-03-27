@extends('layouts.app')

@section('content')
<section role="main" class="content-body">
    <header class="page-header">
        <h2>{{ $sendMessage->title }} Details List</h2>
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
                                <th>Group / User Name</th>
                                <th>Sent</th>
                                <th>Sent At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sendMessage->sendMessageDetails as $detail)
                                <tr>
                                    <td>{{ $detail->content_type == "App\Models\TelegramGroup" ? $detail->content->group_name : $detail->content->first_name . $detail->content->last_name }}</td>
                                    <td>{{ $detail->sent_at ? 'Yes' : 'No' }}</td>
                                    <td>{{ $detail->sent_at ?? '-' }}</td>
                                </tr>
                            @endforeach
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
        $('#datatable_all').DataTable({
            language: {
                search: "{{ __('message.datatable-search') }}",
                lengthMenu: "{{ __('message.datatable-show') }} _MENU_ {{ __('message.datatable-entries') }}",
                info: "{{ __('message.datatable-showing') }} _START_ {{ __('message.datatable-to') }} _END_ {{ __('message.datatable-of') }} _TOTAL_ {{ __('message.datatable-entries') }}",
                paginate: {
                    previous: "{{ __('message.datatable-previous') }}",
                    next: "{{ __('message.datatable-next') }}"
                },
                emptyTable: "{{ __('message.datatable-empty') }}"
            }
        });
    });
    </script>
@endsection
