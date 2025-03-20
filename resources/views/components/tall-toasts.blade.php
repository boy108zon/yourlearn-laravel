@if (session('custom_alert_type') && session('custom_alert_message'))
    <div class="container-fluid">
        <div class="alert alert-{{ session('custom_alert_type') }} alert-dismissible fade show" role="alert">
                <div class="row justify-content-center">
                    <div class="col-12 col-md-8 col-lg-6 d-flex justify-content-center align-items-center">
                        <div class="alert-content w-100 text-center">
                            {{ session('custom_alert_message') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
