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
              font-weight: 300;
            ">Hola <?= $etrc_name; ?>, hemos recibido tu solicitud de recuperación de contraseña</h3>

            <div style="
              border: 0.1rem solid rgb(240,240,240);
              padding: 1rem;
              border-radius: 0.3rem;
              margin-top: 1rem;
            ">
              <div style="
                padding: 0.5rem;
                background-color: #eaf5ff;
                font-size: 2rem;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
                margin-top: 1rem;
                border-radius: 0.3rem;
              ">
                <p style="
                  font-size: 1rem;
                  margin: 0;
                  margin-bottom: 0.5rem;
                ">
                  <b>Datos de acceso</b>
                </p>

                <p style="
                  font-size: 0.9rem;
                  margin: 0;
                ">
                  <b>Correo:</b> <?= $etrc_email; ?> <br>
                  <b>Usuario:</b> <?= $etrc_username; ?> <br>
                  <b>Contraseña:</b> <?= $etrc_password; ?> <br>
                </p>
              </div>
            </div>

            <p style="
              font-size: 0.8rem;
              margin: 0;
              text-align: center;
              margin-top: 1rem;
              font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
              color: gray;
            ">
              <?php if ($etrc_user_level === 'Usuario') : ?>
                Ingresa al sig. Link: <a href="<?= BASE_URL; ?>/soy-proveedor">manteleslargos.com/soy-proveedor</a> para iniciar sesión.
              <?php endif; ?>

              <?php if ($etrc_user_level === 'Usuario Final') : ?>
                Ingresa al sig. Link: <a href="<?= BASE_URL; ?>?uid=login">manteleslargos.com</a> para iniciar sesión.
              <?php endif; ?>

              <br>

              Esperamos verte nuevamente.
            </p>

            <div style="height: 1px;background-color: rgb(200,200,200);margin: 1rem;"></div>

            <p style="
              font-size: 0.8rem;
              margin: 0;
              margin-top: 1rem;
              font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
              color: gray;
              text-align: center;
            ">
              Está recibiendo este correo electrónico desde Manteles Largos.
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