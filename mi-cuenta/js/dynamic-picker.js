const pickerTag = ({
  id,
  name,
  title
}) => {
  return `
        <div id="input-picker-container-${id}">
            <div id="files-container-${id}">
                <div class="btn btn-default border d-flex align-content-center justify-content-center p-3" id="file-container-${id}" onclick="openFileManager('${id}', '${name}', '${title}')">
                    <div class="d-flex flex-column justify-content-center">
                        <i class="fa fa-file fa-3x"></i>
                        ${title}
                    </div>
                </div>
            </div>
  
            <input type="file" name="${name}" id="${id}" value="" style="display: none;">
         </div>
      `;
}

const createPicker = id => {
  const name = $(`#${id}`).attr('data-name');
  const title = $(`#${id}`).attr('data-title');

  const inputPicker = pickerTag({ id, name, title });

  $(`#${id}`).replaceWith(inputPicker);
}

const openFileManager = async (id, name, title) => {
  await $(`#${id}`).trigger("click");

  $(document).on("change", `#${id}`, function () {
    let files = this.files;
    let element;
    const allSupportedFiles = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
    const supportedImages = ['image/jpeg', 'image/png', 'image/gif'];
    const supportedFiles = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];

    for (let i = 0; i < files.length; i++) {
      element = files[i];

      if (allSupportedFiles.indexOf(element.type) === -1) {
        showSweetAlert({
          icon: 'error',
          title: ' ¡Archivo no valido!'
        });

        //this.form.reset();
        cleanPicker(id, name, title);
      }

      if (supportedFiles.indexOf(element.type) != -1) {
        createFilePreview({
          file: element,
          id,
          name,
          title
        });
      }

      if (supportedImages.indexOf(element.type) != -1) {
        createImagePreview({
          file: element,
          id,
          name,
          title
        });
      }
    }
  });
}

const createFilePreview = ({ id, file, name, title }) => {
  const fileName = file.name;
  const fileSize = file.size / 1000000;
  const fileType = file.type;

  let icon;
  let iconColor;

  if (fileType == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document') {
    icon = 'fa-file-word';
    iconColor = 'text-primary';
  }
  if (fileType == 'application/msword') {
    icon = 'fa-file-word';
    iconColor = 'text-primary';
  }
  if (fileType == 'application/pdf') {
    icon = 'fa-file-pdf';
    iconColor = 'text-danger';
  }

  document.getElementById(`files-container-${id}`).innerHTML = `
      <div class="btn btn-default border d-flex align-content-center justify-content-center p-3" onclick="cleanPicker('${id}', '${name}', '${title}')" title="Click para quitar archivo">
          <div class="d-flex flex-column justify-content-center">
              <i class="fa ${icon} fa-3x ${iconColor}"></i>
              <p class="lead" data-dz-name="">${fileName}</p>
              <p class="lead"><strong>Tamaño: </strong>${fileSize} mb</p>
  
              <a href="javascript:void(0)" onclick="cleanPicker('${id}', '${name}', '${title}')"><i class="fa fa-times fa-2x text-danger"></i></a>
          </div>
      </div>
      `;
}

const createImagePreview = ({ id, file, name, title }) => {
  const image = URL.createObjectURL(file);
  const fileName = file.name;
  const fileSize = file.size / 1000000;

  document.getElementById(`files-container-${id}`).innerHTML = `
      <div class="btn btn-default border d-flex align-content-center justify-content-center p-3">
          <div class="d-flex flex-column justify-content-center p-2">
              <img src="` + image + `" class="img-fluid" style="height: 100px;object-fit: contain;">
              <p class="lead" data-dz-name="">${fileName}</p>
              <p class="lead"><strong>Tamaño: </strong>${fileSize} mb</p>
  
              <a href="javascript:void(0)" onclick="cleanPicker('${id}', '${name}', '${title}')"><i class="fa fa-times fa-2x text-danger"></i></a>
          </div>

          <input type="hidden" name="${name}-preview" id="${id}-preview" value="${image}">
      </div>
    `;
}

const cleanPicker = async (id, name, title) => {
  const itemFileContainer = `
              <div class="btn btn-default border d-flex align-content-center justify-content-center p-3" id="file-container-${id}" onclick="openFileManager('${id}', '${name}', '${title}')">
                  <div class="d-flex flex-column justify-content-center">
                      <i class="fa fa-file fa-3x"></i>
                      ${title}
                  </div>
              </div>
      `;

  await $(`#${id}`).remove();

  $(`#input-picker-container-${id}`).append(`<input type="file" name="${name}" id="${id}" value="" style="display: none;">`);

  document.getElementById(`files-container-${id}`).innerHTML = itemFileContainer;
}

/* const createGlobalFilePreview = ({ idPicker, idFile, fileName, extraClass }) => {
  const name = fileName;
 
  let icon = 'fa-file';
  let iconColor;
 
  if (buscarExtension(name, '.doc')) {
    icon = 'fa-file-word';
    iconColor = 'text-primary';
  }
 
  if (buscarExtension(name, '.pdf')) {
    icon = 'fa-file-pdf';
    iconColor = 'text-danger';
  }
 
  document.getElementById(`files-container-${idPicker}`).innerHTML = `
    <div class="btn btn-default border d-flex align-content-center justify-content-center p-3">
        <div class="d-flex flex-column justify-content-center">
            <i class="fa ${icon} fa-3x ${iconColor}"></i>
            <p class="lead" data-dz-name="">${name.substring(0, 30)}...</p>
 
            <a class="${extraClass}" data-idFile="${idFile}" data-idPicker="${idPicker}" title="Click para eliminar el archivo"><i class="fa fa-trash fa-2x text-danger"></i></a>
        </div>
    </div>
    `;
} */

const createGlobalFilePreview = ({ idPicker, idFile, fileName, extraClass, uriImage, namePicker }) => {
  const name = fileName;

  let icon = 'fa-file';
  let iconColor;
  let item;

  if (buscarExtension(name, '.doc')) {
    icon = 'fa-file-word';
    iconColor = 'text-primary';
    item = `<i class="fa ${icon} fa-3x ${iconColor}"></i>`;
  }

  if (buscarExtension(name, '.pdf')) {
    icon = 'fa-file-pdf';
    iconColor = 'text-danger';
    item = `<i class="fa ${icon} fa-3x ${iconColor}"></i>`;
  }

  if (uriImage) {
    item = `<img src="${uriImage}${fileName}" class="img-fluid" style="height: 100px;object-fit: contain;">`;
  }

  document.getElementById(`files-container-${idPicker}`).innerHTML = `
      <div class="btn btn-default border d-flex align-content-center justify-content-center p-3">
          <div class="d-flex flex-column justify-content-center">
              ${item}
              <p class="lead" data-dz-name="">${name.substring(0, 30)}...</p>
  
              <a class="${extraClass}" data-idFile="${idFile}" data-idPicker="${idPicker}" title="Click para eliminar el archivo"><i class="fa fa-trash fa-2x text-danger"></i></a>

              <input type="hidden" name="${namePicker}-preview" value="${uriImage}${fileName}">
          </div>
      </div>
      `;
}

const buscarExtension = (cadena, termino) => {
  let posicion = cadena.indexOf(termino);
  if (posicion !== -1) return true;
  else return false;
}