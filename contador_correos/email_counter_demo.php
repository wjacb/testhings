<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Impacto de Adjuntos en Email</title>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>

body{
    font-family: Arial;
    background:#f4f4f4;
    text-align:center;
}

.container{
    background:white;
    width:700px;
    margin:auto;
    padding:20px;
    border-radius:10px;
    box-shadow:0px 0px 10px #ccc;
}

input{
    width:120px;
    padding:5px;
    margin:8px;
}

button{
    padding:10px 20px;
    background:#c0392b;
    color:white;
    border:none;
    border-radius:5px;
    cursor:pointer;
}

button:hover{
    background:#e74c3c;
}

canvas{
    margin-top:30px;
}

</style>

</head>

<body>

<div class="container">

<h2>Simulador de impacto de correos con adjuntos</h2>

<p>Visualización de usar Reply-All con adjuntos</p>

<label>Tamaño del mensaje (MB)</label><br>
<input id="size" type="number" value="3"><br>

<label>Número de destinatarios</label><br>
<input id="recipients" type="number" value="3"><br>

<label>Réplicas de buzón</label><br>
<input id="replicas" type="number" value="2"><br>

<label>Número de respuestas / reenvíos</label><br>
<input id="forwards" type="number" value="1"><br>

<label>Días de retención de backup</label><br>
<input id="days" type="number" value="30"><br>

<br>

<button onclick="calcular()">Calcular impacto</button>

<h3 id="resultado"></h3>

<canvas id="chartCopias"></canvas>

<canvas id="chartBackup"></canvas>

</div>

<script>

let chartCopias;
let chartBackup;

function calcular(){

let size = parseFloat(document.getElementById("size").value);
let recipients = parseInt(document.getElementById("recipients").value);
let replicas = parseInt(document.getElementById("replicas").value);
let forwards = parseInt(document.getElementById("forwards").value);
let days = parseInt(document.getElementById("days").value);



let copiasIniciales = recipients * (replicas + 1);
let copiasRespuestas = copiasIniciales * forwards;
let totalCopias = copiasIniciales + copiasRespuestas;

let almacenamiento = totalCopias * size;

let nrocorreos = 50000 / almacenamiento;


document.getElementById("resultado").innerHTML =
"Copias totales: " + totalCopias +
"<br>Espacio ocupado por el correo: " + almacenamiento.toFixed(2) + " MB"
"<br>Número de correos para llegar al límite de 50 Gigas: " + nrocorreos.toFixed(2);



/* -------- GRAFICO 1 COPIAS ---------- */

let data1 = {
labels:["Inicial","Respuestas"],
datasets:[{
label:"Copias de correo",
data:[copiasIniciales,copiasRespuestas],
borderWidth:1
}]
};

if(chartCopias){
chartCopias.destroy();
}

chartCopias = new Chart(
document.getElementById('chartCopias'),
{
type:'bar',
data:data1,
options:{
plugins:{
title:{
display:true,
text:"Multiplicación de correos"
}
}
}
}
);



/* -------- GRAFICO 2 BACKUP EXPONENCIAL ---------- */

let labels = [];
let dataBackup = [];

let acumulado = 0;

for(let i=1;i<=days;i++){

acumulado += almacenamiento;

labels.push("Día "+i);
dataBackup.push(acumulado);

}


let data2 = {
labels:labels,
datasets:[{
label:"Consumo acumulado de backup (MB)",
data:dataBackup,
fill:false,
tension:0.1
}]
};

if(chartBackup){
chartBackup.destroy();
}

chartBackup = new Chart(
document.getElementById('chartBackup'),
{
type:'line',
data:data2,
options:{
plugins:{
title:{
display:true,
text:"Impacto acumulado en almacenamiento de backup"
}
}
}
}
);

}

</script>

</body>

</html>