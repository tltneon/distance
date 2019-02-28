<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<script src="https://api-maps.yandex.ru/2.1/?apikey=c2ea6378-b62a-402a-929b-e9eb89630770&lang=ru_RU" type="text/javascript"></script>
</head>

<body>
<p>Вывод координат по названию города. Введите название:</p>
  <input type="input" id="inputline" />
  <input type="button" onclick="searchPoint()" value="Search"/>
	<div>	
		<div>Название: <span id="resultName">-</span></div>
		<div>Широта: <span id="resultLat">-</span></div>
		<div>Долгота: <span id="resultLong">-</span></div>
		<input type="button" onclick="savePoint()" value="Добавить в БД"/>
	</div>
	
	<hr />
	
<p>Расчёт дистанции:</p>
<div>
<div><select id="selector1"></select><input type="input" id="point1x" disabled /><input type="input" id="point1y" disabled /></div>
<div><select id="selector2"></select><input type="input" id="point2x" disabled /><input type="input" id="point2y" disabled /></div>
</div>
  <input type="button" onclick="btnAction('checkdistance1')" value="Distance"/>
  <input type="button" onclick="btnAction('checkdistance2')" value="Distance2"/>
  <input type="button" onclick="btnAction('checkdistance3')" value="Distance3"/>
  <input type="button" id="remover" onclick="removePoint(0)" value="Remove ()"/>
	<div id="result2"></div>
