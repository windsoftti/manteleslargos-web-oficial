$(window).on('load', () => loadBusinesses(1, false));

$('#date').datepicker({
  locale: 'es',
  onSelect: () => loadBusinesses(1, true)
});

const loadBusinesses = async (page = 1, scrollTop = true) => {
  showPageLoading();

  if (scrollTop) {
    const position = $('#listing').position();
    $('html').animate({ scrollTop: position.top - 240 }, 100);
  }

  const parameters = new FormData($('#businesses-filter')[0]);

  const state = $('#searchStateId').val();
  const city = $('#searchCityId').val();

  parameters.append('action', 'load_businesses');
  parameters.append('state', state);
  parameters.append('city', city);
  parameters.append('page', page);

  const response = await fetchData({
    place: 'businesses',
    parameters
  });

  hidePageLoading();

  if (response.status === 'success') {
    const businesses = decryptData(response.content);
    const pagination = response.pagination;
    const results = response.results;

    if (results == '0') $('#list-businesses').addClass('no-grid');
    if (results != '0') $('#list-businesses').removeClass('no-grid');

    $('#list-businesses').html(businesses);
    $('#pagination').html(pagination);
    $('#results').html(results);

    initBusinessesMap();
  }
}

$('.modality').on('click', function () {
  const modality = $(this).attr('data-value');
  $('#modality').val(modality);
});

$('#businesses-filter').on('submit', function (e) {
  e.preventDefault();
  loadBusinesses(1);
});

$('#businesses-filter input').on('keyup', function() {
  const valueLength = $(this).val().length;
  if (valueLength >= 3) loadBusinesses(1, false);
});
$('#businesses-filter select').on('change', function () {
  const idAttr = $(this).attr('id');
  if (idAttr != 'eventTypeId') loadBusinesses(1)
});
$('#businesses-filter [type=checkbox]').on('click', () => loadBusinesses(1));