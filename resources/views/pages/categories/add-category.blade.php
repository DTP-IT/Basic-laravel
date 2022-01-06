@extends('default')
@section('title', 'Thêm mới thể loại')
@section('breadcrumb', 'Thêm mới thể loại')
@section('content')
<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <!-- /.col -->
      <div class="col-md-12">
        <div class="card"> 
          @if (session('message'))
            <div class="card-header p-2">
              <div class="alert alert-primary alert-dismissible fade show" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  <span class="sr-only">Close</span>
                </button>
                <strong>Notify!</strong> {{ session('message') }}
              </div>
            </div><!-- /.card-header -->
          @endif
          @if ($errors)
            <div class="mb-4 font-medium text-sm text-green-600">
              @foreach ($errors->all() as $error)
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <strong>{{ $error }}</strong> 
                </div>
              @endforeach
            </div>
          @endif
          <div class="card-body">
            <div class="tab-content">
              <div class="active tab-pane" id="settings">
                <form action="{{ route('category.store') }}" method="post" class="form-horizontal" id="frmAddCategory">
                  @csrf {{ csrf_field() }}
                  <div class="form-group row">
                    <label for="title" class="col-sm-2 col-form-label">Name</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" name="name" id="name" value="{{ old('name') }}"  placeholder="Name" />
                    </div>
                    <small id="errorName" class="form-text text-danger"></small>
                  </div>                    
                  <div class="offset-sm-2 col-sm-10">
                    <button type="submit" class="btn btn-success"  id="btnSave">Save</button>
                  </div>
                </form>
              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div><!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection
