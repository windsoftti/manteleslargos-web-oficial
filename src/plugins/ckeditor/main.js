class EditorHTML {
  constructor(selector) {
    this.selector = selector;
    this.editor = {}

    this.initEditor();
  }

  getSelector = () => this.selector;

  initEditor = () => ClassicEditor.create(document.querySelector(this.selector), {
    toolbar: [
      'undo', 'redo', '|', 'heading', '|', 'bold', 'italic', 'underline', 'strikethrough', '|', 'bulletedList', 'numberedList', '|',
      'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor'
    ]
  }).then(editor => {
    this.editor = editor
  });

  getData = () => this.editor.getData();
}