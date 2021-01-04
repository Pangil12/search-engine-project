var pagesToIndex = [];

$(document).ready(function() {
  indexedPagesCardOnClick();
  indexAPageCardOnClick();
  searchQueriesCardOnClick();
  statisticsCardOnClick();
});

// If `Indexed Pages` card is clicked show its table
// and hide the other tables
const indexedPagesCardOnClick = () => {
  $("#indexedPagesCard").on("click", function() {
    // Test if `Indexed Pages` div/table is not showing
    if ($("#IndexedPagesTable").css("display") == "none") {
      $("#IndexedPagesTable").show(); // show
      // hide other tables
      $("#IndexAPage").hide();
      $("#SearchQueriesTable").hide();
      $("#StatisticsTables").hide();
    } else {
      // else it is already showing, so hide other tables
      $("#IndexAPage").hide();
      $("#SearchQueriesTable").hide();
      $("#StatisticsTables").hide();
    }
  });
};

// If the `Index a Page` card is clicked show its table
// and hide the other tables
const indexAPageCardOnClick = () => {
  $("#indexAPageCard").on("click", function() {
    // Test if the `Index a Page` div/table is not showing
    if ($("#IndexAPage").css("display") == "none") {
      $("#IndexAPage").show(); //show
      //hide other tables
      $("#IndexedPagesTable").hide();
      $("#SearchQueriesTable").hide();
      $("#StatisticsTables").hide();
    } else {
      // else it is already showing, so hide other tables
      $("#IndexedPagesTable").hide();
      $("#SearchQueriesTable").hide();
      $("#StatisticsTables").hide();
    }
  });
};

// If the `Search Queries` card is clicked show its table
// and hide the other tables
const searchQueriesCardOnClick = () => {
  $("#searchQueriesCard").on("click", function() {
    // Test if the `Index a Page` div/table is not showing
    if ($("#SearchQueriesTable").css("display") == "none") {
      $("#SearchQueriesTable").show(); //show table
      //hide other tables
      $("#IndexedPagesTable").hide();
      $("#IndexAPage").hide();
      $("#StatisticsTables").hide();
    } else {
      // else it is already showing, so hide other tables
      $("#IndexedPagesTable").hide();
      $("#IndexAPage").hide();
      $("#StatisticsTables").hide();
    }
  });
};

// If the `Statistics` card is clicked show its tables
// and hide the other tables
const statisticsCardOnClick = () => {
  $("#statisticsCard").on("click", function() {
    // Test if the `statistics` div/tables are not showing
    if ($("#StatisticsTables").css("display") == "none") {
      $("#StatisticsTables").show(); //show tables
      //hide other tables
      $("#IndexedPagesTable").hide();
      $("#IndexAPage").hide();
      $("#SearchQueriesTable").hide();
    } else {
      // else it is already showing, so hide other tables
      $("#IndexedPagesTable").hide();
      $("#IndexAPage").hide();
      $("#SearchQueriesTable").hide();
    }
  });
};

// This is for the "check all" box in the header of the
// `Index a Page` table; check all the boxes in the table if checked
// uncheck all otherwise; add/remove from array accordingly
$("#checkAll").on("change", function() {
  $("input[type=checkbox]").prop("checked", $(this).is(":checked"));
  if ($(this).is(':checked')) {
  $('table tbody tr').each(function() {
    var pageLink = $(this).children('td').eq(1);
        secondCellContent = pageLink.text();
        addToPagesToIndexArray(secondCellContent);
  });
} else {
  pagesToIndex.length = 0; //clear array
}
});

// on checkbox checked/unchecked, check if selected/ deselected
// checkbox is in pagesToIndex array_keys, if not add, if so delete
// this will keep an array of selected pages(url's soecifically)
$('input[type=checkbox]').change(function() {
  var tempURL; // var to store url
  if ($(this).is(':checked')) {
    $(this).closest('tr').find('td').eq(1).each( // get url column
      function(i) {
        tempURL = $(this).text();
        addToPagesToIndexArray(tempURL);
      });
  } else { // remove from array
    var i = pagesToIndex.indexOf(tempURL);
    pagesToIndex.splice(i, 1);
  }
});

// add page url to array if not already there
addToPagesToIndexArray = (tempUrl) => {
  if (pagesToIndex.indexOf(tempUrl) < 0) {
    pagesToIndex.push(tempUrl);
  }
}

$(document).ready(function() {
  $("#submitButton").click(function() {
    // <-- on `Index Pages` button click)`
    pagesToIndex.forEach(function(page) { // for each page in the array
      $.ajax({
          type: "POST",
          url: "includes/siteIndexer.php",
          data: {
            'URL' : page
          },
          success: function(msg) {
            //alert("Pages Indexed"); // < here you can do anything; modify html, etc..
          },
          error: function(XMLHttpRequest, textStatus, errorThrown) {
            alert("Status: " + textStatus);
            alert("Error: " + errorThrown);
          }
        })
        .done(function(response) {
          //console.log(response);
        });
    });
  });
});

$( document ).ready(function(){
  $('#addUrlForm').on('submit', function() {
    //console.log("testing");
    event.preventDefault();
    $.ajax({
      type: "POST",
      url: "includes/urlValidator.php",
      data: $('#addUrlForm').serialize(),
      global: false,
      success: function() {

      }
    })
    .done(function(response) {
      //console.log("\nresponse = " + response);
      if (response === 'TRUE'){
        //console.log("success");
        $('#label').attr('class', 'text-success');
        $('#warningLabel').css('visibility','visible');
        $('#warningLabel').text("Page added successfully!");
        //$( "#myTable" ).load( "indexATable.php #myTable" );
      } else if (response === 'EXISTS'){
        //console.log("exists");
        $('#warningLabel').css('visibility','visible');
        $('#label').attr('class', 'text-danger');
        $('#warningLabel').text("Error! URL already exists in database.");
        //$('#urlField').attr('placeholder', 'Example: https://');
       } else {
        //console.log("failure");
        $('#warningLabel').css('visibility','visible');
        $('#label').attr('class', 'text-danger');
        $('#warningLabel').text("Error! Enter valid URL, including scheme");
        //$('#urlField').attr('placeholder', 'Example: https://');
       }
    })
  })
})

$( document ).ajaxStart(function() {
    $( "#loading" ).show();
});

$( document ).ajaxStop(function() {
    $( "#loading" ).hide();
});
