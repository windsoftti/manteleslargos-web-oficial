$('.dashboar-custom-icon-menu').on('click', function () {
	const state = $('#custom-sidebar').hasClass('custom-sidebar');

	if (state) $('#custom-sidebar').removeClass('custom-sidebar');
	if (!state) $('#custom-sidebar').addClass('custom-sidebar');
});