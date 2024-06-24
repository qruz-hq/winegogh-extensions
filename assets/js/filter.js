jQuery(document).ready(function($) {
    var availableDates = [];

    var setCalsClearButton = function(year,month,elem){

        var afterShow = function(){
            var d = new $.Deferred();
            var cnt = 0;
            setTimeout(function(){
                if(elem.dpDiv[0].style.display === "block"){
                    d.resolve();
                }
                if(cnt >= 500){
                    d.reject("datepicker show timeout");
                }
                cnt++;
            },10);
            return d.promise();
        }();

        afterShow.done(function(){

            // datepickerのz-indexを指定
            $('.ui-datepicker').css('z-index', 2000);

            var buttonPane = $( elem ).datepicker( "widget" ).find( ".ui-datepicker-buttonpane" );

            var btn = $('<button class="ui-datepicker-current ui-state-default ui-priority-primary ui-corner-all" type="button">Clear</button>');
            btn.off("click").on("click", function () {
                    $.datepicker._clearDate( elem.input[0] );
                });
            btn.appendTo( buttonPane );
        });
   }
        // Initialize date picker
        $('#wg-filter-date').datepicker({
            beforeShow: function(isnt, elem) {
                setCalsClearButton(null,null,elem);
            },
            onChangeMonthYear:setCalsClearButton,
            beforeShowDay: function(date) {
                var string = jQuery.datepicker.formatDate('yy-mm-dd', date);
                return [availableDates.indexOf(string) != -1];
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
                success: function(response) {
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
    $('#wg-filter-category').on('change', function(event) {
        event.preventDefault();
        submitSearch()
    });
    $('#wg-filter-date').on('change', submitSearch)

    // Set initial filter values from URL parameters
    var urlParams = new URLSearchParams(window.location.search);
    $('#wg-filter-category').val(urlParams.get('category'));
    $('#wg-filter-date').val(urlParams.get('event_date'));
});