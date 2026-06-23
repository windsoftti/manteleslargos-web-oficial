<?php 
$link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
?>
<?php if (!isset($_COOKIE['webpagecookies']) && !$_REQUEST['cuid']) : ?>
  <section id="cookies" class="cookies" style="position: fixed;bottom: 0;left: 10%;right: 10%;z-index: 4;">
    <div class="card shadow flex-center lg-flex-row gap-1" style="padding: 0rem;">
      <div class="card-heading no-margin">
        <p class="text-center lg-text-left"><?= $_COOKIE['webpagecookies']; ?>Este sitio web utiliza cookies, puede obtener  <a href="<?= BASE_URL; ?>/politicas-de-privacidad">más información aquí.</a></p>
      </div>

      <a class="btn btn-black" onclick="acceptCookies()" href="javascript:void(0)" style="width: 200px;margin: 0.2rem 0.2rem;">
        Aceptar cookies
      </a>
    </div>
  </section>

<?php endif; ?>

<footer class="footer">
  <div class="top">
    <div>
      <h1>¿ERES PROVEEDOR DE EVENTOS?</h1>
      <p>Impulsa tu empresa y llega a miles de posibles clientes.</p>

      <!--h2>+1000 FIESTAS ORGANIZADAS A TRAVÉS DE NUESTRA PLATAFORMA</h2!-->
      <h2>¡DESCUBRE TODAS LAS HERRAMIENTAS QUE TENEMOS PARA TI!</h2>
      <?php if ($_SESSION['session_user_id'] && $_SESSION['session_user_level'] == 'Usuario') :
        $btn_access = checkSupplierAccessStatus();
        $btn_target = $btn_access['status'] == 'logged' ? 'target="_blank"' : '';
        endif;
      ?>

      <a class="footer-btn" href="<?= BASE_URL; ?>/registro-proveedor" <?= $btn_target ?> >
        ¡REGÍSTRATE AHORA!
      </a>
    </div>

    <img class="mobile-copas" src="<?= BASE_URL; ?>/src/assets/images/backgrounds/footer/copas.webp">
    <img class="desktop-copas" src="<?= BASE_URL; ?>/src/assets/images/backgrounds/footer/copas_desktop.webp">
  </div>

  <div class="middle">
    <form class="newsletter">
      <h3>Inscríbete a nuestro Newsletter</h3>
      <p>Recibe grandes descuentos y promociones</p>

      <div>
        <ion-icon name="mail-outline"></ion-icon>
        <input type="email" placeholder="Email">
      </div>

      <button type="submit">
        Regístrame
      </button>
    </form>

    <div class="our-business">
      <img src="<?= BASE_URL; ?>/src/assets/images/transparent-logo.svg" alt="Manteles Largos Logo">

      <p>Somos una agencia especializada en proveerte todo lo que necesitas para organizar todo tipo de eventos y brindarte una amplia gama de proveedores de confianza.</p>
    </div>

    <div class="social-icons">
      <a target="_blank" href="https://www.facebook.com/manteleslargoscom/">
        <img src="<?= BASE_URL; ?>/src/assets/images/social/facebook.png">
      </a>

      <a target="_blank" href="https://www.instagram.com/manteleslargoscom/">
        <img src="<?= BASE_URL; ?>/src/assets/images/social/instagram.png">
      </a>

      <a target="_blank" href="https://www.youtube.com/channel/UCXTOevC99RNKUvKQXwMQUTg">
        <img src="<?= BASE_URL; ?>/src/assets/images/social/youtube.png">
      </a>
    </div>

    <div class="contact">
      <!--a href="tel:+529613663565">
        <img src="<?= BASE_URL; ?>/src/assets/images/social/phone.png">
        +52 961 366 3565
      </a-->

      <a href="mailto:ontacto@manteleslargos.com">
        <img src="<?= BASE_URL; ?>/src/assets/images/social/email.png">
        contacto@manteleslargos.com
      </a>
    </div>

    <ul class="links popular-searchs">
      <li class="title">
        <a href="javascript:void(0)">
          Tipos de eventos
        </a>
      </li>

      <li>
        <a href="<?= BASE_URL; ?>/tipo-proveedores/bodas">
          Bodas
        </a>
      </li>

      <li>
        <a href="<?= BASE_URL; ?>/tipo-proveedores/xv-anios">
          XV Años
        </a>
      </li>

      <li>
        <a href="<?= BASE_URL; ?>/tipo-proveedores/infantiles">
          Infantiles
        </a>
      </li>

      <li>
        <a href="<?= BASE_URL; ?>/tipo-proveedores/bautizos">
          Bautizos
        </a>
      </li>

      <li>
        <a href="<?= BASE_URL; ?>/tipo-proveedores/convenciones">
          Convenciones
        </a>
      </li>

      <li>
        <a href="<?= BASE_URL; ?>/tipo-proveedores/otros">
          Otros
        </a>
      </li>
    </ul>

    <ul class="links interest-links">
      <li class="title">
        <a href="javascript:void(0)">
          Links de interés
        </a>
      </li>

      <li>
        <a href="<?= BASE_URL; ?>/mi-cuenta">
          Mi cuenta
        </a>
      </li>

      <li>
        <?php if (!$_SESSION['session_user_id'] || $_SESSION['session_user_level'] != 'Usuario Final') : ?>
          <a class="tab-toggle" data-toggle="modal" data-target="modal-login-register" data-content="tab-login" href="javascript:void(0)">
            Crear invitación grátis
          </a>
        <?php endif; ?>

        <?php if ($_SESSION['session_user_id'] && $_SESSION['session_user_level'] == 'Usuario Final') : ?>
          <a href="<?= BASE_URL; ?>/crear-invitacion">
            Crear invitación grátis
          </a>
        <?php endif; ?>
      </li>

      <li>
        <a href="<?= BASE_URL; ?>/soy-proveedor">
          Acceso a proveedores
        </a>
      </li>

      <li>
        <a href="<?= BASE_URL; ?>/eventos-recientes">
          Eventos recientes
        </a>
      </li>

      <li>
        <a href="<?= BASE_URL; ?>/tips">
          Tips
        </a>
      </li>
    </ul>
  </div>

  <div class="bottom">
    <p>© <?= date('Y'); ?> Manteles largos. All Rights Reserved</p>
    <p><a href="<?= BASE_URL; ?>/terminos-y-condiciones">Términos y Condiciones</a> | <a href="<?= BASE_URL; ?>/politicas-de-privacidad">Políticas de privacidad</a></p>
  </div>
</footer>