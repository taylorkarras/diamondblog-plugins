var data = {};
$(function() {
$('#facebookfeedpost').on('click', 'input[type="submit"]', function() {
	  var myinstances = [];
	  $("input, button, select, textarea").prop('disabled', true);
	  $('body').append('<div class="message"><div class="successmessage" style="background-color:#f0f0f0">Please wait...</div></div>');

//this is the foreach loop
      $.each($('form input, form select'), function(i, v) {
          if (v.type !== 'submit') {
              data[v.name] = v.value;
          }
	  }); $.each($('textarea'), function(i, v) {
          if (v.type !== 'submit') {
              data[v.name] = v.value;
          }
      }); //end each
      $.ajax({
          dataType: 'json',
          type: 'POST',
          url: '/console/social/facebook/update',
          data: data,
          success: function(resp) {
              if (resp.resp === true) {
                  	//successful validation
					$(".successmessage").remove();
					$('body').append('<div class="message"><div class="successmessage">'+resp.message+'</div></div>')
					$('.successmessage').delay(5000).fadeOut('fast');
					window.setTimeout(function(){
					window.location.reload(true);
					}, 5000);
			  } else if (resp.divfail === true) {
				  div_id.append('<div class="message"><div class="successmessage" style="background-color:#ff8181;">'+resp.message+'</div></div>');
				  $('.successmessage').delay(5000).fadeOut('fast');
			  }
          }
      });
      return false;
  });
  
 $('#facebookpostcomment').on('click', 'input[type="submit"]', function() {
	  var myinstances = [];
	  $("input, button, select, textarea").prop('disabled', true);
	  $('body').append('<div class="message"><div class="successmessage" style="background-color:#f0f0f0">Please wait...</div></div>');

//this is the foreach loop
      $.each($('form input, form select'), function(i, v) {
          if (v.type !== 'submit') {
              data[v.name] = v.value;
          }
	  }); $.each($('textarea'), function(i, v) {
          if (v.type !== 'submit') {
              data[v.name] = v.value;
          }
      }); //end each
      $.ajax({
          dataType: 'json',
          type: 'POST',
          url: '/console/social/facebook/comment',
          data: data,
          success: function(resp) {
              if (resp.resp === true) {
                  	//successful validation
					$(".successmessage").remove();
					$('body').append('<div class="message"><div class="successmessage">'+resp.message+'</div></div>')
					$('.successmessage').delay(5000).fadeOut('fast');
					window.setTimeout(function(){
					window.location.reload(true);
					}, 5000);
			  } else if (resp.divfail === true) {
				  div_id.append('<div class="message"><div class="successmessage" style="background-color:#ff8181;">'+resp.message+'</div></div>');
				  $('.successmessage').delay(5000).fadeOut('fast');
			  }
          }
      });
      return false;
  });
  
$('#convomessage').keypress(function (e) {
        if(e.which == 13) {
	  var myinstances = [];
	  $("input, button, select, textarea").prop('disabled', true);

//this is the foreach loop
      $.each($('form input, form select'), function(i, v) {
          if (v.type !== 'submit') {
              data[v.name] = v.value;
          }
	  }); $.each($('textarea'), function(i, v) {
          if (v.type !== 'submit') {
              data[v.name] = v.value;
          }
      }); //end each
      $.ajax({
          dataType: 'json',
          type: 'POST',
          url: '/console/social/facebook/message',
          data: data,
          success: function(resp) {
              if (resp.resp === true) {
                  	//successful validation
					window.location.reload(true);
			  } else if (resp.divfail === true) {
					$("input, button, select, textarea").prop('disabled', false);
			  }
		  }
		});
      return false;
}});
});