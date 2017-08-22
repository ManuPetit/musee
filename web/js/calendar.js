$(function () {
    var disabledDates = ["01/11/2017", "25/12/2017", "01/05/2018", "01/11/2018", "25/12/2018", "01/05/2019", "01/11/2019", "25/12/2019"];
    $('.venuedatepick').datepicker({
        beforeShowDay: function (date) {
            var day = date.getDay();
            var string = jQuery.datepicker.formatDate('dd/mm/yy', date);
            var isDisabled = ($.inArray(string, disabledDates) != -1);
            //day != 2 disables all tuesdays
            return [day != 2 && !isDisabled];
        },
        minDate: 0,
        maxDate: new Date(2019, 11, 31)
    });
    //get the div to replicate
    var $container = $('div#order_items');
    //get unique index
    var index = $container.find(':input').length;
    //add a new field for each click on add
    $('#add_line').click(function (e) {
        addLine($container);
        e.preventDefault(); //hide # in url
        return false;
    });

    //add a first item if none exists
    if (index == 0) {
        addLine($container);
    } else {
        //add delete button on each existing container
        $container.children('div').each(function () {
            addDeleteLink($(this));
        });
    }

    //add an extra form for the lines
    function addLine($container) {
        var template = $container.attr('data-prototype')
            .replace(/__name__label__/g, 'Billet n°' + (index + 1))
            .replace(/__name__/g, index);
        var $prototype = $(template);
        //adding delete button
        addDeleteLink($prototype);
        //adding new order line to form
        $container.append($prototype);
        $prototype.find(".birthdatepick").datepicker({
            minDate: new Date(1900,1,1),
            maxDate: "-4y",
            changeMonth: true,
            changeYear: true
        });
        index++;
    }

    //function to add link to remove object
    function addDeleteLink($prototype) {
        var $deleteLink = $('<a href="#" class="btn mini danger spacer">Supprimer le billet n°' + (index + 1) +'</a>');

        $prototype.append($deleteLink);

        $deleteLink.click(function (e) {
            $prototype.remove();
            e.preventDefault(); //hide # in url
            return false;
        });
    }
});
