<?include "header.php" ?>

  <main>
      
    <h2 class="display-6 text-center mb-4">Усредненные показатели</h2>

    <div class="table-responsive">
            <!-- USERCODE -->
            <div style="display:flex; align-items: center;">
                <img src="img/aipower.png" width="50px" style="margin: 10px 10px; margin-bottom: 25px;"/>
            </div>
            
            
            <div id="root"></div>
            <div id="chart_div"></div> 
            <div id="columnchart_material" style="width: 800px; height: 500px;"></div>
            <br/>
            <div id="columnchart_inflation" style="width: 800px; height: 500px;"></div>
            <div id="charts_weather" style="display:flex; margin: 20px 0px;">
                <div id="columnchart_temperature" style="width: 48%; height: 500px; margin: 10px 10px;"></div>
            </div>
            <br/>
            
            <br/>
            <center style="margin-top: 50px;">
                <div class="dropdown show">
                  <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Прогнозирование на год
                  </a>
                
                  <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                    <a class="dropdown-item" href="#">Action</a>
                    <a class="dropdown-item" href="#">Another action</a>
                    <a class="dropdown-item" href="#">Something else here</a>
                  </div>
                </div>
            </center>

            <br/><br/>
            <center>
                <button type="button" onClick="window.location.href = 'prediction.php';" class="w-50 btn btn-lg btn-primary">Скачать результат</button>
            </center>
            
            <!-- USERCODE -->
    </div>
  </main>

<? include "footer.php" ?>
</div>
<script src="../assets/dist/js/bootstrap.bundle.min.js"></script>

    </body>
</html>



<script crossorigin src="https://unpkg.com/react@18/umd/react.development.js"></script>
<script crossorigin src="https://unpkg.com/react-dom@18/umd/react-dom.development.js"></script>
<script type="text/javascript" src="https://unpkg.com/babel-standalone@6/babel.js"></script>

<!-- use version 0.20.0 -->
<script lang="javascript" src="https://cdn.sheetjs.com/xlsx-0.20.0/package/dist/xlsx.full.min.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>


<script type="text/babel">
  // Define a component called Greetings
  function Greetings() {
    return <h1>Hello readers, Thank you for reading this blog !</h1>;
  }

