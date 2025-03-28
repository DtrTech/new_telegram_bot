@extends('layouts.app')

@section('content')
<style>
.text-wrap {
    word-wrap: break-word;
    white-space: normal;
}
body.dark .dataTables_wrapper {
    padding: 20px !important;
}
</style>
<div class="middle-content container-xxl p-0">
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
                                <li class="breadcrumb-item">{{$telegram_user->telegram_id??''}} Messages</li>
                            </ol>
                        </nav>
        
                    </div>
                </div>
            </header>
        </div>
    </div>

    <div class="row layout-spacing layout-top-spacing">
        <div class="col-lg-12">
            <div class="statbox widget box box-shadow">
                <div class="widget-content widget-content-area">
                    <table id="style-3" class="table style-3 dt-table-hover non-hover">
                        <thead>
                            <tr>
                                <th width="10%">Message Id</th>
                                <th width="20%">Datetime</th>
                                <th width="10%">Message at</th>
                                <th>Text</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="9" class="text-center">Loading data...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('scripts')
    <script src="{{ asset('porto-assets/js/examples/examples.datatables.default.js') }}"></script>
    <script src="{{ asset('porto-assets/js/examples/examples.datatables.row.with.details.js') }}"></script>
    <script src="{{ asset('porto-assets/js/examples/examples.datatables.tabletools.js') }}"></script>
    <script>
        
    $(document).ready(function() {
        var telegram_user_id = "<?php echo $telegram_user->id; ?>";
        var table = $('#style-3').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "{{ url('telegram_user/load_message') }}/" + telegram_user_id,
                "type": "GET",
                "error": function(xhr, error, thrown) {
                    console.error("Error loading data:", error, thrown);
                    $('#style-3 tbody').html('<tr><td colspan="9" class="text-center">No data available</td></tr>');
                }
            },
            "columns": [
              { "data": "message_id" },
              { "data": "message_time" },
              { "data": "chat_type" },
              { "data": "text" },
            ],
            "language": {
                "emptyTable": "No data available",
                "search": "{{ __('message.datatable-search') }}",
                "lengthMenu": "{{ __('message.datatable-show') }} _MENU_ {{ __('message.datatable-entries') }}",
                "info": "{{ __('message.datatable-showing') }} _START_ {{ __('message.datatable-to') }} _END_ {{ __('message.datatable-of') }} _TOTAL_ {{ __('message.datatable-entries') }}",
                "paginate": {
                    "previous": "{{ __('message.datatable-previous') }}",
                    "next": "{{ __('message.datatable-next') }}"
                }
            }
        });
    });

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
