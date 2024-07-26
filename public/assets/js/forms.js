function printErrorMsg (msg) {
    $.each( msg, function( key, value ) {
        console.log(key);
          $('.'+key+'_err').text(value);
        });
}

$(document).ready(function(){
  $('.datepicker').daterangepicker({
    singleDatePicker: true,
    showDropdowns: true,
    minYear: 1901,
    maxYear: parseInt(moment().format('YYYY'),10),
    "locale": {
      "format": "DD-MM-YYYY",
      "separator": " - ",
      "applyLabel": "Pilih",
      "cancelLabel": "Kembali",
      "fromLabel": "Dari",
      "toLabel": "Sampai",
      "customRangeLabel": "Custom",
      "weekLabel": "W",
      "daysOfWeek": [
          "Su",
          "Mo",
          "Tu",
          "We",
          "Th",
          "Fr",
          "Sa"
      ],
      "monthNames": [
          "January",
          "February",
          "March",
          "April",
          "May",
          "June",
          "July",
          "August",
          "September",
          "October",
          "November",
          "December"
      ],
      "firstDay": 1
  },
  });

});