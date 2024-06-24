jQuery(function (jQuery) {

    jQuery.datepicker.setDefaults({
        closeText: 'Cerrar',
        prevText: '< Ant',
        nextText: 'Sig >',
        currentText: 'Hoy',
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
            'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        ],
        monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun',
            'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'
        ],
        dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'],
        dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
        weekHeader: 'Sm',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''
    });
})

jQuery(document).ready(function ($) {
    var availableDates = [];

    var setCalsClearButton = function (year, month, elem) {

        var afterShow = function () {
            var d = new $.Deferred();
            var cnt = 0;
            setTimeout(function () {
                if (elem.dpDiv[0].style.display === "block") {
                    d.resolve();
                }
                if (cnt >= 500) {
                    d.reject("datepicker show timeout");
                }
                cnt++;
            }, 10);
            return d.promise();
        }();

        afterShow.done(function () {

            $('.ui-datepicker').css('z-index', 2000);

            var buttonPane = $(elem).datepicker("widget").find(".ui-datepicker-buttonpane");

            var btn = $('<button class="ui-datepicker-current ui-state-default ui-priority-primary ui-corner-all" type="button">Clear</button>');
            btn.off("click").on("click", function () {
                $.datepicker._clearDate(elem.input[0]);
            });
            btn.appendTo(buttonPane);
        });
    }


    $("#wg-filter-date").datepicker({
        dateFormat: "dd 'de' MM 'de' yy", // standard format for storing,
        beforeShow: function (isnt, elem) {
            setCalsClearButton(null, null, elem);
        },
        onChangeMonthYear: setCalsClearButton,
        beforeShowDay: function (date) {
            if((new Date(date).getTime() + 1000*60*60*24) < Date.now()) return [false];
            var string = jQuery.datepicker.formatDate("dd-mm-yy", new Date(date));
            return [availableDates.indexOf(string.toUpperCase()) != -1];
        },
        minDate: 0,
        showButtonPanel: true,
    });

    function fetchAvailableDates() {
        $.ajax({
            url: winegogh.ajax_url,
            type: 'POST',
            data: {
                action: 'get_event_dates'
            },
            success: function (response) {
                if (response) {
                    availableDates = response;
                    $('#wg-filter-date').datepicker('refresh');
                }
            }
        });
    }

    fetchAvailableDates();


    // Function to update URL parameters
    function updateUrlParameter(param, value) {
        var searchParams = new URLSearchParams(window.location.search);
        searchParams.set(param, value);
        var newRelativePathQuery = window.location.pathname + '?' + searchParams.toString();
        history.pushState(null, '', newRelativePathQuery);
    }

    function submitSearch() {

        // Get the selected category
        var category = $('#wg-filter-category').val();
        var eventDate = $('#wg-filter-date').val();

        // Update the URL with the selected category
        updateUrlParameter('category', category);
        updateUrlParameter('event_date', eventDate);

        // Reload the page to apply the filters
        window.location.reload();
    }

    // Listen for the filter form submission
    $('#wg-filter-category').on('change', function (event) {
        event.preventDefault();
        submitSearch()
    });
    $('#wg-filter-date').on('change', submitSearch)

    // Set initial filter values from URL parameters
    var urlParams = new URLSearchParams(window.location.search);
    $('#wg-filter-category').val(urlParams.get('category'));
    $('#wg-filter-date').val(urlParams.get('event_date'));
});

document.addEventListener('DOMContentLoaded', function() {
    function updateCategoryOptions() {
        var select = document.getElementById('wg-filter-category');
        var options = select.options;
        var isMobile = window.innerWidth <= 752;

        for (var i = 0; i < options.length; i++) {
            var option = options[i];
            if (isMobile) {
                option.text = option.getAttribute('data-abbr');
            } else {
                option.text = option.getAttribute('data-full');
            }
        }
    }

    // Update options on page load
    updateCategoryOptions();

    // Update options on window resize
    window.addEventListener('resize', updateCategoryOptions);
});