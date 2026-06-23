<?php
include 'inc/session.php';

$id_user_create = $_SESSION['session_user_children_id'] ? $_SESSION['session_user_children_id'] : $_SESSION['session_user_id'];

$query = "SELECT
    idUsuario,
    Usuario,
    Correo,
    Celular,
    Pais,
    idEstado,
    Username,
    Password,
    AccessType
  FROM usuarios
  WHERE idUsuario = '$id_user_create'
  LIMIT 1
";

$query_result = mysqli_query($mysqli, $query);
$num_rows     = mysqli_num_rows($query_result);

if (!$num_rows) {
  header('location:dashboard');
  exit();
}

$user_data = mysqli_fetch_array($query_result);

$full_name    = $user_data['Usuario'];
$user_email   = $user_data['Correo'];
$user_phone   = $user_data['Celular'];
$user_country = $user_data['Pais'];
$user_state   = $user_data['idEstado'];
$username     = $user_data['Username'];
$password     = decrypt($user_data['Password'], $secret);
$access_type  = $user_data['AccessType'];

$meta_title = 'Mi perfil';
?>

<!doctype html>
<html lang="es">

<head>
  <?php include 'inc/meta-tags.php'; ?>
</head>

<body>
  <div class="wrapper dashboard-wrapper">
    <div class="d-flex flex-wrap flex-xl-nowrap">
      <div class="db-sidebar bg-white" id="custom-sidebar">
        <nav class="navbar navbar-expand-xl navbar-light d-block px-0 header-sticky dashboard-nav py-0">
          <div class="sticky-area shadow-xs-1 py-3">
            <!-- MOBILE HEADER -->
            <?php include 'inc/mobile-header.php'; ?>

            <!-- SIDEBAR -->
            <?php include 'inc/sidebar.php' ?>
          </div>
        </nav>
      </div>

      <div class="page-content">
        <!-- HEADER -->
        <?php include 'inc/header.php'; ?>

        <main id="content" class="bg-gray-01">
          <div class="p-2">
            <div class="mr-md-auto">
              <h2 class="text-heading fs-22 lh-15">
                <?= $meta_title ?>
              </h2>
            </div>

            <div class="row">
              <div class="col-md-4">
                <div class="card">
                  <div class="card-header text-center">
                    <i class="fal fa-user fa-5x"></i>
                  </div>

                  <div class="card-body text-center">
                    <p class="card-text"><?= $full_name; ?></p>

                    <ul class="list-group text-left">
                      <li class="list-group-item">
                        <b>Correo: </b><?= $user_email ?>
                        <b>Teléfono móvil: </b><?= $user_phone; ?>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>

              <div class="col-md-8">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title mb-5">Datos de mi cuenta</h4>

                    <form id="register-form" class="form" method="POST">
                      <div class="row">
                        <div class="col-md-6 mb-2">
                          <div class="form-group">
                            <label class="mb-0" for="userFullName">Nombre completo</label>
                            <input id="userFullName" class="form-control" type="text" name="userFullName" value="<?= $full_name; ?>" required>
                          </div>
                        </div>

                        <div class="col-md-6 mb-2">
                          <div class="form-group">
                            <?php
                            $disable_email = $access_type !== 'Manteles Largos' ? 'readonly' : '';
                            $email_tootlip = $access_type !== 'Manteles Largos' ? 'title="Esta cuenta está vinculada a una red social."' : '';
                            ?>
                            <label class="mb-0" for="userEmail">Correo electrónico</label>
                            <input id="userEmail" class="form-control tooltip-msg" type="email" name="userEmail" value="<?= $user_email; ?>" <?= $email_tootlip; ?> <?= $disable_email; ?> required>
                          </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-4 mb-2">
                          <div class="form-group">
                            <label class="mb-0" for="userPhone">Teléfono/Movil</label>
                            <input id="userPhone" class="form-control" type="text" name="userPhone" value="<?= $user_phone; ?>" required>
                          </div>
                        </div>

                        <div class="col-md-4 mb-2">
                          <div class="form-group">
                            <label class="mb-0" for="userCountry">Pais</label>
                            <input id="userCountry" class="form-control" type="text" name="userCountry" value="Mexico" readonly required>
                          </div>
                        </div>

                        <?php
                        $query = "SELECT
                          idEstado,
                          Estado
                        FROM estados
                        ORDER BY Estado
                        ASC
                      ";

                        $query_result = mysqli_query($mysqli, $query);
                        ?>

                        <div class="col-md-4 mb-2">
                          <div class="form-group">
                            <label class="mb-0" for="userState">Estado</label>
                            <select id="userState" class="form-control" name="userState" required>
                              <option value="">Seleccionar</option>
                              <?php while ($row = mysqli_fetch_array($query_result)) : ?>
                                <option <?php if ($user_state == $row['idEstado']) echo 'selected'; ?> value="<?= $row['idEstado']; ?>"><?= $row['Estado']; ?></option>
                              <?php endwhile; ?>
                            </select>
                          </div>
                        </div>
                      </div>

                      <?php if ($access_type === 'Manteles Largos') : ?>
                        <span class="mt-4"><b>DATOS DE CUENTA</b></span>

                        <div class="row">
                          <div class="col-md-4 mb-2">
                            <div class="form-group">
                              <label class="mb-0" for="username">Usuario</label>
                              <input id="username" class="form-control" type="text" name="username" value="<?= $username; ?>" readonly>
                            </div>
                          </div>

                          <div class="col-md-12 mb-4">
                            <div class="row">
                              <div class="col-md-12 mb-2">
                                <div class="col-md-12">
                                  <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="changePassword" name="changePassword" value="Si">
                                    <label class="custom-control-label" for="changePassword">Cambiar contraseña</label>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div id="password-container" class="row" style="display: none;">
                              <div class="col-md-4 mb-2">
                                <div class="form-group">
                                  <label class="mb-0" for="userPassword">Nueva contraseña</label>
                                  <input id="userPassword" class="form-control" type="password" name="userPassword">
                                </div>
                              </div>

                              <div class="col-md-4 mb-2">
                                <div class="form-group">
                                  <label class="mb-0" for="userConfirmPassword">Confirmar contraseña</label>
                                  <input id="userConfirmPassword" class="form-control" type="password" name="userConfirmPassword">
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      <?php endif; ?>

                      <div class="row">
                        <div class="col-md-12 text-right">
                          <button class="btn btn-block btn-primary" type="submit">
                            Actualizar información
                          </button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </main>
      </div>
    </div>

    <!-- PAGE LOADING -->
    <?php include 'inc/page-loading.php' ?>
  </div>

  <!-- REQUIRED SCRIPTS -->
  <?php include 'inc/required-scripts.php'; ?>

  <script src="js/functions.js"></script>
  <script src="main/my-profile/my-profile.js"></script>

  <?php include 'inc/svg.php'; ?>

  <script>
    $('#changePassword').on('change', function() {
      const isChecked = $(this).is(':checked');

      if (isChecked) $('#password-container').slideDown();
      if (!isChecked) $('#password-container').slideUp();
    });

    $('.tooltip-msg').tooltip();
  </script>
</body>

</html>