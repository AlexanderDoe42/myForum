const input = document.querySelector('#fileToUpload');
const preview = document.querySelector('.preview');
const submit = document.querySelector('#submit');

input.style.opacity = 0;

function updateImageDisplay() {
  while(preview.firstChild) {
    preview.removeChild(preview.firstChild);
  }

  const files = input.files;
  if(files.length !== 0) {
    file = files[0];
    console.log(file.type);
    const para = document.createElement('p');
    if(validFileType(file)) {
      if (file.size <= 512000) {
        submit.removeAttribute('disabled');
        para.style.color = 'green';
        para.textContent = `file size ${returnFileSize(file.size)}.`;
      } else {
        submit.setAttribute('disabled', '');
        para.style.color = 'red';
        para.textContent = `file size ${returnFileSize(file.size)}.`;
        para.innerHTML += '<br>must be less then 500KB';
      }
      const image = document.createElement('img');
      image.src = URL.createObjectURL(file);

      preview.appendChild(image);
      preview.appendChild(para);
    } else {
      para.textContent = `File name ${file.name}: Not a valid file type. Update your selection.`;
      preview.appendChild(para);
    }
  }
}

const fileTypes = [
  "image/apng",
  "image/bmp",
  "image/gif",
  "image/jpeg",
  "image/pjpeg",
  "image/png",
  "image/svg+xml",
  "image/tiff",
  "image/webp",
  "image/x-icon"
];

function validFileType(file) {
  return fileTypes.includes(file.type);
}

function returnFileSize(number) {
  if(number < 1024) {
    return number + 'bytes';
  } else if(number >= 1024 && number < 1048576) {
    return (number/1024).toFixed(1) + 'KB';
  } else if(number >= 1048576) {
    return (number/1048576).toFixed(1) + 'MB';
  }
}

input.addEventListener('change', updateImageDisplay);

let params = new URLSearchParams(location.search);
if (params.has('q')) {
  const body = document.body;
  const resultBox = document.createElement('div');
  resultBox.setAttribute("id", "resultBox");
  if (params.get('q') == 'success') {
    resultBox.textContent = `Your image has been uploaded successfully.`;
    resultBox.style.color = 'green';
    resultBox.style.border = '2px solid green';
  }
  if (params.get('q') == 'failure') {
    resultBox.textContent = `Sorry, something went wrong. :(`;
    resultBox.style.color = 'red';
    resultBox.style.border = '2px solid red';
  }
  body.appendChild(resultBox);
  setTimeout(fading, 3000);

  function fading() {
    let myInterval = setInterval(function(){lowerOpacity()}, 70);
    let opacity = 1;
    setTimeout(
      function() {
        clearInterval(myInterval);
        resultBox.remove();
      },
      2000
    )
    function lowerOpacity() {
      opacity -= 0.05
      resultBox.style.opacity = opacity;
    }
  }
}
