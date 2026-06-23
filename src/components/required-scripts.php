<!-- BASE URL -->
<script>
  var BASE_URL = '<?= BASE_URL; ?>';
  const GOOGLE_MAPS_API_KEY = '<?= GOOGLE_MAPS_API_KEY; ?>';
</script>

<!-- IonIcons -->
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

<!-- JQuery -->
<script src="<?= BASE_URL; ?>/src/plugins/jquery/jquery.min.js"></script>

<!-- JQUERY UI -->
<script src="<?= BASE_URL; ?>/src/plugins/jquery-ui/jquery-ui.min.js"></script>

<!-- SELECT2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- SWEETALERT2 -->
<script src="<?= BASE_URL; ?>/src/plugins/sweetalert/sweetalert.min.js"></script>
<script src="<?= BASE_URL; ?>/src/plugins/sweetalert/sweetalert-functions.js"></script>

<!-- Main JS -->
<script src="<?= BASE_URL; ?>/src/js/main.js"></script>

<!-- Functions JS -->
<script src="<?= BASE_URL; ?>/src/js/functions.js"></script>

<!-- Navbar LogIn -->
<script src="<?= BASE_URL; ?>/src/js/authentication.js"></script>

<!-- Principal searchbar -->
<script src="<?= BASE_URL; ?>/src/js/principal-searchbar.js"></script>

<!-- JIVO CHAT -->
<!-- <script src="//code.jivosite.com/widget/pPAdyqIhDD" async></script> -->

<script type="text/javascript">
  /*var Tawk_API = Tawk_API || {},
    Tawk_LoadStart = new Date();
  (function() {
    var s1 = document.createElement("script"),
      s0 = document.getElementsByTagName("script")[0];
    s1.async = true;
    s1.src = 'https://embed.tawk.to/63278ef554f06e12d8957296/1gd98csut';
    s1.charset = 'UTF-8';
    s1.setAttribute('crossorigin', '*');
    s0.parentNode.insertBefore(s1, s0);
  })();*/

  const acceptCookies = () => {
    $.ajax({
      url: `${BASE_URL}/data/cookies/cookies_data.php`,
      type: 'GET',
      cache: false,
      crossDomain: true,
      dataType: 'json',
      xhrFields: {
        withCredentials: true
      },
      beforeSend: function(objeto) {
        document.body.style.cursor = 'wait';
      },
      success: function(data) {
        document.body.style.cursor = 'default';
        $('#cookies').hide();
      }
    });

    /* fetchData({
      place: 'cookies'
    }).then(response => {
      document.body.style.cursor = 'default';
      if (response.status === 'success') $('#cookies').hide();
    }); */
  }
</script>

<script src="<?= BASE_URL; ?>/src/js/turnstile.js"></script>

<script
    src="https://challenges.cloudflare.com/turnstile/v0/api.js?render=explicit"
    async
    defer>
</script>

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-92JVV7C919"></script>
<script>
  window.dataLayer = window.dataLayer || [];

  function gtag() {
    dataLayer.push(arguments);
  }
  gtag('js', new Date());

  gtag('config', 'G-92JVV7C919');
</script>

<script>
  $('input[name="username"]').on('keyup', function() {
    const id = $(this).attr('id');
    const specialCharacters = /\W+/;
    const value = $(this).val();
    const form = $(this).closest('form');

    if (id === 'login-modal-username' || id === 'supplier-username') return;

    if (value.match(specialCharacters)) {
      $(this).closest('form').find('[type="submit"]').attr('disabled', true);
      if ($(this).parent().find('p.alert-message').length == 0) $(this).parent().append(
        `<p class="alert-message" style="
          font-size: 0.8rem;
          color: red;
          margin: 0;
        ">Lo sentimos, solo se permiten letras (a-z) y números (0-9).</p>`
      );
    };

    if (!value.match(specialCharacters)) {
      $(this).closest('form').find('[type="submit"]').removeAttr('disabled');
      $(this).parent().children('p.alert-message').remove();
    }
  });
</script>