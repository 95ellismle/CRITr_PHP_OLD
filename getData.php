function getData(xmin, xmax, ymin, ymax, func) {
	var result = "";
	$.ajax({
		url: 'getData.php',
		dataType: 'json',
		type: 'post',
		contentType: 'application/x-www-form-urlencoded',
		data: {'xmin': xmin,
			   'xmax': xmax,
			   'ymin': ymin,
			   'ymax': ymax},
		success: function(data){
			result = func(data);
		},
		error: function( jqXhr, textStatus, errorThrown ){
			console.log( errorThrown );
		}
	});

	return result;
}


function cancelCreate(){};
function submitReport(){};

require([
	// The map
	"esri/Map",
	"esri/views/MapView",
	// // Basemap toggle (satelite and nav view)
	// "esri/widgets/BasemapToggle",
	// Widget to find location of user
	"esri/widgets/Locate",
	// Widget for Graphics
	"esri/Graphic",
	"esri/layers/GraphicsLayer",
	"esri/widgets/Sketch/SketchViewModel",
],

		function(Map, MapView, Locate, Graphic,
				  GraphicsLayer, SketchViewModel) {

	let editGraphic;

	// Add the drop pin functionality
	const graphicsLayer = new GraphicsLayer({
		id: "dropPins"
	});

	const map = new Map({
		basemap: "streets-navigation-vector",
		layers: [graphicsLayer]
	});

	// Set the map view and zoom to the Milnrow and Newhey
	const view = new MapView({
		container: "viewDiv",
		map: map,
		center: [-2.105367, 53.604835], // longitude, latitude
		zoom: 15
	});

	// Create the tools for dropping pins
	const pointSymbol = {
		type: "simple-marker", // autocasts as new SimpleMarkerSymbol()
		style: "circle",
		color: "#8A2BE2",
		size: "13px",
		outline: {'color': '#000000',
				  'width': '1px'},
	};
	const activityColors = {'Littering': '#a6cee3',
							'Loitering': '#1f78b4',
							'Graffiti': '#b2df8a',
							'Parking': '#33a02c',
							'Speeding': '#fb9a99',
							'': '#e31a1c',	'': '#fdbf6f',
							'': '#ff7f00', '': '#cab2d6',
							'': '#6a3d9a', '': '#ffff99',
							'': '#b15928'};
	// Create popup template
	var popupTemplate = {
		title: "<h2>{name}</h2>",
		content: function() {
			hideActButton();
			return "The incident of <b>{name}</b> was reported at <b>{reportedTime}</b>.{details}{img}";
		}
	};
	function createDetails(deets) {
		if (deets == "") {
			return "<br><br>There are no extra details.";
		} else {
			return "<br><br>The details that came with it were:<br><br><p style='width: 80%; float: right'>"+deets+"</p>.";
		}
	}
	function createImg(path) {
		if (path == "") {
			return "";
		} else {
			return "<br><br><img height=\"80px\" width=\"80px\" src=\""+path+"\">";
		}
	}
	function drawAllIncidents(allData) {
		var points = [];
		var symbol = pointSymbol;
		for (var i=0; i<allData.length; i++) {
			var data = allData[i];
			// Create attributes
			var attributes = {
				name: data['incidentType'],  // The name of the
				reportedTime: data['timeSubmitted'],
				details: createDetails(data['details']),
				img: createImg(data['photoPath']),
			};
			var point = {
				type: "point",
				longitude: data['longitude'],
				latitude: data['latitude']
			};
			ptCol = activityColors[data['incidentType']];
			symbol['color'] = ptCol;
			//symbol['outline'] = {'color': ptCol};
			var pointGraphic = new Graphic({
				geometry: point,
				symbol: symbol,
				attributes: attributes,
				popupTemplate: popupTemplate,
			});
			//console.log("Drawn "+data['latitude']+data['longitude']);
			points.push(pointGraphic);
			graphicsLayer.add(pointGraphic);
		}

		return points;
	}


	view.when(function() {
		// Create the add incident button
		const sketchViewModel = new SketchViewModel ({
			view,
			layer: graphicsLayer,
			pointSymbol,
		});

		var coords = {};
		view.on("pointer-down", updateCoords);

		sketchViewModel.on("create", handleEventCreation);

		// Handles updating the coords dict to allow the sketchViewModel.on("create")
		//  access the lat and long
		function updateCoords(evt) {
			hideCards();
			pt = view.toMap({ x: evt.x, y: evt.y });

			coords = {"lat": pt.latitude.toFixed(5),
					  "lon": pt.longitude.toFixed(5),
					  "x": pt.x.toFixed(5),
					  "y": pt.y.toFixed(5)};
		}

		// logic for handling the creation of pins
		function handleEventCreation(event) {
			if (event.state === "complete") {
				document.getElementById("submitReportBtn").style.display = "inline-block";
			}
		}

		// After the user presses the OK button
		submitReport = function() {
			window.localStorage.setItem("coords", JSON.stringify(coords));
			window.location.href = "reportIncident.php";
		}


		cancelCreate = function () {
			sketchViewModel.cancel("point");
			backToFullMap();
		}

		// Create the drop a pin button
		var drawPointButton = document.getElementById("pointButton");
		drawPointButton.onclick = function () {
			sketchViewModel.create("point");

			document.getElementById("overlayCards").style.display = 'none';
			document.getElementById("overlayBtn").style.display = 'inline-block';

			var mapView = document.getElementById("viewDiv");
			mapView.style.position = "absolute";
			mapView.style.bottom = 0;
			mapView.style.height = "90%";

			var topBar = document.getElementById("reportIncidentBar");
			topBar.style.display = "block";
			topBar.style.height = "10%";
		}

		getData(view.extent.xmin,
				view.extent.xmax,
				view.extent.ymin,
				view.extent.ymax,
				drawAllIncidents);

	}); // End view.when

	view.on("drag", function() {
		getData(view.extent.xmin,
				view.extent.xmax,
				view.extent.ymin,
				view.extent.ymax,
				drawAllIncidents);
	});

	// Will add a widget to zoom into the user
	var locate = new Locate({
		view: view,
		useHeadingEnabled: false,
		goToOverride: function(view, options) {
			options.target.scale = 1500;  // Override the default map scale
			return view.goTo(options.target);
		}
	});

	view.ui.add(locate, "top-left");	

});

