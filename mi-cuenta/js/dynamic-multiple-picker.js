var multipleImagesData = [];
var inputsCount = [];

const multiplePickerTag = ({
    id,
    name,
    title,
    idListar
}) => {
    multipleImagesData[id] = [];
    inputsCount[id] = 0;

    return `
            <div class="d-flex align-items-center justify-content-center p-1 table-bordered rounded flex-row" id="multiple-file-container-${id}">
                <div id="${idListar}" class="d-flex align-items-center justify-content-center flex-row"></div>

                <div class="btn btn-default d-flex p-3 border flex-column justify-content-center align-items-center" id="multiple-file-button-add-${id}" onclick="openMultipleFileManager('${id}', '${name}', '${title}', '${idListar}')">
                    <i class="fa fa-plus-circle fa-4x"></i>
                </div>

                <input id="multiple-input-file-${id}-0" name="${name}[]" type="file" class="multiple-file-input-${id}" style="display: none;" value="">
            </div>
    `;
}

const createMultiplePicker = id => {
    const name = $(`#${id}`).attr('data-name');
    const title = $(`#${id}`).attr('data-title');
    const idListar = $(`#${id}`).attr('data-idListar');

    const inputMultiplePicker = multiplePickerTag({
        id,
        name,
        title,
        idListar
    });

    $(`#${id}`).replaceWith(inputMultiplePicker);
}

const openMultipleFileManager = async (id, name, title, idListar) => {
    await $(`#multiple-input-file-${id}-${inputsCount[id]}`).trigger("click");

    $(`.multiple-file-input-${id}`).on("change", function () {
        const files = this.files;

        let archivosRepetidos = false;

        console.log(archivosRepetidos);

        const allSupported = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];

        const supportedFiles = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        const supportedImages = ['image/jpeg', 'image/png', 'image/gif'];

        for (let i = 0; i < 1; i++) {
            const element = files[i];

            let findFiles = false;

            if (allSupported.indexOf(element.type) === -1) {
                showSweetAlert({ icon: 'error', title: 'El archivo que intenta subir no es valido.' });

                $(`#multiple-input-file-${id}-${inputsCount[id]}`).remove();
                $(`#multiple-file-container-${id}`).append(`<input type="file" id="multiple-input-file-${id}-${inputsCount[id]}" name="${name}[]" class="multiple-file-input-${id}" value="" style="display: none;">`);
                return;
            }

            const arrayData = multipleImagesData[id];

            //console.log(arrayData);

            arrayData.map(item => {
                const nameItem = item.name;

                if (element.name === nameItem) {
                    findFiles = true;
                }
            });

            if (findFiles) {
                archivosRepetidos = true;

                $(`#multiple-input-file-${id}-${inputsCount[id]}`).remove();
                $(`#multiple-file-container-${id}`).append(`<input type="file" id="multiple-input-file-${id}-${inputsCount[id]}" name="${name}[]" class="multiple-file-input-${id}" value="" style="display: none;">`);
            }

            if (!findFiles) {
                if (supportedFiles.indexOf(element.type) != -1) {
                    multipleImagesData[id].push(element);

                    createMultipleFilePreview({
                        file: element,
                        id,
                        name
                    });
                }

                if (supportedImages.indexOf(element.type) != -1) {
                    multipleImagesData[id].push(element);

                    createMultipleImagePreview({
                        file: element,
                        id,
                        name
                    });
                }
            }
        }

        if (archivosRepetidos) {
            showSweetAlert({ icon: 'warning', title: 'El archivo ya esta adjunto.', timer: 3000 });
            console.log(multipleImagesData[id]);

            return;
        }

        //showSweetAlert({ icon: 'success', title: 'Todos los archivos se cargaron correctamente.' });

        //console.log(multipleImagesData[id]);

        return;
    });
}

const createMultipleFilePreview = ({ id, file, name, title, idListar }) => {
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

    const fileElement = $(`
        <div class="btn btn-default d-flex flex-column border justify-content-center align-items-center mr-2 p-0 multiple-input-item" title="${fileName} / ${fileSize} mb" data-globalID="${id}" data-idElement="${inputsCount[id]}" data-name="${fileName}">
            <div class="d-flex flex-column justify-content-center align-items-center" style="height: 84px; width: 84px">
                <i class="fa ${icon} ${iconColor}"></i>
            </div>
            <i class="fa fa-times fa-2x icon-hidden"></i>
        </div>`
    );

    $(fileElement).insertBefore(`#multiple-file-button-add-${id}`);

    inputsCount[id] = inputsCount[id] + 1;

    $(`.multiple-file-input-${id}`).removeAttr('class');
    $(`#multiple-file-container-${id}`).append(`<input type="file" id="multiple-input-file-${id}-${inputsCount[id]}" name="${name}[]" class="multiple-file-input-${id}" value="" style="display: none;">`);
}

const createMultipleImagePreview = ({ id, file, name, title, idListar }) => {
    const image = URL.createObjectURL(file);
    const imageName = file.name;
    //const imageType = data.type;    

    const imageElement = $(`
        <div class="btn btn-default d-flex flex-column border justify-content-center align-items-center mr-2 p-0 multiple-input-item" data-globalID="${id}" data-idElement="${inputsCount[id]}" data-name="${imageName}">
            <img src="${image}" class="img-fluid rounded" style="height: 84px; width: 84px; object-fit: cover;">
            <i class="fa fa-times fa-2x icon-hidden"></i>
        </div>`
    );

    $(imageElement).insertBefore(`#multiple-file-button-add-${id}`);

    inputsCount[id] = inputsCount[id] + 1;

    $(`.multiple-file-input-${id}`).removeAttr('class');
    $(`#multiple-file-container-${id}`).append(`<input type="file" id="multiple-input-file-${id}-${inputsCount[id]}" name="${name}[]" class="multiple-file-input-${id}" value="" style="display: none;">`);
}

$(document).on("click", ".multiple-input-item", function (e) {
    const id = $(this).attr('data-globalID');
    const fileName = $(this).attr('data-name');
    const idElement = $(this).attr('data-idElement');

    multipleImagesData[id].map((item, index) => {
        const itemName = item.name;

        if (itemName === fileName) {
            multipleImagesData[id].splice(index, 1);
        }
    });

    $(`#multiple-input-file-${id}-${idElement}`).remove();
    $(this).remove();

    console.log(multipleImagesData[id]);
});

const cleanMultiplePicker = ({ id, name, title, idListar }) => {
    const inputMultiplePicker = multiplePickerTag({
        id,
        name,
        title,
        idListar
    });

    $(`#multiple-file-container-${id}`).replaceWith(inputMultiplePicker);
}