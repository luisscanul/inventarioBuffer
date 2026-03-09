const ctx = document.getElementById('graficaInventario');

new Chart(ctx, {
type: 'bar',
data: {
labels: ['Lunes','Martes','Miércoles','Jueves','Viernes'],
datasets: [
{
label: 'Entradas',
data: [12,19,7,14,10]
},
{
label: 'Salidas',
data: [5,8,6,9,4]
}
]
}
});