let fileInput = document.getElementById('blacklist-mass-upload');
let mergeButton = document.getElementById('blacklist-mass-upload-merge');
let resetButton = document.getElementById('blacklist-mass-upload-reset');
let blacklist = document.getElementById('blacklist');
let notification = document.getElementById('mass-upload-notification');

let original;
let list;
let backup;

function merge() {
    if (!list) {
        alert('Please choose an import file first.')
        fileInput.focus();
    }

    backup = blacklist.value;
    blacklist.value = list;
}

function reset() {
    if (backup) {
        blacklist.value = backup;
    }

    fileInput.value = '';
    mergeButton.disabled = true;
    resetButton.disabled = true;
    mergeButton.classList.remove('button-primary')
    mergeButton.classList.add('button-primary-disabled')
    resetButton.classList.remove('button-secondary')
    resetButton.classList.add('button-secondary-disabled')
    backup = null;
}

function flash(contents) {
    notification.innerHTML = "[Read File Success]: (" + original.split(',').length + ") Existing, (" + contents.length + ") New."
        + "<br> Would you like to merge the records?";
}

function cleanAndSplit(string) {
    return string.replace(/(?:\r\n|\r|\n)/g, ",").replace(/,+/g, ",").split(',');
}

function read(event) {
    list = original = blacklist.value;
    let file = event.target.files[0];

    if (!file) {
        alert("Failed to load file");
    } else if (!file.type.match('text/csv') && !file.type.match('application/vnd.ms-excel')) {
        alert(file.name + " is not a valid csv, it is a(n): " + file.type);
    } else {
        let reader = new FileReader();
        reader.onload = function (e) {
            let contents = cleanAndSplit(e.target.result);
            list = cleanAndSplit(list);
            list = list.concat(contents.filter((item) => list.indexOf(item) < 0))

            flash(contents);
        }
        reader.readAsText(file);
        mergeButton.disabled = false;
        resetButton.disabled = false;
        mergeButton.classList.add('button-primary')
        mergeButton.classList.remove('button-primary-disabled')
        resetButton.classList.add('button-secondary')
        resetButton.classList.remove('button-secondary-disabled')
    }
}

if (fileInput) {
    fileInput.addEventListener('change', read, false);
}
if (mergeButton) {
    mergeButton.addEventListener('click', merge, false);
}
if (resetButton) {
    resetButton.addEventListener('click', reset, false);
}
