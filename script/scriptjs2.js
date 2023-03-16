const data2 = {
    labels: dataLibelleDonught,
    datasets: [{
      label: 'Nombre de fois que l\'humeur est revenue ',
      data: dataCountDonught,
      backgroundColor: [
        '#ff7b7b',
    '#9bff9b' ,
    '#9797ff' ,
    '#ffff8a' ,
    '#a6ffff' ,
    '#ff9fff' ,
    '#C0C0C0' ,
    '#808080' ,
    '#000000' ,
    '#feb52e' ,
    '#900000',
    '#008000' ,
    '#000080' ,
    '#808000' ,
    '#800080' ,
    '#008080',
    '#FFC0CB',
    '#ff71b8' ,
    '#71ffb8' ,
    '#80bffe' ,
    '#dbffb7' ,
    '#d3b6f0',
    '#FF8000' ,
    '#d8d8ff' ,
    '#ffeaea' ,
    '#e9ffe9' 
      ],
      hoverOffset: 70
    }]
  };
  
  //Création du diagramme pour afficher les
  const ctx2 = document.getElementById('myChart2');
  new Chart(ctx2, {
    type: 'doughnut',
    data: data2,
  });



  