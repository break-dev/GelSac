<?php

	// Navbar principal
	$navbar_maintop_new = '

			<!--NAVBAR-->
	        <!--===================================================-->
	        <header id="navbar">
	            <div id="navbar-container" class="boxed">

	           

	                <!--Brand logo & name-->
	                <!--================================-->
	                <div class="navbar-header">
	                    <a href="#" class="navbar-brand">
	                        <img src="template/img/logo_gelsac_2.png" alt="Minera Logo" class="brand-icon" style="padding: 8px">
	                        <div class="brand-title">
	                            <span class="brand-text" style="color: white; font-size: 30px !important; text-align: center">GEL <label style="font-size: 10px">SAC</label></span>
	                        </div>
	                    </a>
	                </div>
	                <!--================================-->
	                <!--End brand logo & name-->


	                <!--Navbar Dropdown-->
	                <!--================================-->
	                <div class="navbar-content" style="background-color: #0477BF;">
	                
	                	<ul class="nav navbar-top-links">
                      <!--Navigation toogle button-->
                      <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                      <li class="tgl-menu-btn">
                          <a class="mainnav-toggle" href="#">
                              <i class="demo-pli-list-view"></i>
                          </a>
                      </li>
                      <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                      <!--End Navigation toogle button-->
                    </ul>

                    <ul class="nav navbar-top-links">
                      <!--User dropdown-->
                      <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                      <li id="dropdown-user" class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle text-right">
                          <span class="ic-user pull-right" style="font-size: 13px;">
                            <i class="demo-pli-male"></i> 
                            '.$_SESSION["des_sucursal"].' - '.$_SESSION["nom_usuario"].'
                          </span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right panel-default">
                          <ul class="head-list">
                            <li>
                              <a style="cursor: pointer" onclick="f_CerrarSesion();"><i class="demo-pli-unlock icon-lg icon-fw"></i> Cerrar sesión</a>
                            </li>
                          </ul>
                        </div>
                      </li>
                      <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                      <!--End user dropdown-->
                    </ul>
	                </div>
	                <!--================================-->
	                <!--End Navbar Dropdown-->

	            </div>

	        </header>
	        <!--===================================================-->
	        <!--END NAVBAR-->

	        ';


	$navbar_mainleft_new = '
		 <div class="boxed">

	        	<!-- <div id="content-container">
	                <div id="page-head">
						<div class="pad-all text-center">
						    <h3>Bienvenido</h3>
						    <p1>Sistema de Gestión</p>
						</div>
		            </div>
	            </div> -->

	            <nav id="mainnav-container">
	                <div id="mainnav">

	                    <!--Menu-->
	                    <!--================================-->
	                    <div id="mainnav-menu-wrap">
	                        <div class="nano has-scrollbar">
	                            <div class="nano-content" tabindex="0" style="right: -17px;">


	                                <ul id="mainnav-menu" class="list-group">
							
										<div id="div_menu_new" style="font-size: 14px"></div>
									</ul>

	                            </div>
		                        <div class="nano-pane" style="display: none;">
		                        	<div class="nano-slider" style="height: 1789px; transform: translate(0px, 0px);"></div>
		                        </div>
	                    	</div>
	                    </div>
	                    <!--================================-->
	                    <!--End menu-->

	                </div>
	            </nav>



	        </div>	
	';
		

?>

