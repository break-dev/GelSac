<?php

	use Dompdf\Dompdf;
	use Dompdf\Options;
	require_once "dompdf/autoload.inc.php";
	$dompdf = new Dompdf();

	$html = '	<style>
							@font-face {
						    font-family : "AgencyFB";
						    src: url("fonts/AgencyFB.ttf");
							}

							@font-face {
						    font-family : "AgencyFBb";
						    src: url("fonts/AgencyFB-Bold.ttf");
							}

							.fstyle{
								font: AgencyFB;
							}

							.fstyleb{
								font: AgencyFBb;
							}

							html, body, label {
								font-family: AgencyFB;
								margin: 0;
								padding: 0;
							}

							div{
								margin: 0;
								padding: 0;
							}
						</style>

						<body>
							<div class="fstyle"><label>HOLASSS</label></div>
							<div class="fstyle">0864</div>

							<div>HOLASSS</div>
							<div>0864</div>';

	$html .= '	<div class="d-flex fstyle">
								<div class="">
									<div class="row" style="font-family: AgencyFB;">
										> > > >
									</div>

									<div class="row" style="font-family: AgencyFB;">
										<label style="font-family: AgencyFB !important;">CLIENTE</label>
									</div>

									<div class="row" style="font-family: AgencyFB;">
										> > > >
									</div>
								</div>

								<div class="fstyleb">
									<label style="font-size: 25px; text-align: left; margin-left: -2px; margin-top: -10px;">
										0865
									</label>
								</div>

								<div class="">
									<div class="d-flex">
										<div style="margin-right: 25px; font-size: 9px;">
											15/02/2023
										</div>

										<div style="margin-right: 25px; font-size: 9px;">
											19:33
										</div>
									</div>
								</div>
							</div>';

	$html .= '	<div class="d-flex" style="height: 105px; margin-top: 0px; margin-bottom: -10px;">
								<table style="width: 100%; border-spacing: 0; margin-top: -5px;">
									<td style="border-spacing: 0; width: 50%;">
										<div style="margin-top: 0px; height: 100px;">
											<table style="width: 100%; border-spacing: 0">
												<tr style="font-size: 9px; font-weight: bold;">
													<td style="width: 20%; text-align: center;">
														<div class="row" style="font-family: AgencyFB;">
															> > > >
														</div>

														<div class="row" style="font-family: AgencyFB;">
															<label style="font-family: AgencyFB !important;">CLIENTE</label>
														</div>

														<div class="row" style="font-family: AgencyFB;">
															> > > >
														</div>
													</td>

													<td style="width: 40%; text-align: center;">
														<label style="font-size: 37px; font-weight: 700; text-align: left; margin-left: -2px; margin-top: -10px;">
															0864
														</label>
													</td>

													<td style="width: 10%; text-align: right;">
														<div style="margin-right: 25px; font-size: 9px;">
															15/02/2023
														</div>

														<div style="margin-right: 25px; font-size: 9px;">
															19:33
														</div>
													</td>
												</tr>
											</table>
										</div>
									</td>
								</table>
							<div>
						</body>';

	$options = new Options();
  $options->set('isRemoteEnabled', TRUE);
  $dompdf = new Dompdf($options);
	$dompdf -> loadHtml($html, 'UTF-8');
	$dompdf -> setPaper(array(0, 0, 305, 75));
	$dompdf -> render();
	$dompdf -> stream("xxx", array('Attachment' => 0));
?>