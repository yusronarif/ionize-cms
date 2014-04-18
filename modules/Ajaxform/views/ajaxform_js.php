<?php
/**
 * Added after each form by the tag :<ion:form ajax="true" >
 *
 * Handles the form submit event
 *
 * jQuery version.
 *
 * Receives from Ajaxform_Tags::tag_form():
 *
 * $form_name : 		Name of the Form
 * $form_submit_id : 	ID of the button which will send the form
 * $url :				The URL to the module's controller which handles the form data (post)
 *
 */
?>

<script type="text/javascript">

	var initForm = function()
	{
		// This part of script can be put in your own JS script if
		// you set "nojs" to TRUE in the ionize form open tag.
		var submitButton = 	$('#<?php echo $form_submit_id ?>');
		var form_name = 	'<?php echo $form_name ?>';
		var form = 			$('form[name="'+form_name+'"]');

		// Submit DOM element click event
		// Can be a link, a button, what you want
		submitButton.click(function(e)
		{
			e.preventDefault();

			// Post the form
			if (form.prop('tagName') == 'FORM')
			{
				// Prepare data to send : Add 'form_name'
				var data = form.serialize();
				data += '&form_name=' + form_name;

				// Remove all previous error messages if any
				$('#' + form_name + ' span.error').remove();
				$('#' + form_name + ' div').removeClass('error');

				/*
				 * Post the form
				 */
				$.post(
					'<?php echo $url ?>',
					data,
					/**
					 * Post Callback.
					 * Gets one JSON object, containing the success message or errors
					 * Success means the form was posted.
					 * Errors gives the errors detail (key
					 *
					 *
					 * @param data			Object {
					 * 							validation: 	boolean, false if the validation doesn't passed
					 * 							errors:[		array, contains all fields keys and error messages
					 * 								{field: error_message}
					 * 								...
					 * 							],
					 * 							title: 			String, title of the message
					 * 							message: 		String, message
					 * 						}
					 * @param textStatus	jQuery status. "success" if the request was succesful
					 * @param jqxhr			jQuery return code. 200.
					 *
					 */
					function(data)
					{
						// Add one global error message
						var id = 'form_' + form_name + '_message';

						// Remove previous one if any
						$('#' + id).remove();
						var type = data.validation == false ? 'alert-error' : 'alert-success';

						var div = $(
							'<div id="' + id + '" class="alert ' + type + '">' +
								'<button class="close" data-dismiss="alert" type="button">&times;</button>' +
								'<strong>' + data.title + '</strong> '
								+ data.message +
							'</div>'
						);
						$(form).prepend(div);

						// Errors : For each of them, get the input field and add one error message
						if (data.validation == false)
						{
							// Iterates through each error
							$.each(data.errors, function(key, val)
							{
								// Try to get the corresponding field
								var field = $(form).find('[name="' + key + '"]').first();

								if (field)
								{
									// Key fo the error DOM element
									var id = 'form_' + form_name + '_' + key + '_error';

									// Remove the previous message if any
									$('#' + id).remove();

									// Add error message
									var span = $('<span id="' + id + '" class="error">' + val + '</span>');
									$(field[0]).after(span);

									// Add error class to parent (we suppose it is one div...)
									$(field[0]).closest('div').addClass('error');
								}
							});
						}
					},
					'json'
				);
			}
			else
			{
				console.log('Ajaxform ERROR : Cannot get the form "' + form_name + '". Is it a form, btw ?');
			}
		});
	};

	// Pure JS Event listener, so jQuery can be loaded at the bottom fo the page
	if (window.addEventListener)
		window.addEventListener('load', initForm, false);
	else
		window.attachEvent('onload', initForm);

</script>