<?php
include_once 'header.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<?php echo $header_dashboard->getHeaderDashboard() ?>
	<link href='https://fonts.googleapis.com/css?family=Antonio' rel='stylesheet'>
	<title>Dashboard</title>
</head>

<body>

	<!-- Loader -->
	<div class="loader"></div>

	<!-- SIDEBAR -->
	<?php echo $sidebar->getSideBar(); ?> <!-- This will render the sidebar -->
	<!-- SIDEBAR -->



	<!-- CONTENT -->
	<section id="content">
		<!-- NAVBAR -->
		<nav>
			<i class='bx bx-menu'></i>
			<form action="#">
				<div class="form-input">
					<button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
				</div>
			</form>
			<div class="username">
				<span>Hello, <label for=""><?php echo $user_fname ?></label></span>
			</div>
			<a href="profile" class="profile" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Profile">
				<img src="../../src/img/<?php echo $user_profile ?>">
			</a>
		</nav>
		<!-- NAVBAR -->

		<!-- MAIN -->
		<main>
			<div class="head-title">
				<div class="left">
					<h1>Dashboard</h1>
					<ul class="breadcrumb">
						<li>
							<a class="active" href="./">Home</a>
						</li>
						<li>|</li>
						<li>
							<a href="">Dashboard</a>
						</li>
					</ul>
				</div>
			</div>

			</div>
			<ul class="dashboard_data">
				<div class="gauge_dashboard">
					<div class="status">
						<div class="card arduino">
							<h1>DEVICE STATUS</h1>
							<div class="sensor-data">
								<span id="wifiStatus">Loading...</span>
							</div>
						</div>
					</div>
					<div class="status">
						<div class="card arduino">
							<h1>WATER STATUS</h1>
							<div class="sensor-data">
								<span id="waterStatus">Loading...</span>
							</div>
						</div>
						<div class="card arduino">
							<h1>PUMP STATUS</h1>
							<div class="sensor-data">
								<span id="pumpStatus">Loading...</span>
							</div>
						</div>
					</div>
					<div class="status">
						<div class="card arduino">
							<h1>VALVE 1 STATUS</h1>
							<div class="sensor-data">
								<span id="valve1Status">Loading...</span>
							</div>
						</div>
						<div class="card arduino">
							<h1>VALVE 2 STATUS</h1>
							<div class="sensor-data">
								<span id="valve2Status">Loading...</span>
							</div>
						</div>
					</div>
					<div class="gauge">
						<div class="card gauge_card">
							<p class="card-title">SENSOR 1 STATUS</p>
							<div id="sensorData1"></div>
						</div>
						<div class="card gauge_card">
							<p class="card-title">SENSOR 2 STATUS</p>
							<div id="sensorData2"></div>
						</div>
					</div>
				</div>
			</ul>
		</main>
		<!-- MAIN -->
		<!-- MODALS -->
		<div class="class-modal">
			<div class="modal fade" id="setTimeModals" tabindex="-1" aria-labelledby="classModalLabel" aria-hidden="true" data-bs-backdrop="static">
				<div class="modal-dialog modal-dialog-centered modal-lg">
					<div class="modal-content">
						<div class="header"></div>
						<div class="modal-header">
							<h5 class="modal-title" id="classModalLabel"><i class='bx bxs-timer'></i> Set Analyzing Time</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeButton"></button>
						</div>
						<div class="modal-body">
							<section class="data-form-modals">
								<div class="registration">
									<form action="controller/waterQuality-controller.php" method="POST" class="row gx-5 needs-validation" name="form" onsubmit="return validate()" novalidate style="overflow: hidden;">
										<div class="row gx-5 needs-validation">
											<input type="hidden" name="user_id" value="<?php echo $user_id ?>">

											<div class="col-md-12">
												<label for="analyzingTime" class="form-label">Analyzing Time<span> *</span></label>
												<input type="text" class="form-control numbers" autocapitalize="off" inputmode="numeric" autocomplete="off" name="analyzingTime" id="analyzingTime" minlength="4" maxlength="7" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" required placeholder="ex.3000">
											</div>
											<div class="invalid-feedback">
												Please set the analyzing time .
											</div>

										</div>

										<div class="addBtn">
											<button type="submit" class="btn-dark" name="btn-set-time" id="btn-add" onclick="return IsEmpty(); sexEmpty();">SET</button>
										</div>
									</form>
								</div>
							</section>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- CONTENT -->

	<?php echo $footer_dashboard->getFooterDashboard() ?>
	<?php include_once '../../config/sweetalert.php'; ?>
	<script src="../../src/js/gauge.js"></script>

	<script>
		// Fetch data every 2 seconds
		function fetchData() {
			var xhr = new XMLHttpRequest();

			// Monitor when request state changes
			xhr.onreadystatechange = function() {
				if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
					var data = JSON.parse(xhr.responseText);

					// Update the HTML elements with the fetched data
					document.getElementById('wifiStatus').textContent = data.wifi_status;
					document.getElementById('pumpStatus').textContent = data.pumpStatus;
					document.getElementById('valve1Status').textContent = data.valve1Status;
					document.getElementById('valve2Status').textContent = data.valve2Status;
					document.getElementById('soilMoisture1').textContent = data.soilMoisture1;
					document.getElementById('soilMoisture2').textContent = data.soilMoisture2;
					document.getElementById('waterStatus').textContent = data.waterStatus;
				}
			};

			// Prepare the POST request with optional data (if needed)
			var postData = JSON.stringify({}); // You can pass data here if needed
			xhr.open('POST', 'controller/receive_data.php', true);
			xhr.setRequestHeader('Content-Type', 'application/json');
			xhr.send(postData); // Send the request with postData
		}

		// Fetch data every 2 seconds
		setInterval(fetchData, 2000);
		fetchData(); // Initial fetch
	</script>
</body>

</html>