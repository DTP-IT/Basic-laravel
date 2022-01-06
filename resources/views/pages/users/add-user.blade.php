@extends('default')
@section('title', 'Thêm User')
@section('breadcrumb', 'Thêm User')
@section('content')
<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <!-- /.col -->
      <div class="col-md-12">
        <div class="card">
          @if(session('message'))
            <div class="card-header p-2">
              <div class="alert alert-primary alert-dismissible fade show" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  <span class="sr-only">Close</span>
                </button>
                <strong>Notify!!</strong> {{ session('message') }}
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
                <form action="{{ route('user.store') }}" method="post" class="form-horizontal" id="frmAddUSer">
                  @csrf {{ csrf_field() }}
                  <div class="form-group row">
                    <label for="title" class="col-sm-2 col-form-label">Name</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" name="name" id="name" value="{{old('name')}}"  placeholder="Name" />
                    </div>
                    <small id="errorName" class="form-text text-danger"></small>
                  </div>
                  <div class="form-group row">
                    <label for="email" class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-10">
                      <input type="email" class="form-control" name="email" id="email" value="{{old('email')}}"  placeholder="Email" />
                    </div>
                    <small id="errorEmail" class="form-text text-danger"></small>
                  </div>
                  <div class="form-group row">
                    <label for="inputName" class="col-sm-2 col-form-label">Level</label>
                    <div class="col-sm-10">
                      <div class="form-group">
                        <label for="level"></label>
                        <select class="form-control" name="level" id="level">
                          <option value="User">User</option>
                          <option value="Admin">Admin</option>
                        </select>
                      </div>
                    </div>
                    <small id="errorLevel" class="form-text text-danger"></small>
                  </div>
                  <div class="form-group row">
                    <label for="password" class="col-sm-2 col-form-label">Password</label>
                    <div class="col-sm-10">
                      <input type="password" class="form-control" name="password"  id="password" placeholder="Password"/>
                    </div>
                    <small id="errorPasswd" class="form-text text-danger"></small>
                  </div>
                  <div class="form-group row" id="confirm">
                    <label for="confirmPassword" class="col-sm-2 col-form-label">Confirm Password</label>
                    <div class="col-sm-10">
                      <input type="password" class="form-control" name="confirmPassword" id="confirmPassword" placeholder="Confirm Password"/>
                    </div>
                    <small id="ErrorConfirmPassword" class="form-text text-danger"></small>
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
@section('script')
<script>
$('#frmAddUSer').on('submit', function() 
{
  var name = $('#name').val();
  var mail = $('#email').val();
  var passwd = $('#password').val();
  var confirmPasswd = $('#confirmPassword').val();
  var level = $('#level').val();
  var reGexName = /[^/d]{6,200}/;
  var reGexMail = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})/;
  var reGexPassword = /[a-zA-Z0-9]{6,100}/;

  if (name == '') {
      $('#errorName').html('Vui lòng nhập tên!');

      return false;
  } else if (!reGexName.test(name)) {
        $('#errorName').html('Vui lòng nhập đúng định dạng!');
        
        return false;
    } else {
        $('#errorName').html(''); 
      }

  if (mail == '') {
    $('#errorEmail').html('Vui lòng nhập địa chỉ mail!');

    return false;
  } else if (!reGexMail.test(mail)) {
      $('#errorEmail').html('Vui lòng nhập đúng định dạng!');

      return false;
    } else {
        $('#errorEmail').html(''); 
      }

  if (passwd == '') {
      $('#errorPasswd').html('Vui lòng nhập mật khẩu!');

      return false;
  } else if (!reGexPassword.test(passwd)) {
      $('#errorPasswd').html('Vui lòng nhập đúng định dạng!');

      return false;
    } else {
        $('#errorPasswd').html(''); 
      }
  if (confirmPasswd == '') {
    $('#ErrorConfirmPassword').html('Vui lòng nhập lại mật khẩu!');

    return false;
  } else if (confirmPasswd != passwd) {
      $('#ErrorConfirmPassword').html('Mật khẩu không trùng khớp. Vui lòng nhập lại!');

      return false;
    } else {
        $('#ErrorConfirmPassword').html(''); 
      }
  
  if (level == '') {
    $('#errorLevel').html('Vui lòng chọn quyền hạn');

    return false;
  } else if (level != 'Admin' && level != 'User') {
      $('#errorLevel').html('Vui lòng chọn đúng quyền hạn');

      return false;
    } else {
        $('#errorLevel').html('');
      }

  return true;
});
</script>
@endsection
