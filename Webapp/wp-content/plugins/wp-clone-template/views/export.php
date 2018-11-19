<h2>Export themes</h2>
<?php
	if(isset($_POST['Templates'])){
		wpct_export_template($_POST['Templates']);
	}
?>
<form method="post">
	<table class="form-table">
		<tr>
			<th><label for="name">Export theme:</label></th>
			<td><select name="Templates" class="regular-text"><?=wpct_get_templates_options();?></select>Please, choose a template to export</td>
		</tr>
	</table>
	<p class="submit">
		<input type="submit" class="button-primary" value="Export" name="export_template" />
	</p>
</form>
