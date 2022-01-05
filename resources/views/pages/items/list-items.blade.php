@extends('default')
@section('title', 'Danh sách sản phẩm')
@section('breadcrumb', 'Danh sách sản phẩm')
@section('content')
<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            @if (!Session::get('softDelete')) 
            <a class="btn btn-success" href="item/add-item"><i class="fas fa-plus"></i> Add</a>
            @endif
            <a class="btn btn-default" href="item/showSoftDelete"><i class="fas fa-plus"></i> View Item Deleted</a>
            <div class="card-tools">
              <form action="item/search" method="get">
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
                  <th>Title</th>
                  <th>Publisher</th>
                  <th>Image</th>
                  <th>Category</th>
                  <th>User</th>
                  <th>Quantity</th>
                  <th>Price</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($data as $items)
                  <tr>
                      <td>{{$items->id}}</td>
                      <td>{{$items->title}}</td>
                      <td>{{$items->publisher}}</td>
                      <td><img style="max-width: 80px;" src="images/{{$items->image}}" alt="image"></td>
                      <td>{{$items->category_name}}</td>
                      <td>{{$items->user_name}}</td>
                      <td>{{$items->quantity}}</td>
                      <td>{{$items->price}}</td>
                      <td>
                        @if (!Session::get('softDelete')) 
                        <a href="item/edit/{{$items->id}}" class="btn btn-success"><i class="fas fa-edit"></i></a> || 
                        <button class="btn btn-warning btnDeleteItem" value={{$items->id}}><i class="fas fa-trash"></i></button>
                        @else
                        <button class="btn btn-success btnRestoreItem" value={{$items->id}}><i class="fas fa-edit"></i>Restore</button> 
                        @endif
                      </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
          
        </div>
        <!-- /.card -->
      </div>
        <!-- /.col -->
    </div>
      {{ $data->links()}}
  </div><!-- /.container-fluid -->
  
</section>
<!-- /.content -->
@endsection
@section('script')
<script>
  $(document).on('click','.btnDeleteItem', function(e){
    e.preventDefault();
    var id = $(this).val();//id sản phẩm
    var tr = $(this).closest('tr');
    var level = `{{Session::get('level')}}`;
    $.ajax({
        url: 'api/item/'+id,
        type : 'DELETE',
        data : {level : level},
        success : function(data){
            tr.remove();
            var success ='';
            success += '<div class="card-header p-2"> \
            <div class="alert alert-primary alert-dismissible fade show" role="alert">\
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">\
                        <span aria-hidden="true">&times;</span>\
                        <span class="sr-only">Close</span>\
                    </button>\
                    <strong>Thông báo!</strong> Xóa sản phẩm thành công\
                </div>\
            </div>';
            $('#alertDelete').html(success);
        },
        error : function(){
            console.log('error');
        },
        always : function(){
            console.log('complete');
        }
    });
  }); 
  $(document).on('click','.btnRestoreItem', function(e){
    e.preventDefault();
    var id = $(this).val();//id sản phẩm
    var tr = $(this).closest('tr');
    $.ajax({
        url: 'api/item/restore/'+id,
        type : 'GET',
        success : function(data){
            tr.remove();
            var success ='';
            success += '<div class="card-header p-2"> \
            <div class="alert alert-primary alert-dismissible fade show" role="alert">\
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">\
                        <span aria-hidden="true">&times;</span>\
                        <span class="sr-only">Close</span>\
                    </button>\
                    <strong>Thông báo!</strong> Khôi phục sản phẩm thành công\
                </div>\
            </div>';
            $('#alertRestore').html(success);
        },
        error : function(){
            console.log('error');
        },
        always : function(){
            console.log('complete');
        }
    });
   }); 
</script>
@endsection
