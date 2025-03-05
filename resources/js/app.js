
//import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js'
import $ from 'jquery';
import { Filters } from './modules/filters'; 
import './modules/checkboxHandler';
import loadProductsWithFilters from './modules/loadProductsWithFilters';

$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    
});

document.querySelector("#sidebar-toggle")?.addEventListener("click", () => {
    document.querySelector("#sidebar")?.classList.toggle("collapsed");
});

document.querySelector(".theme-toggle")?.addEventListener("click", () => {
    const isLight = localStorage.getItem("light");
    if (isLight) localStorage.removeItem("light");
    else localStorage.setItem("light", "set");

    document.documentElement.setAttribute('data-bs-theme', isLight ? 'light' : 'dark');
});

if (localStorage.getItem("light")) {
    document.documentElement.setAttribute('data-bs-theme', 'dark');
}

document.addEventListener('DOMContentLoaded', () => {

    const filterForm = document.getElementById('filter-form');
    const currentModule = filterForm ? filterForm.dataset.module : null;
    if (currentModule) {
        switch (currentModule) {
            case 'users.index':
                console.log('ff');
                Filters.applyFilter('users');
                break;
            case 'roles.index':
                Filters.applyFilter('roles');
                break;
            case 'log-activity.index':
                Filters.applyFilter('log-activity');
                break;
            case 'products.index':
                Filters.applyFilter('products');
                break;
            default:
                console.log("No matching module found for filter application");
                break;
        }
    }  


    

    
});
