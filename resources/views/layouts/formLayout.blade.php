<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<!--Google Icons-->
	<link href="/css/materialize.min.css" rel="stylesheet" type="text/css">
	<link href="/css/dropzone.css" rel="stylesheet" type="text/css">
	<link href="/css/icon.css" rel="stylesheet" type="text/css">
	<link href="/css/style.css" rel="stylesheet" type="text/css">
	<link href="/js/vendor/video-js/video-js.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.css">
	<!--For Mobile-->
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />

	<title>Swine E-Commerce PH @yield('title') </title>
</head>

<body @yield('pageId')>
	<nav class="teal">
		<div class="nav-wrapper container">
			<a href="{{ route('home_path') }}" class="brand-logo">Swine E-Commerce</a>
		</div>
	</nav>

	<div class="container">
		<div class="row">
			<div class="col s12 center">
				<h3>Swine Breeder Farms Accreditation Program</h3>

			</div>
		</div>
		<div class="container">

			<div class="row">
				{!!Form::open(['route'=>'admin.register.submit', 'method'=>'POST', 'class'=>'col s12'])!!}
					<div class="row">
						<div class="col s12">
							<ul class="tabs">
								<li class="tab col s3"><a class="active" href="#registration">Application Form</a></li>

								<li class="tab col s3"><a href="#documents">Uploads</a></li>
							</ul>
						</div>
						<div id="registration" class="col s12">
							<div class="row">
								<div class="input-field col s12">
									<input placeholder="The Piggery Farm" id="farm_name" type="text" class="validate">
									<label for="farm">Farm Name</label>
								</div>
							</div>
							<div class="row">
								<div class="input-field col s12">
									<input placeholder="John Doe" id="farm_owner" type="text" class="validate">
									<label for="farm_owner">Farm Owner</label>
								</div>
							</div>
							<div class="row">
								<div class="input-field col s12">
									<textarea id="location" class="materialize-textarea"></textarea>
									<label for="location">Farm Location</label>
								</div>
							</div>
							<div class="row">
								<div class="input-field col s6">
									<input placeholder="0912-3456789/123-4567" id="number" type="text" class="validate">
									<label for="number">Phone Number</label>
								</div>
								<div class="input-field col s6">
									<input placeholder="john_doe@swinecommerce.com" id="email" type="email" class="validate">
									<label for="email">Email Address</label>
								</div>
							</div>
							<div class="row">
								<div class="input-field col s12">
									<h5>Applying as Producer of</h5>
									<div class="col s4">
										<input class="with-gap" name="group1" type="radio" id="test1" />
										<label for="test1">Great Grand Parent (GGP)</label>
									</div>
									<div class="col s4">
										<input class="with-gap" name="group1" type="radio" id="test2" />
										<label for="test2">Grand Parent (GP)</label>
									</div>
									<div class="col s4">
										<input class="with-gap" name="group1" type="radio" id="test3" />
										<label for="test3">Parent Stock (PS)</label>
									</div>
								</div>
							</div>
							<div class="row">
								<h5 class="col s12">
									Number of Breeding Animals per Breed/Line
								</h5>
								<div id="breeding-animals-wrapper" >
									<div class="input-field col s8">
										<input placeholder="Breed" class="breed validate" type="text">
									</div>
									<div class="input-field col s2">
										<input placeholder="Female" class="breed validate" type="number" min="0">
									</div>
									<div class="input-field col s2">
										<input placeholder="Male" class="breed validate" type="number" min="0">
									</div>
								</div>
							</div>
							<div class=" row center">
								<a id="breeding-animals" class="waves-effect waves-light btn"><i class="material-icons right">add</i>Add More</a>
							</div>

							<div class="row">
								<div class="col s12">
									<h5>List of Performance Testing Facilities</h5>
									<div id="testing-facilities-wrapper" class="input-field">
										<input placeholder="Testing Facility" type="text" class="validate">
									</div>
									<div class="center">
										<a id = "testing-facilities" class="waves-effect waves-light btn"><i class="material-icons right">add</i>Add More</a>
									</div>
								</div>

								<div class="row">
									<div class="col s12">
										<div class="input-field col s12">
											<input placeholder="Vaccine Name" id="vaccination" type="text" class="validate">
											<label for="vaccination">Vaccination in the Farm</label>
										</div>
									</div>

									<div class="col s12">
										<div class="input-field col s12">
											<input placeholder="Johnny Doe" id="consultant" type="text" class="validate">
											<label for="consultant">Breeding Consultant/Animal Breeder</label>
										</div>
									</div>

									<div class="col s12">
										<div class="input-field col s12">
											<input placeholder="Johnny Doe" id="veterinarian" type="text" class="validate">
											<label for="veterinarian">Veterinarian</label>
										</div>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col s12">
									<h5>Certified True and Correct</h5>
									<div class="file-field input-field">
										<div class="btn">
											<span>Upload Signature</span>
											<input type="file">
										</div>
										<div class="file-path-wrapper">
											<input class="file-path validate" type="text">
										</div>
									</div>
								</div>
							</div>

						</div>

						<div id="documents" class="col s12">
							<div class="row">

								<div class="col s4 card-panel">
									<div class="row">
										<div class="col s12">


										    <div class="file-field input-field">
										      <div class="btn upload col s12">
										        <span>Mayor's Business Permit</span>
										        <input class="btn" type="file">
										      </div>
										      <div class="file-path-wrapper">
										        <input class="file-path validate file-input-property" type="text">
										      </div>
										    </div>

											 <div class="file-field input-field">
 											  <div class="btn upload col s12">
 												 <span>Animal Welfare Registration</span>
 												 <input class="btn" type="file">
 											  </div>
 											  <div class="file-path-wrapper">
 												 <input class="file-path validate file-input-property" type="text">
 											  </div>
 											</div>

											<div class="file-field input-field">
											 <div class="btn upload col s12">
												<span>Sample Health Record</span>
												<input class="btn tooltipped" data-position="bottom" data-delay="50" data-tooltip="Sample Health Record for Breeders Sold" type="file">
											 </div>
											 <div class="file-path-wrapper">
												<input class="file-path validate file-input-property" type="text">
											 </div>
										  </div>

										  <div class="file-field input-field">
											<div class="btn upload col s12">
											  <span>Source of Genetic Material</span>
											  <input class="btn tooltipped" data-position="bottom" data-delay="50" data-tooltip="Certificate of Source of Breeders/Genetic Material" type="file">
											</div>
											<div class="file-path-wrapper">
											  <input class="file-path validate file-input-property" type="text">
											</div>
										 </div>

										 <div class="file-field input-field">
										  <div class="btn upload col s12">
											 <span>Organizational Profile</span>
											 <input class="btn" type="file">
										  </div>
										  <div class="file-path-wrapper">
											 <input class="file-path validate file-input-property" type="text">
										  </div>
										</div>

										<div class="file-field input-field">
										 <div class="btn upload col s12">
											<span>Track Record of the Farm</span>
											<input class="btn" type="file">
										 </div>
										 <div class="file-path-wrapper">
											<input class="file-path validate file-input-property" type="text">
										 </div>
									  </div>

									  <div class="file-field input-field">
										<div class="btn upload col s12">
										  <span>Sample Birth Certificate</span>
										  <input class="btn tooltipped" data-position="bottom" data-delay="50" data-tooltip="Birth Certificate of Youngest Employee" type="file">
										</div>
										<div class="file-path-wrapper">
										  <input class="file-path validate file-input-property" type="text">
										</div>
									 </div>

									 <div class="file-field input-field">
									  <div class="btn upload col s12">
										 <span>Water Analysis Result</span>
										 <input class="btn tooltipped" data-position="bottom" data-delay="50" data-tooltip="For microbial contaminants" type="file">
									  </div>
									  <div class="file-path-wrapper">
										 <input class="file-path validate file-input-property" type="text">
									  </div>
									</div>

									<div class="file-field input-field">
									 <div class="btn upload col s12">
										<span>Monitoring Record</span>
										<input class="btn" type="file">
									 </div>
									 <div class="file-path-wrapper">
										<input class="file-path validate file-input-property" type="text">
									 </div>
								  </div>


										</div>
									</div>
								</div>

								<div class="col s4 card-panel">
									<div class="row">
										<div class="col s12">

												<div class="file-field input-field">
													<div class="btn upload col s12">
														<span>ECC (DENR)</span>
														<input class="btn"  type="file">
													</div>
													<div class="file-path-wrapper">
														<input class="file-path validate file-input-property" type="text">
													</div>
												</div>

												<div class="file-field input-field">
													<div class="btn upload col s12">
														<span>Sample Pedigree Record</span>
														<input class="btn" type="file">
													</div>
													<div class="file-path-wrapper">
														<input class="file-path validate file-input-property" type="text">
													</div>
												</div>

												<div class="file-field input-field">
													<div class="btn upload col s12">
														<span>Performance Testing Result</span>
														<input class="btn tooltipped" data-position="bottom" data-delay="50" data-tooltip="Sample Result of Performance Testing"  type="file">
													</div>
													<div class="file-path-wrapper">
														<input class="file-path validate file-input-property" type="text">
													</div>
												</div>

												<div class="file-field input-field">
													<div class="btn upload col s12">
														<span>Price List</span>
														<input class="btn"  type="file">
													</div>
													<div class="file-path-wrapper">
														<input class="file-path validate file-input-property" type="text">
													</div>
												</div>

												<div class="file-field input-field">
													<div class="btn upload col s12">
														<span>Production Flowchart</span>
														<input class="btn"  type="file">
													</div>
													<div class="file-path-wrapper">
														<input class="file-path validate file-input-property" type="text">
													</div>
												</div>

												<div class="file-field input-field">
													<div class="btn upload col s12">
														<span>Breeding Program</span>
														<input class="btn"  type="file">
													</div>
													<div class="file-path-wrapper">
														<input class="file-path validate file-input-property" type="text">
													</div>
												</div>


												<div class="file-field input-field">
											  <div class="btn upload col s12">
												 <span>SSS Remittance Payment</span>
												 <input class="btn tooltipped" data-position="bottom" data-delay="50" data-tooltip="For the quarter preceding to the application for registration" type="file">
											  </div>
											  <div class="file-path-wrapper">
												 <input class="file-path validate file-input-property" type="text">
											  </div>
											</div>

											<div class="file-field input-field">
											 <div class="btn upload col s12">
												<span>Procurement Records</span>
												<input class="btn tooltipped" data-position="bottom" data-delay="50" data-tooltip="Procurement Records of Materials Used" type="file">
											 </div>
											 <div class="file-path-wrapper">
												<input class="file-path validate file-input-property" type="text">
											 </div>
										  </div>

										  <div class="file-field input-field">
											<div class="btn upload col s12">
											  <span>Vaccination Program</span>
											  <input class="btn" type="file">
											</div>
											<div class="file-path-wrapper">
											  <input class="file-path validate file-input-property" type="text">
											</div>
										 </div>

										</div>
									</div>
								</div>

								<div class="col s4 card-panel">
									<div class="row">

										<div class="col s12">
											<div class="file-field input-field">
												<div class="btn upload col s12">
													<span>SEC</span>
													<input class="btn" type="file">
												</div>
												<div class="file-path-wrapper">
													<input class="file-path validate file-input-property" type="text">
												</div>
											</div>
										</div>

										<div class="col s12">
											<div class="file-field input-field">
												<div class="btn upload col s12">
													<span>Sample Farm Record</span>
													<input class="btn" type="file">
												</div>
												<div class="file-path-wrapper">
													<input class="file-path validate file-input-property" type="text">
												</div>
											</div>
										</div>

										<div class="col s12">
											<div class="file-field input-field">
												<div class="btn upload col s12">
													<span>Certificate of Franchise</span>
													<input class="btn tooltipped" data-position="bottom" data-delay="50" data-tooltip="If Applicable" type="file">
												</div>
												<div class="file-path-wrapper">
													<input class="file-path validate file-input-property" type="text">
												</div>
											</div>
										</div>

										<div class="col s12">
											<div class="file-field input-field">
												<div class="btn upload col s12">
													<span>Identification System</span>
													<input class="btn" type="file">
												</div>
												<div class="file-path-wrapper">
													<input class="file-path validate file-input-property" type="text">
												</div>
											</div>
										</div>

										<div class="col s12">
											<div class="file-field input-field">
												<div class="btn upload col s12">
													<span>Operational Manual</span>
													<input class="btn" type="file">
												</div>
												<div class="file-path-wrapper">
													<input class="file-path validate file-input-property" type="text">
												</div>
											</div>
										</div>

										<div class="col s12">
											<div class="file-field input-field">
												<div class="btn upload col s12">
													<span>Zoning Permit</span>
													<input class="btn" type="file">
												</div>
												<div class="file-path-wrapper">
													<input class="file-path validate file-input-property" type="text">
												</div>
											</div>
										</div>

										<div class="col s12">
											<div class="file-field input-field">
												<div class="btn upload col s12">
													<span>Medical Certificate</span>
													<input class="btn tooltipped" data-position="bottom" data-delay="50" data-tooltip="Copy of Employees Medical Certificate" type="file">
												</div>
												<div class="file-path-wrapper">
													<input class="file-path validate file-input-property" type="text">
												</div>
											</div>
										</div>

										<div class="col s12">
											<div class="file-field input-field">
												<div class="btn upload col s12">
													<span>PRC ID</span>
													<input class="btn tooltipped" data-position="bottom" data-delay="50" data-tooltip="Laboratory equipment, weighing scale, pregnancy and back fat tester" type="file">
												</div>
												<div class="file-path-wrapper">
													<input class="file-path validate file-input-property" type="text">
												</div>
											</div>
										</div>

										<div class="col s12">
											<div class="file-field input-field">
												<div class="btn upload col s12">
														<span>Serial Numbers of Equipment</span>
													<input class="btn" type="file">
												</div>
												<div class="file-path-wrapper">
													<input class="file-path validate file-input-property" type="text">
												</div>
											</div>
										</div>

									</div>
								</div>
								<div class="row">
									<div class="col s5 offset-s5">
										<a type="submit" class="waves-effect waves-light btn">Submit</a>
									</div>
								</div>

							</div>
						</div>
						<!-- end of sample -->


					</div>
				{!!Form::close()!!}
			</div>
		</div>
	</div>


	<!--Import jQuery before materialize.js-->
	<script src="/js/vendor/jquery.min.js"></script>
  	<script src="/js/vendor/materialize.min.js"></script>
  	<script src="/js/vendor/dropzone.js"></script>
  	<script src="/js/vendor/video-js/video.min.js"></script>
  	<script src="/js/config.js"></script>
  	<script src="/js/custom.js"></script>
   {{-- <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.12/datatables.min.js"></script> --}}
   <script type="text/javascript" src="/js/vendor/datatables.min.js"></script>
   @yield('initScript')
  	{{-- Custom scripts for certain pages/functionalities --}}
  	@yield('customScript')
</body>

</html>
