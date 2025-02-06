@if(session('swal'))
    @php
        $swalMessage = session('swal.message');
        $swalType = session('swal.type', 'success'); 
    @endphp
    <script>
        window.addEventListener('load', () => {
            swal({
                title: '',
                text: '{{ $swalMessage }}',
                icon: '{{ $swalType }}',
                buttons: false,  // Hide the confirm button
                timer: 3000,  // Duration in milliseconds
                position: 'top-right',  // Positioning in the top-right corner
                customClass: {
                    popup: 'swal-sm p-3 bg-light border border-primary rounded-3 shadow-lg',  // Small popup with light background, border, and shadow
                    title: 'h4 text-center text-primary',  // Centered title with primary color
                    content: 'text-muted',  // Muted content text
                    container: 'w-auto'  // Automatic width based on content
                }
            });
        });
    </script>
@endif


@if(session('alert_message'))
    <div class="container-fluid">
        <div class="alert alert-{{ session('alert_type') }} alert-dismissible fade show" role="alert">
            {{ session('alert_message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
@endif
