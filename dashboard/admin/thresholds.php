<?php
include_once 'header.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<?php echo $header_dashboard->getHeaderDashboard() ?>
	<link href='https://fonts.googleapis.com/css?family=Antonio' rel='stylesheet'>
	<title>Quality Parameter</title>
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
					<h1>Thresholds</h1>
					<ul class="breadcrumb">
						<li>
							<a class="active" href="./">Home</a>
						</li>
						<li>|</li>
						<li>
							<a href="">Thresholds</a>
						</li>
					</ul>
				</div>
			</div>

			<div class="table-data">
				<div class="order">
					<div class="head">
						<h3><i class='bx bxs-cog'></i> Configuration of Thresholds</h3>
					</div>
					<!-- BODY -->
					<section class="data-form">
						<div class="header"></div>
						<div class="registration">
							<form action="controller/sensor-controller.php" method="POST" class="row gx-5 needs-validation" name="form" onsubmit="return validate()" novalidate style="overflow: hidden;">
								<div class="row gx-5 needs-validation">

									<label class="form-label" style="text-align: left; padding-top: .5rem; padding-bottom: 2rem; font-size: 1rem; font-weight: bold;"><i class='bx bxs-cog'></i> Sensor 1 Configuration</label>
									<input type="hidden" name="sensorId" value="1">

									<div class="col-md-6">
										<label for="mode" class="form-label">Mode<span> *</span></label>
										<select class="form-select form-control" name="mode" maxlength="6" autocomplete="off" id="mode" required>
											<option selected value="<?php echo $sensor1Mode ?>"><?php echo $sensor1Mode ?></option>
											<option value="AUTOMATIC">AUTOMATIC</option>
											<option value="SCHEDULE ">SCHEDULE</option>
										</select>
										<div class="invalid-feedback">
											Please select Mode.
										</div>
									</div>

									<div class="col-md-6">
										<label for="plant_name" class="form-label">Plant Name<span> *</span></label>
										<input type="text" class="form-control" autocapitalize="on" autocomplete="off" name="plant_name" id="plant_name" value="<?php echo $sensor1PlantName ?>" required>
										<div class="invalid-feedback">
											Please provide a Plant Name.
										</div>
									</div>

									<div class="col-md-6">
										<label for="dry_threshold" class="form-label">Dry Threshold<span> (for automatic mode)*</span></label>
										<input type="text" class="form-control" autocapitalize="on" autocomplete="off" name="dry_threshold" id="dry_threshold" value="<?php echo $sensor1Dry ?>" required>
										<div class="invalid-feedback">
											Please provide a Dry Threshold.
										</div>
									</div>

									<div class="col-md-6">
										<label for="watered_threshold" class="form-label">Watered Threshold<span> (for automatic mode)*</span></label>
										<input type="text" class="form-control" autocapitalize="on" autocomplete="off" name="watered_threshold" id="watered_threshold" value="<?php echo $sensor1Watered ?>" required>
										<div class="invalid-feedback">
											Please provide a Watered Threshold.
										</div>
									</div>

									<div class="col-md-6">
										<label for="start_date" class="form-label">Start Date<span> (for scheduled mode)*</span></label>
										<input type="datetime-local" step="1" class="form-control" name="start_date" id="start_date" value="<?php echo $sensor1StartDate ?>" required>
										<div class="invalid-feedback">
											Please provide a Start Date for scheduled mode.
										</div>
									</div>

									<div class="col-md-6">
										<label for="end_date" class="form-label">Stop Date<span> (for scheduled mode)*</span></label>
										<input type="datetime-local" step="1" class="form-control" name="end_date" id="end_date" value="<?php echo $sensor1EndDate ?>" required>
										<div class="invalid-feedback">
											Please provide a Stop Date for scheduled mode.
										</div>
									</div>

								</div>

								<div class="addBtn">
									<button type="submit" class="btn-dark" name="btn-update-thresholds" id="btn-update" onclick="return IsEmpty(); sexEmpty();">Update</button>
								</div>
							</form>
						</div>
					</section>

					<!-- System Logo  -->

					<section class="data-form">
						<div class="header"></div>
						<div class="registration">
							<form action="controller/sensor-controller.php" method="POST" enctype="multipart/form-data" class="row gx-5 needs-validation" name="form" onsubmit="return validate()" novalidate style="overflow: hidden;">
								<div class="row gx-5 needs-validation">

									<label class="form-label" style="text-align: left; padding-top: .5rem; padding-bottom: 2rem; font-size: 1rem; font-weight: bold;"><i class='bx bxs-cog'></i> Sensor 2 Configuration</label>
									<input type="hidden" name="sensorId" value="2">

									<div class="col-md-6">
										<label for="mode" class="form-label">Mode<span> *</span></label>
										<select class="form-select form-control" name="mode" maxlength="6" autocomplete="off" id="mode" required>
											<option selected value="<?php echo $sensor2Mode ?>"><?php echo $sensor2Mode ?></option>
											<option value="AUTOMATIC">AUTOMATIC</option>
											<option value="SCHEDULE ">SCHEDULE</option>
										</select>
										<div class="invalid-feedback">
											Please select Mode.
										</div>
									</div>

									<div class="col-md-6">
										<label for="plant_name" class="form-label">Plant Name<span> *</span></label>
										<input type="text" class="form-control" autocapitalize="on" autocomplete="off" name="plant_name" id="plant_name" value="<?php echo $sensor2PlantName ?>" required>
										<div class="invalid-feedback">
											Please provide a Plant Name.
										</div>
									</div>

									<div class="col-md-6">
										<label for="dry_threshold" class="form-label">Dry Threshold<span> (for automatic mode)*</span></label>
										<input type="text" class="form-control" autocapitalize="on" autocomplete="off" name="dry_threshold" id="dry_threshold" value="<?php echo $sensor2Dry ?>" required>
										<div class="invalid-feedback">
											Please provide a Dry Threshold.
										</div>
									</div>

									<div class="col-md-6">
										<label for="watered_threshold" class="form-label">Watered Threshold<span> (for automatic mode)*</span></label>
										<input type="text" class="form-control" autocapitalize="on" autocomplete="off" name="watered_threshold" id="watered_threshold" value="<?php echo $sensor2Watered ?>" required>
										<div class="invalid-feedback">
											Please provide a Watered Threshold.
										</div>
									</div>

									<div class="col-md-6">
										<label for="start_date" class="form-label">Start Date<span> (for scheduled mode)*</span></label>
										<input type="datetime-local" step="1" class="form-control" name="start_date" id="start_date" value="<?php echo $sensor2StartDate ?>" required>
										<div class="invalid-feedback">
											Please provide a Start Date for scheduled mode.
										</div>
									</div>

									<div class="col-md-6">
										<label for="end_date" class="form-label">Stop Date<span> (for scheduled mode)*</span></label>
										<input type="datetime-local" step="1" class="form-control" name="end_date" id="end_date" value="<?php echo $sensor2EndDate ?>" required>
										<div class="invalid-feedback">
											Please provide a Stop Date for scheduled mode.
										</div>
									</div>

								</div>

								<div class="addBtn">
									<button type="submit" class="btn-dark" name="btn-update-thresholds" id="btn-update" onclick="return IsEmpty(); sexEmpty();">Update</button>
								</div>
							</form>
						</div>
					</section>
				</div>
			</div>
			</div>
			</div>
		</main>
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->

	<?php echo $footer_dashboard->getFooterDashboard() ?>
	<?php include_once '../../config/sweetalert.php'; ?>
</body>

</html>