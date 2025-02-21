@if (session('custom_alert_type') && session('custom_alert_message'))
    <div class="container-fluid">
        <div class="alert alert-{{ session('custom_alert_type') }} alert-dismissible fade show" role="alert">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-12 col-md-8 col-lg-6">
                        <div class="alert-content">
                            {{ session('custom_alert_message') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif


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
