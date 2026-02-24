<script>
    $(document).ready(function() {
        $('#toggle-amounts').click(function() {
            // loop through all amount fields
            $('h2').each(function() {
                var classes = $(this).attr('class');
                if (classes && classes.indexOf('amount-') !== -1) {
                    // toggle visibility manually
                    if ($(this).css('display') === 'none') {
                        $(this).css('display', 'block'); // show
                    } else {
                        $(this).css('display', 'none'); // hide
                    }
                }
            });

            // toggle button text
            if ($("#toggle-amounts").attr("text") == 1) {
                $(this).text('Show Amounts');
                $(this).attr('text', 2); // ✔ valid

            } else {
                $(this).text('Hide Amounts');
                $(this).attr('text', 1); // ✔ valid


            }
        });
    });
</script>
