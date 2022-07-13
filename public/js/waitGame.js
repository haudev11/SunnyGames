const base_url = "http://localhost:8000/";
let submit = document.getElementById('submit');

submit.addEventListener('click', function(){
  console.log('hello');
  let minElo = parseInt(document.getElementById('minElo').value);
  let maxElo = parseInt(document.getElementById('maxElo').value);
  let xhr = new XMLHttpRequest();
  let url = base_url + "waitGame/joinGame/" + minElo + "/" + maxElo;
  console.log(url);
  xhr.open('GET', url, true);
  xhr.send();
})