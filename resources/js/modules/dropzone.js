import Dropzone from 'dropzone';
import 'dropzone/dist/dropzone.css';


let isDropzoneInitialized = false;
Dropzone.autoDiscover = false;

let productId, csrfToken, productName, storeUrl, removeUrl, primaryStatusUrl;

const initializeGlobalVariables = () => {
  const dropzoneElement = document.querySelector('.dropzone');
  ({ productId, csrfToken, productName, storeUrl, removeUrl, primaryStatusUrl } = dropzoneElement.dataset);
};


/**
 * Fetch existing images data from the dropzone element.
 * @param {HTMLElement} dropzoneElement - The Dropzone element.
 * @returns {Array} - Parsed existing images or an empty array.
 */
const getExistingImages = (dropzoneElement) => {
  return dropzoneElement.dataset.existingImages ? JSON.parse(dropzoneElement.dataset.existingImages) : [];
};

/**
 * Setup headers for Dropzone requests.
 * @param {string} csrfToken - The CSRF token to include in the request.
 * @returns {Object} - Headers object for Dropzone.
 */
const getHeaders = () => ({
  'X-CSRF-TOKEN': csrfToken,
});

const getPreviewTemplate = () => `
  <div class="dz-preview dz-file-preview card shadow-sm mb-3 position-relative" style="width: 18rem;">
    <img data-dz-thumbnail class="card-img" alt="Preview" />
    <div class="dz-progress mb-2">
      <div class="progress">
        <div class="progress-bar progress-bar-striped progress-bar-animated" data-dz-uploadprogress></div>
      </div>
    </div>
    <div class="card-body">
      <h5 class="card-title">${productName}</h5>
      <button type="button" class="btn btn-danger btn-sm dz-remove" data-dz-remove>
        Remove Thumbnail
      </button>
    </div>
  </div>
`;

export function initializeDropzone() {

  if (isDropzoneInitialized) return;
  isDropzoneInitialized = true;
  
  initializeGlobalVariables(); 
  const dropzoneElement = document.querySelector('.dropzone');
  const existingImages = getExistingImages(dropzoneElement);

  const myDropzone = new Dropzone(dropzoneElement, {
    url: storeUrl, 
    paramName: "images[]",
    parallelUploads: 3,
    maxFiles: 3,
    maxFilesize: 5,
    autoProcessQueue: true, 
    acceptedFiles: ".png,.jpg,.jpeg,.webp",
    addRemoveLinks: false,
    dictDefaultMessage: "",
    previewTemplate: getPreviewTemplate(), 
    headers: getHeaders(), 
    dictFileTooBig: "File is too large! Max size is 5 MB.",
    dictInvalidFileType: "Only PNG, JPG, and JPEG files are allowed.",
    dictResponseError: "Server error. Please try again later.",
    init() {

      this.element.style.border = 'none'; 

      existingImages.forEach(image => {
        addExistingImageToContainer(image); 
      });

      this.on('addedfile', (file) => {
        const previewElement = file.previewElement;
        const productNameElement = previewElement.querySelector('[data-dz-name]');
        if (productNameElement) {
          productNameElement.textContent = productName; 
        }
      });

      this.on('error', (file, message) => {
        const previewElement = file.previewElement;
        const errorMessageContainer = document.getElementById('dropzone-error-messages');
        
        errorMessageContainer.textContent = message;  
        errorMessageContainer.classList.remove('d-none'); 
        
        showMessage(message, 'danger');

        setTimeout(() => {
          previewElement.remove(); 
        }, 2000);
      });

      this.on('success', (file, response) => {

        const previewElement = file.previewElement;
        const errorMessageContainer = document.getElementById('dropzone-error-messages');
        errorMessageContainer.classList.add('d-none');  
        if(response.status === 0){
           
           const newImageData = {
              id: response.image_id,
              image_url: response.image_url,
            };

            addExistingImageToContainer(newImageData); 
            showMessage(response.message, 'success');
           
          }else{
            showMessage(response.message, 'danger');
          }

          setTimeout(() => {
            previewElement.remove(); 
          }, 2000);
      });
    },
    success(file, response) {
      console.log('File uploaded successfully:', response);
      file.id = response.image_id;
    },
    removedfile(file) {
      removeImage(file); 
    },
  });
}


