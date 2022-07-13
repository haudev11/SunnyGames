let gamePlay = document.getElementById('gamePlay').innerText;
function convert(time){
  if (time.length == 1){
    return "0"+time;
  }
  return time;
}
for (let i = 0; i < 25; i++){
  for (let j = 0; j < 25; j++){
    let item = document.createElement("div");
    item.setAttribute('row', i);
    item.setAttribute('column', j);
    item.classList.add("item");
    item.id = "item" + convert(i.toString()) + convert(j.toString());
    board.appendChild(item);
  }
}
function takeGame(){
        let next = 1;
        for (let i = 0; i < gamePlay.length; i+=4){
          let row = parseInt(gamePlay[i] + gamePlay[i+1]);
          let column = parseInt(gamePlay[i+2] + gamePlay[i+3]);
          let item = document.getElementById("item" +convert(row.toString()) + convert(column.toString()));
          if (next == 1){
            item.innerText = "X";
          }else {
            item.innerText = "O";
          }
          next *= -1;
        }
}
takeGame();