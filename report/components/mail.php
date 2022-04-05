<?php 
	function modal_mail($name,$action,$additional_fields, $target){
		?>
			<div id="<?php echo $name;?>" class="modal fade" role="dialog">
		        <div class="modal-dialog modal-lg">
		            <!-- Modal content-->
		            <div class="modal-content">
		                <form method="post" class="form-horizontal" role="form" action="<?php echo $action;?>" <?php echo $target;?>>
		                    <div class="modal-header">
		                        <button type="button" class="close" data-dismiss="modal">&times;</button>
		                        <h4 class="modal-title">Enviar Correo</h4>
		                    </div>
		                    <div class="modal-body">
		                        <div class="form-group">
		                            <label class="control-label col-sm-2" for="subject">Asunto:</label>
		                            <div class="col-sm-4">
		                                <input type="text" class="form-control" id="subject" name="subject" placeholder="Asunto" autocomplete="off" autofocus required> </div>
		                        </div>
		                        <div class="form-group">
		                            <label class="control-label col-sm-2" for="minimum_percentage">Porcentaje Minimo:</label>
		                            <div class="col-sm-4">
		                                <input type="number" class="form-control" id="minimum_percentage" name="minimum_percentage" autocomplete="off" min="0" max="100" step="0.01"> </div>
		                            <label class="control-label col-sm-2" for="maximum_percentage">Porcentaje Maximo:</label>
		                            <div class="col-sm-4">
		                                <input type="number" class="form-control" id="maximum_percentage" name="maximum_percentage" autocomplete="off"  min="0" max="100" step="0.01"> </div>
		                            <div class="col-sm-12">
		                            	<span>para los profesores sin datos dejar los campos de porcentaje vacios</span>
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <label class="control-label col-sm-2" for="item_sub_category">Mensaje:</label>
		                            <div class="col-sm-10">
		                                <textarea class="form-control" id="body" name="body" autocomplete="off" required></textarea>
		                            </div>
		                        </div>
		                        <?php echo $additional_fields; ?>
		                    </div>
		                    <div class="modal-footer">
		                        <button type="submit" class="btn btn-primary" name="send"><span class="glyphicon glyphicon-envelope"></span> Enviar</button>
		                        <button type="button" class="btn btn-warning" data-dismiss="modal"><span class="glyphicon glyphicon-remove-circle"></span> Cancel</button>
		                    </div>
		                </form>
		            </div>
		        </div>
		    </div>
		<?php
	}
?>