</body>


	<script type="text/javascript">
		apikey = "c2ea6378-b62a-402a-929b-e9eb89630770";
		lastResult = {};
		// Переменная со списком точек. Заполнение из БД.
		pointList = [<?php
			include('sqlclass.php');
			$sql = new SQL();
			if($sql->connect()){
				$result = $sql->query("SELECT * FROM `savedpoints`");
				while ($row = mysqli_fetch_assoc($result))
					echo "{name: '" .$row["name"]."', latitude: " .$row["latitude"].", longitude: " .$row["longitude"]."}, ";
			}
		?>];
		
		/* Объединённая функция для способов определения расстояния между точками */
		function btnAction(actiontype){
			switch(actiontype){
				case "checkdistance1": 
					x1 = parseFloat(document.getElementById("point1x").value) * Math.PI / 180;
					y1 = parseFloat(document.getElementById("point1y").value) * Math.PI / 180;
					x2 = parseFloat(document.getElementById("point2x").value) * Math.PI / 180;
					y2 = parseFloat(document.getElementById("point2y").value) * Math.PI / 180;
					document.getElementById("result2").innerHTML = "DISTANCE1:" + Math.ceil(Math.acos(Math.sin(y1)*Math.sin(y2)+Math.cos(y1)*Math.cos(y2)*Math.cos(x2-x1))*6371) + " KM";
					break;
					
				case "checkdistance2":
					ymaps.geocode(document.getElementById("selector1").options[document.getElementById("selector1").selectedIndex].text).then(function (res) {
						var coords1 = res.geoObjects.get(0).geometry.getCoordinates();
						ymaps.geocode(document.getElementById("selector2").options[document.getElementById("selector2").selectedIndex].text).then(function (res) {
							var coords2 = res.geoObjects.get(0).geometry.getCoordinates();
							document.getElementById("result2").innerHTML = "DISTANCE2:" + Math.ceil(ymaps.coordSystem.geo.getDistance(coords1, coords2)/1000) + " KM";
						});
					});
					break;
					
				case "checkdistance3": 
					x1 = parseFloat(document.getElementById("point1x").value) * Math.PI / 180;
					y1 = parseFloat(document.getElementById("point1y").value) * Math.PI / 180;
					x2 = parseFloat(document.getElementById("point2x").value) * Math.PI / 180;
					y2 = parseFloat(document.getElementById("point2y").value) * Math.PI / 180;
					document.getElementById("result2").innerHTML = "DISTANCE3:" + Math.ceil(Math.sqrt(Math.pow(x2-x1, 2)+Math.pow(y2-y1, 2))*6371) + " KM";
					break;
			}
		}
		// Функция ищет координаты точки + обновляет поля
		function searchPoint(){
			if(document.getElementById("inputline").value.length > 0)
				sendRequest("get", "https://geocode-maps.yandex.ru/1.x/", "format=json&apikey="+apikey+"&geocode="+document.getElementById('inputline').value+"&results=1", function(){
					json = JSON.parse(request.responseText);
					if(json["response"]["GeoObjectCollection"]["featureMember"].length == 0){
						document.getElementById("resultName").innerHTML = "Указанный пункт не найден";
						document.getElementById("resultLat").innerHTML = "-";
						document.getElementById("resultLong").innerHTML = "-";
					}
					else {
						pos = json["response"]["GeoObjectCollection"]["featureMember"][0]["GeoObject"]["Point"]["pos"].split(" ");
						lastResult = {
							name: document.getElementById("inputline").value,
							latitude: pos[0],
							longitude: pos[1],
						};
						document.getElementById("resultName").innerHTML = document.getElementById("inputline").value;
						document.getElementById("resultLat").innerHTML = pos[0];
						document.getElementById("resultLong").innerHTML = pos[1];
					}
				});
		}
		
		// Функция для сохранения точки в БД + обновляет список в селекторе
		function savePoint(){
			if(lastResult.name != null){
				pointList[pointList.length] = lastResult;
				
				buildSelector('selector1');
				buildSelector('selector2');
				
				sendRequest("post", "sqlclass.php", "action=query&query=INSERT INTO savedpoints (name, latitude, longitude) VALUES ('"+lastResult.name+"', "+lastResult.latitude+", "+lastResult.longitude+")", function(){});
			}
		}
		// Функция для удаления точки в БД + обновляет список в селекторе
		function removePoint(id){
			if(id){
				sendRequest("post", "sqlclass.php", "action=query&query=DELETE FROM `savedpoints` WHERE `savedpoints`.`name` = '"+pointList[id].name+"';", function(){});
				console.log("DELETE FROM `savedpoints` WHERE `savedpoints`.`name` = '"+pointList[id].name+"';");
				
				pointList[id] = null;
				
				buildSelector('selector1');
				buildSelector('selector2');
			}
		}
		
		// Функция для формирования и отправки запроса
		function sendRequest(method, path, args, handler){
			request = new XMLHttpRequest();
			if(request){
				request.onreadystatechange = function()
				{
						if (request.readyState == 4)
						{
							handler(request);
						}
				}
				
				if (method.toLowerCase() == "get" && args.length > 0)
				path += "?" + args;
				
				request.open(method, path, true);
				
				if (method.toLowerCase() == "post")
				{
					request.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=utf-8");
					request.send(args);
				}
				else
				{
					request.send(null);
				}
			}
		}
		
		// Функция обновления полей и кнопки удаления
		function changeOption(elem){
			var selectedID = elem.options[elem.selectedIndex].value;
			elem.parentNode.children[1].value = pointList[selectedID].latitude;
			elem.parentNode.children[2].value = pointList[selectedID].longitude;
			document.getElementById("remover").value = "Remove (" + pointList[selectedID].name + ")";
			document.getElementById("remover").onclick = function(){removePoint(selectedID)};
			
			// document.getElementById("remover").attachEvent('onclick', removePoint(elem.selectedIndex));
		}
		// Функция для обновления селектора и листенер для него
		function buildSelector(selectorID, init = false){
			var selector = document.getElementById(selectorID);

			if(init == false)
				for (i = selector.options.length; i > -1; i--)
					selector.options[i] = null;
			
			for (i = 0; i < pointList.length; i++){
				if(pointList[i]){
					var newOption = new Option(pointList[i].name, i);
					selector.options[selector.options.length] = newOption;
				}
			}
			
			if(init)
				document.getElementById(selectorID).addEventListener("change", function(){changeOption(this);});
			
			changeOption(document.getElementById(selectorID));
		}
		buildSelector('selector1', true);
		buildSelector('selector2', true);
	</script>

</html>