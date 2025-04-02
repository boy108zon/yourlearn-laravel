
import 'bootstrap/dist/js/bootstrap.bundle.min.js'
import $ from 'jquery';
import { Filters } from './modules/filters'; 
import './modules/checkboxHandler';
import loadProductsWithFilters from './modules/loadProductsWithFilters';
import { initializeZoom, setupZoomOnModal } from  './modules/photoZoom.js';
import {initializeDropzone} from './modules/dropzone.js';
import '../css/rating.css';
import '../css/productdetails.css';
import './modules/rating.js';

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

const verifyDropzone = () => {
    const dropzoneElement = document.querySelector('.dropzone'); 
    if (dropzoneElement) {
      initializeDropzone();
    }
};

const initializeZoomEffects = () => {
    const zoomElement = document.querySelector('.zoom-effects'); 
    if (zoomElement) {
        initializeZoom('.zoom-effects');
        setupZoomOnModal('#imageCarouselModal', '.zoom-effects');
    }
};


document.addEventListener('DOMContentLoaded', () => {

    const filterForm = document.getElementById('filter-form');
    const currentModule = filterForm ? filterForm.dataset.module : null;
    if (currentModule) {
        switch (currentModule) {
            case 'users.index':
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
            case 'orders.index':
                    Filters.applyFilter('orders');
                    break; 
            case 'promocodes.index':
                Filters.applyFilter('promocodes');
                break;                 
            default:
                console.log("No matching module found for filter application");
                break;
        }
    } 
     
    verifyDropzone();
    initializeZoomEffects();
    
});

