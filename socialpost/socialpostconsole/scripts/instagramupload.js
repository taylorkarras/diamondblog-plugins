    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#grampreview').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#gramupload").change(function () {
        readURL(this);
    });
	
$('#instagramupload').on('click', 'input[type="submit"]', function() {
var formdata = new FormData($(this).parents('#instagramupload')[0]);
$('.error').remove();
event.preventDefault();
$("input, button, select, textarea").prop('disabled', true);
$('body').append('<div class="message"><div class="successmessage" style="background-color:#f0f0f0">Please wait...</div></div>');
	      $.ajax({
          type: 'POST',
          url: '/console/social/instagram/upload/upload',
          data: formdata,
		  cache: false,
		  contentType: false,
		  processData: false, 
          success: function(data) {
              if (data == 'Success') {
                  	//successful validation
					$(".successmessage").remove();
					$('body').append('<div class="message"><div class="successmessage">Item Uploaded!</div></div>')
					$('.successmessage').delay(5000).fadeOut('fast');
					window.setTimeout(function(){
					window.location.href = 'https://' + location.host + '/console/social/instagram/';
					}, 5000);
			  } else {
	        console.log('Error: ' + data); // view in console for error messages
                      $('#gramerror').after('<label class="error">'+data+'</label>');
	  $("input, button, select, textarea").prop('disabled', false);
	  $(".successmessage").remove();
                  $('#gramerror').focus();
              }
          }
      });
});