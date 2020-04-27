jQuery(function($){

/* ##################################
    男女別グラフ
  ################################## */

if($("#stiBarChart").length){

if(!sti_widget_arr.membersSexArr){
  sti_widget_arr.membersSexArr = 0;
}
if(!sti_widget_arr.membersSexArr.female){
  sti_widget_arr.membersSexArr.female = 0;
}
var
  a = Math.round(Math.max(sti_widget_arr.membersSexArr.male
      ,sti_widget_arr.membersSexArr.female)*1.3);

if(
  a == sti_widget_arr.membersSexArr.male
  || a == sti_widget_arr.membersSexArr.female
){
  a *= 2;
}

var ctx = document.getElementById("stiBarChart").getContext("2d");
var myBar = new Chart(ctx, {
  type: 'bar',//◆棒グラフ
  data: {//◆データ
    labels: ['男性','女性'],//ラベル名
    datasets: [{//データ設定
      data: [
        sti_widget_arr.membersSexArr.male,
        sti_widget_arr.membersSexArr.female
      ],//データ内容
       backgroundColor: ['rgb(115, 179, 254)','rgb(255, 139, 227)'],
       barPercentage: 0.8,//棒グラフ幅
       categoryPercentage: 0.8,//棒グラフ幅
    }]
  },
  options: {//◆オプション
    responsive: true,
    maintainAspectRatio: false,
    responsive: true,//グラフ自動設定
    plugins:{
      datalabels: {
        font:{
          size:50
        },
        color:'white'
      }
    },
    legend: {//凡例設定
      display: false,//表示設定
    },
    scales: {//軸設定
      yAxes: [{//y軸設定
        display: true,//表示設定
        ticks: {//最大値最小値設定
          min: 0,//最小値
          max: a,//最大値
          fontSize: 18,//フォントサイズ
          stepSize: 1//軸間隔
        },
      }],
      xAxes: [{//x軸設定
        display: true,//表示設定
        ticks: {
          fontSize: 18//フォントサイズ
        },
      }],
    },
  }
});
}

/* ##################################
    年齢別グラフ
  ################################## */

if($("#stiPieChart").length){

  var ctx = document.getElementById("stiPieChart");
  var myPieChart = new Chart(ctx, {
    type: 'pie',
    data: {
      labels: ["10代", "20代","30代", "40代","50代", "60代","70代", "80代"],
      datasets: [{
          data: []
      }]
    },
    options: {
      maintainAspectRatio: false,
      plugins: {
        datalabels: {
          formatter: function(value, context) {
          },
          font:{
            size:50
          },
          color:'white'
        },
        colorschemes: {
          scheme: 'brewer.SetOne9'
        }
      }
    }
  });

  for(var i = 0;i<sti_widget_arr.membersAgeArr.length;i++){
    if(sti_widget_arr.membersAgeArr[i] !== 0){
      myPieChart.data.datasets[0].data[i] = sti_widget_arr.membersAgeArr[i];
    }
  }
  myPieChart.update();

}

  /* ##################################
      隊員紹介
    ################################## */

  $('#sti_slick').slick({
    autoplay:true,
    autoplaySpeed:4000,
    dots:true,
    pauseOnFocus: false,
    pauseOnHover: false,
    pauseOnDotsHover: false,
  });




});
