@extends('admin.layouts.index')
@section('content')
    <section class="content mt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Product Lists</h3>
                            <a href="{{route('product.create')}}" class="btn btn-primary float-right">Add Product</a>
                        </div>

                        <div class="card-body">
                            <table class="table table-bordered" id="datatable_rows">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Price</th>
                                    <th>Category</th>
                                    <th>Brand</th>
                                    <th>Status</th>
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
                ajax: "{{ route('product.index') }}",
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
                        orderable: true,
                        searchable: true,
                        "data": "description"
                    },
                    {
                        orderable: true,
                        searchable: true,
                        "data": "price"
                    },
                    {
                        orderable: true,
                        searchable: true,
                        "data": "category"
                    },{
                        orderable: true,
                        searchable: true,
                        "data": "brand"
                    },
                    {
                        orderable: true,
                        searchable: true,
                        "data": "status"
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
                    url: "admin/product/"+id,
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
            $(document).on('click','.btn-status',function (){
                const id = $(this).data('id');
                $.ajax({
                    type: 'POST',
                    url: "admin/product/status/"+id,
                    data: {
                        _token: "{{csrf_token()}}",
                        is_active:$(this).data('value')

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
