@extends('default')
@section('title', 'Danh sách users')
@section('breadcrumb', 'Danh sách users')
@section('content')
<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <a class="btn btn-success" href="{{ route('user.create') }}"><i class="fas fa-plus"></i> Add</a>
            <div class="card-tools">
              <form action="{{ route('user.search') }}" method="get">
                <div class="input-group input-group-sm" style="width: 150px;">
                  <input type="text" name="key" class="form-control float-right" placeholder="Search">
                  <div class="input-group-append">
                    <button type="submit" class="btn btn-default">
                      <i class="fas fa-search"></i>
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Level</th>
                </tr>
              </thead>
              <tbody>
                @foreach($data as $key => $user)
                  <tr>
                    <td>{{$user->id}}</td>
                    <td>{{$user->name}}</td>
                    <td>{{$user->email}}</td>
                    <td>{{$user->level}}</td>
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
</div>
@endsection
