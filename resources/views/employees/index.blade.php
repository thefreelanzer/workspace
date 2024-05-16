@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Employees</h1>
    <a href="{{ route('employees.create') }}" class="btn btn-primary mb-3">Create Employee</a>

    <!-- DataTable -->
    @component('partials.datatable')
    @slot('id', 'employee-table')
    @slot('headers')
    <th>First Name</th>
    <th>Last Name</th>
    <th>Company</th>
    <th>Email</th>
    <th>Phone</th>
    <th>Actions</th>
    @endslot
    @slot('route', route('employees.index'))
    @slot('data')
    [
    { data: 'first_name', name: 'first_name' },
    { data: 'last_name', name: 'last_name' },
    { data: 'company.name', name: 'company.name' },
    { data: 'email', name: 'email' },
    { data: 'phone', name: 'phone' },
    { data: 'actions', name: 'actions', searchable: false, orderable: false }
    ]
    @endslot
    @endcomponent
</div>
@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('#employee-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route('get_employees') }}',
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Authorization': 'Bearer {{ auth()->user()->api_token }}'
                },
                xhrFields: {
                    withCredentials: true // Include cookies in cross-origin requests
                }
            },
            columns: [{
                    data: 'first_name',
                    name: 'first_name'
                },
                {
                    data: 'last_name',
                    name: 'last_name'
                },
                {
                    data: 'company.name',
                    name: 'company.name'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'phone',
                    name: 'phone'
                },
                {
                    data: 'actions',
                    name: 'actions',
                    searchable: false,
                    orderable: false
                }
            ]
        });

        // Edit button click handler
        $('#employee-table').on('click', '.edit-btn', function() {
            var employeeId = $(this).data('id');
            window.location.href = '/employees/' + employeeId + '/edit';
        });

        // Delete button click handler
        $('#employee-table').on('click', '.delete-btn', function() {
            var employeeId = $(this).data('id');
            if (confirm('Are you sure you want to delete this employee?')) {
                $.ajax({
                    url: '/employees/' + employeeId,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(result) {
                        $('#employee-table').DataTable().ajax.reload();
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            }
        });
    });
</script>