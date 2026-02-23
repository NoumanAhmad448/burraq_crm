<script>
    $(document).ready(function() {
        $('#cnic').on('blur', function() {
            let cnic = $(this).val().trim();
            let studentId = $('#student_id').val().trim() == "" ? null : $('#student_id').val()
        .trim(); // hidden input on edit

            if (!cnic) return;

            $.ajax({
                url: '/ajax/validate-cnic',
                type: 'POST',
                data: {
                    cnic: cnic,
                    student_id: studentId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#cnic-message')
                        .removeClass()
                        .addClass('alert alert-success')
                        .html(response.message)
                        .show();
                },
                error: function(xhr) {
                    let res = xhr.responseJSON;
                    let alertClass = 'alert-danger';

                    if (res?.type === 'warning') {
                        alertClass = 'alert-warning';
                    }

                    $('#cnic-message')
                        .removeClass()
                        .addClass('alert ' + alertClass)
                        .html(res?.message ?? 'Something went wrong')
                        .show();
                }
            });
        });

    });
</script>
