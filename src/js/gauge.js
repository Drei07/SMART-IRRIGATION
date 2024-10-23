// PH LEVEL GAUGE
am5.ready(function () {
    var SoilSensor1Gauge = am5.Root.new("SoilSensor1");

    // Set themes
    SoilSensor1Gauge.setThemes([
        am5themes_Animated.new(SoilSensor1Gauge)
    ]);

    // Create SoilSensor1 chart
    var SoilSensor1chart = SoilSensor1Gauge.container.children.push(am5radar.RadarChart.new(SoilSensor1Gauge, {
        panX: false,
        panY: false,
        startAngle: 160,
        endAngle: 380
    }));

    // Create axis and its renderer
    var SoilSensor1axisRenderer = am5radar.AxisRendererCircular.new(SoilSensor1Gauge, {
        innerRadius: -40
    });

    SoilSensor1axisRenderer.grid.template.setAll({
        stroke: SoilSensor1Gauge.interfaceColors.get("background"),
        visible: true,
        strokeOpacity: 1
    });

    var SoilSensor1xAxis = SoilSensor1chart.xAxes.push(am5xy.ValueAxis.new(SoilSensor1Gauge, {
        maxDeviation: 0,
        min: 0,
        max: 1000,
        strictMinMax: true,
        renderer: SoilSensor1axisRenderer
    }));

    // Add clock hand
    var SoilSensor1axisDataItem = SoilSensor1xAxis.makeDataItem({});
    var SoilSensor1clockHand = am5radar.ClockHand.new(SoilSensor1Gauge, {
        pinRadius: am5.percent(25),
        radius: am5.percent(65),
        bottomWidth: 30
    });

    var SoilSensor1bullet = SoilSensor1axisDataItem.set("bullet", am5xy.AxisBullet.new(SoilSensor1Gauge, {
        sprite: SoilSensor1clockHand
    }));

    SoilSensor1xAxis.createAxisRange(SoilSensor1axisDataItem);

    var SoilSensor1label = SoilSensor1chart.radarContainer.children.push(am5.Label.new(SoilSensor1Gauge, {
        fill: am5.color(0xffffff),
        centerX: am5.percent(50),
        textAlign: "center",
        centerY: am5.percent(50),
        fontSize: "1.3em"
    }));

    SoilSensor1axisDataItem.set("value", 0);

    SoilSensor1bullet.get("sprite").on("rotation", function () {
        var value = SoilSensor1axisDataItem.get("value");
        var fill = am5.color(0x000000);
        SoilSensor1xAxis.axisRanges.each(function (range) {
            if (value >= range.get("value") && value <= range.get("endValue")) {
                fill = range.get("axisFill").get("fill");
            }
        });

        SoilSensor1label.set("text", Math.round(value).toString());

        SoilSensor1clockHand.pin.animate({
            key: "fill",
            to: fill,
            duration: 500,
            easing: am5.ease.out(am5.ease.cubic)
        });
        SoilSensor1clockHand.hand.animate({
            key: "fill",
            to: fill,
            duration: 500,
            easing: am5.ease.out(am5.ease.cubic)
        });
    });

    // Variables for animation control
    var SoilSensor1current = 0;
    var SoilSensor1target = 0;
    var SoilSensor1animationStartTime = performance.now(); // Timestamp to track animation start time
    var SoilSensor1animationDuration = 1000; // Duration for smooth animation (in milliseconds)

    // Function to update the gauge with animation
    function soilMoisture1Update(level) {
        var parsed = parseFloat(level);
        if (!isNaN(parsed)) {
            SoilSensor1target = parsed; // Set the new target SoilSensor1 level

            // Update the gauge smoothly using requestAnimationFrame
            function animate() {
                var now = performance.now();
                var progress = (now - SoilSensor1animationStartTime) / SoilSensor1animationDuration;

                // Perform linear interpolation between current and target values
                var interpolatedValue = SoilSensor1current + (SoilSensor1target - SoilSensor1current) * progress;

                // Update the gauge with the interpolated value
                // Use Number.toFixed(2) to format with two decimal places
                SoilSensor1axisDataItem.set("value", Number(interpolatedValue.toFixed(0)));

                if (progress < 1) {
                    requestAnimationFrame(animate);
                } else {
                    SoilSensor1current = SoilSensor1target;
                }
            }

            animate();
        } else {
            console.error('Invalid SoilSensor1 level:', level);
        }
    }

    // Setup WiFi status check and monitoring
    function SoilSensor1setupWiFiStatusCheckAndEnableMonitoring() {
        function updateWifiStatus() {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var data = JSON.parse(xhr.responseText);
                        var wifiStatusElement = document.getElementById('wifi_status');
                        if (wifiStatusElement) {
                            wifiStatusElement.innerText = data.wifi_status;
                            wifiStatusElement.style.color = (data.wifi_status.toLowerCase() === 'connected') ? 'green' : 'red';
                        }
                    } else {
                        console.error("WiFi status update failed");
                    }
                }
            };
            xhr.open('POST', 'controller/receive_data.php', true);
            xhr.send();
        }

        function SoilSensor1LevelFetchData() {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    var data = JSON.parse(xhr.responseText);
                    var soilMoisture1 = data.soilMoisture1;
                    var soilMoisture2 = data.soilMoisture2;
                    var humidity = data.humidity;
                    var temperature = data.temperature;

                    const waterStatusElement = document.getElementById('waterStatus');
                    const pumpStatusElement = document.getElementById('pumpStatus');
                    const valve1StatusElement = document.getElementById('valve1Status');
                    const valve2StatusElement = document.getElementById('valve2Status');

                    waterStatusElement.textContent = data.waterStatus;
                    pumpStatusElement.textContent = data.pumpStatus;
                    valve1StatusElement.textContent = data.valve1Status;
                    valve2StatusElement.textContent = data.valve2Status;

                    soilMoisture1Update(soilMoisture1); // Assuming this is a function you've defined elsewhere
                    soilMoisture2Update(soilMoisture2); // Assuming this is a function you've defined elsewhere
                    humidityUpdate(humidity); // Assuming this is a function you've defined elsewhere
                    temperatureUpdate(temperature); // Assuming this is a function you've defined elsewhere
                }
            };
            xhr.open('POST', 'controller/receive_data.php', true);
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.send(JSON.stringify({}));
        }

        // Initial fetch and setup for periodic updates
        SoilSensor1LevelFetchData();
        setInterval(SoilSensor1LevelFetchData, 2000);
        updateWifiStatus();
    }

    // Call the setup function
    SoilSensor1setupWiFiStatusCheckAndEnableMonitoring();

    // Create axis ranges bands for soil moisture sensor (0-1000)
    var SoilSensor1bandsData = [{
        title: "Very Wet",
        color: "#6699ff", // Light Blue
        lowScore: 0,
        highScore: 200
    }, {
        title: "Wet",
        color: "#b0d136", // Light Green
        lowScore: 200,
        highScore: 400
    }, {
        title: "Moist",
        color: "#f3eb0c", // Yellow
        lowScore: 400,
        highScore: 600
    }, {
        title: "Dry",
        color: "#fdae19", // Orange
        lowScore: 600,
        highScore: 800
    }, {
        title: " Very Dry",
        color: "#f04922", // Red
        lowScore: 800,
        highScore: 1000
    }];

    am5.array.each(SoilSensor1bandsData, function (data) {
        var range = SoilSensor1xAxis.createAxisRange(SoilSensor1xAxis.makeDataItem({}));
        range.setAll({
            value: data.lowScore,
            endValue: data.highScore
        });
        range.get("axisFill").setAll({
            visible: true,
            fill: am5.color(data.color),
            fillOpacity: 0.8
        });
        range.get("label").setAll({
            text: data.title,
            inside: true,
            radius: 15,
            fontSize: "9px",
            fill: SoilSensor1Gauge.interfaceColors.get("background")
        });
    });

    // Make chart animate on load
    SoilSensor1chart.appear(1000, 100);

    //SoilSensor2-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

    var SoilSensor2Gauge = am5.Root.new("SoilSensor2");

    // Set themes
    SoilSensor2Gauge.setThemes([
        am5themes_Animated.new(SoilSensor2Gauge)
    ]);

    // Create SoilSensor2 chart
    var SoilSensor2chart = SoilSensor2Gauge.container.children.push(am5radar.RadarChart.new(SoilSensor2Gauge, {
        panX: false,
        panY: false,
        startAngle: 160,
        endAngle: 380
    }));

    // Create axis and its renderer
    var SoilSensor2axisRenderer = am5radar.AxisRendererCircular.new(SoilSensor2Gauge, {
        innerRadius: -40
    });

    SoilSensor2axisRenderer.grid.template.setAll({
        stroke: SoilSensor2Gauge.interfaceColors.get("background"),
        visible: true,
        strokeOpacity: 1
    });

    var SoilSensor2xAxis = SoilSensor2chart.xAxes.push(am5xy.ValueAxis.new(SoilSensor2Gauge, {
        maxDeviation: 0,
        min: 0,
        max: 1000,
        strictMinMax: true,
        renderer: SoilSensor2axisRenderer
    }));

    // Add clock hand
    var SoilSensor2axisDataItem = SoilSensor2xAxis.makeDataItem({});
    var SoilSensor2clockHand = am5radar.ClockHand.new(SoilSensor2Gauge, {
        pinRadius: am5.percent(25),
        radius: am5.percent(65),
        bottomWidth: 30
    });

    var SoilSensor2bullet = SoilSensor2axisDataItem.set("bullet", am5xy.AxisBullet.new(SoilSensor2Gauge, {
        sprite: SoilSensor2clockHand
    }));

    SoilSensor2xAxis.createAxisRange(SoilSensor2axisDataItem);

    var SoilSensor2label = SoilSensor2chart.radarContainer.children.push(am5.Label.new(SoilSensor2Gauge, {
        fill: am5.color(0xffffff),
        centerX: am5.percent(50),
        textAlign: "center",
        centerY: am5.percent(50),
        fontSize: "1.3em"
    }));

    SoilSensor2axisDataItem.set("value", 0);

    SoilSensor2bullet.get("sprite").on("rotation", function () {
        var value = SoilSensor2axisDataItem.get("value");
        var fill = am5.color(0x000000);
        SoilSensor2xAxis.axisRanges.each(function (range) {
            if (value >= range.get("value") && value <= range.get("endValue")) {
                fill = range.get("axisFill").get("fill");
            }
        });

        SoilSensor2label.set("text", Math.round(value).toString());

        SoilSensor2clockHand.pin.animate({
            key: "fill",
            to: fill,
            duration: 500,
            easing: am5.ease.out(am5.ease.cubic)
        });
        SoilSensor2clockHand.hand.animate({
            key: "fill",
            to: fill,
            duration: 500,
            easing: am5.ease.out(am5.ease.cubic)
        });
    });

    // Variables for animation control
    var SoilSensor2current = 0;
    var SoilSensor2target = 0;
    var SoilSensor2animationStartTime = performance.now(); // Timestamp to track animation start time
    var SoilSensor2animationDuration = 1000; // Duration for smooth animation (in milliseconds)

    // Function to update the gauge with animation
    // Function to update the gauge with animation
    function soilMoisture2Update(level) {
        var parsed = parseFloat(level);
        if (!isNaN(parsed)) {
            SoilSensor2target = parsed; // Set the new target SoilSensor2 level

            // Update the gauge smoothly using requestAnimationFrame
            function animate() {
                var now = performance.now();
                var progress = (now - SoilSensor2animationStartTime) / SoilSensor2animationDuration;

                // Perform linear interpolation between current and target values
                var interpolatedValue = SoilSensor2current + (SoilSensor2target - SoilSensor2current) * progress;

                // Update the gauge with the interpolated value
                // Use Number.toFixed(2) to format with two decimal places
                SoilSensor2axisDataItem.set("value", Number(interpolatedValue.toFixed(0)));

                if (progress < 1) {
                    requestAnimationFrame(animate);
                } else {
                    SoilSensor2current = SoilSensor2target;
                }
            }

            animate();
        } else {
            console.error('Invalid SoilSensor2 level:', level);
        }
    }

    // Create axis ranges bands for soil moisture sensor (0-1000)
    var SoilSensor2bandsData = [{
        title: "Very Wet",
        color: "#6699ff", // Light Blue
        lowScore: 0,
        highScore: 200
    }, {
        title: "Wet",
        color: "#b0d136", // Light Green
        lowScore: 200,
        highScore: 400
    }, {
        title: "Moist",
        color: "#f3eb0c", // Yellow
        lowScore: 400,
        highScore: 600
    }, {
        title: "Dry",
        color: "#fdae19", // Orange
        lowScore: 600,
        highScore: 800
    }, {
        title: " Very Dry",
        color: "#f04922", // Red
        lowScore: 800,
        highScore: 1000
    }];

    am5.array.each(SoilSensor2bandsData, function (data) {
        var range = SoilSensor2xAxis.createAxisRange(SoilSensor2xAxis.makeDataItem({}));
        range.setAll({
            value: data.lowScore,
            endValue: data.highScore
        });
        range.get("axisFill").setAll({
            visible: true,
            fill: am5.color(data.color),
            fillOpacity: 0.8
        });
        range.get("label").setAll({
            text: data.title,
            inside: true,
            radius: 15,
            fontSize: "9px",
            fill: SoilSensor2Gauge.interfaceColors.get("background")
        });
    });

    // Make chart animate on load
    SoilSensor2chart.appear(1000, 100);

    //humidity-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

    var humidityGauge = am5.Root.new("humidity");

    // Set themes
    humidityGauge.setThemes([
        am5themes_Animated.new(humidityGauge)
    ]);

    // Create humidity chart
    var humiditychart = humidityGauge.container.children.push(am5radar.RadarChart.new(humidityGauge, {
        panX: false,
        panY: false,
        startAngle: 160,
        endAngle: 380
    }));

    // Create axis and its renderer
    var humidityaxisRenderer = am5radar.AxisRendererCircular.new(humidityGauge, {
        innerRadius: -40
    });

    humidityaxisRenderer.grid.template.setAll({
        stroke: humidityGauge.interfaceColors.get("background"),
        visible: true,
        strokeOpacity: 1
    });

    var humidityxAxis = humiditychart.xAxes.push(am5xy.ValueAxis.new(humidityGauge, {
        maxDeviation: 0,
        min: 0,
        max: 100,
        strictMinMax: true,
        renderer: humidityaxisRenderer
    }));

    // Add clock hand
    var humidityaxisDataItem = humidityxAxis.makeDataItem({});
    var humidityclockHand = am5radar.ClockHand.new(humidityGauge, {
        pinRadius: am5.percent(25),
        radius: am5.percent(65),
        bottomWidth: 30
    });

    var humiditybullet = humidityaxisDataItem.set("bullet", am5xy.AxisBullet.new(humidityGauge, {
        sprite: humidityclockHand
    }));

    humidityxAxis.createAxisRange(humidityaxisDataItem);

    var humiditylabel = humiditychart.radarContainer.children.push(am5.Label.new(humidityGauge, {
        fill: am5.color(0xffffff),
        centerX: am5.percent(50),
        textAlign: "center",
        centerY: am5.percent(50),
        fontSize: "1.3em"
    }));

    humidityaxisDataItem.set("value", 0);

    humiditybullet.get("sprite").on("rotation", function () {
        var value = humidityaxisDataItem.get("value");
        var fill = am5.color(0x000000);
        humidityxAxis.axisRanges.each(function (range) {
            if (value >= range.get("value") && value <= range.get("endValue")) {
                fill = range.get("axisFill").get("fill");
            }
        });

        humiditylabel.set("text", Math.round(value).toString());

        humidityclockHand.pin.animate({
            key: "fill",
            to: fill,
            duration: 500,
            easing: am5.ease.out(am5.ease.cubic)
        });
        humidityclockHand.hand.animate({
            key: "fill",
            to: fill,
            duration: 500,
            easing: am5.ease.out(am5.ease.cubic)
        });
    });

    // Variables for animation control
    var humiditycurrent = 0;
    var humiditytarget = 0;
    var humidityanimationStartTime = performance.now(); // Timestamp to track animation start time
    var humidityanimationDuration = 1000; // Duration for smooth animation (in milliseconds)

    // Function to update the gauge with animation
    // Function to update the gauge with animation
    function humidityUpdate(level) {
        var parsed = parseFloat(level);
        if (!isNaN(parsed)) {
            humiditytarget = parsed; // Set the new target humidity level

            // Update the gauge smoothly using requestAnimationFrame
            function animate() {
                var now = performance.now();
                var progress = (now - humidityanimationStartTime) / humidityanimationDuration;

                // Perform linear interpolation between current and target values
                var interpolatedValue = humiditycurrent + (humiditytarget - humiditycurrent) * progress;

                // Update the gauge with the interpolated value
                // Use Number.toFixed(2) to format with two decimal places
                humidityaxisDataItem.set("value", Number(interpolatedValue.toFixed(0)));

                if (progress < 1) {
                    requestAnimationFrame(animate);
                } else {
                    humiditycurrent = humiditytarget;
                }
            }

            animate();
        } else {
            console.error('Invalid humidity level:', level);
        }
    }

    // Create axis ranges bands for humidity sensor (0-100)
    var humiditybandsData = [{
        title: "Very Dry",
        color: "#f04922", // Red
        lowScore: 0,
        highScore: 20
    }, {
        title: "Dry",
        color: "#fdae19", // Orange
        lowScore: 20,
        highScore: 40
    }, {
        title: "Moderate",
        color: "#f3eb0c", // Yellow
        lowScore: 40,
        highScore: 60
    }, {
        title: "Humid",
        color: "#b0d136", // Light Green
        lowScore: 60,
        highScore: 80
    }, {
        title: "Very Humid",
        color: "#6699ff", // Light Blue
        lowScore: 80,
        highScore: 100
    }];

    am5.array.each(humiditybandsData, function (data) {
        var range = humidityxAxis.createAxisRange(humidityxAxis.makeDataItem({}));
        range.setAll({
            value: data.lowScore,
            endValue: data.highScore
        });
        range.get("axisFill").setAll({
            visible: true,
            fill: am5.color(data.color),
            fillOpacity: 0.8
        });
        range.get("label").setAll({
            text: data.title,
            inside: true,
            radius: 15,
            fontSize: "9px",
            fill: humidityGauge.interfaceColors.get("background")
        });
    });

    // Make chart animate on load
    humiditychart.appear(1000, 100);

    //Temperature-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

    var temperatureGauge = am5.Root.new("temperature");

    // Set themes
    temperatureGauge.setThemes([
        am5themes_Animated.new(temperatureGauge)
    ]);

    // Create temperature chart
    var temperaturechart = temperatureGauge.container.children.push(am5radar.RadarChart.new(temperatureGauge, {
        panX: false,
        panY: false,
        startAngle: 160,
        endAngle: 380
    }));

    // Create axis and its renderer
    var temperatureaxisRenderer = am5radar.AxisRendererCircular.new(temperatureGauge, {
        innerRadius: -40
    });

    temperatureaxisRenderer.grid.template.setAll({
        stroke: temperatureGauge.interfaceColors.get("background"),
        visible: true,
        strokeOpacity: 1
    });

    var temperaturexAxis = temperaturechart.xAxes.push(am5xy.ValueAxis.new(temperatureGauge, {
        maxDeviation: 0,
        min: 0,
        max: 100,
        strictMinMax: true,
        renderer: temperatureaxisRenderer
    }));

    // Add clock hand
    var temperatureaxisDataItem = temperaturexAxis.makeDataItem({});
    var temperatureclockHand = am5radar.ClockHand.new(temperatureGauge, {
        pinRadius: am5.percent(25),
        radius: am5.percent(65),
        bottomWidth: 30
    });

    var temperaturebullet = temperatureaxisDataItem.set("bullet", am5xy.AxisBullet.new(temperatureGauge, {
        sprite: temperatureclockHand
    }));

    temperaturexAxis.createAxisRange(temperatureaxisDataItem);

    var temperaturelabel = temperaturechart.radarContainer.children.push(am5.Label.new(temperatureGauge, {
        fill: am5.color(0xffffff),
        centerX: am5.percent(50),
        textAlign: "center",
        centerY: am5.percent(50),
        fontSize: "1.3em"
    }));

    temperatureaxisDataItem.set("value", 0);

    temperaturebullet.get("sprite").on("rotation", function () {
        var value = temperatureaxisDataItem.get("value");
        var fill = am5.color(0x000000);
        temperaturexAxis.axisRanges.each(function (range) {
            if (value >= range.get("value") && value <= range.get("endValue")) {
                fill = range.get("axisFill").get("fill");
            }
        });

        temperaturelabel.set("text", Math.round(value).toString());

        temperatureclockHand.pin.animate({
            key: "fill",
            to: fill,
            duration: 500,
            easing: am5.ease.out(am5.ease.cubic)
        });
        temperatureclockHand.hand.animate({
            key: "fill",
            to: fill,
            duration: 500,
            easing: am5.ease.out(am5.ease.cubic)
        });
    });

    // Variables for animation control
    var temperaturecurrent = 0;
    var temperaturetarget = 0;
    var temperatureanimationStartTime = performance.now(); // Timestamp to track animation start time
    var temperatureanimationDuration = 1000; // Duration for smooth animation (in milliseconds)

    // Function to update the gauge with animation
    // Function to update the gauge with animation
    function temperatureUpdate(level) {
        var parsed = parseFloat(level);
        if (!isNaN(parsed)) {
            temperaturetarget = parsed; // Set the new target temperature level

            // Update the gauge smoothly using requestAnimationFrame
            function animate() {
                var now = performance.now();
                var progress = (now - temperatureanimationStartTime) / temperatureanimationDuration;

                // Perform linear interpolation between current and target values
                var interpolatedValue = temperaturecurrent + (temperaturetarget - temperaturecurrent) * progress;

                // Update the gauge with the interpolated value
                // Use Number.toFixed(2) to format with two decimal places
                temperatureaxisDataItem.set("value", Number(interpolatedValue.toFixed(0)));

                if (progress < 1) {
                    requestAnimationFrame(animate);
                } else {
                    temperaturecurrent = temperaturetarget;
                }
            }

            animate();
        } else {
            console.error('Invalid temperature level:', level);
        }
    }

    // Create axis ranges bands for temperature sensor (0-100°C)
    var temperaturebandsData = [{
        title: "Very Cold",
        color: "#6699ff", // Light Blue
        lowScore: 0,
        highScore: 20
    }, {
        title: "Cold",
        color: "#66ccff", // Sky Blue
        lowScore: 20,
        highScore: 40
    }, {
        title: "Moderate",
        color: "#b0d136", // Light Green
        lowScore: 40,
        highScore: 60
    }, {
        title: "Warm",
        color: "#fdae19", // Orange
        lowScore: 60,
        highScore: 80
    }, {
        title: "Hot",
        color: "#f04922", // Red
        lowScore: 80,
        highScore: 100
    }];

    am5.array.each(temperaturebandsData, function (data) {
        var range = temperaturexAxis.createAxisRange(temperaturexAxis.makeDataItem({}));
        range.setAll({
            value: data.lowScore,
            endValue: data.highScore
        });
        range.get("axisFill").setAll({
            visible: true,
            fill: am5.color(data.color),
            fillOpacity: 0.8
        });
        range.get("label").setAll({
            text: data.title,
            inside: true,
            radius: 15,
            fontSize: "9px",
            fill: temperatureGauge.interfaceColors.get("background")
        });
    });

    // Make chart animate on load
    temperaturechart.appear(1000, 100);
});
