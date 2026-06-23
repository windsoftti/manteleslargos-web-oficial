const $csFilePickerElement = ({
  id,
  inputName,
  title = 'Adjuntar archivo',
  subtitle = '',
  labelError = '',
  required = '',
  requiredState
}) => `
  <div
    id="${id}" 
    class="cs-filepicker"
    data-name="${inputName}"
    data-title="${title}"
    data-subtitle="${subtitle}"
    data-labelError="${labelError}"
    data-required="${requiredState}"
  >
    <div id="cs-filepicker-container-${id}">
      <div class="cs-filepicker-icon"></div>

      <h3 class="cs-filepicker-title">${title}</h3>

      <button id="cs-filepicker-btn-${id}" class="cs-filepicker-button" type="button">
        Buscar archivo
      </button>

      ${!!subtitle ? `<p id="cs-filepicker-subtitle-${id}" class="cs-filepicker-subtitle">${subtitle}</p>` : ''}
    </div>

    <input ${!!labelError ? `labelError="${labelError}"` : ``}
      id="cs-filepicker-input-${id}"
      type="file" name="${inputName}"
      style="opacity: 0;height:1px;"
      ${required}
    >

    <input
      id="cs-filepicker-input-${id}-preview"
      type="hidden" name="${inputName}-preview"
    >
  </div>
`;

const $csFilePickerCreateElement = async id => {
  return new Promise((resolve, reject) => {
    const element = document.getElementById(id);

    if (!element) {
      console.error('CS FilePicker :: Debes de pasar el id del elemento a remplazar.');
      return;
    }

    const inputName = element.getAttribute('data-name');
    const title = element.getAttribute('data-title');
    const subtitle = element.getAttribute('data-subtitle');
    const labelError = element.getAttribute('data-labelError') ? element.getAttribute('data-labelError') : 'Adjunta una imagen';
    const requiredTag = element.getAttribute('data-required');

    const requiredState = requiredTag;
    const required = requiredTag == 'true' ? 'required' : '';

    const csFilePicker = $csFilePickerElement({
      id,
      inputName,
      title,
      subtitle,
      labelError,
      required,
      requiredState
    });

    element.outerHTML = csFilePicker;

    $csFilePickerInitListener(id);

    resolve(true);
  });
}

const $csFilePickerInitListener = id => {
  var inputTag = document.getElementById(`cs-filepicker-input-${id}`);
  const buttonTag = document.getElementById(`cs-filepicker-btn-${id}`);

  buttonTag.addEventListener("click", function (e) {
    inputTag.click();
    return false;
  });

  inputTag.addEventListener("change", function (event) {
    let element = this.files[0];

    console.log(element);

    /* const allSupportedFiles = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
    const supportedImages = ['image/jpeg', 'image/png', 'image/gif'];
    const supportedFiles = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']; */

    const allSupportedFiles = ['image/jpeg', 'image/png', 'image/gif'];
    const supportedImages = ['image/jpeg', 'image/png', 'image/gif'];
    const supportedFiles = [];

    if (allSupportedFiles.indexOf(element.type) === -1) {
      alert('Archivo no valido');
      $csFilePickerCreateElement(id);
    }

    if (supportedFiles.indexOf(element.type) != -1) {
      const fileName = element.name;
      const fileSize = element.size / 1000000;

      $csFilePickerCreateFilePreview({
        id,
        fileName,
        fileSize,
        onRemove: () => $csFilePickerCreateElement(id)
      });
    }

    if (supportedImages.indexOf(element.type) != -1) {
      const imageSrc = URL.createObjectURL(element);
      const imageName = element.name;
      const imageSize = element.size / 1000000;

      $csFilePickerCreateImagePreview({
        id,
        imageSrc,
        imageName,
        imageSize,
        onRemove: () => $csFilePickerCreateElement(id)
      });
    }
  });
}

const $csFilePickerCreateImagePreview = ({
  id,
  imageSrc,
  imageName,
  imageSize,
  imageId,
  onRemove
}) => {
  const container = document.getElementById(`cs-filepicker-container-${id}`);

  const btnAttr = !!imageId ? `data-imageId="${imageId}" data-imageName="${imageName}"` : ``;

  const imagePreview = `
    <div class="cs-filepicker-preview">
      <img class="cs-filepicker-img-preview" src="${imageSrc}" alt="${imageName}">
    </div>

    <button id="cs-filepicker-remove-${id}" class="cs-filepicker-button remove" type="button" ${btnAttr}>
      Remover
    </button>

    <p class="cs-filepicker-subtitle">${imageName}</p>
    ${!!imageSize ? `<p class="cs-filepicker-subtitle"><b>Tamaño:</b> ${imageSize} mb</p>` : ``}
  `;

  container.innerHTML = imagePreview;

  //document.getElementById(`cs-filepicker-input-${id}`).validate = false;
  $(`#cs-filepicker-input-${id}`).removeAttr('required');

  document.getElementById(`cs-filepicker-input-${id}-preview`).value = imageSrc;

  const buttonRemove = document.getElementById(`cs-filepicker-remove-${id}`);
  const handleRemove = () => !!onRemove ? onRemove() : $csFilePickerCreateElement(id);
  buttonRemove.addEventListener("click", handleRemove);
}

const $csFilePickerCreateFilePreview = ({
  id,
  fileName,
  fileSize,
  fileId,
  onRemove
}) => {
  const container = document.getElementById(`cs-filepicker-container-${id}`);
  const btnAttr = !!fileId ? `data-fileId="${fileId}"` : ``;

  let icon;

  if ($csFilePickerSearchExtension(fileName, '.pdf')) icon = 'pdf';
  else if ($csFilePickerSearchExtension(fileName, '.doc')) icon = 'doc';
  else if ($csFilePickerSearchExtension(fileName, '.xls')) icon = 'xls';
  else icon = 'file';

  const filePreview = `
    <div class="cs-filepicker-icon ${icon}"></div>

    <button id="cs-filepicker-remove-${id}" class="cs-filepicker-button remove" type="button" ${btnAttr}>
      Remover
    </button>

    <p class="cs-filepicker-subtitle">${fileName}</p>
    <p class="cs-filepicker-subtitle"><b>Tamaño:</b> ${fileSize} mb</p>
  `;

  container.innerHTML = filePreview;

  console.log(`cs-filepicker-input-${id}`);

  document.getElementById(`cs-filepicker-input-${id}`).required = false;

  const buttonRemove = document.getElementById(`cs-filepicker-remove-${id}`);
  buttonRemove.addEventListener("click", !!onRemove && onRemove);
}

const $csFilePickerSearchExtension = (str, fileType) => {
  let position = str.indexOf(fileType);
  if (position !== -1) return true;
  else return false;
}

/* buttonRemove.addEventListener("click", function (e) {
  console.log(this.getAttribute('data-imageId'));
}); */