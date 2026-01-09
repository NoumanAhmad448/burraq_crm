<script>
    document.addEventListener('DOMContentLoaded', function() {

        const items = document.querySelectorAll('.menu-item');

        let index = 0;

        function showNextItem() {
            if (index < items.length) {
                loader = $('.menu-loader' + index);

                    loader.addClass('d-none');

                    items[index].classList.remove('d-none');

                index++;

                setTimeout(showNextItem, 1000); // 2 seconds

            } else {}
        }

        setTimeout(showNextItem, 1000); // initial delay
    });
</script>

<script>
    $('#sidebar-wrapper').toggleClass('closed');
    $('#sidebar-col').toggleClass('d-none');
</script>
