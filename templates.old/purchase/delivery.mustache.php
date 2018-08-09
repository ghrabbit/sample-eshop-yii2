
<h4><?php echo Yii::t('app','Comments / Special Instructions');  ?></h4>
  
	<div class="form-group">
		<label class="col-lg-2">
			<input type="hidden" id="deliveryUsedId" name="deliveryUsed" value=<?php echo ($data->deliveryUsed == 1)?"1":"0"?>>
			<input type="checkbox" name="_deliveryUsed" <?php echo ($data->deliveryUsed == 1)?"checked":""?> onclick="var fs = $('#fieldset0'); this.checked?fs.show():fs.hide(); $('#deliveryUsedId').val(this.checked?1:0);/*alert($(this).attr('checked')?this.name + ' check '+this.checked:this.name + ' uncheck '+this.checked)*/">
			<?php echo  Yii::t('app','Delivery used')?>
		</label>
	</div>
  <fieldset id="fieldset0" <?php echo ($data->deliveryUsed == 0)?"hidden":""?>>	
	<div class="form-group">
		<label class="col-lg-2 control-label" for="daddress"><?php echo  Yii::t('app','Address')?></label>
		<div class="col-lg-5">
			<textarea class="form-control" name="daddress"  cols="50" rows="3"><?php echo  CHTML::decode($data->daddress) ?></textarea>
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-lg-2 control-label" for="comments"><?php echo  Yii::t('app','Comments')?></label>
		<div class="col-lg-offset-2 col-lg-5">
			<textarea class="form-control" name="comments"  rows="5"><?php echo CHTML::decode($data->comments) ?></textarea>
		</div>	
	</div>
  </fieldset>	
	

