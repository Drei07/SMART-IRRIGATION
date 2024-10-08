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
					<h1>Threasholds</h1>
					<ul class="breadcrumb">
						<li>
							<a class="active" href="./">Home</a>
						</li>
						<li>|</li>
						<li>
							<a href="">Threasholds</a>
						</li>
					</ul>
				</div>
			</div>

			<div class="table-data">
				<div class="order">
					<div class="head">
						<h3><i class='bx bxs-cog' ></i> Configuration of Threasholds</h3>
					</div>
                    <!-- BODY -->
					<section class="data-form">
						<div class="header"></div>
						<div class="registration">
							<form action="controller/waterQuality-controller.php" method="POST" class="row gx-5 needs-validation" name="form" onsubmit="return validate()"  novalidate style="overflow: hidden;">
								<div class="row gx-5 needs-validation">

									<label class="form-label" style="text-align: left; padding-top: .5rem; padding-bottom: 2rem; font-size: 1rem; font-weight: bold;"><i class='bx bxs-cog'></i> pH Parameter Configuration</label>
									<input type="hidden" name="id" value="1">
									<div class="col-md-6">
										<label for="ph_low" class="form-label">Low<span> *</span></label>
										<input type="text" class="form-control" autocapitalize="on" autocomplete="off" name="low" id="ph_low" value="<?php echo $phLow ?>" required>
										<div class="invalid-feedback">
										Please provide a Low Value.
										</div>
									</div>

									<div class="col-md-6">
										<label for="ph_high" class="form-label">High<span> *</span></label>
										<input type="text" class="form-control" autocapitalize="on" autocomplete="off" name="high" id="ph_high"  value="<?php echo $phHigh ?>" required>
										<div class="invalid-feedback">
										Please provide a High Value.
										</div>
									</div>

								</div>

								<div class="addBtn">
									<button type="submit" class="btn-dark" name="btn-update-parameter" id="btn-update" onclick="return IsEmpty(); sexEmpty();">Update</button>
								</div>
							</form>
						</div>
					</section>
					
					<!-- System Logo  -->

					<section class="data-form">
						<div class="header"></div>
						<div class="registration">
							<form action="controller/waterQuality-controller.php" method="POST" enctype="multipart/form-data" class="row gx-5 needs-validation" name="form" onsubmit="return validate()"  novalidate style="overflow: hidden;">
								<div class="row gx-5 needs-validation">

								<label class="form-label" style="text-align: left; padding-top: .5rem; padding-bottom: 2rem; font-size: 1rem; font-weight: bold;"><i class='bx bxs-cog'></i> TDS Parameters Configuration</label>
								<input type="hidden" name="id" value="2">
								<div class="col-md-6">
									<label for="tds_low" class="form-label">Low<span> *</span></label>
									<input type="text" class="form-control" autocapitalize="on" autocomplete="off" name="low" id="tds_low"  value="<?php echo $tdsLow ?>" required>
									<div class="invalid-feedback">
									Please provide a Low Value.
									</div>
								</div>

								<div class="col-md-6">
									<label for="tds_high" class="form-label">High<span> *</span></label>
									<input type="text" class="form-control" autocapitalize="on" autocomplete="off" name="high" id="tds_high"  value="<?php echo $tdsHigh ?>" required>
									<div class="invalid-feedback">
									Please provide a High Value.
									</div>
								</div>

								</div>

								<div class="addBtn">
									<button type="submit" class="btn-dark" name="btn-update-parameter" id="btn-update" onclick="return IsEmpty(); sexEmpty();">Update</button>
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