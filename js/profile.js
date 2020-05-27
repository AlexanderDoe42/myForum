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

var params = new URLSearchParams(location.search);
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

const uploadButton = document.querySelector('.profile__column1__button');
if (!params.has("id")) {
  uploadButton.addEventListener('mouseover', showUploadButton);
  uploadButton.addEventListener('mouseout', hideUploadButton);
}
function showUploadButton() {
  document.getElementById("uploadbutton").style.display = 'block';
}
function hideUploadButton() {
  document.getElementById("uploadbutton").style.display = 'none';
}

const mySubjects = document.querySelector('#mySubjects');
const myPosts = document.querySelector('#myPosts');
mySubjects.addEventListener('click', loadUsersSubjects);
myPosts.addEventListener('click', loadUsersPosts);

function loadUsersSubjects() {
  const main = document.querySelector("#users-posts");
  main.style.minHeight = '6em';
  main.innerHTML = "";

  const head = document.createElement('div');
  head.setAttribute("id", "users-posts__head");
  const h = document.createElement('h4');
  if (params.has("id")) {
    let username = document.querySelector(".profile__column2 h3").innerHTML;
    h.textContent = username + "'s subjects";
  } else {
    h.textContent = "My subjects";
  }
  head.appendChild(h);
  const whiteBlock = document.createElement('div');
  head.appendChild(whiteBlock);
  main.appendChild(head);

  const noSubjects = document.createElement('div');
  noSubjects.setAttribute("class", "post bg2 no-posts");
  noSubjects.textContent = "no subjects";
  main.appendChild(noSubjects);

  const subjects = document.createElement('div');
  subjects.setAttribute("id", "subjects");
  main.appendChild(subjects);

  var query = "AuthorID=";
  if (params.has("id")) {
    query += params.get("id");
  } else {
    query += getCookie("usrID");
  }
  loadSubjects(query);
}
function loadUsersPosts() {
  const main = document.querySelector("#users-posts");
  main.style.minHeight = '6em';
  main.innerHTML = "";

  const head = document.createElement('div');
  head.setAttribute("id", "users-posts__head");
  const h = document.createElement('h4');
  if (params.has("id")) {
    let username = document.querySelector(".profile__column2 h3").innerHTML;
    h.textContent = username + "'s posts";
  } else {
    h.textContent = "My posts";
  }
  head.appendChild(h);
  const whiteBlock = document.createElement('div');
  head.appendChild(whiteBlock);
  main.appendChild(head);

  const noPosts = document.createElement('div');
  noPosts.setAttribute("class", "post bg2 no-posts");
  noPosts.textContent = "no posts";
  main.appendChild(noPosts);

  const posts = document.createElement('div');
  posts.setAttribute("id", "posts");
  main.appendChild(posts);

  var query = "AuthorID=";
  if (params.has("id")) {
    query += params.get("id");
  } else {
    query += getCookie("usrID");
  }
  loadPosts(query);
}
