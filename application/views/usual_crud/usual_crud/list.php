<?php
$title_sufix = " ";
$col=$junction_col;
$val=$junction_val;
//echo "<pre>"; print_r($junction);
if( is_array($junction) AND array_key_exists($col, $junction)  ){
    $junction = (array)$junction;
    $arrlength = count($junction[$col]);
    //print_r($col);
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
    $title = strtoupper($title[0]);
}
?>    

<div style="width: 98%;">
<h4 style=""><?php echo $title . " " .$title_sufix ?></h4>
<div class="row" style="margin-bottom: 10px">
    
    <?php if(!empty($create_allowed)){  ?>
        <div class="col-sm-2">            
                <?php echo anchor(site_url($ctrl_name.'/create/'.$recall_url),'Ajouter', 'class="btn btn-primary"'); ?>           
        </div>             
    
        <div class="col-sm-10 text-right">
            <form action="<?php echo site_url($ctrl_name.'/index/'.$recall_url); ?>" class="form-inline" method="get">
                <div class="input-group">
                    <input type="text" class="form-control" name="q" value="<?php echo $q; ?>">
                    <span class="input-group-btn">
                        <?php 
                            if ($q <> '')
                            {
                                ?>
                                <a href="<?php echo site_url($ctrl_name.'/index/'.$recall_url); ?>" class="btn btn-default">Réinitialiser</a>
                                <?php
                            }
                        ?>
                        <button class="btn btn-primary" type="submit">Recherche</button>
                    </span>
                </div>
            </form>
        </div>
    <?php } ?>
</div>

<div  style="">
<?php if (COUNT($table_data)==0){ ?>
     <div style="text-align: center;">Aucune donnée trouvé.</div>
    <?php return;
} ?>
<table class=" table-bordered table-striped table-hover table-condensed" style="margin-bottom: 20px;  vertical-align: middle; width: 100%;">
    <thead>
    <tr>
        <th>No</th>
        <?php foreach ($table_data as $datas){
            foreach ($datas as $col => $val){ 
                
                    if($col != $primary_key OR $hide_primary_key == false){
                        if(is_array($alias) AND array_key_exists($col, $alias)){
                            $col = strtoupper($alias[$col]); ?>
                            <th><?php echo $col; ?></th>
                        <?php } else {?>            
                        <th><?php echo str_replace("_"," ", strtoupper($col)); ?></th>
                        <?php }
                    } else if($hide_primary_key == false) {
                        ?> <td><?php 
                            if($col == $attach_col_name AND !empty($val)){
                            echo anchor(site_url("./uploads/".$val), $val, "target='_blank'");
                            } else {
                            echo $val;
                            } ?>
                        </td> <?php
                    }
                
            } ?>
        <?php break;} ?>			
        <th style="text-align:center">ACTION</th>
    </tr>
    </thead><tbody><?php 
    foreach ($table_data as $dat)
    {
    ?>
    
    <tr>
        <td width=""><?php echo ++$start ?></td>
        <?php foreach ($dat as $col => $val) {
            
                if($col != $primary_key){ 
                    ?> <td><?php 
                        if($col == $attach_col_name AND !empty($val)){
                        echo anchor(site_url("./uploads/".$val), $val, "target='_blank'");
                        } else {
                            
                            if( is_array($junction) AND array_key_exists($col, $junction)  ){
                                $junction = (array)$junction;
                                $arrlength = count($junction[$col]);
                                
                                foreach($junction[$col] AS $no => $dt) { 
                                    if($dt->id == $val){ 
                                        echo $dt->$col; 
                                        break;
                                    }                                                                                                         
                                }
                            } else {
                                echo $val;
                            }
                                                    
                        } ?>
                    </td> <?php 
                } else if($hide_primary_key == false) {
                    ?> <td><?php 
                        if($col == $attach_col_name AND !empty($val)){
                        echo anchor(site_url("./uploads/".$val), $val, "target='_blank'");
                        } else {
                        echo $val;
                        } ?>
                    </td> <?php
                }
            
        } ?>				
        <td style="text-align:center" width="">
            <?php 
            if(!empty($read_allowed)){ 
                //echo $read_allowed;
                echo anchor(site_url($ctrl_name.'/read/'.$dat->$primary_key.'/'.$this->uri->segment(4, '').'/'.$this->uri->segment(5, ' ')),'Voir détails'); 
            }
            if(!$is_view){ 
                if(!empty($update_allowed)){               
                    echo ' | '; 
                    echo anchor(site_url($ctrl_name.'/update/'.$dat->$primary_key),'Modifier'); 
                }
                if(!empty($delete_allowed)){ 
                    echo ' | '; 
                    echo anchor(site_url($ctrl_name.'/delete/'.$dat->$primary_key.'/'.$this->uri->segment(4, '').'/'.$this->uri->segment(5, ' ')),'Supprimer','onclick="javasciprt: return confirm(\'Etes-vous sûr(e) ?\')"'); 
                }
            }

            if(!empty($actions)){
                foreach ($actions as $x => $action)
                {
                    echo ' | '; 
                    echo anchor(site_url($action['link'].'/'.$dat->$primary_key).'/' . $action['rel_col'], $action['title']);                 
                }
            }
            ?>
        </td>
    </tr>
    
        <?php
    }
    ?>
    </tbody>
</table>
<div class="row">
    <div class="col-sm-2">
        <a href="#" class="btn btn-primary">Total enr : <?php echo $total_rows ?></a>
</div>
    <div class="col-sm-10 text-right">
        <?php echo $pagination ?>
    </div>
</div>

<script>

    $(document).ready(function() {
        $('#table_id').DataTable();
    } );        

</script>
</div>


    