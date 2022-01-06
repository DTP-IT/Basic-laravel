@extends('default')
@section('title', 'Danh sách thể loại')
@section('breadcrumb', 'Danh sách thể loại')
@section('content')
<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            @if(session('login.level') == 'Admin')
              <a class="btn btn-success" href="{{ route('category.create') }}"><i class="fas fa-plus"></i> Add</a>
            @endif
            <div class="card-tools">
              <div class="input-group input-group-sm" style="width: 150px;">
                <input type="text" name="table_search" class="form-control float-right" placeholder="Search">
                <div class="input-group-append">
                  <button type="submit" class="btn btn-default">
                    <i class="fas fa-search"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Name</th>                        
                </tr>
              </thead>
              <tbody>
                @foreach($data as $key => $category)
                  <tr>
                    <td>{{$category->id}}</td>
                    <td>{{$category->name}}</td>              
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          {{ $data->links()}}
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->
    </div>
  </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection
