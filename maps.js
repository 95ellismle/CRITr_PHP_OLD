function getData(xmin, xmax, ymin, ymax, func) {
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
			func(data);
		},
		error: function( jqXhr, textStatus, errorThrown ){
			console.log( errorThrown );
		}
	});
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
      style: "square",
      color: "#8A2BE2",
      size: "16px",
      outline: { // autocasts as new SimpleLineSymbol()
        color: [255, 255, 255],
         width: 3
      }
    };
	
	

    view.when(function() {
        // Create the add incident button
        const sketchViewModel = new SketchViewModel ({
            view,
            layer: graphicsLayer,
            pointSymbol,
        });
		
		var coords = {};
		view.on("pointer-move", updateCoords);

		sketchViewModel.on("create", handleEventCreation);

		// Handles updating the coords dict to allow the sketchViewModel.on("create")
		//  access the lat and long
		function updateCoords(evt) {
		  pt = view.toMap({ x: evt.x, y: evt.y });

		  coords = {"lat": pt.latitude.toFixed(3),
					"lon": pt.longitude.toFixed(3),
					"x": event.x,
					"y": event.y};
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
			   	console.log);
		
    }); // End view.when
	
	view.on("drag", function() {
			console.log("BOB");
			getData(view.extent.xmin,
					view.extent.xmax,
					view.extent.ymin,
					view.extent.ymax,
				   	console.log);
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
