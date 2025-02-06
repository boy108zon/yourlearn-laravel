import swal from 'sweetalert';

const Filters = {
    applyFilter: function(module) {
        const applyFilterButton = document.getElementById('applyFilter');
        
        if (!applyFilterButton) {
            return;
        }

        applyFilterButton.addEventListener('click', function() {
        
            let filters = {};

            document.querySelectorAll('#filter-form [data-filter]').forEach(function(filterElement) {
                const filterType = filterElement.getAttribute('data-filter');
                const filterId = filterElement.querySelector('input, select').id; 
                let filterValue = '';

                if (filterType === 'date') {
                    filterValue = document.getElementById(filterId).value;
                } else if (filterType === 'select') {
                    filterValue = document.getElementById(filterId).value;
                }

                if (filterValue) {
                    filters[filterId] = filterValue;
                }
            });

            if (Object.keys(filters).length === 0) {
                // Use SweetAlert to show a message instead of Toastify
                swal({
                    title: "No Filters Applied",
                    text: "Please apply at least one filter.",
                    icon: "warning",  // Optional: You can use "info", "error", "success"
                    buttons: "OK",  // Button to close the alert
                    dangerMode: true,  // Optional: Red for a warning style
                });
                return; 
            }

            let newUrl = `/${module}?`;
            Object.keys(filters).forEach((key, index) => {
                newUrl += `${key}=${encodeURIComponent(filters[key])}`;
                if (index < Object.keys(filters).length - 1) {
                    newUrl += '&';
                }
            });

            const table = document.getElementById(`${module}-table`);
            if (!table) {
                console.error(`Table for ${module} not found.`);
                return;
            }

            const dataTable = $(table).DataTable();
            dataTable.ajax.url(newUrl).load();
        });
    }
};

export { Filters };