function addExistingImageToContainer(image) {
  const container = document.getElementById('existing-images-container');
  const imageDiv = document.createElement('div');
  imageDiv.classList.add('col-3', 'mb-1');
  imageDiv.dataset.imageId = image.id;

  const isChecked = image.is_primary === 1 ? 'checked' : ''; 
  
  imageDiv.innerHTML = `
    <div class="card shadow-sm w-100 bg-white">
      <div class="card-body">
        <img src="${image.image_url}" class="card-img" alt="Existing Image" />
      </div>
      <div class="card-footer d-flex justify-content-between align-items-center">
        <button type="button" class="btn btn-danger btn-sm" data-image-id="${image.id}">
          <i class="bi bi-trash"></i> 
        </button>
        <div class="form-check">
          <label class="form-check-label" for="is_primary_${image.id}">
            <strong>Is Primary</strong>
          </label>
          <input class="form-check-input" type="checkbox" value="" id="is_primary_${image.id}" data-image-id="${image.id}" ${isChecked}>
        </div>
      </div>
    </div>
  `;

  container.appendChild(imageDiv);

  const removeButton = imageDiv.querySelector('button');
  removeButton.addEventListener('click', () => {
    removeImageFromContainer(image.id);
  });

  const checkbox = imageDiv.querySelector('.form-check-input');
  checkbox.addEventListener('change', (event) => {
    const isChecked = event.target.checked;
    updatePrimaryStatus(image.id, isChecked);
  });
}

function removeImageFromContainer(imageId) {
  showConfirmationDialog().then((isConfirmed) => {
    if (!isConfirmed) return;

    const container = document.getElementById('existing-images-container');
    const imageCard = container.querySelector(`[data-image-id="${imageId}"]`);
    if (imageCard) {
      imageCard.remove();
    }
    deleteImageFromServer(imageId); 
  });
}

const deleteImageFromServer = async (imageId) => {
  try {
    const response = await fetch(removeUrl, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken, 
      },
      body: JSON.stringify({ image_id: imageId }),
    });

    if (!response.ok) {
      showMessage('There was an error removing the image. Please try again.', 'danger');
      throw new Error('Failed to remove image');
    }

    const data = await response.json();
    console.log('Server response:', data);
    showMessage('Image removed successfully.', 'success');
  } catch (error) {
    console.error('Error removing image:', error);
    showMessage('There was an error removing the image. Please try again.', 'danger');
  }
};


function updatePrimaryStatus(imageId, isPrimary) {
  fetch(primaryStatusUrl, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': csrfToken, 
    },
    body: JSON.stringify({ image_id: imageId, is_primary: isPrimary }),
  })
    .then(response => response.json())
    .then(data => {
      if (data.status === 0) {
        showMessage(data.msg, 'success');
      } else {
        const checkbox = document.querySelector(`#is_primary_${imageId}`);
        if (checkbox) {
          checkbox.checked = false;
        }
        showMessage(data.msg, 'danger');
      }
    })
    .catch(error => {
      console.error('Error updating primary status:', error);
      showMessage('Error updating primary status.', 'danger');
    });
}


const showMessage = (message, type = 'success') => {
  const messageContainer = document.getElementById('dropzone-error-messages');

  messageContainer.textContent = message;
  messageContainer.classList.remove('d-none');
  messageContainer.classList.add(`alert-${type}`);  
  
  //messageContainer.scrollIntoView({ behavior: 'smooth' });
  window.scrollTo({ top: 0, behavior: 'smooth' });

  setTimeout(() => {
    messageContainer.classList.add('d-none');
  }, 5000);
};

const showConfirmationDialog = () => {
  return new Promise((resolve) => {
    const isConfirmed = window.confirm("Are you sure you want to remove?");
    resolve(isConfirmed);
  });
};
