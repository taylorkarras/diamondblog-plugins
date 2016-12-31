$( document ).ready(function() {
$('ul.tabs').each(function(){
  // For each set of tabs, we want to keep track of
  // which tab is active and its associated content
  var $active, $content, $links = $(this).find('a');

  // If the location.hash matches one of the links, use that as the active tab.
  // If no match is found, use the first link as the initial active tab.
  $active = $($links.filter('[href="'+location.hash+'"]')[0] || $links[0]);
  $active.addClass('active');

  $content = $($active[0].hash);

  // Hide the remaining content
  $links.not($active).each(function () {
    $(this.hash).hide();
  });

  // Bind the click event handler
  $(this).on('click', 'a', function(e){
    // Make the old tab inactive.
    $active.removeClass('active');
    $content.hide();

    // Update the variables with the new link and content
    $active = $(this);
    $content = $(this.hash);

    // Make the tab active.
    $active.addClass('active');
    $content.show();

    // Prevent the anchor's default click action
    e.preventDefault();
  });
});
});

function favoritetweet(id) {
	$.get('/console/social/twitter/tweet?favoriteid=' + id, function(data) {
		if (data == "Favorited") {
			$("[tweetid=" + id + "]").find(".tweetfavorite").css({color: "#e0001d", "font-weight": "bold"}).text("Favorited").attr("onclick","disfavoritetweet('" + id + "')");
		}
	});
}

function disfavoritetweet(id) {;
	$.get('/console/social/twitter/tweet?disfavoriteid=' + id, function(data) {
		if (data == "Disfavorited") {
			$("[tweetid=" + id + "]").find(".tweetfavorite").removeAttr("style").text("Favorite").attr("onclick","favoritetweet('" + id + "')");
		}
	});
}

function retweet(id) {
	$.get('/console/social/twitter/tweet?retweetid=' + id, function(data) {
		if (data == "Retweeted") {
			$("[tweetid=" + id + "]").find(".tweetretweet").css({color: "#29a533", "font-weight": "bold"}).attr("onclick","disretweet('" + id + "')");
		}
	});
}

function disretweet(id) {
	$.get('/console/social/twitter/tweet?disretweetid=' + id, function(data) {
		if (data == "Disretweeted") {
			$("[tweetid=" + id + "]").find(".tweetretweet").removeAttr("style").attr("onclick","retweet('" + id + "')");
		}
	});
}

var data = {};
$(function() {
$('#status').keypress(function (e) {
        if(e.which == 13) {
	  var myinstances = [];
	  var div_id = $( "#twitterbar" );
      $.each($('form input, form select'), function(i, v) {
          if (v.type !== 'submit') {
              data[v.name] = v.value;
          }
	  }); $.each($('textarea'), function(i, v) {
          if (v.type !== 'submit') {
              data[v.name] = v.value;
          }
      });
	  //end each
     $.ajax({
          dataType: 'json',
          type: 'POST',
          url: 'twitter/tweet',
          data: data,
          success: function(resp) {
              if (resp.divsubmit === true) {
				  $('#statusupdate').val('');
        var text_length = $('#statusupdate').val().length;
        var text_remaining = text_max - text_length;
        $('#chars').html(text_remaining + ' characters remaining');
				  div_id.append('<div class="message"><div class="successmessage">'+resp.message+'</div></div>');
				  $('.successmessage').delay(5000).fadeOut('fast');
			  } else if (resp.divfail === true) {
				  div_id.append('<div class="message"><div class="successmessage" style="background-color:#ff8181;">'+resp.message+'</div></div>');
				  $('.successmessage').delay(5000).fadeOut('fast');
			  } else {
                  $.each(resp, function(i, v) {
	        console.log(i + " => " + v); // view in console for error messages
                      var msg = '<div class="break"></div><label class="error" for="'+i+'">'+v+'</label>';
                      $('input[name="' + i + '"], select[name="' + i + '"], textarea[name="' + i + '"]').addClass('inputTxtError').after(msg);
                  });
                  var keys = Object.keys(resp);
                  $('input[name="'+keys[0]+'"]').focus();
              }
          },
          error: function(xhr, status, error) {
			  var err = eval("(" + xhr.responseText + ")");
              console.log(err.Message);
          }
      }); 
      return false;
}});
});

