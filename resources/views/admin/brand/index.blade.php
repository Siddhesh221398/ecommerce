@extends('admin.layouts.index')
@section('content')
    <section class="content mt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Brand Lists</h3>
                            <a href="{{route('brand.create')}}" class="btn btn-primary float-right">Add Brand</a>
                        </div>

                        <div class="card-body">
                            <table class="table table-bordered" id="datatable_rows">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>



                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function(){
            var tableData = $('#datatable_rows').DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                processing: true,
                serverSide: true,
                "dom": '<"top"f>rt<"bottom"ilp><"clear">',
                searchable: true,
                scrollX: true,
                order: [0, false],
                ajax: "{{ route('brand.index') }}",
                columns: [
                    {
                        orderable: true,
                        searchable: true,
                        "data": "id"
                    },
                    {
                        orderable: true,
                        searchable: true,
                        "data": "name"
                    },
                    {
                        orderable: false,
                        searchable: false,
                        data: 'action',
                    },

                ]
            });

            $(document).on('click','.btn-delete',function (){
                const id = $(this).data('id');
                $.ajax({
                    type: 'POST',
                    url: "admin/brand/"+id,
                    data: {
                        _token: "{{csrf_token()}}",
                        _method: "Delete"

                    },
                    dataType: 'json',
                    success: function (data) {
                        if (data.status == 'success') {
                            toastr.success(data.message)
                            tableData.draw();

                        }else{
                            toastr.error(data.message)
                        }
                    }
                });
            })
        })
    </script>
@endpush
