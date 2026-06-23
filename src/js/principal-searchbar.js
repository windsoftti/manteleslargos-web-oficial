//$(document).ready(() => keyEvents());

$(document).ready(() => {
  if ($("#searchTerm").length) {
    $("#searchTerm").autocomplete({
      source: `${BASE_URL}/data/autocompletes/businesses.php?eventTypeId=${$('#eventTypeId').val()}`,
      minLength: 1,
      select: function (event, ui) {
        const type = ui.item.type;

        if (type == 'item') {
          showPageLoading();
          location.href = ui.item.url;
        }

        if (type == 'header') {
          $('#searchTerm').val(ui.item.value)
        }
      }
    }).data("ui-autocomplete")._renderItem = function (ul, item) {
      const newText = String(item.value).replace(
        new RegExp(this.term, "gi"),
        "<span style='font-weight:bold;'>$&</span>"
      );

      const supplierType = String(item.supplierType).replace(
        new RegExp(this.term, "gi"),
        "<span style='font-weight:bold;'>$&</span>"
      );

      const business = item.business;
      const image = item.image;

      return $("<li></li>")
        .data("item.autocomplete", item)
        .append(`
          <div class="cs-autocomplete-item" style="
            display: flex;
            align-items: center;
            width: 100%;
            border-bottom: 1px solid #99999920;
            padding-top: 0rem;
            padding-bottom: 0rem;
            padding-left: 0.6rem;
            padding-right: 0.6rem;
            font-size: 0.9rem;
            cursor: pointer;
            color: #000;
            font-weight: 500;  
          " data-type="${item.type}">
            <img class="img-autocomplete" src="${image}" alt="${business}" style="
              height: 1.4rem;
              width: 1.4rem;
              border-radius: 0.3rem;
              object-fit: cover;
              margin-right: 0.5rem;
            " />
        
            <p>
              ${newText}
            </p>
          </div>
      `).appendTo(ul);
    };
  }
});

const loadSearchCitys = stateId => useLoadSelect({
  select: '#searchCityId',
  action: 'business-citys',
  data: stateId
});

const loadSearchCitysByLabel = state => useLoadSelect({
  select: '#searchCityId',
  action: 'business-citys-by-label',
  data: state
});

$('#searchStateId').on('change', function () {
  const stateId = $(this).val();
  loadSearchCitys(stateId);

  $('#searchCityId').show();
  $('#searchCity-container').show();
});

$('#searchStateIdByLabel').on('change', function () {
  const state = $(this).val();
  loadSearchCitysByLabel(state);

  $('#searchCityId').show();
  $('#searchCity-container').show();
});

const searchSupplierTypesElementsLength = $('.search-supplier-types a').length;
var searchSupplierTypesElementFocus = 0;
var keyCodePressed

$('#searchTerm').on('focus', function () {
  const valueLength = $(this).val().length;

  if (valueLength == 0) {
    $('.search-supplier-types').addClass('active')
    searchSupplierTypesElementFocus = 0;
  }
});
$('body').click(() => $('.search-supplier-types').removeClass('active'));
$('.search-supplier-types').on('click', function (e) {
  e.stopPropagation();
});
$('#searchTerm').on('click', function (e) {
  e.stopPropagation();
});

$('.search-supplier-types a').on('click', function (e) {
  e.stopPropagation();

  const value = $(this).attr('data-value');
  const slug = $(this).attr('data-slug');

  $('#searchTerm').val(value);
  $('.search-supplier-types').removeClass('active');
});

$('#searchTerm').on('keyup', function () {
  const valueLength = $(this).val().length;

  if (valueLength == 0) $('.search-supplier-types').addClass('active');
  if (valueLength > 0) $('.search-supplier-types').removeClass('active');
});

// Arriba = 38;
// Abajo  = 40;

$('#searchTerm').on('keyup', function (event) {
  var keycode = (event.keyCode ? event.keyCode : event.which);

  if (keycode == 40) {
    if (keyCodePressed == 38) searchSupplierTypesElementFocus = searchSupplierTypesElementFocus + 2;
    if (searchSupplierTypesElementFocus >= searchSupplierTypesElementsLength) searchSupplierTypesElementFocus = 0;

    const newFocusItem = $('.search-supplier-types a')[searchSupplierTypesElementFocus];
    $('.search-supplier-types a').removeClass('active');
    $(newFocusItem).addClass('active');

    searchSupplierTypesElementFocus = searchSupplierTypesElementFocus + 1;

    keyCodePressed = keycode;

    console.log(searchSupplierTypesElementFocus);
  }

  if (keycode == 38) {
    if (keyCodePressed == 40) searchSupplierTypesElementFocus = searchSupplierTypesElementFocus - 2;
    if (searchSupplierTypesElementFocus < 0) searchSupplierTypesElementFocus = searchSupplierTypesElementsLength - 1;

    const newFocusItem = $('.search-supplier-types a')[searchSupplierTypesElementFocus];
    $('.search-supplier-types a').removeClass('active');
    $(newFocusItem).addClass('active');

    searchSupplierTypesElementFocus = searchSupplierTypesElementFocus - 1;

    keyCodePressed = keycode;
    console.log(searchSupplierTypesElementFocus);
  }

  if (keycode == 13) {
    $(this).blur();
    if ($(this).val().length == 0) {
      $('#searchTerm').val($('.search-supplier-types a.active').attr('data-value'));
      $('.search-supplier-types').removeClass('active')
      return false;
    }
  }
});

$('#global-search-form').on('keydown', function (e) {
  return e.key != "Enter";
})