<nav class="invitations-navbar">
  <div>
    <div class="title">
      <h1>Diseña tus invitaciones</h1>
    </div>

    <div class="options">
      <!-- <ul>
        <li>
          <a href="<?= BASE_URL; ?>/configuracion-cuenta">
            <ion-icon name="person-circle-outline"></ion-icon>
            <?= $_SESSION['session_user_name']; ?>
          </a>
        </li>

        <li>
          <a href="<?= BASE_URL; ?>/cerrar-sesion">
            <ion-icon name="log-out-outline" title="Cerrar sesión"></ion-icon>
          </a>
        </li>
      </ul> -->
    </div>
  </div>

  <ul>
    <li>
      <a href="<?= BASE_URL; ?>/mis-invitaciones">
        <img src="<?= BASE_URL; ?>/src/assets/images/invitations-icons/my-invitations.png">
        <img class="hover" src="<?= BASE_URL; ?>/src/assets/images/invitations-icons/my-invitations-active.png">

        <span>MIS INVITACIONES</span>
      </a>
    </li>

    <li>
      <a href="<?= BASE_URL; ?>/crear-invitacion">
        <img src="<?= BASE_URL; ?>/src/assets/images/invitations-icons/create.png">
        <img class="hover" src="<?= BASE_URL; ?>/src/assets/images/invitations-icons/create-active.png">

        <span>CREAR INVITACIÓN</span>
      </a>
    </li>

    <li>
      <a href="<?= BASE_URL; ?>/disenio-personalizado">
        <img src="<?= BASE_URL; ?>/src/assets/images/invitations-icons/custom-design.png">
        <img class="hover" src="<?= BASE_URL; ?>/src/assets/images/invitations-icons/custom-design-active.png">

        <span>DISEÑO PERSONALIZADO</span>
      </a>
    </li>
  </ul>
</nav>