$(document).ready(function() {
  adminButtonOnClick();
  loginButton();
});

// show password field and login button on admin click
const adminButtonOnClick = () => {
  $('#adminButton').on('click', function() {
    // Test if `Indexed Pages` div/table is not showing
    if ($('.login').css('display') == 'none') {
      $('.login').show(); // sho
    }
  });
}

const loginButton = () => {
  $('#submit').on('click', function() {
    var pass = $('#pass').val();
    if (pass === 'admin' || pass === 'ADMIN') {
      window.location = "adminPage/index.php";
    } else {
      alert('Wrong Password!');
    }
  });
}

// If the length of the element's string is 0 then display helper message
$('.myForm').submit(function() {
    if ($.trim($("input[name=searchBox]").val()) === "") {
        alert('Error; Input is Empty!');
        return false;
    }
});
