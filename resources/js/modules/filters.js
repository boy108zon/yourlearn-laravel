import toastr from 'toastr';
import 'toastr/build/toastr.min.css';

window.toastr = toastr;

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

const Filters = {
    applyFilter: function(module) {
        const applyFilterButton = document.getElementById('applyFilter');
        
        if (!applyFilterButton) {
            return;
        }

        applyFilterButton.addEventListener('click', function() {
        
            let filters = {};

            // Collecting all the filter inputs
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

            // Show a toast if no filters were applied
            if (Object.keys(filters).length === 0) {
                showToast("Please apply at least one filter.", 'error');
                return; 
            }

            // Build the new URL with applied filters
            let newUrl = `/${module}?`;
            Object.keys(filters).forEach((key, index) => {
                newUrl += `${key}=${encodeURIComponent(filters[key])}`;
                if (index < Object.keys(filters).length - 1) {
                    newUrl += '&';
                }
            });

            // Update the DataTable with the new URL
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

function showToast(message, type = 'success') {
    // Display a toastr notification based on the type
    if (type === 'success') {
        toastr.success(message);
    } else if (type === 'error') {
        toastr.error(message);
    } else if (type === 'info') {
        toastr.info(message);
    } else if (type === 'warning') {
        toastr.warning(message);
    }
}

export { Filters };
