<!DOCTYPE html>
<html lang="en">
<head>
  <base href="{{asset('')}}">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Log in</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="../../index2.html"><b>Admin</b>LTE</a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Sign in to start your session</p>
      @if (session('message')) 
        <h3 class="card-title">{{ session('message') }}</h3>
      @endif
      <form action="{{ route('user.login') }}" method="post" id="frmLogin">
        @csrf {{ csrf_field() }}
        <div class="input-group mb-3">
          <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control" placeholder="Email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope" id="errorEmail"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" name="password" id="password" value="{{ old('password') }}" class="form-control" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock" id="errorPasswd"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="remember">
              <label for="remember">
                Remember Me
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <div class="social-auth-links text-center mb-3">
        <p>- OR -</p>
        <a href="#" class="btn btn-block btn-primary">
          <i class="fab fa-facebook mr-2"></i> Sign in using Facebook
        </a>
        <a href="#" class="btn btn-block btn-danger">
          <i class="fab fa-google-plus mr-2"></i> Sign in using Google+
        </a>
      </div>
      <!-- /.social-auth-links -->

      <p class="mb-1">
        <a href="forgot-password.html">I forgot my password</a>
      </p>
      <p class="mb-0">
        <a href="/register" class="text-center">Register a new membership</a>
      </p>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<script>
$('#frmLogin').on('submit', function() {
  // alert('form login');

  var mail = $('#email').val();
  var passwd = $('#password').val();


  var reGexMail = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})/;
  var reGexPassword = /[a-zA-Z0-9]{6,100}/;

  
  if (mail == '') {
    $('#errorEmail').html('Vui lòng nhập địa chỉ mail!');

    return false;
  } else {
      if (!reGexMail.test(mail)) {
        $('#errorEmail').html('Vui lòng nhập đúng định dạng!');

        return false;
      } else {
          $('#errorEmail').html(''); 
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

  return true;
});
</script>
</body>
</html>
