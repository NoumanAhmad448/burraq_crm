<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/2.3.6/js/dataTables.js"></script>

<!-- DataTables Buttons -->
<script src="https://cdn.datatables.net/buttons/3.2.6/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.6/js/buttons.dataTables.js"></script>

<!-- JSZip for Excel export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<!-- pdfmake for PDF export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<!-- Buttons HTML5 export -->
<script src="https://cdn.datatables.net/buttons/3.2.6/js/buttons.html5.min.js"></script>

<!-- Buttons print -->
<script src="https://cdn.datatables.net/buttons/3.2.6/js/buttons.print.min.js"></script>

    <script>
        function loadStudentDetail(id) {
            showLoader();
            $.get('/students/' + id, function(res) {
                hideLoader();
                $('#studentDetailModal').html(res).modal('show');
            });
        }
    </script>
    <script>
        $(document).ready(function() {
            new DataTable('{{$id}}', {
                pageLength: 10,
                layout: {
                    topStart: {
                        buttons: ['excel']
                    }
                }
            });
        });
    </script>