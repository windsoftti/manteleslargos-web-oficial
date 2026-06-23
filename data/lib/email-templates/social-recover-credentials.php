<table border="0" width="100%">
  <tbody>
    <tr align="center">
      <td>
        <div style="
          max-width: 550px;
          width: 100%;
        ">
          <div style="
            text-align: left;
            padding: 0.5rem 1rem;
          ">
            <div style="
              text-align: left;
              margin-bottom: 1rem;
            ">
              <img src="<?= BASE_URL; ?>/src/assets/images/logo.png" alt="Mateles Largos" height="80px">
            </div>

            <h3 style="
              font-size: 1.2rem;
              font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
              margin: 0;
              font-weight: 500;
            ">Tu solicitud de restablecimiento de contraseña</h3>

            <div style="
              border: 0.1rem solid rgb(240,240,240);
              padding: 1rem;
              border-radius: 0.3rem;
              margin-top: 1rem;
            ">
              <p style="
                font-size: 0.9rem;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
                margin: 0;
                margin-bottom: 0.5rem;
              ">
                Has solicitado un restablecimiento de contraseña para tu cuenta de Manteles Largos. Sin embargo, estás usando el inicio de sesión con red social. Estos son los detalles para acceder a tu cuenta:
                <br>
                <br>
                <b>Cuenta de inicio de sesión con red social:</b> <?= $etrc_account_type; ?>
                <br>
                <b>Registrado en:</b> <a href="https://www.manteleslargos.com">manteleslargos.com</a>
                <br>
                <br>
                <?php if ($etrc_account_type === 'Google') : ?>
                  Haz clic en el botón a continuación y haz clic en el ícono Google para acceder a tu cuenta.
                <?php endif; ?>

                <?php if ($etrc_account_type === 'Facebook') : ?>
                  Haz clic en el botón a continuación y haz clic en el ícono Facebook para acceder a tu cuenta.
                <?php endif; ?>
              </p>

              <?php $etrc_uid = $etrc_account_type === 'Google' ? 'googlelogin' : 'facebooklogin'; ?>

              <a href="<?= BASE_URL; ?>?uid=<?= $etrc_uid; ?>" style="text-decoration: none;">
                <div style="
                  padding: 0.8rem 0.5rem;
                  text-align: center;
                  background-color: #b88c1c;
                  font-size: 1.5rem;
                  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
                  margin-top: 1rem;
                  font-weight: bold;
                  border-radius: 0.3rem;
                  color: #fff;
                  box-shadow: 0px 1px 5px 1px rgba(0, 0, 0, 0.1);
                ">
                  Iniciar sesión
                  </dvi>
                </div>
              </a>

              <div style="height: 1px;background-color: rgb(200,200,200);margin: 1rem;"></div>

              <p style="
                font-size: 0.8rem;
                margin: 0;
                margin-top: 1rem;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
                color: gray;
                text-align: center;
              ">
                Está recibiendo este correo electrónico porque recientemente creó una nueva cuenta de Manteles Largos. Si no fue usted, ignore este correo electrónico.
              </p>

              <p style="
                font-size: 0.8rem;
                margin: 0;
                margin-top: 2rem;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
                color: gray;
                text-align: center;
              ">
                ¡Visita <a href="https://www.manteleslargos.com">manteleslargos.com</a>!
              </p>
            </div>
          </div>
      </td>
    </tr>
  </tbody>
</table>