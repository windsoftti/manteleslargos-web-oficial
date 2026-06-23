$(document).ready(() => loadRecentEvents(1, false));

const loadRecentEvents = async (page = 1, scrollTop = true) => {
  showPageLoading();

  if (scrollTop) {
    $('html').animate({ scrollTop: 100 }, 100);
  }

  const parameters = new FormData();

  parameters.append('action', 'load_recent_events');
  parameters.append('page', page);

  const response = await fetchData({
    place: 'recent_events',
    parameters
  });

  hidePageLoading();

  if (response.status === 'success') {
    const recentEvents = decryptData(response.content);
    const pagination = response.pagination;
    const results = response.results;

    if (results == '0') $('#list-recent-events').addClass('no-grid');
    if (results != '0') $('#list-recent-events').removeClass('no-grid');

    $('#list-recent-events').html(recentEvents);
    $('#pagination').html(pagination);
    $('#results').html(results);
  }
}