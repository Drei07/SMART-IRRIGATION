<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toggle Switch</title>
    <style>
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: 0.4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: 0.4s;
            border-radius: 50%;
        }

        input:checked + .slider {
            background-color: #4CAF50;
        }

        input:checked + .slider:before {
            transform: translateX(26px);
        }
    </style>
</head>
<body>

    <h2>Toggle Switch</h2>

    <label class="switch">
        <input type="checkbox" id="toggleSwitch">
        <span class="slider"></span>
    </label>
    <p id="status">Status: CLOSED</p>

    <script>
        // Example of data coming from JSON (CLOSED/OPEN)
        const dataFromJson = {
            status: "CLOSED"  // This could be either "CLOSED" or "OPEN"
        };

        const toggleSwitch = document.getElementById('toggleSwitch');
        const statusText = document.getElementById('status');

        // Set the initial state of the switch based on the JSON value
        function setInitialState() {
            if (dataFromJson.status === "OPEN") {
                toggleSwitch.checked = true;
                statusText.innerText = "Status: OPEN";
            } else {
                toggleSwitch.checked = false;
                statusText.innerText = "Status: CLOSED";
            }
        }

        // Add an event listener to handle the toggle switch change
        toggleSwitch.addEventListener('change', function () {
            if (this.checked) {
                statusText.innerText = "Status: OPEN";
                // If you need to update the data in JSON or send it to a server:
                dataFromJson.status = "OPEN";
                console.log("Switch is OPEN");
            } else {
                statusText.innerText = "Status: CLOSED";
                dataFromJson.status = "CLOSED";
                console.log("Switch is CLOSED");
            }
        });

        // Initialize the switch state
        setInitialState();
    </script>

</body>
</html>
