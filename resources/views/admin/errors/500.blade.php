<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>500 Error</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('admin/themes/dist/css/adminlte.min.css') }}">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Content Wrapper. Contains page content -->
    <!-- Main content -->
      <div class="error-page">
        <h2 class="headline text-danger">500</h2>

        <div class="error-content">
          <h3><i class="fas fa-exclamation-triangle text-danger"></i> Oops, something went wrong </h3>
          <form class="search-form">
            <div class="input-group">
              <input type="text" name="search" class="form-control" placeholder="Search">

              <div class="input-group-append">
                <button type="submit" name="submit" class="btn btn-danger"><i class="fas fa-search"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
      <!-- /.error-page -->
</div>
<!-- ./wrapper -->
</body>
</html>
