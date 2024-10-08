am5.ready(function() {

    // Create root element
    // https://www.amcharts.com/docs/v5/getting-started/#Root_element
    var root = am5.Root.new("sensorData1");
    
    
    // Set themes
    // https://www.amcharts.com/docs/v5/concepts/themes/
    root.setThemes([
      am5themes_Animated.new(root)
    ]);
    
    
    // Create chart
    // https://www.amcharts.com/docs/v5/charts/radar-chart/
    var chart = root.container.children.push(am5radar.RadarChart.new(root, {
      panX: false,
      panY: false,
      startAngle: 160,
      endAngle: 380
    }));
    
    
    // Create axis and its renderer
    // https://www.amcharts.com/docs/v5/charts/radar-chart/gauge-charts/#Axes
    var axisRenderer = am5radar.AxisRendererCircular.new(root, {
      innerRadius: -40
    });
    
    axisRenderer.grid.template.setAll({
      stroke: root.interfaceColors.get("background"),
      visible: true,
      strokeOpacity: 1
    });
    
    var xAxis = chart.xAxes.push(am5xy.ValueAxis.new(root, {
      maxDeviation: 0,
      min: 100,
      max: 1000,
      strictMinMax: true,
      renderer: axisRenderer
    }));
    
    
    // Add clock hand
    // https://www.amcharts.com/docs/v5/charts/radar-chart/gauge-charts/#Clock_hands
    var axisDataItem = xAxis.makeDataItem({});
    
    var clockHand = am5radar.ClockHand.new(root, {
      pinRadius: am5.percent(25),
      radius: am5.percent(65),
      bottomWidth: 30
    })
    
    var bullet = axisDataItem.set("bullet", am5xy.AxisBullet.new(root, {
      sprite: clockHand
    }));
    
    xAxis.createAxisRange(axisDataItem);
    
    var label = chart.radarContainer.children.push(am5.Label.new(root, {
      fill: am5.color(0xffffff),
      centerX: am5.percent(50),
      textAlign: "center",
      centerY: am5.percent(50),
      fontSize: "1.3em"
    }));
    
    axisDataItem.set("value", 100);
    bullet.get("sprite").on("rotation", function () {
      var value = axisDataItem.get("value");
      var text = Math.round(axisDataItem.get("value")).toString();
      var fill = am5.color(0x000000);
      xAxis.axisRanges.each(function (axisRange) {
        if (value >= axisRange.get("value") && value <= axisRange.get("endValue")) {
          fill = axisRange.get("axisFill").get("fill");
        }
      })
    
      label.set("text", Math.round(value).toString());
    
      clockHand.pin.animate({ key: "fill", to: fill, duration: 500, easing: am5.ease.out(am5.ease.cubic) })
      clockHand.hand.animate({ key: "fill", to: fill, duration: 500, easing: am5.ease.out(am5.ease.cubic) })
    });
    
    setInterval(function () {
      axisDataItem.animate({
        key: "value",
        to: 1000,
        duration: 500,
        easing: am5.ease.out(am5.ease.cubic)
      });
    }, 2000)
    
    chart.bulletsContainer.set("mask", undefined);
    
    
    // Create axis ranges bands
    // https://www.amcharts.com/docs/v5/charts/radar-chart/gauge-charts/#Bands
    var bandsData = [{
      title: "Watered",
      color: "#54b947",
      lowScore: 100,
      highScore: 200
    }, {
      title: "Moistured",
      color: "#0f9747",
      lowScore: 200,
      highScore: 400
    }, {
      title: "Dry",
      color: "#f3eb0c",
      lowScore: 400,
      highScore: 600
    }, {
      title: "Super Dry",
      color: "#f04922",
      lowScore: 600,
      highScore: 800
    }, {
      title: "No Water",
      color: "#ee1f25",
      lowScore: 800,
      highScore: 1000
    }];
    
    am5.array.each(bandsData, function (data) {
      var axisRange = xAxis.createAxisRange(xAxis.makeDataItem({}));
    
      axisRange.setAll({
        value: data.lowScore,
        endValue: data.highScore
      });
    
      axisRange.get("axisFill").setAll({
        visible: true,
        fill: am5.color(data.color),
        fillOpacity: 0.8
      });
    
      axisRange.get("label").setAll({
        text: data.title,
        inside: true,
        radius: 15,
        fontSize: "0.9em",
        fill: root.interfaceColors.get("background")
      });
    });
    
    
    // Make stuff animate on load
    chart.appear(1000, 100);
    
    }); // end am5.ready()


// <------------------->
// SENSOR 2

