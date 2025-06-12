<!DOCTYPE html>

<html>

<head>

    <title>Yajra Datatables</title>

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

        .buttons-excel {
            background: #006100 !important;
            color: white !important;
            border: none !important;
            border-radius: 5px;
        }
    </style>

</head>

<body>
    <header class="container my-4" style="font-weight: bold; display: flex; justify-content: space-between; align-items: center;">
        <p>{{ auth()->user()->name }}</p>
        <div class="right-section">
            <img src="{{ auth()->user()->avatar }}" style="margin-right: 30px; border-radius: 50px; height: 40px; width: 40px;"></img>
            <a class="btn btn-danger" href="{{ route('logout') }}">Logout</a>
        </div>
    </header>
    <div class="container">
        {{ $slot }}
    </div>
</body>
@stack('scripts')
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

</html>
