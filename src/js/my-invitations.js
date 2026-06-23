$(document).ready(() => {
  loadInvitations();
  initSearchForm(loadInvitations);
});

const createInvitationPreview = ({
  invitationData,
  linkData,
  editURL,
  image,
  title,
  subtitle
}) => `
  <div class="top">
    <a href="javascript:void(0)" data-invitation="${invitationData}" style="flex: 1;">
      <img id="invitation-img" src="${image}">
    </a>

    <div class="actions">
      <button class="btn btn-danger btn-delete-invitation tooltip" data-invitation="${invitationData}">
        <ion-icon name="trash"></ion-icon>
        <span class="tooltiptext">Eliminar invitación</span>
      </button>

      <a class="btn btn-primary tooltip" data-invitation="${invitationData}" href="${editURL}">
        <ion-icon name="pencil"></ion-icon>
        <span class="tooltiptext">Editar invitación</span>
      </a>

      <a title="Visualizar invitación" target="_blank" class="btn btn-default tooltip" href="${linkData}">
        <ion-icon title="Visualizar invitación" name="eye"></ion-icon>
        <span class="tooltiptext">Visualizar invitación</span>
      </a>

      <a title="Copiar link" class="btn btn-secondary btn-shared-invitation tooltip" data-link="${linkData}" href="javascript:void(0)">
        <ion-icon title="Copiar link" name="arrow-redo"></ion-icon>
        <span class="tooltiptext">Copiar link</span>
      </a>
    </div>
  </div>

  <div class="bottom">
    <h4>${title}</h4>
    <h5>${subtitle}</h5>
  </div>
`;

const loadInvitations = async (page = 1) => {
  showPageLoading();

  const parameters = new FormData($('#filters-form')[0]);

  parameters.append('action', 'list_my_invitations');
  parameters.append('page', page);

  const response = await fetchData({
    place: 'invitations',
    parameters
  });

  hidePageLoading();

  if (response.status === 'success') {
    const invitations = decryptData(response.content);
    const pagination = response.pagination;

    $('#my-invitations').html(invitations);
    $('#pagination').html(pagination);
  }
}

function deleteInvitation() {
  const data = JSON.parse(atob($(this).attr('data-invitation')));

  const itemId = data.idInvitacion;
  const item = data.NombrePersona;

  useSimpleActionFromTable({
    item,
    itemId,
    place: 'invitations',
    action: 'delete_invitation',
    onAction: () => {
      loadInvitations();
      $('#invitation-preview').html('<b>SELECCIONA UNA INVITACIÓN</b>');
      const position = $('#invitation-preview').position();
      $('html').animate({ scrollTop: position.top - 240 }, 100);
    }
  });
}

function setInvitationPreview(e) {
  e.stopPropagation();

  const invitationData = $(this).attr('data-invitation');
  const image = $(this).attr('data-image');
  const editURL = $(this).attr('data-edit');
  const linkData = $(this).attr('data-link');

  const data = JSON.parse(decryptData(invitationData));

  const invitationPreview = createInvitationPreview({
    invitationData,
    editURL,
    linkData,
    image,
    title: data.NombrePersona,
    subtitle: data.NombreEvento
  });

  $('#invitation-preview').html(invitationPreview);
}

function generateInvtationLink() {
  const invitationLink = $(this).attr('data-link');

  navigator.clipboard.writeText(invitationLink).then(() => {
    showSweetToast({
      icon: 'success',
      message: 'link copiado'
    })
  }).catch(err => {
    alert('Something went wrong', err);
  });
}

$(document).on('click', '.invitation-item', setInvitationPreview);
$(document).on('click', '.btn-delete-invitation', deleteInvitation);
$(document).on('click', '.btn-shared-invitation', generateInvtationLink);