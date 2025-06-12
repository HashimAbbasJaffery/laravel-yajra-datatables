<!DOCTYPE html>

<html>

<head>

    <title>Laravel 8 Datatables Tutorial - ItSolutionStuff.com</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">


    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />

    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">

    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>

    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>

    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

    
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script> 
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js"></script>

    <style>
    a.dt-button.buttons-excel.buttons-html5 {
        background: #007bff;
        color: white;
        margin-left: 10px;
        padding: 5px 10px 5px 10px;
        border-radius: 5px;
    }
    </style>

</head>

<body>

    <div class="container mt-5 pt-5">


        <table class="table table-bordered data-table ">

            <thead>

                <tr>

                    <th>No</th>

                    <th>Name</th>

                    <th>Email</th>
                    <th>Total Posts</th>

                    <th width="100px">Action</th>

                </tr>

            </thead>

            <tbody>

            </tbody>

        </table>

    </div>
</body>
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

    $(function() {
        $('.data-table').DataTable({
            // Show "Processing..." indicator when loading data
            processing: true,

            // Enable server-side processing mode (ideal for large datasets)
            serverSide: true,

            // URL for Ajax call to fetch the data
            ajax: {
                url: '{{ route('users') }}', // Laravel route returning JSON
                type: 'GET', // HTTP method (GET or POST)
                data: function(d) {
                    // You can send additional parameters here if needed
                    // Example: d.customParam = 'value';
                }
            },

            // Define columns and how they map to server response
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'total_posts',
                    name: 'Total Posts'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },

            ],

            // Initial sorting (by ID descending)
            order: [
                [0, 'desc']
            ],

            // Enable pagination
            paging: true,

            // Number of rows per page (can also be changed by the user)
            pageLength: 10,

            // Length menu options
            lengthMenu: [5, 10, 25, 50, 100],

            // Enable searching
            searching: true,

            // Enable column ordering
            ordering: false,

            // Enable info text (e.g., "Showing 1 to 10 of 50 entries")
            info: true,
            "dom": 'T<"clear">lfrtip',
            "tableTools": {
                "sSwfPath": "/swf/copy_csv_xls_pdf.swf"
            },
            // Show the DataTables UI
            dom: 'lBfrtip', // Layout control - 'l' = length, 'B' = buttons, etc.

            // Language settings (optional)
            language: {
                search: "Filter records:", // Customize search placeholder
                lengthMenu: "Show _MENU_ entries per page",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                infoEmpty: "No entries found",
                processing: "Loading...",
                paginate: {
                    next: "Next",
                    previous: "Previous"
                }
            },

            layout: {
                topStart: {
                    buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
                }
            },
            buttons: [
                'excel',
            ]
        });
    });


  
</script>

</html>
