<?php
session_start();
include('../config.php');
if (empty($_SESSION['username'])) {
  header("location:../index.php");
}
$last = $_SESSION['username'];
$sqlupdate = "UPDATE users SET last_activity=now() WHERE username='$last'";
$queryupdate = mysqli_query($connect, $sqlupdate);

if (isset($_POST['hapus_file'])) {
  $sql_file = "DELETE FROM file WHERE id_file='$_POST[id_file]'";
  $paths = "file_decrypt/" . $_POST['file_name'];
  unlink($paths);
  mysqli_query($connect, $sql_file);
}
?>
<!DOCTYPE html>
<html>
<?php
$user = $_SESSION['username'];
$query = mysqli_query($connect, "SELECT fullname,job_title,last_activity FROM users WHERE username='$user'");
$data = mysqli_fetch_array($query);
?>

<head>
  <title>Halo, <?php echo $data['fullname']; ?> - Aplikasi Enkripsi dan Dekripsi Celebes Konstruksindo PT</title>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="../assets/css/main.css">
  <link rel="stylesheet" type="text/css" href="../assets/plugins/datatables/css/jquery.dataTables.css">
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries-->
  <!--if lt IE 9
    script(src='https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js')
    script(src='https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js')
    -->
</head>

<body class="sidebar-mini fixed">
  <div class="wrapper">
    <header class="main-header hidden-print"><a class="logo" href="index.php" style="font-size:13pt">Celebes Konstruksindo PT</a>
      <nav class="navbar navbar-static-top">
        <a class="sidebar-toggle" href="#" data-toggle="offcanvas"></a>
        <div class="navbar-custom-menu">
          <ul class="top-nav">
            <li class="dropdown"><a class="dropdown-toggle" href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user fa-lg"></i></a>
              <ul class="dropdown-menu settings-menu">
                <li><a href="logout.php"><i class="fa fa-sign-out fa-lg"></i> Logout</a></li>
              </ul>
            </li>
          </ul>
        </div>
      </nav>
    </header>
    <aside class="main-sidebar hidden-print">
      <section class="sidebar">
        <div class="user-panel" style='margin-bottom: 30px'>
          <div class="pull-left image"><img class="img-circle" src="assets/images/logo.png" alt="User Image"></img> </div>
          <div class="pull-left info">
            <p style="margin-top:-5px;"><?php echo $data['fullname']; ?></p>
            <p class="designation"><?php echo $data['job_title']; ?></p>
            <p class="designation" style="font-size:6pt;">Aktivitas Terakhir: <?php echo $data['last_activity'] ?></p>
          </div>
        </div>
        <ul class="sidebar-menu">
          <li><a href="index.php"><i class="fa fa-dashboard"></i><span>Dashboard</span></a></li>
          <li class="treeview"><a href="#"><i class="fa fa-file-o"></i><span>File</span><i class="fa fa-angle-right"></i></a>
            <ul class="treeview-menu">
              <li><a href="encrypt.php"><i class="fa fa-circle-o"></i> Enkripsi</a></li>
              <li><a href="decrypt.php"><i class="fa fa-circle-o"></i> Dekripsi</a></li>
            </ul>
          </li>
          <li class="active"><a href="history.php"><i class="fa fa-list-alt"></i><span>Daftar List</span></a></li>
          <li><a href="about.php"><i class="fa fa-info"></i><span>Tentang</span></a></li>
          <li><a href="help.php"><i class="fa fa-question-circle"></i><span>Bantuan</span></a></li>
        </ul>
      </section>
    </aside>
    <div class="content-wrapper">
      <div class="page-title">
        <div>
          <h1><i class="fa fa-dashboard"></i> History Aplikasi Enkripsi dan Dekripsi Celebes Konstruksindo PT</h1>
        </div>
        <div>
          <ul class="breadcrumb">
            <li><i class="fa fa-home fa-lg"></i></li>
            <li><a href="index.php">Dashboard</a></li>
            <li>Daftar List Enkripsi dan Dekripsi</li>
          </ul>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-body">
              <div class="table-responsive">
                <table id="file" class="table striped">
                  <thead>
                    <tr>
                      <td><strong>ID File</strong></td>
                      <td><strong>Username</strong></td>
                      <td><strong>Nama File</strong></td>
                      <td><strong>Nama File Enkripsi</strong></td>
                      <td><strong>Ukuran File</strong></td>
                      <td><strong>Tanggal</strong></td>
                      <td><strong>Status</strong></td>
                      <td><strong>Status</strong></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $query = mysqli_query($connect, "SELECT * FROM file ORDER BY id_file DESC");
                    while ($data = mysqli_fetch_array($query)) { ?>
                      <tr>
                        <td><?php echo $data['id_file']; ?></td>
                        <td><?php echo $data['username']; ?></td>
                        <td><?php echo $data['file_name_source']; ?></td>
                        <td><?php echo $data['file_name_finish']; ?></td>
                        <td><?php echo $data['file_size']; ?> KB</td>
                        <td><?php echo $data['tgl_upload']; ?></td>
                        <td><?php if ($data['status'] == 1) {
                              echo "<span class='btn btn-success'>Terenkripsi</span>";
                            } elseif ($data['status'] == 2) {
                              echo "<span class='btn btn-info'>Sudah Didekripsi</span>";
                            } else {
                              echo "<span class='btn btn-danger'>Status Tidak Diketahui</span>";
                            }
                            ?>
                        </td>

                        <?php if ($data['status'] == 2) {
                        ?>
                          <td>
                            <a href="download.php?path=<?= $data["file_name_source"] ?>" class='btn btn-success'>Download</a>
                          </td>
                          <td>
                            <form action="history.php" method="post">
                              <input type="hidden" name="id_file" value="<?= $data['id_file'] ?>">
                              <input type="hidden" name="file_name" value="<?= $data['file_name_source'] ?>">
                              <button type="submit" name="hapus_file" class='btn btn-danger'>Hapus</button>
                            </form>
                          </td>
                        <?php
                        }
                        ?>


                      </tr>
                    <?php
                    } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="../assets/js/jquery-2.1.4.min.js"></script>
    <script type="text/javascript">
      $(document).ready(function() {
        $('#file').dataTable({
          "bPaginate": true,
          "bLengthChange": false,
          "bFilter": true,
          "bInfo": true,
          "bAutoWidth": true,
          "order": [0, "asc"]
        });
      });
    </script>
    <script src="../assets/js/essential-plugins.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <script src="../assets/plugins/datatables/js/jquery.dataTables.js"></script>
    <script src="../assets/js/plugins/pace.min.js"></script>
    <script src="../assets/js/main.js"></script>
</body>

</html>