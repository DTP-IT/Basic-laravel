@extends('default')
@section('title', 'Cập nhật sản phẩm')
@section('breadcrumb', 'Cập nhật sản phẩm')
@section('content')
<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <!-- /.col -->
      <div class="col-md-12">
        <div class="card"> 
          @if(Session::get('message'))
          <div class="card-header p-2">
            <div class="alert alert-primary alert-dismissible fade show" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Close</span>
              </button>
              <strong>Thông báo!</strong> {{Session::get('message')}}
            </div>
            {{Session::put('message', null);}}
          </div><!-- /.card-header -->
          @endif
          <div class="card-body">
            <div class="tab-content">
              <div class="active tab-pane" id="settings">
                <form action="/item/update/{{ $item->id }}{" method="post" class="form-horizontal" id="frmAddItem" enctype="multipart/form-data">
                    @csrf {{ csrf_field() }}
                  <input name="_method" type="hidden" value="PUT">
                  <div class="form-group row" hidden>
                    <label for="title" class="col-sm-2 col-form-label">ID</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" name="id" id="id" value="{{ $item->id }}" placeholder="Title" readonly/>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="title" class="col-sm-2 col-form-label">Title</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" name="title" id="title" value="{{ $item->title }}"  placeholder="Title" />
                    </div>
                    <small id="errorName" class="form-text text-danger"></small>
                  </div>
                  <div class="form-group row">
                    <label for="publisher" class="col-sm-2 col-form-label">Publisher</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" name="publisher" id="publisher" value="{{ $item->publisher }}" placeholder="Publisher" />
                    </div>
                    <small id="errorName" class="form-text text-danger"></small>
                  </div>
                  <div class="form-group row">
                    <label for="inputName" class="col-sm-2 col-form-label">Image</label>
                    <div class="col-sm-10">
                      <input type="file" class="form-control" name="image" id="image"/>
                      <img src="images/{{$item->image}}" class="p-2" alt="{{$item->image}}">
                    </div>      
                    <small id="errorName" class="form-text text-danger"></small>
                  </div>
                  <div class="form-group row">
                    <label for="inputName" class="col-sm-2 col-form-label">Category</label>
                    <div class="col-sm-10">
                        <div class="form-group">
                          <label for=""></label>
                          <select class="form-control" name="category" id="category">
                            @foreach($categoryDatas as $category_data)
                            @if($item->category_id == $category_data->id)
                            <option value="{{ $category_data->id }}" selected='selected'>{{ $category_data->name }}</option>
                            @else
                            <option value="{{ $category_data->id }}">{{ $category_data->name }}</option>
                            @endif
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
                      <input type="number" class="form-control" name="quantity" id="quantity" value="{{ $item->quantity }}" placeholder="Quantity"/>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="price" class="col-sm-2 col-form-label">Price</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" name="price" id="price" value="{{ $item->price }}" placeholder="Price"/>
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
