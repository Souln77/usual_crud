<?php
$title_sufix = " ";
$col="$junction_col";
$val=$junction_val;
if( is_array($junction) AND array_key_exists($col, $junction)  ){
    $junction = (array)$junction;
    $arrlength = count($junction[$col]);
    foreach($junction[$col] AS $no => $dt) { 
        if($dt->id == $val) {
        $title_sufix = $dt->$col; 
        break;
        }                                                                                                                 
    }
}

if(empty($title)){
    $title = str_replace("_"," ", strtoupper($table));
} else {
    $title = strtoupper($title[1]);
}
?>    

<h4 style=""><?php echo strtoupper($button) .' '. $title . ' '.$title_sufix ?>  </h4>
<?php echo form_open_multipart($action) ?>

<?php echo form_error($primary_key) ?>
<input type="hidden" name="<?php echo $primary_key; ?>" value="<?php echo $datas[$primary_key]; ?>" /> 


<?php
foreach ($table_infos as $no => $infos){            

    if(/*$infos->COLUMN_NAME != $primary_key AND*/ is_array($cols) AND in_array($infos->COLUMN_NAME, $cols) ) {
        
        if( !empty($alias) AND array_key_exists($infos->COLUMN_NAME, $alias)){
            $label = $alias[$infos->COLUMN_NAME];
        } else {
            $label = $infos->COLUMN_NAME;
        }

            if($infos->COLUMN_NAME == $primary_key){ ?>
                <div class="form-group">
                    <label for=""><?php echo str_replace("_"," ", strtoupper($label)); ?> </label>
                    <input type="text" disabled class="form-control" name="" id="" value="<?php echo $datas[$infos->COLUMN_NAME]; ?>"/>
                </div>
            <?php }

            // Test s'il s'agit d'un type file
            else if($infos->COLUMN_NAME == $attach_col_name ){                     
                if(empty($datas[$infos->COLUMN_NAME])){ ?>
                    <div class="form-group">
                        <label for="<?php echo $infos->COLUMN_NAME; ?>"><?php echo str_replace("_"," ", strtoupper($label)); ?> <?php echo form_error($infos->COLUMN_NAME) ?></label>
                        <input type="file" class="form-control" name="<?php echo $infos->COLUMN_NAME; ?>" id="<?php echo $infos->COLUMN_NAME; ?>" />
                    </div>
                <?php } else { ?>
                    <label><?php echo strtoupper($infos->COLUMN_NAME); ?> </label>
                    <p>
                    <a target="_blank" href="<?php echo site_url('uploads/'.$datas[$infos->COLUMN_NAME]); ?>"><?php echo $datas[$infos->COLUMN_NAME]; ?></a>
                    <a style="color: red;" href="<?php echo site_url($ctrl_name.'/delete_file/'.$datas[$primary_key].'?name='.$datas[$infos->COLUMN_NAME]); ?>">Supprimer</a>                        
                    <input type="hidden" name="allready_exist" id="allready_exist" />
                    </p>
                <?php }                    
            } 

            else if( is_array($junction) AND array_key_exists($infos->COLUMN_NAME, $junction)  ){ 
                $junction = (array)$junction;
                $arrlength = count($junction[$infos->COLUMN_NAME]);?>
                <div class="form-group">
                    <label for="<?php echo $infos->COLUMN_NAME; ?>"><?php echo str_replace("_"," ", strtoupper($label)); ?> <?php echo form_error($infos->COLUMN_NAME) ?></label>                        
                    <select class="form-control"  class="form-control" name="<?php echo $infos->COLUMN_NAME; ?>" id="<?php echo $infos->COLUMN_NAME; ?>">
                        <option class="form-control"></option>           
                        <?php 
                        foreach($junction[$infos->COLUMN_NAME] AS $no => $dat) { 
                            $col = (string)$infos->COLUMN_NAME;
                            $id = "id";
                            ?> 
                            <div class="form-control">
                            <option class="form-control" <?php if($dat->$id == $datas[$infos->COLUMN_NAME]){echo " selected ";} ?> value="<?php echo $dat->$id; ?>"><?php echo $dat->$col; ?></option>
                            </div>
                        <?php } ?>
                    </select>
                </div>
            <?php } 

            else if ($infos->DATA_TYPE == 'text' AND $infos->COLUMN_NAME != $attach_col_name){ ?>
                <div class="form-group">
                    <label for="<?php echo $infos->COLUMN_NAME; ?>"><?php echo str_replace("_"," ", strtoupper($label)); ?> <?php echo form_error($infos->COLUMN_NAME) ?></label>
                    <textarea class="form-control" row="3" name="<?php echo $infos->COLUMN_NAME; ?>" id="<?php echo $infos->COLUMN_NAME; ?>" placeholder="<?php echo $infos->COLUMN_NAME; ?>" > <?php echo $datas[$infos->COLUMN_NAME]; ?> </textarea>
                </div>
            <?php }                

            else if($infos->DATA_TYPE == 'enum'){                     
                $enum = str_replace("enum(","",$infos->COLUMN_TYPE); 
                $enum = str_replace(")","",$enum); 
                $enum = str_replace("'","",$enum);
                $enum = explode(",",$enum); 
                $arrlength = count($enum);?>
                <div class="form-group">
                    <label for="<?php echo $infos->COLUMN_NAME; ?>"><?php echo str_replace("_"," ", strtoupper($label)); ?> <?php echo form_error($infos->COLUMN_NAME) ?></label>                        
                    <select class="form-control"  class="form-control" name="<?php echo $infos->COLUMN_NAME; ?>" id="<?php echo $infos->COLUMN_NAME; ?>">
                        <option class="form-control" value=""></option>            
                        <?php 
                        for($x = 0; $x < $arrlength; $x++) { ?> 
                            <div class="form-control">
                            <option class="form-control" <?php if($enum[$x] == $datas[$infos->COLUMN_NAME]){echo " selected ";} ?> value="<?php echo $enum[$x]; ?>"><?php echo $enum[$x]; ?></option>
                            </div>
                        <?php } ?>
                    </select>
                </div>
            <?php } 

            else if($infos->DATA_TYPE == "datetime" OR $infos->DATA_TYPE == "date"){ ?>
                <div class="form-group">
                    <label for="<?php echo $infos->COLUMN_NAME; ?>"><?php echo str_replace("_"," ", strtoupper($label)); ?> <?php echo form_error($infos->COLUMN_NAME) ?></label>
                    <input type="date" class="form-control" name="<?php echo $infos->COLUMN_NAME; ?>" id="<?php echo $infos->COLUMN_NAME; ?>" placeholder="<?php echo $infos->COLUMN_NAME; ?>" value="<?php echo $datas[$infos->COLUMN_NAME]; ?>" />
                </div>
            <?php } 

            else { ?>
                <div class="form-group">
                    <label for="<?php echo $infos->COLUMN_NAME; ?>"><?php echo str_replace("_"," ", strtoupper($label)); ?> <?php echo form_error($infos->COLUMN_NAME) ?></label>
                    <input type="text" class="form-control" name="<?php echo $infos->COLUMN_NAME; ?>" id="<?php echo $infos->COLUMN_NAME; ?>" placeholder="<?php echo $infos->COLUMN_NAME; ?>" value="<?php echo $datas[$infos->COLUMN_NAME]; ?>" />
                </div>
            <?php } 
    }
}
    
    
$junction_val = "";
$junction_col = "";

if(!empty($_SESSION['junction_val'])){
    $junction_val = $_SESSION['junction_val'];
}

if(!empty($_SESSION['junction_col'])){
    $junction_col = $_SESSION['junction_col'];
}
?>

<input type="hidden" name="callback_url" value="<?php echo '/index/'.$junction_val.'/'.$junction_col; ?>" /> 
<input type="hidden" name="junction_col" value="<?php echo $junction_col; ?>" /> 
<input type="hidden" name="junction_val" value="<?php echo $junction_val; ?>" /> 

<button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
<a href="<?php echo site_url($ctrl_name.'/index/'.$junction_val.'/'.$junction_col) ?>" class="btn btn-default">Annuler</a>
</form>
