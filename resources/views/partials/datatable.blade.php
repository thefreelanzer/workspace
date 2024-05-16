<table id="{{ $id }}" class="table" style="width:100%">
    <thead>
        <tr>
            {{ $headers }}
        </tr>
    </thead>
</table>

@push('scripts')
<script>
    $(document).ready(function() {
        $('#{{ $id }}').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ $route }}',
                type: 'GET',
            },
            columns: {
                !!$data!!
            }
        });
    });
</script>
@endpush