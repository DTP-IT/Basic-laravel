@extends('default')
@section('title', 'Profile')
@section('breadcrumb', 'Profile')
@section('content')
<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <!-- /.col -->
      <div class="col-md-12">
        <div class="card">
          <div class="card-header p-2">
            <ul class="nav nav-pills">
              <li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab">Settings</a></li>
            </ul>
          </div><!-- /.card-header -->
          <div class="card-body">
            <div class="tab-content">
              <div class="active tab-pane" id="settings">
                <form action="update-profile" method="post" class="form-horizontal" id="frmProfile">
                    @csrf {{ csrf_field() }}
                    <input name="_method" type="hidden" value="PUT">
                    <div class="form-group row" hidden>
                      <label for="idUser" class="col-sm-2 col-form-label" >ID</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" name="id" id="idUser" value="{{$data['id']}}" placeholder="Name" readonly>
                      </div>
                    </div>
                  <div class="form-group row">
                    <label for="inputName" class="col-sm-2 col-form-label">Name</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" name="name" id="inputName" value="{{$data['name']}}" placeholder="Name" readonly>
                    </div>
                    <small id="errorName" class="form-text text-danger"></small>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-10">
                      <input type="email" class="form-control" name="email" id="inputEmail" value="{{$data['email']}}" placeholder="Email" readonly>
                    </div>
                    <small id="errorMail" class="form-text text-danger"></small>
                  </div>
                  <div class="form-group row">
                    <label for="level" class="col-sm-2 col-form-label">Level</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" name="level" id="level" value="{{$data['level']}}" placeholder="Level" readonly>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="password" class="col-sm-2 col-form-label">Password</label>
                    <div class="col-sm-10">
                      <input type="password" class="form-control" name="password" id="password" value="{{$data['password']}}" placeholder="Password" readonly>
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
                  <div class="form-group row">
                    <div class="offset-sm-2 col-sm-10">
                      <button type="button" class="btn btn-danger" id="btnUpdate">Update</button>
                    </div>
                    <div class="offset-sm-2 col-sm-10">
                        <button type="submit" class="btn btn-success" hidden id="btnUpdateSave">Save</button>
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
<script>
  $('#btnUpdate').on('click', function() {
    var data='';
    $('#btnUpdate').attr("hidden",'true');
    $('#inputName').removeAttr('readonly');
    $('#inputEmail').removeAttr('readonly');
    $('#password').removeAttr('readonly');
    $('#btnUpdateSave').removeAttr("hidden");
    data+=`<label for="confirmPassword" class="col-sm-2 col-form-label">Confirm password</label>
        <div class="col-sm-10">
        <input type="password" class="form-control" name="confirmPassword" id="confirmPassword"  placeholder="ConfirmPassword">
        </div>
        <small id="errorConfirmPasswd" class="form-text text-danger"></small>`
    $('#confirm').html(data);
  })
  $('#frmProfile').on('submit', function() {
    var name = $('#inputName').val();
    var mail = $('#inputEmail').val();
    var passwd = $('#password').val();
    var confirmPasswd = $('#confirmPassword').val();

    var reGexName = /[^/d]{6,200}/;
    var reGexMail = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})/;
    var reGexPassword = /[a-zA-Z0-9]{6,100}/;

    if (name == '') {
        $('#errorName').html('Vui lòng nhập tên!');

        return false;
    } else {
        if (!reGexName.test(name)) {
          $('#errorName').html('Vui lòng nhập đúng định dạng!');

            return false;
        } else {
          $('#errorName').html(''); 
          }
      }

    if (mail == '') {
      $('#errorMail').html('Vui lòng nhập địa chỉ mail!');

      return false;
    } else {
        if (!reGexMail.test(mail)) {
          $('#errorMail').html('Vui lòng nhập đúng định dạng!');

          return false;
        } else {
            $('#errorMail').html(''); 
          }
      }

    if (passwd == '') {
        $('#errorPasswd').html('Vui lòng nhập mật khẩu!');

        return false;
    } else {
        if (!reGexPassword.test(passwd)) {
          $('#errorPasswd').html('Vui lòng nhập đúng định dạng!');

            return false;
        } else {
          $('#errorPasswd').html(''); 
          }
      }

      if (confirmPasswd == '') {
        $('#errorConfirmPasswd').html('Vui lòng nhập lại mật khẩu!');

        return false;
    } else {
        if (confirmPasswd != passwd) {
          $('#errorConfirmPasswd').html('Mật khẩu không trùng khớp. Vui lòng nhập lại!');

            return false;
        } else {
          $('#errorConfirmPasswd').html(''); 
          }
      }

    return true;
  });
</script>
@endSection
