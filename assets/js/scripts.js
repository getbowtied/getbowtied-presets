jQuery(function($) {

	"use strict";



	$(document).on("click", ".getbowtied-presets td.edit a.edit", function(e) {
		e.preventDefault();
		$(this).parents('td.edit').toggleClass('active');
	})

	$(document).on("click", ".getbowtied-presets td.edit a.raw", function(e) {
		e.preventDefault();
		$(this).siblings('div.raw-data').toggleClass('active');
	})
})