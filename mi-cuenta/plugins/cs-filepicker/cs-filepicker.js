class CSFilePicker {
  constructor({
    id,
    title = 'Adjuntar imagen',
    subtitle = '',
    inputName = 'filepicker-input',
    supportedImages = ['image/jpeg', 'image/png', 'image/gif'],
    quality = 0.9
  }) {
    this.state = {
      id,
      title,
      subtitle,
      inputName,
      supportedImages,
      imgFile: null,
      quality
    };
  }

  createFilePicker = () => {
    const { id, inputName, title, subtitle } = this.state;
    this.state.imgFile = null

    const elementToReplace = document.getElementById(id);

    const filePicker = `
        <div id="${id}" class="cs-filepicker">
          <div id="cs-filepicker-container-${id}">
            <div id="cs-filepicker-icon-${id}" class="cs-filepicker-icon"></div>

            <div id="cs-filepicker-preview-${id}" class="cs-filepicker-preview"></div>

            <h3 class="cs-filepicker-title">${title}</h3>

            <button id="cs-filepicker-btn-${id}" class="cs-filepicker-button" type="button">
              Buscar archivo
            </button>

            ${!!subtitle ? `<p id="cs-filepicker-subtitle-${id}" class="cs-filepicker-subtitle">${subtitle}</p>` : ''}
          </div>

          <input id="cs-filepicker-input-${id}"
            type="file" name="${inputName}"
            style="display: none"
          >
        </div>
    `;

    elementToReplace.outerHTML = filePicker;
    this.initListeners();
  };

  createImagePreview = imageData => {
    const container = document.getElementById(`cs-filepicker-preview-${this.state.id}`);
    const img = `<img class="cs-filepicker-img-preview" src="${imageData.imageSrc}" alt="${imageData.imageName}">`;
    container.innerHTML = img;
    document.getElementById(`cs-filepicker-icon-${this.state.id}`).style.display = 'none';
    this._setImage(imageData);
  }

  _setImage = img => this.state.imgFile = img;

  initListeners = () => {
    const { id, supportedImages, quality, imgFile } = this.state;

    var inputTag = document.getElementById(`cs-filepicker-input-${id}`);
    const buttonTag = document.getElementById(`cs-filepicker-btn-${id}`);

    const clickButton = (e) => {
      e.stopPropagation();

      inputTag.click();
      return false;
    }

    const setImage = img => this._setImage(img);

    const createImagePreview = img => this.createImagePreview(img);

    function readableBytes(bytes) {
      const i = Math.floor(Math.log(bytes) / Math.log(1024)),
        sizes = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

      return (bytes / Math.pow(1024, i)).toFixed(2) + ' ' + sizes[i];
    }

    function displayInfo(label, file) {
      console.log(`${label} - ${readableBytes(file.size)}`);
      return file.size;
    }

    buttonTag.addEventListener('click', clickButton);

    inputTag.addEventListener('change', function (event) {
      let notSupported = false;

      for (let i = 0; i < this.files.length; i++) {
        const element = this.files[i];

        if (supportedImages.indexOf(element.type) === -1) notSupported = true;

        if (supportedImages.indexOf(element.type) != -1) {
          const imageSrc = URL.createObjectURL(element);
          const img = new Image;

          img.onload = function () {
            //let height = this.height;
            //let width = this.width;

            const max_size = 720
            let height = this.height;
            let width = this.width;

            if (width > height) {
              if (width > max_size) {
                height *= max_size / width;
                width = max_size;
              }
            } else {
              if (height > max_size) {
                width *= max_size / height;
                height = max_size;
              }
            }

            console.log(height, '-', width);

            const canvas = document.createElement("canvas");
            canvas.width = width;
            canvas.height = height;

            const ctx = canvas.getContext("2d");

            ctx.drawImage(img, 0, 0, width, height);
            const originalSize = displayInfo('Original file', element);
            const dataUrl = canvas.toDataURL(element.type);

            canvas.toBlob((blob) => {
              // Handle the compressed image. es. upload or save in local state
              displayInfo('Compressed file', blob);

              const imageData = {
                imageSrc: dataUrl,
                imageName: element.name,
                name: element.name,
                type: element.type,
                blob: new Blob([blob])
              };

              //setImage(imageData);
              createImagePreview(imageData);
            },
              'image/jpeg',
              quality
            );
          }

          img.src = imageSrc;
        }
      }

      if (notSupported) alert('El tipo de archivo que intenta subir no es válido');

      document.getElementById(`cs-filepicker-input-${id}`).value = "";
    });
  }

  getFile = () => this.state.imgFile;

  getPickerId = () => this.state.id;

  clearPicker = () => {
    this.state.imgFile = null;
    this.createFilePicker();
  }
}