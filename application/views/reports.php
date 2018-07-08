<?php include('admin_header.php');?>
			<div class='content-page'>
				<div class='content'>
					<div class='container'>
						
						<nav class="navbar navbar-default">
						  <div class="container-fluid">
							<ul class="nav navbar-nav">
							  <li ><a data-toggle="tab" href="#material_taskwise" >Materials Taskwise</a></li>
							  <li><a data-toggle="tab" href="#labour_taskwise">Labours Taskwise</a></li>
							  <li><a data-toggle="tab" href="#equipment_taskwise" >Equipments Taskwise</a></li>
							  <li><a data-toggle="tab" href="#materials" >Materials</a></li>
							  <li><a data-toggle="tab" href="#labours" >Labours</a></li>
							  <li><a data-toggle="tab" href="#equipments"class="active">Equipments</a></li>
							</ul>
						  </div>
						</nav>
						<div class="tab-content" >
							<div id="material_taskwise" class="row tab-pane fade in ">
								<div class='col-md-12'>
									<div class='panel'>
										<div class='panel-body'>
											<div class='col-md-12 col-sm-12 col-xs-12'>
												<?php 
												for($i=0;$i<count($tasks);$i++){
												$total=0;
												?>
												<span><h4><?php echo $task_detail[$i]['task_name'];?></h4></span>
												<table id='table' class="table"  style="width: 100% !important;">
													<thead >
														<tr>
															<th>Material Name</th>
															<th>Material Qty</th>
															<th>Material Rate</th>
															<th>Material Transport Rate</th>
															<th>Material Cost</th>
															<th>Material Task Cost</th>
														</tr>
													</thead>	
													<tbody>	
															<?php	
																foreach($tasks[$i]['task_material'] as $material)
																{
																	
																	echo "<tr>";
															?>
																<td><?php echo $material['material_name'];?></td>
																<td><?php echo $material['material_quantity'];?></td>
																<td><?php echo $material['material_rate'];?></td>
																<td><?php echo $material['material_transport_cost'];?></td>
																<td><?php echo $material['task_material_total_cost'];?></td>
																<td><?php $total+=(int)$material['task_material_total_cost']*(int)$task_detail[$i]['task_area'];echo (int)$material['task_material_total_cost']*(int)$task_detail[$i]['task_area'];?></td>
																
															<?php
																	echo "</tr>";					
																}
															?>	
													</tbody>
													
												</table>
												<span align="right" ><h5 style="padding-right:9%;"><span style="padding-right:20px;"><b>Task Area:<?php echo "  ".$task_detail[$i]['task_area']?></span>Total Cost:<?php echo "  ".$total;?></b></h5></span>
												<?php
												}?>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div id="labour_taskwise" class="row tab-pane fade in">
								<div class='col-md-12'>
									<div class='panel'>
										<div class='panel-body'>
											<div class='col-md-12 col-sm-12 col-xs-12'>
												<?php 
												for($i=0;$i<count($tasks);$i++){
													$total=0;
												?>
												<span><h4><?php echo $task_detail[$i]['task_name'];?></h4></span>
												<table id='table' class="table"  style="width: 100% !important;">
													<thead >
														<tr>
															<th>Labour Name</th>
															<th>Labour Qty</th>
															<th>Labour Rate</th>
															<th>Labour Cost</th>
															<th>Labour Task Cost</th>
														</tr>
													</thead>	
													<tbody>	
															<?php	
																foreach($tasks[$i]['task_labour'] as $labour)
																{
																	
																	echo "<tr>";
															?>
																<td><?php echo $labour['labour_name'];?></td>
																<td><?php echo $labour['labour_quantity'];?></td>
																<td><?php echo $labour['labour_rate'];?></td>
																<td><?php echo $labour['task_labour_total_cost'];?></td>
																<td><?php $total+=(int)$labour['task_labour_total_cost']*(int)$task_detail[$i]['task_area'];echo (int)$labour['task_labour_total_cost']*(int)$task_detail[$i]['task_area'];?></td>
															<?php
																	echo "</tr>";					
																}
															?>	
													</tbody>
													
												</table>
												<span align="right" ><h5 style="padding-right:9%;"><span style="padding-right:20px;"><b>Task Area:<?php echo "  ".$task_detail[$i]['task_area']?></span>Total Cost:<?php echo "  ".$total;?></b></h5></span>
												<?php
												}?>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div id="equipment_taskwise" class="row tab-pane fade in  ">							
								<div class='col-md-12'>
									<div class='panel'>
										<div class='panel-body'>
											<div class='col-md-12 col-sm-12 col-xs-12'>
												<?php 
												for($i=0;$i<count($tasks);$i++){
													$total=0;
												?>
												<span><h4><?php echo $task_detail[$i]['task_name'];?></h4></span>
												<table id='table' class="table"  style="width: 100% !important;">
													<thead >
														<tr>
															<th>Equipment Name</th>
															<th>Equipment Qty</th>
															<th>Equipment Rate</th>
															<th>Equipment Run Rate</th>
															<th>Equipment Cost</th>
															<th>Equipment Task Cost</th>
														</tr>
													</thead>	
													<tbody>	
															<?php	
																foreach($tasks[$i]['task_equipment'] as $equipment)
																{
																	
																	echo "<tr>";
															?>
																<td><?php echo $equipment['equipment_name'];?></td>
																<td><?php echo $equipment['equipment_quantity'];?></td>
																<td><?php echo $equipment['equipment_rate'];?></td>
																<td><?php echo $equipment['equipment_run_cost'];?></td>
																<td><?php echo $equipment['task_equipment_total_cost'];?></td>
																<td><?php $total+=(int)$equipment['task_equipment_total_cost']*(int)$task_detail[$i]['task_area'];echo (int)$equipment['task_equipment_total_cost']*(int)$task_detail[$i]['task_area'];?></td>
															<?php
																	echo "</tr>";					
																}
															?>	
													</tbody>
													
												</table>
												<span align="right" ><h5 style="padding-right:9%;"><span style="padding-right:20px;"><b>Task Area:<?php echo "  ".$task_detail[$i]['task_area']?></span>Total Cost:<?php echo "  ".$total;?></b></h5></span>
												<?php
												}?>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div id="materials" class="row tab-pane fade in ">							
								<div class='col-md-12'>
									<div class='panel'>
										<div class='panel-body'>
											<div class='col-md-12 col-sm-12 col-xs-12'>
												<table id='table ' class="table"  style="width: 100% !important;">
													<thead >
														<tr>
															<th>Material Name</th>
															<th>Material Rate</th>
															<th>Material Transport Rate</th>
															<th>Material Total Qty</th>
															<th>Material Cost</th>
															<th>Material Transport Cost</th>
															<th>Total Cost</th>
														</tr>
													</thead>	
													<tbody>	
															<?php	
															$total=0;
																foreach($total_materials as $material)
																{
																	
																	echo "<tr>";
															?>
																<td><?php echo $material['material_name'];?></td>
																<td><?php echo $material['material_rate'];?></td>
																<td><?php echo $material['material_transport_cost'];?></td>
																<td><?php echo $material['total_quantity']." ".$material['unit_name'];?></td>
																<td><?php echo $material['task_material_cost'];?></td>
																<td><?php echo $material['task_transport_cost'];?></td>
																<td><?php $total+=$material['task_material_total_cost'];echo $material['task_material_total_cost'];?></td>
															<?php
																	echo "</tr>";					
																}
															?>	
													</tbody>
													
												</table>
												<hr><hr>
												<span align="right" ><h5 style="padding-right:3%;"><b>Total Cost :&nbsp;<?php echo " ".$total;?></b></h5></span>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div id="labours" class="row tab-pane fade in">							
								<div class='col-md-12'>
									<div class='panel'>
										<div class='panel-body'>
											<div class='col-md-12 col-sm-12 col-xs-12'>
												<table id='table ' class="table"  style="width: 100% !important;">
													<thead >
														<tr>
															<th>Labour Name</th>
															<th>Labour Rate</th>
															<th>Labour Total Qty</th>
															<th>Labour Cost</th>
														</tr>
													</thead>	
													<tbody>	
															<?php	
															$total=0;
																foreach($total_labours as $labour)
																{
																	
																	echo "<tr>";
															?>
																<td><?php echo $labour['labour_name'];?></td>
																<td><?php echo $labour['labour_rate'];?></td>
																<td><?php echo $labour['total_quantity']." ".$labour['unit_name'];?></td>
																<td><?php $total+=$labour['task_labour_total_cost'];echo $labour['task_labour_total_cost'];?></td>
															<?php
																	echo "</tr>";					
																}
															?>	
													</tbody>
													
												</table>
												<hr><hr>
												<span align="right" ><h5 style="padding-right:17%;"><b>Total Cost :&nbsp;<?php echo " ".$total;?></b></h5></span>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div id="equipments" class="row tab-pane fade in active">							
								<div class='col-md-12'>
									<div class='panel'>
										<div class='panel-body'>
											<div class='col-md-12 col-sm-12 col-xs-12'>
												<table id='table ' class="table"  style="width: 100% !important;">
													<thead >
														<tr>
															<th>Equipment Name</th>
															<th>Equipment Rate</th>
															<th>Equipment Run Rate</th>
															<th>Equipment Total Qty</th>
															<th>Equipment Cost</th>
															<th>Equipment Run Cost</th>
															<th>Equipment Cost</th>
														</tr>
													</thead>	
													<tbody>	
															<?php	
															$total=0;
																foreach($total_equipments as $equipment)
																{
																	
																	echo "<tr>";
															?>
																<td><?php echo $equipment['equipment_name'];?></td>
																<td><?php echo $equipment['equipment_rate'];?></td>
																<td><?php echo $equipment['equipment_run_cost'];?></td>
																<td><?php echo $equipment['total_quantity']." ".$equipment['unit_name'];?></td>
																<td><?php echo $equipment['task_equipment_cost'];?></td>
																<td><?php echo $equipment['task_equipment_run_cost'];?></td>
																<td><?php $total+=$equipment['task_equipment_total_cost'];echo $equipment['task_equipment_total_cost'];?></td>
															<?php
																	echo "</tr>";					
																}
															?>	
													</tbody>
													
												</table>
												<hr><hr>
												<span align="right" ><h5 style="padding-right:7%;"><b>Total Cost :&nbsp;<?php echo " ".$total;?></b></h5></span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
			</div>
<?php include('admin_footer.php');?>