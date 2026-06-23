<?php include 'inc/public-session.php'; ?>

<!DOCTYPE html>
<html lang="es">

<head>
  <?php include 'src/components/head.php'; ?>

  <link rel="stylesheet" href="<?= BASE_URL; ?>/src/css/pages/tips.css">
</head>

<body class="navbar-white">
  <!-- Preloader -->
  <?php include 'src/components/preloader.php'; ?>

  <!-- Navbar -->
  <?php include 'src/components/navbar.php'; ?>

  <!-- Main -->
  <main class="main">
    <section class="tips-section">
      <h1>Cuenta</h1>
      <h2>Instrucciones para dar de baja tu cuenta</h2>

      <div>
        <p>
        
    <p>Para eliminar tu cuenta de la app <strong>Manteles Largos</strong>, sigue los siguientes pasos:</p>
    
    <ol style="padding-left: 20px;">
      <li>Ingresa a tu cuenta desde la app.</li>
      <li>Despliega el menú superior izquierdo <strong>(icono de tres rayitas)</strong>.</li>
      <li>Haz clic en la opción <strong>Configuración</strong> del menú.</li>
      <li>Desplázate hacia abajo y haz clic en el botón <strong>“Eliminar cuenta”</strong>. Serás redirigido a una pantalla de confirmación donde deberás confirmar tu decisión.</li>
    </ol>
    
    <p>Una vez completado este procedimiento, tu cuenta y todos los datos registrados serán eliminados de forma permanente.</p>

    <hr style="margin: 30px 0; border: none; border-top: 1px solid #eee;">

    <p>¿Qué datos se conservan?</p>
    
    <p><strong>Ninguno.</strong> No conservamos ningún dato de tu cuenta. Toda tu información personal y la de tu negocio serán eliminados definitivamente.</p>
  
        </p>
      </div>
    </section>

    <!-- Modal for login and register -->
    <?php include 'src/modals/login-register.php'; ?>

    <!-- Page loading -->
    <?php include 'src/components/page-loading.php'; ?>
  </main>

  <!-- Footer -->
  <?php include 'src/components/footer.php'; ?>

  <!-- Required scripts -->
  <?php include 'src/components/required-scripts.php'; ?>
  <script src="<?= BASE_URL; ?>/src/js/tips.js"></script>
</body>

</html>