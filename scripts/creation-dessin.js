const canvas = document.getElementById("drawing-board");
const toolbar = document.getElementById("toolbar");
const ctx = canvas.getContext("2d");

const canvasOffSetX = canvas.offsetLeft;
const canvasOffSetY = canvas.offsetTop;

canvas.width = window.innerWidth - canvasOffSetX;
canvas.height = window.innerHeight - canvasOffSetY;

let isPainting = false;
let lineWidth = 5;
let startX;
let startY;

const draw = (e) => {
    if(!isPainting){
        return;
    }
    ctx.lineWidth = lineWidth;
    ctx.lineCap = "round";

    ctx.lineTo(e.clientX - canvasOffSetX, e.clientY);
    ctx.stroke;
}

toolbar.addEventListener("click", e => {
    if (e.target.id == "clear"){
        ctx.clearRect(0, 0, canvas.width, canvas.height);
    }
});
toolbar.addEventListener("change", e => {
    if (e.target.id == "stroke"){
        ctx.strokeStyle = e.target.value;
    }
    if (e.target.id == "lineWidth"){
        ctx.lineWidth = e.target.value;
    }
});

canvas.addEventListener("mousedown", (e) => {
    isPainting = true;
    startX = e.clientX;
    startY = e.clientY;
});
canvas.addEventListener("mouseup", (e) => {
    isPainting = false;
    ctx.stroke();
    ctx.beginPath();
});