$(function() {
$('#dm').keypress(function (e) {
        if(e.which == 13) {
	  var myinstances = [];
	  var div_id = $( "#twitterbar" );
      $.each($('form input, form select'), function(i, v) {
          if (v.type !== 'submit') {
              data[v.name] = v.value;
          }
	  }); $.each($('textarea'), function(i, v) {
          if (v.type !== 'submit') {
              data[v.name] = v.value;
          }
      });
	  //end each
     $.ajax({
          dataType: 'json',
          type: 'POST',
          url: '/console/social/twitter/tweet',
          data: data,
          success: function(resp) {
              if (resp.divsubmit === true) {
				  $('#dmupdate').val('');
        var text_length = $('#dmupdate').val().length;
        var text_remaining = text_max - text_length;
        $('#chars').html(text_remaining + ' characters remaining');
				  div_id.append('<div class="message"><div class="successmessage">'+resp.message+'</div></div>');
				  $('.successmessage').delay(5000).fadeOut('fast');
			  } else if (resp.divfail === true) {
				  div_id.append('<div class="message"><div class="successmessage" style="background-color:#ff8181;">'+resp.message+'</div></div>');
				  $('.successmessage').delay(5000).fadeOut('fast');
			  } else {
                  $.each(resp, function(i, v) {
	        console.log(i + " => " + v); // view in console for error messages
                      var msg = '<div class="break"></div><label class="error" for="'+i+'">'+v+'</label>';
                      $('input[name="' + i + '"], select[name="' + i + '"], textarea[name="' + i + '"]').addClass('inputTxtError').after(msg);
                  });
                  var keys = Object.keys(resp);
                  $('input[name="'+keys[0]+'"]').focus();
              }
          },
          error: function(xhr, status, error) {
			  var err = eval("(" + xhr.responseText + ")");
              console.log(err.Message);
          }
      }); 
      return false;
}});
});

$(function() {
$('#status2').keypress(function (e) {
        if(e.which == 13) {
	  var myinstances = [];
	  var div_id = $( "#twitterbar" );
      $.each($('form input, form select'), function(i, v) {
          if (v.type !== 'submit') {
              data[v.name] = v.value;
          }
	  }); $.each($('textarea'), function(i, v) {
          if (v.type !== 'submit') {
              data[v.name] = v.value;
          }
      });
	  //end each
     $.ajax({
          dataType: 'json',
          type: 'POST',
          url: 'twitter/tweet',
          data: data,
          success: function(resp) {
              if (resp.divsubmit === true) {
				  $('#statusupdate2').val('');
        var text_length = $('#statusupdate2').val().length;
        var text_remaining = text_max - text_length;
        $('#chars2').html(text_remaining + ' characters remaining');
				  div_id.append('<div class="message"><div class="successmessage">'+resp.message+'</div></div>');
				  $('.successmessage').delay(5000).fadeOut('fast');
			  } else {
                  $.each(resp, function(i, v) {
	        console.log(i + " => " + v); // view in console for error messages
                      var msg = '<div class="break"></div><label class="error" for="'+i+'">'+v+'</label>';
                      $('input[name="' + i + '"], select[name="' + i + '"], textarea[name="' + i + '"]').addClass('inputTxtError').after(msg);
                  });
                  var keys = Object.keys(resp);
                  $('input[name="'+keys[0]+'"]').focus();
              }
          },
          error: function(xhr, status, error) {
			  var err = eval("(" + xhr.responseText + ")");
              console.log(err.Message);
          }
      }); 
      return false;
}});
});
