// This function will run the the document is loaded
$(document).ready(function() {
  $(".button-collapse").sideNav();
  $('.dropdown-button').dropdown();
  $(".button-collapse").sideNav();
  $('select').material_select();
});


// This function will add a notification in the form of a Toast with the text passsed as a parameter
function addNotification(notification) {
  if (notification != "" || notification != undefined) {
    Materialize.toast(notification, 4000);
  }
} // end addNotification



// This function will wait ms milliseconds to reload the page
function waitToReloadPage(ms) {
  setTimeout(function(){
    location.reload();
  }, ms);
}


// This function will send an AJAX request to the url: url, of type: type and the data: data
function send_ajax(type, url, data, callback = "") {
  $.ajax({
    type: type,
    url: url,
    data: data,
    dataType: 'json'
  }).done(function(data) {

    // Add a notification
    addNotification(data.message);

    // Check if callback function has been passed and call it
    typeof callback === 'function' && callback(data);

    // If there is an errors then...
  }).fail(function(data) {
    addNotification(data.responseJSON.message);
  });
}

// This function will turn on or turn off the loader animation
function toggle_loader() {
  loader = $('#loader');

  if (loader.hasClass('is-active')) {
    loader.removeClass('is-active');
  } else {
    loader.addClass('is-active');
  }
} // end toggle_loader()




// This function will update the parameter on the url to the value passed as a paramter
function replace_url_param(param, value) {

  // Check if the parameter param is on the link
  if (getURLParameter(param) !== false) {
    var newUrl = location.href.replace(param + "=" + getURLParameter(param), param + "=" + value);
  } else {
    // check if the string has any other get variables
    if (string_has_char(location.href, '?')) {
      var newUrl = location.href + "&" + param + "=" + value;
    } else {
      var newUrl = location.href + "?" + param + "=" + value;
    }

  }

  window.location = newUrl;
}




// This function will return the parameter of the url that corresponds to sParam
function getURLParameter(sParam) {
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++) {
        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] == sParam) {
            return sParameterName[1];
        }
    }

    return false;
}



// This function will return wheather a string has a value or not
function string_has_char(str, char) {
  return !(str.split(char).length === 1);
}
