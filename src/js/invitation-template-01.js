$(setActiveCurrentPage)

$(document).scroll(initScrollSpy);

function handleToggleMenu() {
  const isOpen = $('.navbar').hasClass('navbar-open');

  if (isOpen) $('.navbar').removeClass('navbar-open');
  if (!isOpen) $('.navbar').addClass('navbar-open');
}

function closeNavbar() {
  $('.navbar').removeClass('navbar-open');
}

function handleToggleSubmenu() {
  const isOpen = $(this).parent().hasClass('submenu-open');

  if (isOpen) {
    $(this).parent().removeClass('submenu-open');
    $(this).parent().children('.submenu-content').slideUp();
  }

  if (!isOpen) {
    $(this).parent().addClass('submenu-open');
    $(this).parent().children('.submenu-content').slideDown();
  }
}

function setActiveCurrentPage() {
  //const pathName = location.pathname;
  //const pathName = location.protocol + '//' + location.hostname + location.pathname;
  const hash = location.hash;
  const route = `${hash}`;

  console.log(route);

  $('li').removeClass('navbar-item-active');

  const currentTag = document.querySelectorAll(`a[href='${route}']`);

  console.log(currentTag);

  $(currentTag).parent().addClass('navbar-item-active');
}

let $currentSectionHash = '';

function initScrollSpy() {
  $('section').each(function () {
    if (
      ($(this).position().top - 200) <= $(document).scrollTop() &&
      (($(this).position().top - 200) + $(this).outerHeight()) > $(document).scrollTop()) {

      const id = $(this).attr('id');

      if (id && id != '#' && id != $currentSectionHash) {
        $currentSectionHash = id;
        history.replaceState(null, null, document.location.pathname + '#' + id);
        setActiveCurrentPage();
      }
    }
  });
}

$('.navbar-toggle').on('click', handleToggleMenu);
$('.submenu-toggle').on('click', handleToggleSubmenu);
$('.navbar-list-link').on('click', closeNavbar);