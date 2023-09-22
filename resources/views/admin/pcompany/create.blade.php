@extends('admin/layout')
@section('title', 'Add Production Company | Admin Dashboard')
@section('content')


    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800"> Add Production Company </h1>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Add Production Company Data
            <a href="{{ route('admin.pcompany.index') }}" class="float-right btn btn-success btn-sm"> <i class="fa fa-arrow-left"></i> View All </a> </h6>
        </div>
        <div class="card-body">
            
            <div class="table-responsive">
            <form method="POST" action="{{ route('admin.pcompany.store') }}" enctype="multipart/form-data">
                @csrf
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <tbody>
                    <tr>
                        <th>Genre</th>
                        <td>
                            <select required name="genre_id" class="form-control">
                                <option value="0">--- Select Genre ---</option>
                                @foreach ($genres as $gr)
                                <option value="{{$gr->id}}">{{$gr->title}}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>Photo</th>
                        <td><input name="photo" type="file" required></td>
                    </tr>
                    <tr>
                        <th> Title <span class="text-danger">*</span></th>
                        <td><input required name="title" type="text" class="form-control"></td>
                    </tr>
                    <tr>
                        <th>Founded on <span class="text-danger">*</span></th>
                        <td><input required name="founded" type="date" class="form-control" ></td>
                    </tr>
                    <tr>
                        <th>Founders <span class="text-danger">*</span></th>
                        <td><input required name="founders" type="text" class="form-control"></td>
                    </tr>
                    <tr>
                        <th>Parent Organizations</th>
                        <td><input name="parentorganizations" type="text" class="form-control" value="N/A"></td>
                    </tr>
                    <tr>
                        <th>President </th>
                        <td><input name="president" type="text" class="form-control" value="N/A"></td>
                    </tr>
                    <tr>
                        <th>Info</th>
                        <td><textarea name="info" class="form-control"></textarea></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </form>
            </div>
        </div>
    </div>

    @section('scripts')
    <!-- Page level plugins -->
    <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <!-- Page level custom scripts -->
    <script src="{{ asset('js/demo/datatables-demo.js') }}"></script>
    <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    @endsection
@endsection

