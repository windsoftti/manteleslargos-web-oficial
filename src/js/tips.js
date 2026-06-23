$(document).ready(() => loadTips(1, false));

const loadTips = async (page = 1, scrollTop = true) => {
  showPageLoading();

  if (scrollTop) {
    $('html').animate({ scrollTop: 100 }, 100);
  }

  const parameters = new FormData();

  parameters.append('action', 'load_tips');
  parameters.append('page', page);

  const response = await fetchData({
    place: 'tips',
    parameters
  });

  hidePageLoading();

  if (response.status === 'success') {
    const tips = decryptData(response.content);
    const pagination = response.pagination;
    const results = response.results;

    if (results == '0') $('#list-tips').addClass('no-grid');
    if (results != '0') $('#list-tips').removeClass('no-grid');

    $('#list-tips').html(tips);
    $('#pagination').html(pagination);
    $('#results').html(results);
  }
}