<script>
    $(document).ready(function() {

        new DataTable('.courses', {
            language: {
                search: '',
                searchPlaceholder: 'Search Courses ...'
            },
            pageLength: 5,
            order: [
                [0, 'desc']
            ], // checked first
            columnDefs: [{
                    targets: 0,
                    width: '5%'
                }, // first column (e.g., checkbox)
                {
                    targets: 1,
                    width: '15%'
                }, // second column
                {
                    targets: 2,
                    width: '15%'
                }, // third column
                {
                    targets: 3,
                    width: '15%'
                }, // fourth column
                {
                    targets: 4,
                    width: '15%'
                }, // fifth column
                {
                    targets: 5,
                    width: '15%'
                }, // sixth column
                // other columns can auto-size
            ],
        });

    });
</script>
<script>
    $(document).ready(function() {

        setTimeout(function() {
            // console.log($(".dtsp-emptyMessage").first());
            $(".dtsp-emptyMessage").first().hide(); // Hide 'No panes to display' message
        }, 5000);
    });

    $('#form_submisssion').on('submit', function(e) {

        e.preventDefault(); // prevent the default submit until we are ready
        var form = this;
        var table = new DataTable('#course_table');

        // Save the state of all checkboxes before changing pagination
        var checkboxStates = {};
        table.$('input[type="checkbox"]').each(function() {
            var name = $(this).attr('name');
            checkboxStates[name] = $(this).prop('checked');
        });

        // Store current pagination length
        var originalPageLength = table.page.len();

        // Show all rows temporarily
        table.page.len(-1).draw();
        // Wait a tick for the browser to render all rows
        setTimeout(function() {

            // Restore checkbox states
            table.$('input[type="checkbox"]').each(function() {
                var name = $(this).attr('name');
                if (checkboxStates.hasOwnProperty(name)) {
                    $(this).prop('checked', checkboxStates[name]);
                }
            });
            // Submit the form normally
            form.submit();

            // Restore original pagination (optional, if page reloads it won't matter)
            table.page.len(originalPageLength).draw();
        }, 50);

    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusSelect = document.getElementById('student_status');
        const reasonBox = document.getElementById('drop_reason_box');

        function toggleReasonBox() {
            if (statusSelect.value === 'Dropped') {
                reasonBox.style.display = 'block';
            } else {
                reasonBox.style.display = 'none';
            }
        }

        // Initial check (for edit page)
        toggleReasonBox();

        // On change
        statusSelect.addEventListener('change', toggleReasonBox);
    });
</script>
