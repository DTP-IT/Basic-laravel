@extends('default')
@section('title', 'Thêm sản phẩm')
@section('breadcrumb', 'Thêm sản phẩm')
@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <!-- /.col -->
      <div class="col-md-12">
        <div class="card">
          {{-- Hiển thị thông báo nếu có --}}
          @if (session('message'))
          <div class="card-header p-2">
            <div class="alert alert-primary alert-dismissible fade show" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Close</span>
              </button>
                <strong>Thông báo!</strong> {{ session('message') }}
            </div>
          {{Session::put('message', null);}}
          </div><!-- /.card-header -->
          @endif
          
          <div class="card-body">
            <div class="tab-content">
              <div class="active tab-pane" id="settings">
                <form action="/item/store" method="post" class="form-horizontal" id="frmAddItem" enctype="multipart/form-data">
                    @csrf {{ csrf_field() }}
                  <div class="form-group row">
                    <label for="title" class="col-sm-2 col-form-label">Title</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" name="title" id="title" {{old('title')}}  placeholder="Title" />
                    </div>
                    <small id="errorName" class="form-text text-danger"></small>
                  </div>
                  <div class="form-group row">
                    <label for="publisher" class="col-sm-2 col-form-label">Publisher</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" name="publisher" id="publisher" {{old('publisher')}}  placeholder="Publisher" />
                    </div>
                    <small id="errorName" class="form-text text-danger"></small>
                  </div>
                  <div class="form-group row">
                    <label for="inputName" class="col-sm-2 col-form-label">Image</label>
                    <div class="col-sm-10">
                      <input type="file" class="form-control" name="image" id="image" />
                    </div>
                    <small id="errorName" class="form-text text-danger"></small>
                  </div>
                  <div class="form-group row">
                    <label for="category" class="col-sm-2 col-form-label">Category</label>
                    <div class="col-sm-10">
                      <div class="form-group">
                        <select class="form-control" name="category" id="category">
                          @foreach($categoryDatas as $categoryData)
                          <option value="{{ $categoryData->id }}">{{ $categoryData->name }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <small id="errorName" class="form-text text-danger"></small>
                  </div>
                  <div class="form-group row">
                    <label for="user" class="col-sm-2 col-form-label">User</label>
                    <div class="col-sm-10">
                      <select name="user" id="user" class="form-control" readonly>
                        <option value="{{Session::get('id')}}">{{Session::get('name')}}</option>
                      </select>
                    </div>
                    <small id="errorName" class="form-text text-danger"></small>
                  </div>
                  <div class="form-group row">
                    <label for="quantity" class="col-sm-2 col-form-label">Quantity</label>
                    <div class="col-sm-10">
                      <input type="number" class="form-control" name="quantity" {{old('quantity')}} id="quantity" placeholder="Quantity"/>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="price" class="col-sm-2 col-form-label">Price</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" name="price" {{old('price')}} id="price" placeholder="Price"/>
                    </div>
                    <small id="errorPassword" class="form-text text-danger"></small>
                  </div>
                  <div class="form-group row" id="confirm">
                  </div>
                  <div class="form-group row">
                    <div class="offset-sm-2 col-sm-10">
                      <div class="checkbox">
                        <label>
                          <input type="checkbox"> I agree to the <a href="#">terms and conditions</a>
                        </label>
                      </div>
                    </div>
                  </div>
                    <div class="offset-sm-2 col-sm-10">
                        <button type="submit" class="btn btn-success"  id="btnSave">Save</button>
                      </div>
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
