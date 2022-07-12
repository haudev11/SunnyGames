const base_url = "http://localhost:8000/";
let id = document.getElementById("gameID").innerText;
let whoMove = 1;
let gamePlay = "";
function takeGame(){
  let xhr = new XMLHttpRequest();
  let url = base_url + "live/waite/" + id;
  xhr.open('GET', url, true);
  xhr.onload = function(){
    if (this.status === 200){
      let game = JSON.parse(this.responseText);
      if (game.gamePlay != gamePlay){
        gamePlay = game.gamePlay;
        let next = 1;
        for (let i = 0; i < game.gamePlay.length; i+=4){
          let row = parseInt(game.gamePlay[i] + game.gamePlay[i+1]);
          let column = parseInt(game.gamePlay[i+2] + game.gamePlay[i+3]);
          let item = document.getElementById("item" +convert(row.toString()) + convert(column.toString()));
          if (next == 1){
            item.innerText = "X";
          }else {
            item.innerText = "O";
          }
          next *= -1;
        }
        if (gamePlay / 4 % 2 == 0){
          whoMove = 1;
        } else {
          whoMove = 2;
        }
      }
    }
  }
  xhr.send();
}
const waitMove = setInterval(takeGame,1000);
function convert(time){
  if (time.length == 1){
    return "0"+time;
  }
  return time;
}
function changeTime(){
  let Ctime1 = document.getElementById("timeUserOneD");
  let Ctime2 = document.getElementById("timeUserTwoD");
  let Dtime1 = document.getElementById("timeUserOne");
  let Dtime2 = document.getElementById("timeUserTwo");
  let time1 = parseInt(Ctime1.innerText);
  let time2 = parseInt(Ctime2.innerText);
  console.log(whoMove);
  if (whoMove == 1){
    time1--;
  } else {
    time2--;
  }
  if (time1 <= 0 || time2 <= 0){
    let url = base_url + "live/lose/" + id;
    let xhr = new XMLHttpRequest();
    xhr.open('GET', url, true);
    xhr.send();
    clearInterval(CountTinme);
  }
  Ctime1.innerText = time1.toString();
  Ctime2.innerText = time2.toString();
  let hour1 = (Math.floor(time1/60)).toString();
  let hour2 = (Math.floor(time2/60)).toString();
  let minute1 = (time1%60).toString();
  let minute2 =  (time2%60).toString();
  hour1 = convert(hour1);
  hour2 = convert(hour2);
  minute1 = convert(minute1);
  minute2 = convert(minute2);
  Dtime1.innerText = hour1 + ":" + minute1;
  Dtime2.innerText = hour2 + ":" + minute2;
}
const CountTinme = setInterval(changeTime, 1000);

for (let i = 0; i < 25; i++){
  for (let j = 0; j < 25; j++){
    let item = document.createElement("div");
    item.setAttribute('row', i);
    item.setAttribute('column', j);
    item.classList.add("item");
    item.id = "item" + convert(i.toString()) + convert(j.toString());
    board.appendChild(item);
    item.addEventListener("click",function(){
      let userID = document.getElementById("userID").innerText.toString();
      if (userID == whoMove){
        let row = item.getAttribute('row').toString();
        let column = item.getAttribute('column').toString();
        row = convert(row);
        column = convert(column);
        let xhr = new XMLHttpRequest();
        let move = row + column;
        gamePlay += move;
        let url = base_url + "live/move/" + id + "/" + move;
        xhr.open('GET', url, true);
        xhr.send();
        // if (userID == 1){
        //   item.innerText = "X";
        // } else {
        //   item.innerText = "O";
        // }
        if (whoMove == 1){
          whoMove = 2;
        } else {
          whoMove = 1;
        }
      }
    });
  }
}