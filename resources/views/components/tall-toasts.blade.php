
@if(session('swal')) 
    @php
        $swalMessage = session('swal.message');
        $swalType = session('swal.type', 'success'); 
    @endphp
    <script>
        window.addEventListener('load', () => {
            // Toastr.js notification
            toastr.options = {
                closeButton: true,  // Show close button
                progressBar: true,  // Show progress bar
                positionClass: 'toast-top-right', // Position of the toast
                timeOut: 3000,  // Time before toast hides
                showMethod: 'fadeIn', // Toast fade-in effect
                hideMethod: 'fadeOut', // Toast fade-out effect
                extendedTimeOut: 1000, // Additional time after user hovers over the toast
                preventDuplicates: true,  // Prevent duplicate toasts
            };

            // Show the Toastr notification based on the session data
            if ('{{ $swalType }}' === 'success') {
                toastr.success('{{ $swalMessage }}');
            } else if ('{{ $swalType }}' === 'error') {
                toastr.error('{{ $swalMessage }}');
            } else if ('{{ $swalType }}' === 'info') {
                toastr.info('{{ $swalMessage }}');
            } else if ('{{ $swalType }}' === 'warning') {
                toastr.warning('{{ $swalMessage }}');
            }
        });
    </script>
@endif

@if(session('alert_message'))
    <script>
        window.addEventListener('load', () => {
            toastr.options = {
                closeButton: true,
                progressBar: true,
                positionClass: 'toast-top-right',
                timeOut: 3000,
                showMethod: 'fadeIn',
                hideMethod: 'fadeOut',
                extendedTimeOut: 1000,
                preventDuplicates: true,
            };

            // Display the alert message using Toastr.js
            toastr['{{ session('alert_type') }}']('{{ session('alert_message') }}');
        });
    </script>
@endif
