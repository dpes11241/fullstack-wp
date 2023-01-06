jQuery(document).ready(function($) {
    // Perform initial event search
    var s = '';
    var limit = event_search_ajax.limit;
    var paged = 1;
    searchEvents(s, limit, paged);
 
    // Handle event search form submission
    $('#event-search-form').submit(function(e) {
        e.preventDefault();
        var s = $(this).find('#s').val();
        var limit = event_search_ajax.limit;
        var paged = 1;
        searchEvents(s, limit, paged);
    });
 
    // Handle event search pagination
    $('body').on('click', '#event-search-pagination a', function(e) {
        e.preventDefault();
        var s = $('#event-search-form').find('#s').val();
        var limit = event_search_ajax.limit;
        var paged = $(this).data('paged');
        searchEvents(s, limit, paged);
    });
 
    // Perform event search
    function searchEvents(s, limit, paged) {
        $.ajax({
            type: 'POST',
            url: event_search_ajax.ajax_url,
            data: {
                'action': 'event_search_ajax',
                's': s,
                'limit': limit,
                'paged': paged,
            },
            beforeSend: function() {
                $('#event-search-results').html('<p>Searching events...</p>');
            },
            success: function(data) {
                $('#event-search-results').html(data);
            },
            error: function(error) {
                console.log(error);
            },
        });
    }

});
