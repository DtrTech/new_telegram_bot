@extends('layouts.app')

@section('content')
<style>
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
                                <li class="breadcrumb-item">Telegram Users</li>
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
                                <th>Telegram Id</th>
                                <th>Bots</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Username</th>
                                <th>Contact No</th>
                                <th>Groups</th>
                                <th>Is Active</th>
                                <th>Actions</th>
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
<script>
    $(document).ready(function() {
        var table = $('#style-3').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "{{ route('telegram_user.load') }}",
                "type": "GET",
                "error": function(xhr, error, thrown) {
                    console.error("Error loading data:", error, thrown);
                    $('#style-3 tbody').html('<tr><td colspan="9" class="text-center">No data available</td></tr>');
                }
            },
            "columns": [
                { "data": "telegram_id" },
                { "data": "bot_names" },
                { "data": "first_name" },
                { "data": "last_name" },
                { "data": "username" },
                { "data": "contact_no" },
                { "data": "group_names" },
                {
                    "data": "is_active",
                    "orderable": false,
                    "render": function(data, type, row) {
                        return `
                            <label class="switch">
                                <input type="checkbox" data-id="${row.id}" ${row.is_active == 1 ? "checked" : ""} onchange="onToggleSwitch(this)">
                                <span class="slider round"></span>
                            </label>
                        `;
                    }
                },
                {
                    "data": "id",
                    "orderable": false,
                    "render": function(data, type, row) {
                        return `
                            <a href="/telegram_user/view_message/${row.id}" class="btn btn-secondary btn-sm" title="View Message">View Message</a>
                        `;
                    }
                }
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
</script>

    <script>
        function onToggleSwitch(value) {
            var id = value.dataset.id;
            var data = {'user_id': id, 'checked': value.checked};
            console.log(data);
            $.ajax({
                url: '{{route('telegram_user.toggleStatus')}}',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                contentType: 'application/json',
                data: JSON.stringify(data),
                success: function(data) {
                    // alert('success');
                },
                error: function(xhr, status, error) {
                    // alert(error);
                }
            });
        }
    </script>
@endsection
