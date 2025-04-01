
function loadScript(src) {
    return new Promise((resolve, reject) => {
        const script = document.createElement('script');
        script.src = src;
        script.type = 'text/javascript';
        script.onload = () => resolve();
        script.onerror = () => reject(new Error(`Script load error for ${src}`));
        document.head.appendChild(script);
    });
}


async function initializeZoom(selector = '.') {
    try {
        await loadScript('/js/jquery-3.6.0.min.js');
        await loadScript('/js/jquery.ez-plus.js');
        const images = document.querySelectorAll(selector);
        images.forEach(image => {
            image.addEventListener('mouseenter', function () {
                if (!image.dataset.ezPlus) {
                    $(image).ezPlus({
                        responsive: true,
                        scrollZoom: false,
                        showLens: true,
                        zIndex: 1080,
                        easing: true,
                        tint: true,
                        tintOpacity: 0.5,
                        borderSize: 0
                    });
                    image.dataset.ezPlus = true;
                }
            });

            image.addEventListener('mouseleave', function () {
                if (image.dataset.ezPlus) {
                    $(image).ezPlus('destroy');
                    image.dataset.ezPlus = false;
                }
            });
        });
    } catch (error) {
        console.error('Script loading error:', error);
    }
}

function setupZoomOnModal(modalId , selector) {
  initializeZoom(selector);
}

export { initializeZoom, setupZoomOnModal };
