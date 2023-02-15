function myFunction() {
    var x = document.getElementById("myInput");
    if (x.type === "password") {
      x.type = "text";
    } else {
      x.type = "password";
    }
}

const data = {
  labels: [
    'Dimanche',
    'Lundi',
    'Mardi',
    'Mercredi',
    'Jeudi',
    'Vendredi',
    'Samedi'    
  ],
  datasets: [{
    label: document.getElementById('humeur').options[document.getElementById('humeur').selectedIndex].text,
    data: dataHumeur,
    fill: true,

    backgroundColor: 'rgba(0, 197, 255, 0.2)',
    borderColor: 'rgb(0, 197, 255)',
    pointBackgroundColor: 'rgb(0, 197, 255)',
    pointBorderColor: '#fff',
    pointHoverBackgroundColor: 'rgb(0,0,0)',
    pointHoverBorderColor: 'rgb(0, 197, 255)'
  }]
};

//Création du diagramme pour afficher les
const ctx = document.getElementById('myChart');
new Chart(ctx, {
  type: 'radar',
data: data,
options: {  
  scale: {
    min: 0,
  },
  elements: {
    line: {
      borderWidth: 3
     }
   }
  },
});
























  