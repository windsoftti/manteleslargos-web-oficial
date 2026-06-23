$(document).ready(function () {
  $("#search-filter-form").bind("keypress", function (e) {
    if (e.keyCode == 13) {
      return false;
    }
  });

  loadInvitations();
});

function searchInvitations() {
  useSearch(loadInvitations);
}

async function loadInvitations(page = 1) {
  showPageLoading();
  const dataSend = new FormData($('#search-filters-form')[0]);

  dataSend.append('page', page);
  dataSend.append('action', 'list_invitations');

  const response = await fetchData({
    place: 'digital_invitations',
    data: dataSend
  });

  if (response.content) {
    const table = decodeURIComponent(escape(atob(response.content)));
    $('#list-invitations').html(table).show('slow');
  }

  hidePageLoading();
}

async function deleteInvitation() {
  const invitationId = $(this).attr('data-invitationId');
  const invitation = $(this).attr('data-invitation');

  const alertResponse = await showSweetConfirm({
    title: '!Cuidado!',
    subtitle: `¿Realmente desea eliminar la invitación de "${invitation}"?`,
    buttonTitle: 'Si, continuar',
    cancelButtonText: 'No, cancelar'
  });

  if (!alertResponse) return;

  showPageLoading();

  const dataSend = new FormData();

  dataSend.append('invitationId', invitationId);
  dataSend.append('invitation', invitation);
  dataSend.append('action', 'delete_invitation');

  const response = await fetchData({
    place: 'digital_invitations',
    data: dataSend
  });

  if (response) {
    showSweetAlert({
      icon: response.status,
      title: response.title
    });

    if (response.status === 'success') {
      loadInvitations();
    }
  }

  hidePageLoading();
}

function generateInvtationLink() {
  const invitationLink = $(this).attr('data-link');

  navigator.clipboard.writeText(invitationLink).then(() => {
    /* showBigAlert({
      icon: 'success',
      title: '¡Link copiado al portapapeles!',
      subtitle: 'Comparte el link con los usuarios que quieres que vean tu invitación digital'
    }); */
    showSweetAlert({
      icon: 'success',
      title: 'link copiado'
    })
  }).catch(err => {
    alert('Something went wrong', err);
  });
}

$(document).on('click', '.btn-delete-digital-invitation', deleteInvitation);
$(document).on('click', '.btn-generate-link', generateInvtationLink);