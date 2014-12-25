(function ( $ ) {

	$.fn.tablejs = function()
	{
		var $container = $(this);
		var $form = $(this).find('form');
		var $table = $(this).find('table');
		var $row = $table.children('tbody').children('tr:not(a,button)');

		$container.find('button').each(function()
		{
			if ($(this).attr('toggle')) {
				$(this).attr("disabled", true);
			}
		});

		// $row.click(function() {
		// 	var id = $(this).data("id");

		// 	toggleButtons();

		// 	$row.each(function()
		// 	{
		// 		if ($(this).hasClass('selected')) {
		// 			$(this).removeClass('selected');
		// 		}
		// 	});

		// 	$(this).addClass('selected');

  //  			if ($('#tablejs-id').length < 1) {
  //  				$form.append('<input type="hidden" name="id" value="'+id+'" id="tablejs-id" />');
  //  			} else {
  //  				$form.find('#tablejs-id').val(id);
  //  			}
		// });

		$container.on('click', 'a', function(e)
		{
			if ($(this).attr('confirmed')) {
				e.preventDefault();

				var url = $(this).attr('href');
				
				alertify.confirm($(this).attr('confirmed'), function (e) {
					if (e) {
						document.location = url;
					}
				});
			}
		});

		function toggleButtons()
		{
			$container.find('button').each(function()
			{
				if ($(this).attr('toggle')) {
					if ($(this).attr('disabled')) {
						$(this).removeAttr('disabled');
					}
				}
			});
		}
   	}; 

})( jQuery );