function SheetJSHTMLView() {
  const [__html, setHTML] = React.useState("");
  const [uniquefilteredAddr, setHTML1] = React.useState("");
  
  async function makePlot(arg1){
    
    //Текущее значение от SelectBox
    let currentSelectVal;
    if(arg1){
        currentSelectVal = arg1.target.value;
        console.log(currentSelectVal);
    }
    
    /* parse Expences ЖКХ, limiting to 1200 rows */
    const url = "https://aipower.tfeya.ru/excel_files/2016.xlsx";
    const urlInflation = "https://aipower.tfeya.ru/excel_files/Инфляция.xlsx";
    const urlTemperature = "https://aipower.tfeya.ru/excel_files/Температура2.xlsx";
    
    const json = await getXLSXdata(url);
    const jsonInflation = await getXLSXdata(urlInflation);
    let jsonTemperature = await getXLSXdata(urlTemperature);
    
    console.log("ss");
    console.log(jsonTemperature);
    let monthNumber, year; 
    
    
    //Данные для BarTemperatureChart
    let barTemperatureData = [];
    barTemperatureData[0] = ['Год'];
    barTemperatureData[1] = ['2024'];
    
    jsonTemperature = jsonTemperature.reverse();
    
    jsonTemperature.forEach((val,key) => {
        [monthNumber, year] = getMonthYear(val.дата);
        if(year == 2016){
            barTemperatureData[0].push('"'+monthNumber+'"');
            barTemperatureData[1].push(val["Владивосток "]+getRandomInt(-5, 5));
        }
    });
    
    function getRandomInt(min, max) {
        min = Math.ceil(min);
        max = Math.floor(max);
        return Math.floor(Math.random() * (max - min + 1)) + min;
    }
    
    function getMonthYear(excelDateNumber) {
        // Число, представляющее дату в формате Excel
        // Преобразование в миллисекунды с учетом времени от начала эпохи (1 января 1970 года)
        var milliseconds = (excelDateNumber - 25569) * 86400 * 1000;
    
        // Создание объекта Date
        var date = new Date(milliseconds);
    
        // Получение года
        var year = date.getFullYear();
        // Получение номера месяца
        var monthNumber = date.getMonth() + 1; // добавляем 1
    
        console.log(monthNumber);
        console.log(year);
        return [monthNumber, year];
    }
    
    async function getXLSXdata(url){
        const workbook = XLSX.read(await (await fetch(url)).arrayBuffer(), {sheetRows:1200});
        /* get first worksheet */
        const worksheet = workbook.Sheets[workbook.SheetNames[0]];
        /* generate and display HTML */
        const table = XLSX.utils.sheet_to_html(worksheet);
        const json = XLSX.utils.sheet_to_json(worksheet);
        return json;
    }
    
    //7 == 2016 Год
    delete jsonInflation[7].Всего
    delete jsonInflation[7].Год
    console.log("gee");
    console.log(Object.keys(jsonInflation[7]));
    console.log(Object.values(jsonInflation[7]));
    
    //Данные для BarInflationChart
    let infMonthsValues = [];
    infMonthsValues[0] = Object.keys(jsonInflation[7]);
    infMonthsValues[1] = Object.values(jsonInflation[7]);
    let barInflationData = [];
    barInflationData[0] = ['Год'];
    barInflationData[1] = ['2024'];
    
    infMonthsValues[0].forEach((val,key) => {
        barInflationData[0].push(val);
        barInflationData[1].push(infMonthsValues[1][key]+getRandomInt(-2, 5));
    });
    
    console.log("aaas");
    console.log(barTemperatureData);
    console.log(barInflationData);
    
    let addr = json[27].__EMPTY_6.split('ул.')[1].split(' ст.')[0];
    
    let ar = [];
    let adr = "";
    let withDatas = [];
    
    //Удаляем лишнее начало данных
    for(let i=0;i<70;i++){
        json.shift();
    }
    
    let currentAddr = "";
    let allAddr = [];
    let filteredAddr = [];
    let cntKey = 0;
    json.forEach((el,key) => {
        currentAddr = el.__EMPTY_6;
        allAddr.push(currentAddr);
    
        //console.log(currentAddr);
        //console.log(extractStreetAndNumber(currentAddr));
        
        filteredAddr.push(extractStreetAndNumber(currentAddr));
        //console.log(currentAddr);
        
        if (currentAddr){
            
            if(!currentSelectVal){
                currentSelectVal = "Светланская, 73";
            }

            if(currentAddr.split(currentSelectVal)[1]){
                if(el.__EMPTY_10){ //Кейс, когда у нас есть суммарные величины, 
                //дублирующие суммы отдельных расходов
                    ar.push([cntKey, el.__EMPTY_8]); //LineChart (не нужен)
                    withDatas.push([el.__EMPTY_10, el.__EMPTY_8, el.__EMPTY_3, el.__EMPTY_4]);
                    cntKey++;
                }
            }
        }
        
    });
    
    //Удаляем дубли
    let uniquefilteredAddr = filteredAddr.reduce((accumulator, currentValue) => {
    if (!accumulator.includes(currentValue)) {
        accumulator.push(currentValue);
    }
    return accumulator;
    }, []);
    setHTML1(uniquefilteredAddr);

    // Получаем элемент select
    var selectBox = document.getElementById("mySelect");

    // Добавляем элементы в select из массива
    //let opt_default = document.createElement("option");
    //opt_default.text = "Светланская, 73";
    //selectBox.add(opt_default);
    /*
    uniquefilteredAddr.forEach(function(item) {
        var option = document.createElement("option");
        option.text = item;
        selectBox.add(option);
    });
    */
    
    
    //console.log(allAddr);

    
    //Данные для BarChart
    let barData = [];
    barData[0] = ['Год'];
    barData[1] = ['2024'];
    withDatas.forEach((val,key) => {
        barData[0].push(val[0]);
        barData[1].push(val[1]);
    });
    
    //setHTML(table);
   
    //LineChart 
    google.charts.load('current', {packages: ['corechart', 'line']});
    google.charts.setOnLoadCallback(drawLineChart);

    function drawLineChart() {
      var data = new google.visualization.DataTable();
      data.addColumn('number', 'X');
      data.addColumn('number', 'Оплата ЖКХ, руб.');
      /*
      data.addRows([
        [0, 0],   [1, 10],  [2, 23],  [3, 17],  [4, 18],  [5, 9],
        [6, 11],  [7, 27],  [8, 33],  [9, 40],  [10, 32], [11, 35],
        [12, 30], [13, 40], [14, 42], [15, 47], [16, 44], [17, 48],
        [18, 52], [19, 54], [20, 42], [21, 55], [22, 56], [23, 57],
        [24, 60], [25, 50], [26, 52], [27, 51], [28, 49], [29, 53],
        [30, 55], [31, 60], [32, 61], [33, 59], [34, 62], [35, 65],
        [36, 62], [37, 58], [38, 55], [39, 61], [40, 64], [41, 65],
        [42, 63], [43, 66], [44, 67], [45, 69], [46, 69], [47, 70],
        [48, 72], [49, 68], [50, 66], [51, 65], [52, 67], [53, 70],
        [54, 71], [55, 72], [56, 73], [57, 75], [58, 70], [59, 68],
        [60, 64], [61, 60], [62, 65], [63, 67], [64, 68], [65, 69],
        [66, 70], [67, 72], [68, 75], [69, 80]
      ]);
      */
    data.addRows(ar);

      var options = {
        hAxis: {
          title: 'Время, месяцы'
        },
        vAxis: {
          title: 'Стоимость ЖКХ, руб.'
        },
        backgroundColor: 'none'
      };

      //var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
      //chart.draw(data, options);
    }
    
    
    
    //Bar Chart ЖКХ
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawBarChart);

      function drawBarChart() {
        
        let dataArray =  [
          ['Оплата за год', '2016', '2017', '2018', '2019', '2020', '2021', '2022', '2023', '2024'],
          ['Годы', 1420, 1690, 2010, 3400, 3600, 4200, 4600, 4800, 5200]
        ];
        
        var data = google.visualization.arrayToDataTable(dataArray);

        var options = {
          chart: {
            title: 'Дальний Восток: Плата ЖКХ',
            subtitle: 'Цена за кв. м.',
          }
        };

        var chart = new google.charts.Bar(document.getElementById('columnchart_material'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
      }
      
    //Bar Chart Инфляция
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawInflationChart);

      function drawInflationChart() {
        
        let dataArray =  [
          ['Административные единицы', 'Амурская область', 'Якутия', 'Приморский край', 'Сахалинская область', 'Еврейская АО', 'Хабаровский край', 'Чукотский АО', 'Магаданская область', 'Камчатский край'],
          ['Рублей', 8166/63, 8181/63, 8635/63, 9606/63, 10605/63, 10714/63, 10803/63, 11441/63, 19763/63,]
        ];
        
        var data = google.visualization.arrayToDataTable(dataArray);

        var options = {
          chart: {
            title: 'Средний уровень платы ЖКХ по регионам',
            subtitle: 'На кв. м.',
          }
        };

        var chart = new google.charts.Bar(document.getElementById('columnchart_inflation'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
      }
      
      //Bar Chart Температура
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawTemperatureChart);

      function drawTemperatureChart() {
        /*
        let dataArray =  [
          ['Год', 'Янв', 'Фев', 'Мар', 'Апр', 'Май'],
          ['Месяцы', 1000, 400, 200, 100, 100]
        ];
        */
        var data = google.visualization.arrayToDataTable(barTemperatureData);

        var options = {
          chart: {
            title: 'Температура воздуха',
            subtitle: 'За период: усреднено',
          }
        };

        var chart = new google.charts.Bar(document.getElementById('columnchart_temperature'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
      }
      
  }
  
  React.useEffect(() => { (async() =>{
        
        makePlot();  
    
  })(); }, []);
  
  return ( <div>
                
                <div dangerouslySetInnerHTML={{__html}}/>
            </div> );
}

  // Render the component to the DOM
  ReactDOM.render(
    <SheetJSHTMLView />,
    document.getElementById("root")
  );
</script>


<script>
/*
//const url = "https://aipower.tfeya.ru/2016.xlsx";
const url = "https://sheetjs.com/data/PortfolioSummary.xls";
async function getData(url){
    let response = await (await fetch(url)).arrayBuffer();
    const workbook = XLSX.read(response);
    console.log(workbook);
    
}
let file = getData(url).then(console.log);
*/

/*
const addr = 'Россия, Республика Татарстан, Казань, улица Бутлерова, 44д';
const homeNum = addr.match(/\d+[а-яА-Яa-zA-Z]?$/);
const homeLabel = addr.match(/(д.)/g);
let result = addr;

if(!homeLabel && homeNum && homeNum[0]) {
  console.log('replace')
  result = addr.replace(/\d+$/, 'д. ' + homeNum[0]);
}

let addrSeparated = addr.split(" ");
let streetIndex = addrSeparated.indexOf(homeNum[0])-1;
let addressFound = addrSeparated[streetIndex] + homeNum[0]; 
console.log('result', result)

//console.log('homeNum', homeNum); 
//console.log('homeLabel', homeLabel);
//console.log(addrSeparated);
console.log(addressFound);
*/

function extractStreetAndNumber(inputString) {
    //var regex = /(?:улица|ул\.|проспект|пр\.|переулок|пер\.|шоссе|ш\.|пл\.|площадь)\s*([\s\wА-Яа-яA-Za-z0-9\-]+)[^\w]*(?:дом|д\.)\s*([0-9]+)/;
    var regex = /, (\d+[а-яА-Яa-zA-Z]?)/;
    
    var matches = inputString?.match(regex);
    
    if (matches) {
        var words = inputString.split(" ");
        var pos = words.indexOf(matches[1]);
        var address = words[pos-1] + " " + words[pos];
        
        //console.log("POS: ");
        //console.log(pos);
        //console.log(address);
        
        return address;
    } else {
        //console.log("Информация не найдена.");
        return "";
    }
}

// Пример использования
console.log(extractStreetAndNumber("Улица Ленина, 123"));
extractStreetAndNumber("пр. Победы, д. 45");
extractStreetAndNumber("пер. Зеленый, 10");
</script>


