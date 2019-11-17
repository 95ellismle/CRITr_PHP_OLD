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

        setUpClickHandler();
    var coords = {};

    view.on("pointer-move", updateCoords);

    sketchViewModel.on("create", handleEventCreation);
    sketchViewModel.on("create-complete", addGraphic);
    sketchViewModel.on("update-complete", updateGraphic);
    sketchViewModel.on("update-cancel", updateGraphic);

    // Handles updating the coords dict to allow the sketchViewModel.on("create")
    //  access the lat and long
    function updateCoords(evt) {
      pt = view.toMap({ x: evt.x, y: evt.y });

      coords = {"lat": pt.latitude.toFixed(3),
                "lon": pt.longitude.toFixed(3),
                "x": event.x,
                "y": event.y};
    }

    // Handles adding shapes (pins) to the map
    function addGraphic(event) {
      const graphic = new Graphic({
        geometry: event.geometry,
        symbol: sketchViewModel
      });
      graphicsLayer.add(graphic);
    }

    // logic for handling the creation of pins
    function handleEventCreation(event) {
      if (event.state === "complete") {
         // Check the state of the ok button...
         // If the user would like to submit then add to databse
      }
    }

    function updateGraphic(event) {
      console.log(event);
      var graphic = new Graphic({
        geometry: event.geometry,
        symbol: editGraphic.symbol
      });
      graphicsLayer.add(graphic);

      editGraphic = null;
    }

    function setUpClickHandler() {
        view.on("click", function (event) {
        view.hitTest(event).then( function(response) {
          var results = response.results;
            if (results.length > 0) {
              for (var i=0; i< results.length; i++) {
                // Check if we're editting a graphic
                if (!editGraphic && results[i].graphic.layer.id === "tempGraphics") {
                editGraphic = results[i].graphic;

                graphicsLayer.remove(editGraphic);
                sketchViewModel.update(editGraphic);
                break
                }
              }
            }
          });
        });
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

    });

    // // Set the toggle between satelite and standard map
    // var basemapToggle = new BasemapToggle({
    //   view: view,
    //   secondMap: "satellite"
    // });
    // view.ui.add(basemapToggle,"bottom-right");


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
  /*
    // Will show the lattitude and longitude of the cursor
    var coordsWidget = document.createElement("div");
    coordsWidget.id = "coordsWidget";
    coordsWidget.className = "esri-widget esri-component";
    coordsWidget.style.padding = "7px 15px 5px";

    view.ui.add(coordsWidget, "bottom-right");

    function showCoordinates(pt) {
      var coords = "Lat/Lon " + pt.latitude.toFixed(3) + " " + pt.longitude.toFixed(3) +
          " | Scale 1:" + Math.round(view.scale * 1) / 1 +
          " | Zoom " + view.zoom;
      coordsWidget.innerHTML = coords;
    }

    view.watch("stationary", function(isStationary) {
      showCoordinates(view.center);
    });

    view.on("pointer-move", function(evt) {
      showCoordinates(view.toMap({ x: evt.x, y: evt.y }));
    });
  */
});
