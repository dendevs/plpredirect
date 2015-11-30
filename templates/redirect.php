<?php
// c'est crade un form dans une table ??? a mon avis oui // FIXME
?>
<h1> Redirect </h1>
<table style="width:100%">
<form action="<?php echo site_url( '/wp-admin/admin-post.php' ); ?>" method="post" id='all_rules'>
  <!-- for user -->
  <tr>
	<td><input type="text" name="origin" value="origin"></td>
	<td><input type="text" name="bound_to" value="bound_to"></td>
	<td><input type="text" name="code" value="code"></td>
  </tr>
  <!-- for handler -->
  <input type="hidden" name="action" value="add_rule" id='type_submit'>
  <input type="hidden" name="back_url" value="<?php echo $back_url ;?>">
  <tr>
	<td colspan='4'> <input type="submit" value="Ajouter" style='width:100%' id='add_or_delete'></td>
  </tr>

<?php foreach( $datas['rules'] as $rule ): ?>
  <tr>
	  <td><?php echo $rule->origin; ?></td>
	  <td><?php echo $rule->bound_to; ?></td> 
	  <td><?php echo $rule->code;?></td>
	  <td><input type="checkbox" name="rules_to_delete[]" value="<?php echo $rule->id;?>" class='submit_to_delete'></td>
  </tr>
<?php endforeach; ?>
</table>
</form>
