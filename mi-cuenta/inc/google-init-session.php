<?php
if (isset($_GET["code"])) {
  $token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);

  if (!isset($token['error'])) {
    $google_client->setAccessToken($token['access_token']);

    $google_service = new Google_Service_Oauth2($google_client);
    $data = $google_service->userinfo->get();

    $user_full_name = $data['given_name'] . ' ' . $data['family_name'];
    $user_email     = $data['email'];
    $user_gender    = $data['gender'];

    $query_get_user = "SELECT
        idUsuario,
        Usuario,
        Username,
        Nivel
      FROM usuarios
      WHERE
        Correo  = BINARY '$user_email' AND
        Nivel   = 'Usuario Final'
      LIMIT 1
    ";

    $query_get_user_result  = mysqli_query($mysqli, $query_get_user);
    $num_rows               = mysqli_num_rows($query_get_user_num_rows);

    if ($num_rows) {
      $g_user_data = mysqli_fetch_array($query_get_user_result);

      $g_user_id        = $g_user_data['idUsuario'];
      $g_user_full_name = $g_user_data['Usuario'];
      $g_user_level     = $g_user_data['Nivel'];

      $_SESSION['session_user_id']    = $g_user_id;
      $_SESSION['session_user_name']  = $g_user_full_name;
      $_SESSION['session_user_level'] = $g_user_level;

      addUserHistory($user_email, 'Success: Sesión iniciada con Google');
    }

    if (!$num_rows) {
      $g_username = createSlug($data['given_name']);
      $g_password = generatePassword(8);

      $query_add_user = "INSERT INTO usuarios (
          Usuario,
          Correo,
          Status,
          Nivel,
          Username,
          Password
        ) VALUES (
          '$user_full_name',
          '$user_email',
          'Activo',
          'Usuario Final',
          '$g_username',
          '$g_password'
        )
      ";

      $query_add_user_result = mysqli_query($mysqli, $query_add_user);

      if (!$query_add_user_result) {
        $g_user_id = mysqli_insert_id($mysqli);
        $g_user_full_name = $user_full_name;
        $g_user_level     = 'Usuario Final';

        $_SESSION['session_user_id']    = $g_user_id;
        $_SESSION['session_user_name']  = $g_user_full_name;
        $_SESSION['session_user_level'] = $g_user_level;

        addUserHistory($user_email, 'Success: Se creó y se inició sesión con Google');
      }

      if (!$query_add_user_result) {
        addUserHistory($user_email, 'Error: Error al iniciar sesión con Google');
        header("location:$url_host");
      }
    }
  }
}
