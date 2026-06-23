var $modal = [];
var $imageElement = [];
var $imageFile = [];
var $imageName = [];
var $cropper = [];
var $urlElement = [];
var $canvas = [];
var $filePreview = [];
var $files = [];

function pickerWithCropperElement({ id, name, title }) {
  const element = `
    <div id="input-picker-container-${id}">
      <div id="files-container-${id}">
        <div class="btn btn-default border d-flex align-content-center justify-content-center p-3" id="file-container-${id}" onclick="openFileManagerWithCropper('${id}', '${name}', '${title}')">
          <div class="d-flex flex-column justify-content-center">
            <i class="fa fa-plus-circle fa-4x"></i>
            ${title}
          </div>
        </div>
      </div>
  
      <input type="file" name="${name}" id="${id}" value="" style="display: none;">
    </div>

    <div class="modal fade" id="modal-cropper-${id}" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalLabel">Recortar imagen</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="img-container">
              <div class="row">
                <div class="col-md-8">
                  <img id="image-to-crop-${id}" src="">
                </div>
                <div class="col-md-4">
                  <div class="preview"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" id="btn-cancel-crop-${id}" data-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-primary" id="crop-image-${id}">Recortar</button>
          </div>
        </div>
      </div>
    </div>
  `;

  return element;
}

async function createPickerWithCropper(id) {
  const name = $(`#${id}`).attr('data-name');
  const title = $(`#${id}`).attr('data-title');

  const inputPicker = pickerWithCropperElement({ id, name, title });

  await $(`#${id}`).replaceWith(inputPicker);

  $modal[id] = $(`#modal-cropper-${id}`);
  $imageElement[id] = document.getElementById(`image-to-crop-${id}`);

  $(document).on("click", `#btn-cancel-crop-${id}`, function () {
    $(`#${id}`).val('');
  });

  $(document).on("change", `#${id}`, function (event) {
    var files = event.target.files;
    $files[id] = event.target.files;
    var reader;
    var file;

    let element;
    const supportedImages = ['image/jpeg', 'image/png', 'image/gif'];

    for (let i = 0; i < files.length; i++) {
      element = files[i];

      if (supportedImages.indexOf(element.type) === -1) {
        showSweetAlert({
          icon: 'error',
          title: '¡Archivo no valido!'
        });

        $(this).val('');
        return;
      }
    }

    var done = function (url) {
      $imageElement[id].src = url;
      $modal[id].modal('show');
    };

    if (files && files.length > 0) {
      file = files[0];

      if (URL) {
        done(URL.createObjectURL(file));
      } else if (FileReader) {
        reader = new FileReader();
        reader.onload = function (e) {
          done(reader.result);
        };
        reader.readAsDataURL(file);
      }
    }
  });

  $(`#modal-cropper-${id}`).on('shown.bs.modal', function () {
    $cropper[id] = new Cropper($imageElement[id], {
      aspectRatio: 1,
      viewMode: 3,
      preview: ".preview"
    });
  }).on('hidden.bs.modal', function () {
    $cropper[id].destroy();
    $cropper[id] = null;
    $(`#${id}`).val('');
  });

  $(`#crop-image-${id}`).on('click', function () {
    canvas = $cropper[id].getCroppedCanvas({
      width: 1024,
      height: 720,
    });

    canvas.toBlob(function (blob) {
      url = URL.createObjectURL(blob);

      var reader = new FileReader();

      reader.readAsDataURL(blob);
      reader.onloadend = function () {
        $imageFile[id] = blob;
        $filePreview[id] = url;
        $modal[id].modal('hide');
        /* alert("success upload image"); */
        createImageCropperPreview({
          id,
          file: $files[id][0],
          name,
          title
        });
      }
    });
  });
}

function openFileManagerWithCropper(id, name, title) {
  $(`#${id}`).trigger("click");
}

function createImageCropperPreview({ id, file, name, title }) {
  const image = $filePreview[id];
  const fileName = file.name;
  $imageName[id] = file.name;
  const fileSize = file.size / 1000000;

  document.getElementById(`files-container-${id}`).innerHTML = `
      <div class="btn btn-default border d-flex align-content-center justify-content-center p-3">
          <div class="d-flex flex-column justify-content-center p-2">
              <img src="` + image + `" class="img-fluid" style="height: 100px;object-fit: contain;">
              <p class="lead" data-dz-name="">${fileName}</p>
              <p class="lead"><strong>Tamaño: </strong>${fileSize} mb</p>
  
              <a href="javascript:void(0)" onclick="cleanPickerWithCropper('${id}', '${name}', '${title}')"><i class="fa fa-times fa-2x text-danger"></i></a>
          </div>
      </div>
  `;
}

const cleanPickerWithCropper = async (id, name, title) => {
  const itemFileContainer = `
              <div class="btn btn-default border d-flex align-content-center justify-content-center p-3" id="file-container-${id}" onclick="openFileManagerWithCropper('${id}', '${name}', '${title}')">
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

const createGlobalFilePreviewCropped = ({ idPicker, idFile, fileName, extraClass, uriImage }) => {
  const name = fileName;

  let icon = 'fa-file';
  let iconColor;
  let item;

  if (buscarExtensionCropped(name, '.doc')) {
    icon = 'fa-file-word';
    iconColor = 'text-primary';
    item = `<i class="fa ${icon} fa-3x ${iconColor}"></i>`;
  }

  if (buscarExtensionCropped(name, '.pdf')) {
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
          </div>
      </div>
      `;
}

const buscarExtensionCropped = (cadena, termino) => {
  let posicion = cadena.indexOf(termino);
  if (posicion !== -1) return true;
  else return false;
}