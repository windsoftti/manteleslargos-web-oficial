let $csMultipleFilePickerElements = [];
let $csMultipleFilePickerInputsCount = [];

const $csMultipleFilePickerElement = ({
  id,
  inputName
}) => {
  $csMultipleFilePickerElements[id] = [];
  $csMultipleFilePickerInputsCount[id] = 0;

  return `
    <div id="${id}" data-name="${inputName}" class="cs-multiple-filepicker">
      <button id="cs-multiple-filepicker-btn-${id}" class="cs-multiple-filepicker-btn" type="button"></button>
      <input id="cs-multiple-filepicker-input-${id}-0" class="cs-multiple-filepicker-input-${id}" type="file" name="${inputName}[]" style="display: none;">
    </div>
  `;
};

const $csMultipleFilePickerCreateElement = async id => {
  const element = document.getElementById(id);

  if (!element) {
    console.error('CS Multiple FilePicker :: Debes de pasar el id del elemento a remplazar.');
    return;
  }

  const inputName = element.getAttribute('data-name');

  const csMultipleFilePicker = $csMultipleFilePickerElement({
    id,
    inputName
  });

  element.outerHTML = csMultipleFilePicker;

  $csMultipleFilePickerInitListener(id);
}

const $csMultipleFilePickerInitListener = id => {
  var inputTag = document.getElementById(`cs-multiple-filepicker-input-${id}-${$csMultipleFilePickerInputsCount[id]}`);
  const buttonTag = document.getElementById(`cs-multiple-filepicker-btn-${id}`);

  function clickButton(e) {
    e.stopPropagation();
    //console.log(inputTag);
    inputTag.click();
    return false;
  }

  buttonTag.addEventListener("click", clickButton);

  inputTag.addEventListener("change", function (event) {
    this.removeEventListener('change', arguments.callee);
    let element = this.files[0];

    console.log('ok');

    buttonTag.removeEventListener('click', clickButton);

    let repeatedFiles = false;
    let findFiles = false;

    //const allSupportedFiles = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
    const supportedImages = ['image/jpeg', 'image/png', 'image/gif'];
    const supportedFiles = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];

    if (supportedImages.indexOf(element.type) === -1) {
      alert('Archivo no valido');
      this.value = "";
      $csMultipleFilePickerInitListener(id);
      return;
    }

    const arrayData = $csMultipleFilePickerElements[id];

    arrayData.map(item => {
      const nameItem = item.name;

      if (element.name === nameItem) {
        findFiles = true;
      }
    });

    if (findFiles) {
      alert('El archivo ya esta agregado.');
      this.value = "";

      $csMultipleFilePickerInitListener(id);
      return;
    }

    /* if (supportedFiles.indexOf(element.type) != -1) {
      $csMultipleFilePickerElements[id].push(element);
    } */

    if (supportedImages.indexOf(element.type) != -1) {
      $csMultipleFilePickerInputsCount[id] = $csMultipleFilePickerInputsCount[id] + 1;

      const picker = document.getElementById(id);
      const inputName = picker.getAttribute('data-name');
      const newInput = `<input id="cs-multiple-filepicker-input-${id}-${$csMultipleFilePickerInputsCount[id]}" class="cs-multiple-filepicker-input-${id}" type="file" name="${inputName}[]" style="display: none;">`;

      const imageData = [{
        imageSrc: URL.createObjectURL(element),
        imageName: element.name,
        imageSize: element.size / 1000000
      }];

      $csMultipleFilePickerCreateImagePreview({
        id,
        imageData,
        newInput
      });
    }

    console.log($csMultipleFilePickerElements[id]);
    $csMultipleFilePickerElements[id].push(element);
  });
}

const $csMultipleFilePickerCreateImagePreview = ({
  id,
  imageData = [],
  newInput,
  onRemove = false
}) => {
  const container = document.getElementById(id);

  imageData.map(item => {
    const imageSrc = item.imageSrc;
    const imageName = item.imageName;
    const imageSize = item.imageSize;
    const imageId = item.imageId;

    const btnAttr = !!imageId ? `data-imageId="${imageId}" data-imageName="${imageName}"` : ``;
    const count = !onRemove ? $csMultipleFilePickerInputsCount[id] - 1 : ``;
    const btnCount = !onRemove ? `data-count="${$csMultipleFilePickerInputsCount[id] - 1}"` : ``;
    const btnId = imageId ? imageId : count;

    const itemInput = !!imageId ? `<input type="hidden" name="${id}-items[]" value="${imageId}">` : ``;
    const inputPreview = `<input name="${id}-preview[]" type="hidden" value="${imageSrc}">`;

    console.log('item: ', itemInput);

    const imagePreview = `
      <div class="cs-multiple-filepicker-img-container">
        <img src="${imageSrc}" alt="${imageName}">
        <button id="cs-multiple-filepicker-btn-remove-${id}-${btnId}" class="cs-multiple-filepicker-btn-remove ${id}" type="button" ${btnAttr} ${btnCount} data-imageName="${imageName}" title="Remover"></button>
        ${itemInput}
        ${inputPreview}
      </div>
    `;

    // container.innerHTML += imagePreview;
    container.insertAdjacentHTML('beforeend', imagePreview);

    if (!!newInput) {
      //container.innerHTML += newInput;      
      container.insertAdjacentHTML('beforeend', newInput);

      var oldInputTag = document.getElementById(`cs-multiple-filepicker-input-${id}-${$csMultipleFilePickerInputsCount[id]}`);

      oldInputTag.removeAttribute(id);

      $csMultipleFilePickerInitListener(id);
    }

    if (!onRemove) {
      const buttonRemove = document.getElementById(`cs-multiple-filepicker-btn-remove-${id}-${btnId}`);

      buttonRemove.addEventListener('click', function (e) {
        e.stopPropagation();
        e.preventDefault();

        const name = this.getAttribute('data-imageName');
        const findInput = document.getElementById(`cs-multiple-filepicker-input-${id}-${count}`);

        if (findInput) {
          const input = document.getElementById(`cs-multiple-filepicker-input-${id}-${count}`);
          input.remove();
        }

        $csMultipleFilePickerElements[id].map((item, index) => {
          const itemName = item.name;

          if (itemName === name) {
            $csMultipleFilePickerElements[id].splice(index, 1);
          }
        });

        this.parentElement.remove();
      });
    }

    if (!!onRemove) {
      const buttonRemove = document.querySelectorAll(`.cs-multiple-filepicker-btn-remove.${id}`);
      buttonRemove.forEach(button => button.addEventListener('click', onRemove));
    }
  });
}