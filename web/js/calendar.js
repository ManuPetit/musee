$(document).ready(function () {
    var disabledDates = [ "01/11/2017", "25/12/2017", "01/05/2018", "01/11/2018", "25/12/2018", "01/05/2019", "01/11/2019", "25/12/2019"];
    $('.venuedatepick').datepicker({
        beforeShowDay: function(date){
            var day = date.getDay();
            var string = jQuery.datepicker.formatDate('dd/mm/yy', date);
            console.log(string);
            var isDisabled = ($.inArray(string, disabledDates) != -1);
            console.log(disabledDates);
            //day != 2 disables all tuesdays
            return [day != 2 && !isDisabled];
        },
        minDate: 0,
        maxDate: new Date(2019,11,31)
    });
});
