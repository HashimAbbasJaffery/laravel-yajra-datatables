
    
<x-layout.app>

{!! $dataTable->table() !!}

@push("scripts")
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.0.3/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>

<script src="/vendor/datatables/buttons.server-side.js"></script>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function deleteRecord(id, row_id) {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                url: `{{ route('user.delete') }}`,
                type: "post",
                data: {
                    user_id: id,
                    _token: "{{ csrf_token() }}",
                    _method: "DELETE"
                },
                success: function(response) {
                    const row = document.getElementById(`user-${row_id}`);
                    row.remove();
                },
                error: function(xhr) {
                    console.log(xhr.responseText)
                }
            })

                Swal.fire({
                title: "Deleted!",
                text: "Your file has been deleted.",
                icon: "success"
                });
            }
        });
    }
</script>
{!! $dataTable->scripts() !!}

@endpush

</x-layout.app>