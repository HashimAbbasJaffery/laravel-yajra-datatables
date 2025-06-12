
    
<x-layout.app>

<button class="btn btn-primary mb-3" onclick="create()">Create</button>


<!-- Label styled as a button -->
<label for="import-file" style="cursor: pointer; background-color: #3490dc; color: white; padding: 10px 20px; border-radius: 5px;">
    Import
</label>

<!-- Hidden file input -->
<input type="file" name="file" onchange="importFile(this)" id="import-file" style="display: none;" required>


</form>
{!! $dataTable->table() !!}

@push("scripts")

<script>
    function importFile(file) {
        
        let formData = new FormData();
        formData.append("_token", "{{ csrf_token() }}")
        formData.append("excel", file.files[0])

        $.ajax({
            url: "{{ route('users.import') }}",
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                console.log(response);
                alert('Users imported successfully!');
                $('#dataTableBuilder').DataTable().ajax.reload(); // reload DataTable
            },
            error: function(xhr) {
                console.log(xhr.responseText);
                alert('Import failed. Check your file format.');
            }
        });
    }
</script>
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.0.3/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>

<script src="/vendor/datatables/buttons.server-side.js"></script>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    async function create() {
        const { value: formValues } = await Swal.fire({
        title: "User Data",
        html: `
            <label>
                <p>Name</p>
                <input id="swal-input1" class="swal2-input">
            </label>
            <label>
                <p>Email</p>
                <input id="swal-input2" class="swal2-input">
            </label>
            <label>
                <p>Password</p>
                <input type="password" id="swal-input3" class="swal2-input">
            </label>
        `,
        focusConfirm: false,
        showLoaderOnConfirm: true,
        preConfirm: () => {
            const name = document.getElementById("swal-input1").value;
            const email = document.getElementById("swal-input2").value;
            const password = document.getElementById("swal-input3").value;
            const errors = [];

            if(!name) {
                errors.push("Name field is required");
            }

            if(!email) {
                errors.push("Email field is required");
            }
            const emailRegExp = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/; 
            if(!emailRegExp.test(email)) {
                errors.push("Email must be value");
            }

            if(!password) {
                errors.push("Password is required");
            }

            if(errors.length > 0) {
                Swal.showValidationMessage(errors.join(", <br>"));
            }

            return [
                document.getElementById("swal-input1").value,
                document.getElementById("swal-input2").value,
                document.getElementById("swal-input3").value
            ];
        }
        });
        if (formValues) {
            $.ajax({
                url: `{{ route('user.store') }}`,
                type: "post",
                data: {
                    name: document.getElementById("swal-input1").value,
                    email: document.getElementById("swal-input2").value,
                    password: document.getElementById("swal-input3").value,
                    _token: "{{ csrf_token() }}",
                },
                success: function(response, textStatus, xhr) {
                    const table = document.querySelector("#dataTableBuilder tbody");
                    const last_row = document.querySelector("#dataTableBuilder tbody tr:last-child");
                    table.insertAdjacentHTML(`beforebegin`, `
                        <tr id="user-${response.id}" role="row" class="even">
                            <td class="sorting_1">${response.id}</td>
                            <td class="name-cell">${response.name}</td>
                            <td class="email-cell">${response.email}</td>
                            <td>No Posts</td>
                            <td>
                                <a onclick="updateRecord(${response.id}, ${response.id})" class="edit btn btn-primary btn-sm">Edit</a>
                                <a style="color: white" onclick="deleteRecord(${response.id}, ${response.id})" class="delete btn btn-danger btn-sm">Delete</a>
                            </td>
                        </tr>
                    `);
                    last_row.remove();
                    console.log(table);
                    console.log(response);

                },
                error: function(response) {
                    console.log(response.responseText);
                }
            })
        }
    }
    async function updateRecord(id, row_id) {
        const name_cell = document.querySelector(`#user-${row_id} .name-cell`);
        const email_cell = document.querySelector(`#user-${row_id} .email-cell`);

        const { value: formValues } = await Swal.fire({
        title: "User Data",
        html: `
            <label>
                <p>Name</p>
                <input id="swal-input1" value="${name_cell.textContent}" class="swal2-input">
            </label>
            <label>
                <p>Email</p>
                <input id="swal-input2" value="${email_cell.textContent}" class="swal2-input">
            </label>
            <label>
                <p>Password</p>
                <input type="password" id="swal-input3" class="swal2-input">
            </label>
        `,
        focusConfirm: false,
        showLoaderOnConfirm: true,
        preConfirm: () => {
            const name = document.getElementById("swal-input1").value;
            const email = document.getElementById("swal-input2").value;
            const password = document.getElementById("swal-input3").value;
            const errors = [];

            document.getElementById("swal-input1").textContent = name_cell;
            document.getElementById("swal-input2").textContent = email_cell;

            if(!name) {
                errors.push("Name field is required");
            }

            if(!email) {
                errors.push("Email field is required");
            }
            const emailRegExp = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/; 
            if(!emailRegExp.test(email)) {
                errors.push("Email must be value");
            }

            if(!password) {
                errors.push("Password is required");
            }

            if(errors.length > 0) {
                Swal.showValidationMessage(errors.join(", <br>"));
            }

            return [
                document.getElementById("swal-input1").value,
                document.getElementById("swal-input2").value,
                document.getElementById("swal-input3").value
            ];
        }
        });
        console.log(formValues)
        if (formValues) {
            $.ajax({
                url: `{{ route('user.update') }}`,
                type: "post",
                data: {
                    name: document.getElementById("swal-input1").value,
                    email: document.getElementById("swal-input2").value,
                    password: document.getElementById("swal-input3").value,
                    user_id: id,
                    _token: "{{ csrf_token() }}",
                    _method: "PUT",
                },
                success: function(response, textStatus, xhr) {
                    console.log(xhr);
                    const values = formValues;
                    const name = document.querySelector(`#user-${row_id} .name-cell`);
                    const email = document.querySelector(`#user-${row_id} .email-cell`);

                    if(name.textContent !== values[0]) {
                        name.textContent = values[0];
                    }

                    if(email.textContent !== values[1]) {
                        email.textContent = values[1];
                    }
                },
                error: function(response) {
                    console.log(response.responseText);
                }
            })
        }
    }
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