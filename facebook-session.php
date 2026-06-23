<?php
session_start();
include 'inc/global-functions.inc.php';
include 'inc/constants.inc.php';
include 'inc/config.inc.php';
include 'inc/specific-functions.inc.php';

# Facebook
include 'data/lib/facebook-php-sdk/autoload.php';
include 'inc/facebook-api-config.php';

if ($facebook_access_token) {
  $oAuth2Client = $fb->getOAuth2Client();

  $longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($facebook_access_token);

  $fb->setDefaultAccessToken($facebook_access_token);

  if (!isset($_GET['code'])) {
    header('location:' . BASE_URL);
  }

  // Obtener información sobre el perfil de usuario facebook
  try {
    $profile_request = $fb->get('/me?fields=name,first_name,last_name,email,link,gender,locale,picture');
    $fb_user_profile = $profile_request->getGraphNode()->asArray();
  } catch (FacebookResponseException $e) {
    echo 'Graph returned an error: ' . $e->getMessage();
    session_destroy();
    // Redirigir usuario a la página de inicio de sesión de la aplicación
    header('location:' . BASE_URL);
    exit;
  } catch (FacebookSDKException $e) {
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
  }

  // datos de usuario que iran a  la base de datos
  /* $fb_user_data = array(
    'oauth_provider'  => 'facebook',
    'oauth_uid'       => $fb_user_profile['id'],
    'first_name'      => $fb_user_profile['first_name'],
    'last_name'       => $fb_user_profile['last_name'],
    'email'           => $fb_user_profile['email'],
    'gender'          => $fb_user_profile['gender'],
    'locale'          => $fb_user_profile['locale'],
    'picture'         => $fb_user_profile['picture']['url'],
    'link'            => $fb_user_profile['link']
  ); */

  $data = $fb_user_profile;

  /* echo json_encode($data);
  die; */

  if (!empty($data)) {
    $usr_dta = serialize($data);
    $user_full_name = $data['first_name'] . ' ' . $data['last_name'];
    $user_email     = $data['email'];

    $query_get_user = "SELECT
        idUsuario,
        Usuario,
        Correo,
        Username,
        Nivel,
        AccessType,
        Status
      FROM usuarios
      WHERE
        Correo  = BINARY '$user_email' AND
        Nivel   = 'Usuario Final'
      LIMIT 1
    ";

    $query_get_user_result  = mysqli_query($mysqli, $query_get_user);
    $num_rows               = mysqli_num_rows($query_get_user_result);

    if ($num_rows) {
      $g_user_data = mysqli_fetch_array($query_get_user_result);

      $g_user_id        = $g_user_data['idUsuario'];
      $g_user_full_name = $g_user_data['Usuario'];
      $g_user_level     = $g_user_data['Nivel'];

      $g_user_id        = $g_user_data['idUsuario'];
      $g_user_full_name = $g_user_data['Usuario'];
      $g_user_level     = $g_user_data['Nivel'];
      $g_access_type    = $g_user_data['AccessType'];
      $g_access_email   = $g_user_data['Correo'];
      $g_access_status  = $g_user_data['Status'];

      /* if ($g_access_type === 'Google') {
        header('location:' . $url_host . 'usuario-encontrado?uid=google');
        die;
      } */

      /* $_SESSION['session_user_id']    = $g_user_id;
      $_SESSION['session_user_name']  = $g_user_full_name;
      $_SESSION['session_user_level'] = $g_user_level; */
      $_SESSION['session_user_id']      = $g_user_id;
      $_SESSION['session_user_name']    = $g_user_full_name;
      $_SESSION['session_user_level']   = $g_user_level;
      $_SESSION['session_user_email']   = $g_access_email;
      $_SESSION['session_user_status']  = $g_access_status;

      addAuthenticationLogHistory($user_email, 'Success: Sesión iniciada con Facebook');

      header('location:' . BASE_URL);
      die();
    }

    if (!$num_rows) {
      $g_username = createSlug($data['first_name']) . '-' . date('dmyHis');
      $g_password = encrypt(generatePassword(), MYSQLI_PASSWORD_SECRET);

      $query_add_user = "INSERT INTO usuarios (
          Usuario,
          Correo,
          Status,
          Nivel,
          Username,
          Password,
          AccessType,
          usr_data
        ) VALUES (
          '$user_full_name',
          '$user_email',
          'Activo',
          'Usuario Final',
          '$g_username',
          '$g_password',
          'Facebook',
          '$usr_dta'
        )
      ";

      $query_add_user_result = mysqli_query($mysqli, $query_add_user);

      if (!$query_add_user_result) {
        addAuthenticationLogHistory($user_email, 'Error: Error al iniciar sesión con Facebook');
        header('location:' . BASE_URL);
        die();
      }

      if ($query_add_user_result) {
        $g_user_id = mysqli_insert_id($mysqli);
        $g_user_full_name = $user_full_name;
        $g_user_level     = 'Usuario Final';

        $_SESSION['session_user_id']      = $g_user_id;
        $_SESSION['session_user_name']    = $g_user_full_name;
        $_SESSION['session_user_level']   = $g_user_level;
        $_SESSION['session_user_email']   = $user_email;
        $_SESSION['session_user_status']  = 'Activo';

        addAuthenticationLogHistory($user_email, 'Success: Se creó y se inició sesión con Facebook');

        header('location:' . BASE_URL);
        die();
      }
    }
  } else {
    header('location:' . BASE_URL);
  }
}
