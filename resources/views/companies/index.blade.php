@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Companies</h1>
    <a href="{{ route('companies.create') }}" class="btn btn-primary mb-3">Create Company</a>

    <!-- DataTable -->
    @component('partials.datatable')
    @slot('id', 'company-table')
    @slot('headers')
    <th>Name</th>
    <th>Email</th>
    <th>Logo</th>
    <th>Website</th>
    <th>Actions</th>
    @endslot
    @slot('route', route('companies.index'))
    @slot('data')
    [
    { data: 'name', name: 'name' },
    { data: 'email', name: 'email' },
    { data: 'logo', name: 'logo', searchable: false, orderable: false },
    { data: 'website', name: 'website' },
    { data: 'actions', name: 'actions', searchable: false, orderable: false }
    ]
    @endslot
    @endcomponent
</div>
@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    console.log('{{ auth()->user()->api_token }}');
    $(document).ready(function() {
        $('#company-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route('get_companies') }}',
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
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                { 
                    data: 'logo', 
                    name: 'logo', 
                    searchable: false, 
                    orderable: false,
                    render: function(data, type, full, meta) {
                        var filename = data.split('/').pop(); // Extract basename from the full path
                        return '<img src="{{ asset('storage/logos/') }}/' + filename + '" alt="Company Logo" style="max-width: 50px; max-height: 50px;">';
                    }
                },
                {
                    data: 'website',
                    name: 'website'
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
        $('#company-table').on('click', '.edit-btn', function() {
            var companyId = $(this).data('id');
            window.location.href = '/companies/' + companyId + '/edit';
        });

        // Delete button click handler
        $('#company-table').on('click', '.delete-btn', function() {
            var companyId = $(this).data('id');
            if (confirm('Are you sure you want to delete this company?')) {
                // Handle delete action
                // Example: $.ajax({url: '/companies/' + companyId, type: 'DELETE', success: function(result){}});
                $.ajax({
                    url: '/companies/' + companyId,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(result) {
                        // Handle success response, e.g., remove the row from the DataTable
                        $('#company-table').DataTable().ajax.reload();
                    },
                    error: function(xhr, status, error) {
                        // Handle error response, e.g., display error message
                        console.error(xhr.responseText);
                    }
                });
            }
        });
    });
</script>