<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script>
        $(document).ready(function() {
            // Sidebar toggle functionality
            $('#sidebar-toggle').click(function() {
                $('#sidebar').toggleClass('show');
                $('#mobile-backdrop').toggleClass('hidden');
                $('#sidebar-toggle').addClass('hidden');
            });

            // Mobile backdrop click to close sidebar
            $('#mobile-backdrop').click(function() {
                $('#sidebar').removeClass('show');
                $('#sidebar-toggle').removeClass('hidden');
                $(this).addClass('hidden');
            });

            // Close sidebar when clicking outside on mobile
            $(document).click(function(event) {
                if ($(window).width() <= 768) {
                    if (!$(event.target).closest('#sidebar, #sidebar-toggle').length) {
                        $('#sidebar').removeClass('show');
                        $('#mobile-backdrop').addClass('hidden');
                        $('#sidebar-toggle').removeClass('hidden');
                    }
                }
            });

            // Handle window resize
            $(window).resize(function() {
                if ($(window).width() > 768) {
                    $('#sidebar').removeClass('show');
                    $('#mobile-backdrop').addClass('hidden');
                }
            });
        });
    </script>
    
</body>
</html>