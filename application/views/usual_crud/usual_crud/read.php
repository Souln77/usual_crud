<?php
if(empty($title)){
    $title = str_replace("_"," ", strtoupper($table));
} else {
    $title = strtoupper($title[1]);
}  
?>  
	<h4 style="">ENR <?php echo str_replace("_"," ", strtoupper($title)); ?></h4>
	<table class="table">
	<?php foreach ($datas as $key => $val ){ 
		if(is_array($cols) AND in_array($key, $cols)) {	?>
			<tr>
				<td>				
					<?php 
					if(is_array($alias) AND array_key_exists($key, $alias)){
						//$key = $alias[$key];
						echo strtoupper($alias[$key]);
					} else {
						echo strtoupper($key);
					}
						?>
				</td>
				<td>
					<?php 
						if($key == $attach_col_name){ ?>
							<a href="<?php echo site_url("./uploads/".$val); ?>" target="_blank"><?php echo $val; ?></a>
						<?php } else {
							if( is_array($junction) AND array_key_exists($key, $junction)  ){
								$junction = (array)$junction;
								$arrlength = count($junction[$key]);
								foreach($junction[$key] AS $no => $dt) { 
									
									if($dt->id == $val) 
									echo $dt->$key;										
								} 
							} else {
									echo $val;
								}
						} 
					?>
				</td>
			</tr>			
		<?php }
	} ?>	    
	<tr><td></td><td><a href="Javascript:history.go(-1)" class="btn btn-default">Annuler</a></td></tr>
</table>
        