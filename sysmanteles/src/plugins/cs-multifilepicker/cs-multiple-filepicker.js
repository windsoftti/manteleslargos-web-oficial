class CSMultipleFilepicker {
  constructor({
    id,
    inputName = 'cs-multiple-filepicker-input',
    supportedImages = ['image/jpeg', 'image/png', 'image/gif'],
    quality = 0.7,
    limit = 20
  }) {
    this.state = {
      id,
      inputName,
      supportedImages,
      csFiles: [],
      count: 0,
      initialImages: [],
      quality,
      limit
    };
  }

  createFilePicker = () => {
    const { id, inputName } = this.state;
    this.state.csFiles = [];
    this.state.count = 0;
    this.state.initialImages = [];

    const elementToReplace = document.getElementById(id);

    const filePicker = `
      <div id="${id}" data-name="${inputName}" class="cs-multiple-filepicker">
        <button id="cs-multiple-filepicker-btn-${id}" class="cs-multiple-filepicker-btn" type="button"></button>
        <input id="cs-multiple-filepicker-input-${id}" class="cs-multiple-filepicker-input-${id}" multiple type="file" name="${inputName}" style="display: none;">
      </div>
    `;

    elementToReplace.outerHTML = filePicker;
    this.initListeners();
  };

  createImagePreview = (image, onRemove) => {
    const { id, csFiles, initialImages } = this.state;

    const container = document.getElementById(id);
    const imageSrc = image.imageSrc;
    const imageName = image.imageName;
    const imageId = image.imageId;

    const btnAttr = !!imageId ? `data-imageId="${imageId}" data-imageName="${imageName}"` : ``;
    const count = !onRemove ? this.state.count - 1 : ``;
    const btnCount = !onRemove ? `data-count="${this.state.count - 1}"` : ``;
    const btnId = !!imageId ? `id-${imageId}` : count;
    const imgContainerClass = !!imageId ? `cs-multiple-filepicker-cs-file-added-${id}` : '';

    const imagePreview = `
      <div class="cs-multiple-filepicker-img-container ${imgContainerClass}">
        <img src="${imageSrc}" alt="${imageName}">
        <button id="cs-multiple-filepicker-btn-remove-${id}-${btnId}" class="cs-multiple-filepicker-btn-remove ${id}" type="button" ${btnAttr} ${btnCount} data-imageName="${imageName}" title="Remover"></button>
      </div>
    `;

    container.insertAdjacentHTML('beforeend', imagePreview);

    if (!onRemove) {
      const buttonRemove = document.getElementById(`cs-multiple-filepicker-btn-remove-${id}-${btnId}`);

      buttonRemove.addEventListener('click', function (e) {
        e.stopPropagation();
        e.preventDefault();

        const name = this.getAttribute('data-imageName');

        csFiles.map((item, index) => {
          const itemName = item.name;
          if (itemName === name) csFiles.splice(index, 1);
        });

        if (imageId) initialImages.map((item, index) => {
          if (imageId == item) initialImages.splice(index, 1);
        });

        this.parentElement.remove();
      });
    }

    if (!!onRemove) {
      const buttonRemove = document.getElementById(`cs-multiple-filepicker-btn-remove-${id}-${btnId}`);
      //const buttonRemove = document.querySelectorAll(`.cs-multiple-filepicker-btn-remove.${id}`);
      buttonRemove.addEventListener('click', function (e) {
        e.stopPropagation();
        e.preventDefault();

        const name = this.getAttribute('data-imageName');

        const removeElement = () => {
          this.parentElement.remove();

          if (imageId) initialImages.map((item, index) => {
            if (imageId == item) initialImages.splice(index, 1);
          });
        }

        onRemove(imageId, name, removeElement);
      });
    }
  }

  initListeners = () => {
    const { id, supportedImages, csFiles, initialImages, quality, limit } = this.state;

    const increaseCount = () => this.state.count = this.state.count + 1;

    var inputTag = document.getElementById(`cs-multiple-filepicker-input-${id}`);
    const buttonTag = document.getElementById(`cs-multiple-filepicker-btn-${id}`);

    const initialImagesLength = initialImages.length;
    const csFilesLength = csFiles.length;

    const pickerLength = initialImagesLength + csFilesLength;

    const clickButton = (e) => {
      e.stopPropagation();

      const initialImagesLength = initialImages.length;
      const csFilesLength = csFiles.length;

      if ((initialImagesLength + csFilesLength) == limit) {
        alert('Haz alcanzado el limite de imágenes disponibles');
        return;
      }

      inputTag.click();
      return false;
    }

    const createImagePreview = img => this.createImagePreview(img);

    function readableBytes(bytes) {
      const i = Math.floor(Math.log(bytes) / Math.log(1024)),
        sizes = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

      return (bytes / Math.pow(1024, i)).toFixed(2) + ' ' + sizes[i];
    }

    function displayInfo(label, file) {
      console.log(`${label} - ${readableBytes(file.size)}`)
    }

    buttonTag.addEventListener('click', clickButton);

    inputTag.addEventListener('change', function (event) {
      let notSupported = false;
      let findFiles = false;
      let notLoaded = false;

      const initialImagesLength = initialImages.length;
      const csFilesLength = csFiles.length;

      const pickerLength = initialImagesLength + csFilesLength + this.files.length;

      if (pickerLength > limit) {
        alert('No puedes agregar mas de ' + limit + ' imágenes');
        return false;
      }

      for (let i = 0; i < this.files.length; i++) {
        const element = this.files[i];

        if (supportedImages.indexOf(element.type) === -1) notSupported = true;

        if (supportedImages.indexOf(element.type) != -1) {
          const findFile = csFiles.find(item => item.name === element.name);

          if (findFile) findFiles = true;

          if (!findFile) {
            const imageSrc = URL.createObjectURL(element);
            const img = new Image;

            img.onload = function () {
              let height = this.height;
              let width = this.width;

              console.log(height, '-', width);

              const canvas = document.createElement("canvas");
              canvas.width = width;
              canvas.height = height;

              const ctx = canvas.getContext("2d");

              ctx.drawImage(img, 0, 0, width, height);

              canvas.toBlob((blob) => {
                // Handle the compressed image. es. upload or save in local state
                displayInfo('Original file', element);
                displayInfo('Compressed file', blob);

                const imageData = {
                  imageSrc,
                  imageName: element.name,
                  name: element.name,
                  type: element.type,
                  blob: new Blob([blob])
                };

                increaseCount();
                csFiles.push(imageData);
                createImagePreview(imageData);
              },
                'image/jpeg',
                quality
              );
            }

            img.src = imageSrc
          }
        }
      }

      if (notSupported && findFiles) alert('Los archivos no soportados y repetidos, no se agregaron');
      else if (notSupported) alert('Los archivos no soportados, no se agregaron');
      else if (findFiles) alert('Los archivos repetidos, no se agregaron');
    });
  }

  addImages = ({ images = [], onRemove }) => {
    this.state.initialImages = [];
    document.querySelectorAll(`.cs-multiple-filepicker-cs-file-added-${this.state.id}`).forEach(item => item.remove());

    images.map(image => {
      this.createImagePreview(image, onRemove);
      this.state.initialImages.push(image.imageId);
    });
  }

  getCSFiles = () => this.state.csFiles;
  getNewInitialImagesArray = () => this.state.initialImages;

  cleanFilePicker = () => {
    this.createFilePicker();
    this.state.csFiles = [];
    this.state.count = 0;
    this.state.initialImages = [];
  }
}