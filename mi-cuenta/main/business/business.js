$(document).ready(function () {
  loadBusiness();
});

function searchBusiness() {
  useSearch(loadBusiness);
}

async function loadBusiness(page = 1) {
  showPageLoading();
  const searchByBusiness = $('#search-by-business').val();

  const dataSend = new FormData();

  dataSend.append('page', page);
  dataSend.append('searchByBusiness', searchByBusiness);
  dataSend.append('action', 'list_business');

  const response = await fetchData({
    place: 'business',
    data: dataSend
  });

  if (response.content) {
    const businessTable = decodeURIComponent(escape(atob(response.content)));
    $('#list-business').html(businessTable).show('slow');
  }

  hidePageLoading();
}

async function eliminarNegocio(idSalon, salon) {
  const resAlert = await showSweetConfirm({
    title: '¡Cuidado!',
    subtitle: `¿Realmente desea eliminar a "${salon}"?`
  });
  //const resAlert = confirm(`¿Realmente desea eliminar a "${salon}"?`);

  if (resAlert) {
    showPageLoading();

    const data = new FormData();

    data.append('action', 'delete_business');
    data.append('idSalon', idSalon);

    const resData = await fetchData({
      place: 'business',
      data
    });

    showBigAlert({
      title: '¡Negocio eliminado!',
      subtitle: resData.title
    }).then(() => {
      if (resData.state == 'success') {
        location.reload();
      }
    });

    hidePageLoading();
  }
}