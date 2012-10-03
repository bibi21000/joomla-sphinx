window.addEvent('domready', function() {
	document.formvalidator.setHandler('documentation',
		function (value) {
			regex=/^[^0-9]+$/;
			return regex.test(value);
	});
});
window.addEvent('domready', function() {
	document.formvalidator.setHandler('directory',
		function (value) {
			regex=/^[^0-9]+$/;
			return regex.test(value);
	});
});
window.addEvent('domready', function() {
	document.formvalidator.setHandler('updateh',
		function (value) {
			regex=/^[0-9]+$/;
			return regex.test(value);
	});
});
