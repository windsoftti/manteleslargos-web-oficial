$(document).scroll(initScrollSpy);

const handleToggleNavbar = () => {
  const navbar = $('#navbar');
  const isOpen = navbar.hasClass('open');

  if (!isOpen) navbar.addClass('open');
  if (isOpen) navbar.removeClass('open');

  const list = navbar.children('ul');
  list.scrollTop({ position: 0 })
}

const closeNavbar = () => {
  const navbar = $('#navbar');

  navbar.addClass('open');
  navbar.removeClass('open');
}

function setActiveCurrentPage() {
  //const pathName = location.pathname;
  //const pathName = location.protocol + '//' + location.hostname + location.pathname;
  const hash = location.hash;
  const route = `${hash}`;

  //console.log(route);

  $('li').removeClass('active');

  const currentTag = document.querySelectorAll(`a[href='${route}']`);

  //console.log(currentTag);

  $(currentTag).parent().addClass('active');
}

let $currentSectionHash = '';

function initScrollSpy() {
  $('section').each(function () {
    if (
      ($(this).position().top - 400) <= $(document).scrollTop() &&
      (($(this).position().top - 400) + $(this).outerHeight()) > $(document).scrollTop()) {

      const id = $(this).attr('id');

      if (id && id != '#' && id != $currentSectionHash) {
        $currentSectionHash = id;
        history.replaceState(null, null, document.location.pathname + '#' + id);
        setActiveCurrentPage();
      }
    }
  });
}

function confirmateInvitation(e) {
  e.preventDefault();

  const name = $('#name').val();
  const phone = $('#phone').val();
  const personName = $('#personName').val();

  const message = `Hola%20soy%20${name}%20,%20confirmo%20asistencia%20al%20evento%20de%20${personName}`;
  const url = `https://api.whatsapp.com/send?phone=52${phone}&text=${message}`;
  window.open(url, "_blank");
}

$('#confirmation-form').on('submit', confirmateInvitation);

$('.navbar .toggle').on('click', handleToggleNavbar);
$('.navbar ul a').on('click', closeNavbar);

$('.gallery ul li a').simpleLightbox();