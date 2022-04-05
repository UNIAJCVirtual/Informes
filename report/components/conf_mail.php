<?php 
	function modal_conf_mail($name,$action,$additional_fields, $target){
		$archivo = 'proccess/conf';
		$abrir = fopen($archivo,'r+');
		$content = fread($abrir,filesize($archivo));
		fclose($abrir);
		 
		// Separar linea por linea
		$content = explode("\n",$content);
		 // var_dump($content);
		?>
			<div id="<?php echo $name;?>" class="modal fade" role="dialog">
		        <div class="modal-dialog modal-lg">
		            <!-- Modal content-->
		            <div class="modal-content">
		            	<div class="form-horizontal">
		                    <div class="modal-header">
		                        <button type="button" class="close" data-dismiss="modal">&times;</button>
		                        <h4 class="modal-title">Configurar Correo</h4>
		                    </div>
		                    <div class="modal-body">
		                        <div class="form-group">
		                            <label class="control-label col-sm-4" for="email">Correo Electrónico:</label>
		                            <div class="col-sm-4">
		                                <input type="email" class="form-control" id="email" name="email" placeholder="Correo Electrónico" value="<?php echo $content[0];?>" autocomplete="off" autofocus required> </div>
		                        </div>
		                        <div class="form-group">
		                            <label class="control-label col-sm-4" for="password">Password:</label>
		                            <div class="col-sm-4">
		                                <input type="password" class="form-control" id="password" name="password" placeholder="Password" value="<?php echo $content[1];?>" autocomplete="off" autofocus required> </div>
		                        </div>
		                         <div class="form-group">
		                            <label class="control-label col-sm-4" for="smtp">SMTPSecure:</label>
		                            <div class="col-sm-4">
		                                <input type="text" class="form-control" id="smtp" name="smtp" placeholder="SMTPSecure" value="<?php echo $content[2];?>" autocomplete="off" autofocus required> </div>
		                        </div>
		                         <div class="form-group">
		                            <label class="control-label col-sm-4" for="host">Host:</label>
		                            <div class="col-sm-4">
		                                <input type="text" class="form-control" id="host" name="host" placeholder="Host" value="<?php echo $content[3];?>" autocomplete="off" autofocus required> </div>
		                        </div>
		                         <div class="form-group">
		                            <label class="control-label col-sm-4" for="port">Port:</label>
		                            <div class="col-sm-4">
		                                <input type="text" class="form-control" id="port" name="port" placeholder="Port" value="<?php echo $content[4];?>" autocomplete="off" autofocus required> </div>
		                        </div>
		                         <div class="form-group">
		                            <label class="control-label col-sm-4" for="setfrom">setFrom:</label>
		                            <div class="col-sm-4">
		                                <input type="email" class="form-control" id="setfrom" name="setfrom" placeholder="setFrom" value="<?php echo $content[5];?>" autocomplete="off" autofocus required> </div>
		                        </div>
		                        <?php echo $additional_fields; ?>
		                    </div>
		                    <div class="modal-footer">
		                        <button type="button" class="btn btn-primary" name="send" onclick="save()"><span class="glyphicon glyphicon-floppy-disk"></span> Guardar</button>
		                        <button type="button" class="btn btn-warning" data-dismiss="modal" id="close"><span class="glyphicon glyphicon-remove-circle"></span> Cancel</button>
		                    </div>
	                    </div>
		            </div>
		        </div>
		    </div>
		    <input type="hidden" data-toggle="modal" data-target="#myModal" id="md_alert">
			<div class="modal fade" id="myModal" role="dialog">
				<div class="modal-dialog modal-sm">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">Mensaje</h4>
						</div>
						<div class="modal-body">
							<p id="text_alert"></p>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-warning" data-dismiss="modal"> Cerrar</button>
						</div>
					</div>
				</div>
			</div>
		    <script type="text/javascript">
		    	function save() {
		    		var close = document.getElementById("close");
		    		var md_alert = document.getElementById("md_alert");
		    		var text = document.getElementById("text_alert");
					var input1 = document.getElementById("email").value;
					var input2 = document.getElementById("password").value;			
					var input3 = document.getElementById("smtp").value;			
					var input4 = document.getElementById("host").value;			
					var input5 = document.getElementById("port").value;			
					var input6 = document.getElementById("setfrom").value;			
					$.ajax({
						url:'proccess/ajax_processor.php',
						data:{
							email:input1,
							password:input2,
							smtp:input3,
							host:input4,
							port:input5,
							setfrom:input6,
							opc:'1'},
						type:'post',
						success: function(data){
				    		close.click();
				    		text.innerHTML = data;
							md_alert.click();
						},
						fail: function(){
							alert(data);
						}
					})
				}
		    </script>
		<?php
	}
?>