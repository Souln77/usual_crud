<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Usual_crud
{     

    function __construct()
    {
        $this->load->model('Usual_crud_model');
        $this->load->library('form_validation');
    }

    
	public function __get($var)
	{
		return get_instance()->$var;
	}

    public $table;
    public $attach_col_name = '';
    public $is_view = FALSE;
    public $ctrl_name;
    public $cols;
    public $alias = [];
    public $allow_upload;
    public $rel_table_config;
    public $additonnal_query;
    public $title;
    public $list_fields;
    public $read_fields;
    public $create_fields;
    public $update_fields;
    public $hide_primary_key_in_list_fields;
    public $create_validations = [];
    public $update_validations = [];
    public $read_allowed = true;
    public $create_allowed = true;
    public $update_allowed = true;
    public $delete_allowed = false;

    function cols(){        
        return $this->cols;         
    }    

    function set_title($title=['', '']){
        $this->title = $title;
    }

    function title(){
        return $this->title;
    }

    function set_list_fields($cols=[]){
        if(empty($cols)){
            $table_infos =  $this->Usual_crud_model->get_table_infos($this->table());
            $cols = [];            
            foreach ($table_infos as $infos){
                array_push($cols, $infos->COLUMN_NAME); 
            };     
            $this->list_fields = $cols;
        } else {
            $this->set_hide_primary_key_in_list_fields(true);
            $colsL = COUNT($cols);
            for($i= 0; $i < $colsL; $i++){
                $col = $cols[$i];                
                if($col == $this->primary_key()) {
                    //echo $this->primary_key();
                    $this->set_hide_primary_key_in_list_fields(false);
                    break;
                }
            };  
            $this->list_fields = $cols;
        }        
    }     

    function list_fields(){
        return $this->list_fields;
    }

    function set_hide_primary_key_in_list_fields($choice){
        $this->hide_primary_key_in_list_fields = $choice;
    }

    function hide_primary_key_in_list_fields(){
        return $this->hide_primary_key_in_list_fields;
    }

    function unset_list_fields($cols=[]){
        if(!empty($cols)){
            $colsL = COUNT($cols);
            for ($i = 0; $i < $colsL; $i++){                
                $fields_saved = $this->list_fields;                
                $z_count = COUNT($fields_saved);
                for ($z = 0; $z < $z_count; $z++){
                    if($fields_saved[$z] == $cols[$i]){
                        $this->list_fields[$z] = ""; 
                        break;
                    }
                }               
            }
            
        }               
    }

    function set_read_fields($cols=[]){
        if(empty($cols)){
            $table_infos =  $this->Usual_crud_model->get_table_infos($this->table());
            $cols = [];            
            foreach ($table_infos as $infos){
                array_push($cols, $infos->COLUMN_NAME); 
            };     
            $this->read_fields = $cols;
        } else {
            $this->read_fields = $cols;
        }  
    }

    function read_fields(){
        if(empty($this->read_fields)){
            $this->set_read_fields(null);
        }
        return $this->read_fields;
    }

    function unset_read_fields($cols=[]){
        if(!empty($cols)){
            $colsL = COUNT($cols);
            for ($i = 0; $i < $colsL; $i++){                
                $fields_saved = $this->read_fields;
                $z_count = COUNT($fields_saved);
                for ($z = 0; $z < $z_count; $z++){
                    if($fields_saved[$z] == $cols[$i]){
                        $this->read_fields[$z] = ""; 
                        break;
                    }
                }               
            }
            
        }               
    }
    
    function set_create_fields($cols=[]){
        if(empty($cols)){
            $table_infos =  $this->Usual_crud_model->get_table_infos($this->table());
            $cols = [];            
            foreach ($table_infos as $infos){
                array_push($cols, $infos->COLUMN_NAME); 
            };     
            $this->create_fields = $cols;
        } else {
            $this->create_fields = $cols;
        }      
        //$this->create_fields = $fields;
    }

    function unset_create_fields($cols=[]){
        if(!empty($cols)){
            $colsL = COUNT($cols);
            for ($i = 0; $i < $colsL; $i++){                
                $fields_saved = $this->create_fields;
                $z_count = COUNT($fields_saved);
                for ($z = 0; $z < $z_count; $z++){
                    if($fields_saved[$z] == $cols[$i]){
                        $this->create_fields[$z] = ""; 
                        break;
                    }
                }               
            }
            
        }               
    }
    
    function create_fields(){
        if(empty($this->create_fields)){
            $this->set_create_fields(null);
        }
        return $this->create_fields;
    }
    
    function set_update_fields($cols=[]){
        if(empty($cols)){
            $table_infos =  $this->Usual_crud_model->get_table_infos($this->table());
            $cols = [];            
            foreach ($table_infos as $infos){
                array_push($cols, $infos->COLUMN_NAME); 
            };     
            $this->update_fields = $cols;
        } else {
            $this->update_fields = $cols;
        }        
        //$this->update_fields = $fields;
    }

    function unset_update_fields($cols=[]){
        if(!empty($cols)){
            $colsL = COUNT($cols);
            for ($i = 0; $i < $colsL; $i++){                
                $fields_saved = $this->update_fields;
                $z_count = COUNT($fields_saved);
                for ($z = 0; $z < $z_count; $z++){
                    if($fields_saved[$z] == $cols[$i]){
                        $this->update_fields[$z] = ""; 
                        break;
                    }
                }               
            }
            
        }               
    }
    
    function update_fields(){
        if(empty($this->update_fields)){
            $this->set_update_fields(null);
        }
        return $this->update_fields;
    }

    function allow_upload(){
        return $this->allow_upload;
    }

    function upload_allowed($status=true){
        $this->allow_upload = $status;
    }


    function alias(){
        return $this->alias ; 
    }

    function set_alias($field, $alias){
        //if(!empty($field)){
            $this->alias = array_merge($this->alias, [$field => $alias]);
            //$this->alias = $alias;
        //}         
    }


    public $jonctions = [];    
    
    function set_join($col, $dest_table, $dest_select, $separator)
    {        
        $jcts[$col] = [$dest_table, $dest_select, $separator];       
        array_push($this->jonctions, $jcts); 
    }

    function get_join(){
        //if(empty($this->src_col) OR empty($this->dest_table) OR empty($this->dest_select) OR empty($this->separator)){
            //return;
        //}  
        /*if(empty($junction)){
            print_r($junction);
            return;
        }*/

        //echo "<pre>"; print_r($this->jonctions);
        foreach ($this->jonctions as $key => $val ){                       
            $src_col = array_keys($val)[0]; 
            //echo "<pre>"; print_r($src_col);            
            $dest_table = $val[$src_col][0];
            $dest_select = $val[$src_col][1];
            $separator = $val[$src_col][2]; 

            if(!empty($src_col) AND !empty($dest_table) AND !empty($dest_select) ){          
                $result = $this->Usual_crud_model->get_join($this->table(), $src_col, $dest_table, $dest_select, $this->primary_key(), $this->primary_key($dest_table), $separator, $this->additonnal_query());
                $junction[$src_col] = $result;
            } 

        }

        //echo "<pre>"; print_r($junction); return;
        //$result = $this->Usual_crud_model->get_join($this->table(), $this->src_col, $this->dest_table, $this->dest_select, $this->primary_key(), $this->primary_key($this->dest_table), $this->separator);
        //$junction[$this->src_col] = $result;           
        if(!empty($junction)){
            return $junction;
        }
            
    }

    public $actions = [];

    function set_actions($title, $link, $rel_col=null){        
        $acts = [
            'title' => $title,
            'link' => $link,
            'rel_col' => $rel_col,
        ];
        array_push($this->actions, $acts);        
    }

    function actions(){
        return $this->actions;
    }

    function set_create_validations($field, $label, $rule, $error=[]){
        $validations = array (
            'field' => $field, 
            'label' => $label, 
            'rules' => $rule,
            'errors' => $error,
        );
        if(!array_key_exists($field, $this->create_validations)){
            $this->create_validations[$field] = $validations;
        }               
    }

    function create_validations(){
        return $this->create_validations;
    }

    function set_update_validations($field, $label, $rule, $error=[]){
        $validations = array (
            'field' => $field, 
            'label' => $label, 
            'rules' => $rule,
            'errors' => $error,
        );
        if(!array_key_exists($field, $this->update_validations)){
            $this->update_validations[$field] = $validations;
        }               
    }

    function update_validations(){
        return $this->update_validations;
    }

    function rel_table_config(){
        return $this->rel_table_config;
    }

    function set_rel_table_config($id, $rel_col_1, $rel_col_2){
        $this->rel_table_config = [$id, $rel_col_1, $rel_col_2];
    }


    function set_additonnal_query($where_col, $where_val){
        $q = [
            'where_col' => $where_col,
            'where_val' => $where_val,
        ];
        $this->additonnal_query = $q;
    }

    function additonnal_query(){        
        return $this->additonnal_query;
    }    

    function set_read_allowed($status=true){
        $this->read_allowed = $status;
    }

    function read_allowed(){
        return $this->read_allowed;
    }

    function set_create_allowed($status=true){
        $this->create_allowed = $status;
    }

    function create_allowed(){
        return $this->create_allowed;
    }

    function set_update_allowed($status=true){
        $this->update_allowed = $status;
    }

    function update_allowed(){
        return $this->update_allowed;
    }

    function set_delete_allowed($status=false){
        $this->delete_allowed = $status;
    }

    function delete_allowed(){
        return $this->delete_allowed;
    }

    function set_same_val_rules_4_create_and_update($option=true){
        $this->update_validations = $this->create_validations;
    } 

    


    public function upload_config(){
        $config['upload_path']          = './uploads/';
        $config['overwrite']            = FALSE;        
        $config['allowed_types']        = 'gif|jpg|jpeg|png|pdf|doc|docx|odt';
        $config['max_size']             = 1000;
        $config['max_width']            = 1024;
        $config['max_height']           = 768;
        return $config;
    }


    function set_table($name=""){
        $this->table = $name;
    }

    function table(){
        return $this->table;
    }

    function set_ctrl_name($name){
        return $this->ctrl_name = $name;
    }

    function ctrl_name(){
        return $this->ctrl_name;
    }

    function primary_key($table=NULL){
        if(empty($table)){
            $table = $this->table();
        }

        $primary_key = $this->Usual_crud_model->get_primary_key($table); 
        if(COUNT($primary_key) > 0){
            return $primary_key[0]->COLUMN_NAME;
        } else {
            $this->is_view = TRUE;
            return 'id';
        }
    }    

    function attach_col_name(){
        $attach_col_name = $this->Usual_crud_model->get_attach_col_name($this->table(), 'fichier_joint');        
        if (!empty($this->attach_col_name)){
            return $this->attach_col_name;
        } else if (COUNT($attach_col_name) > 0){
            return 'fichier_joint';
        } 
    }

    public function index()
    {  
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));
        
        if ($q <> '') {
            $config['base_url'] = base_url() . $this->ctrl_name().'/index?q=' . urlencode($q);
            $config['first_url'] = base_url() . $this->ctrl_name().'/index?q=' . urlencode($q);
        } else {
            $config['base_url'] = base_url() . $this->ctrl_name().'/index';
            $config['first_url'] = base_url() . $this->ctrl_name().'/index';
        }

        $table_infos =  $this->Usual_crud_model->get_table_infos($this->table());
        //log_message('error', print_r($table_infos, true));
        
        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;  
       
        if(!empty($this->uri->segment(5, 0))){
            $foreign_col_name = $this->uri->segment(5, 0) ;
            $foreign_col_id_val = $this->uri->segment(4, 0);  
            $recall_url = $foreign_col_id_val.'/'.$foreign_col_name; 
            //$recall_url_create = "?junction_val=".$this->uri->segment(3, '')."&junction_col=".$this->uri->segment(4, '');
             
            $_SESSION['junction_col'] =   $foreign_col_name;
            $_SESSION['junction_val'] =   $foreign_col_id_val; 
            $config['total_rows'] = $this->Usual_crud_model->total_rows( $this->list_fields(), $this->table(), $this->primary_key(), $table_infos, $q, $foreign_col_name, $foreign_col_id_val, $this->additonnal_query());
            $table_data = $this->Usual_crud_model->get_limit_data( $this->list_fields(), $this->table(), $this->primary_key(), $table_infos, $config['per_page'], $start, $q, $foreign_col_name, $foreign_col_id_val, $this->additonnal_query());
        } else {
            $_SESSION['junction_col'] = '';
            $_SESSION['junction_val'] = '';
            $recall_url = '';
            //$recall_url_create = '';
            //print_r($this->additonnal_query()); return;       
            
            $config['total_rows'] = $this->Usual_crud_model->total_rows( $this->list_fields(), $this->table(), $this->primary_key(), $table_infos, $q, "", "", $this->additonnal_query());
            $table_data = $this->Usual_crud_model->get_limit_data( $this->list_fields(), $this->table(), $this->primary_key(), $table_infos, $config['per_page'], $start, $q, "", "", $this->additonnal_query());
        }

        
        $this->load->library('pagination');
        $this->pagination->initialize($config);
        
        $data = array(
            'table_data' => $table_data,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
            //'table_infos' => $table_infos,
            'primary_key' => $this->primary_key(),
            'table' => $this->table(),
            'ctrl_name' => $this->ctrl_name(),
            'is_view' => $this->is_view,
            'attach_col_name' => $this->attach_col_name(),
            'alias' => $this->alias(),
            'hide_primary_key' => $this->hide_primary_key_in_list_fields(),
            'recall_url' => $recall_url,
            //'recall_url_create' => $recall_url_create,
            'title' => $this->title(),
        ); 

        $data['junction'] = $this->get_join();
        $data['junction_col'] = $_SESSION['junction_col']; 
        $data['junction_val'] = $_SESSION['junction_val'];        
        $data['actions'] = $this->actions();
        $data['read_allowed'] = $this->read_allowed();            
        $data['create_allowed'] = $this->create_allowed(); 
        $data['update_allowed'] = $this->update_allowed(); 
        $data['delete_allowed'] = $this->delete_allowed();        
        
        $this->load->view('templates/header');
        $this->load->view('usual_crud/list', $data);
        $this->load->view('templates/footer');
    }

    public function read($id) 
    {
        if(!$this->read_allowed()){
            $this->session->set_flashdata('message', 'Affichage non autorisé.');
            redirect(site_url($this->ctrl_name()."/index"));
        }

        $row = $this->Usual_crud_model->get_by_id($id, $this->table(), $this->primary_key());
        if ($row) {
            $table_infos =  $this->Usual_crud_model->get_table_infos($this->table());            
            $row = (array)$row;
            foreach ($table_infos as $no => $infos ){
                $data[$infos->COLUMN_NAME] = $row[$infos->COLUMN_NAME];
            };
            $data['datas'] = $data;
            $data['table'] = $this->table();
            $data['ctrl_name'] = $this->ctrl_name();  
            $data['alias'] = $this->alias(); 
            $data['cols'] = $this->read_fields();             
            $data['attach_col_name'] = $this->attach_col_name();
            $data['junction'] = $this->get_join();
            //$data['recall_url'] = $this->uri->segment(3, 0).'/'.$this->uri->segment(5, 0);           
            $data['title'] = $this->title();

            $this->load->view('templates/header');
            $this->load->view('usual_crud/read', $data);
            $this->load->view('templates/footer');
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url($this->ctrl_name()));
        }
    }
    
    public function create() 
    {
        if(!$this->create_allowed()){
            $this->session->set_flashdata('message', 'Ajout non autorisé.');
            redirect(site_url($this->ctrl_name()."/index"));
        }
        //echo "<pre>"; print_r($this->create_validations);
        if(!empty($this->uri->segment(5, 0))){
            $rel_col = $this->uri->segment(5, 0);
            $table_scr_id_val = $this->uri->segment(4, 0);
            $_POST[$rel_col] = $table_scr_id_val;
        } 

        $data = array(
            'button' => 'Ajouter',
            'table' => $this->table(),
            'ctrl_name' => $this->ctrl_name(),
            'action' => site_url($this->ctrl_name().'/create_action'),			
		);
        
        $table_infos =  $this->Usual_crud_model->get_table_infos($this->table());            
        foreach ($table_infos as $no => $infos ){
            $data[$infos->COLUMN_NAME] = set_value($infos->COLUMN_NAME);
        };          

        $data['datas'] = $data;
        $data['table_infos'] = $table_infos;
        $data['primary_key'] = $this->primary_key();  
        $data['attach_col_name'] = $this->attach_col_name();
        $data['cols'] = $this->create_fields();
        $data['alias'] = $this->alias();
        $data['junction'] = $this->get_join();
        $data['junction_col'] = $this->uri->segment(5, 0); 
        $data['junction_val'] = $this->uri->segment(4, 0);
        $data['title'] = $this->title();              
            
        $this->load->view('templates/header');
        $this->load->view('usual_crud/form', $data);
        $this->load->view('templates/footer');
    }
    
    public function create_action() 
    {    
        if(!$this->create_allowed()){
            $this->session->set_flashdata('message', 'Ajout non autorisé.');
            redirect(site_url($this->ctrl_name()."/index"));
        }

        $rel_table_config = $this->rel_table_config();
        if(!empty($rel_table_config)){
            $_POST[$rel_table_config[0]]  =   $_POST[$rel_table_config[1]].'.'.$_POST[$rel_table_config[2]];
        }
        $this->_create_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
            //redirect(site_url($this->ctrl_name()."/create/".$_POST['junction_val']."/".$_POST['junction_col']));
        } else {
            
            $_POST['date_creation'] = date("Y-m-d H:i:s");
            $_POST['date_maj'] = date("Y-m-d H:i:s");
            
            //echo "<pre>"; print_r($_POST); return;
            if(!empty($this->attach_col_name()) AND $this->allow_upload()){
                $config = $this->upload_config();
                $this->load->library('upload', $config);

                if ( ! $this->upload->do_upload($this->attach_col_name()))
                    {
                        $this->session->set_flashdata('message', $this->upload->display_errors());
                            return $this->create();
                    }                
                    else
                    {
                            $data = array('upload_data' => $this->upload->data());
                    }

                $_POST[$this->attach_col_name()] = $this->upload->data('file_name');            
            }           

            $table_infos =  $this->Usual_crud_model->get_table_infos($this->table());            
            unset($data);
            foreach ($table_infos as $no => $infos ){
                if(!empty($this->input->post($infos->COLUMN_NAME, TRUE))){
                    $data[$infos->COLUMN_NAME] = $this->input->post($infos->COLUMN_NAME, TRUE);
                }
            };

             
            $this->Usual_crud_model->insert($data, $this->table());
            $this->session->set_flashdata('message', 'Enr ajouté');
            redirect(site_url($this->ctrl_name().$_POST['callback_url']));
        }
    }
    
    public function update($id) 
    {   
        if(!$this->update_allowed()){
            $this->session->set_flashdata('message', 'Modification non autorisé.');
            redirect(site_url($this->ctrl_name()."/index"));
        }

        if(null === $this->uri->segment(4, '')){
            $id = $this->uri->segment(4, '');
        }
        //$id = $this->uri->segment(4, 0);
        $row = $this->Usual_crud_model->get_by_id($id, $this->table(), $this->primary_key());

        if ($row) {
            $row = (array)$row;
            $data = array(
                'button' => 'Modifier',
                'table' => $this->table(),
                'ctrl_name' => $this->ctrl_name(),
                'action' => site_url($this->ctrl_name().'/update_action'),				
		    );
            $table_infos =  $this->Usual_crud_model->get_table_infos($this->table());            
            foreach ($table_infos as $no => $infos ){
                $data[$infos->COLUMN_NAME] = $row[$infos->COLUMN_NAME];
            };
            $data['datas'] = $data;
            $data['table_infos'] = $table_infos;
            $data['primary_key'] = $this->Usual_crud_model->get_table_infos($this->table())[0]->COLUMN_NAME;  
            $data[$data['primary_key']] = $id;
            $data['attach_col_name'] = $this->attach_col_name();
            $data['cols'] = $this->update_fields();
            $data['alias'] = $this->alias();
            $data['junction'] = $this->get_join();
            $data['junction_col'] = $this->uri->segment(4, 0); 
            $data['junction_val'] = $this->uri->segment(3, 0);
            $data['title'] = $this->title();            
            
            $this->load->view('templates/header');
            $this->load->view('usual_crud/form', $data);
            $this->load->view('templates/footer');
        } else {
            $this->session->set_flashdata('message', 'Enr non trouvé');
            redirect(site_url($this->ctrl_name()."/index"));
        }
    }
    
    public function update_action() 
    {
        if(!$this->update_allowed()){
            $this->session->set_flashdata('message', 'Modification non autorisé.');
            redirect(site_url($this->ctrl_name()."/index"));
        }

        $this->_update_rules();
            
        if ($this->form_validation->run() == FALSE) {            
            $this->update($this->input->post($this->primary_key(), TRUE));
        } else {

            $_POST['date_maj'] = date("Y-m-d H:i:s");

            if(!empty($this->attach_col_name()) AND null === $this->input->post('allready_exist', TRUE) AND $this->allow_upload() ){
                $config = $this->upload_config();
                $this->load->library('upload', $config);

                if ( ! $this->upload->do_upload($this->attach_col_name() ))
                    {
                        $this->session->set_flashdata('message', $this->upload->display_errors());
                            return $this->update($this->input->post($this->primary_key(), TRUE));
                    }                
                    else
                    {
                            $data = array('upload_data' => $this->upload->data());
                    }

                $_POST[$this->attach_col_name()] = $this->upload->data('file_name');            
            }

            

            $table_infos =  $this->Usual_crud_model->get_table_infos($this->table());            
            //;
            unset($data['upload_data']);
            foreach ($table_infos as $no => $infos ){
                if(!empty($this->input->post($infos->COLUMN_NAME, TRUE))){
                    $data[$infos->COLUMN_NAME] = $this->input->post($infos->COLUMN_NAME, TRUE); 
                }                               
            };            

            if(null !== $this->input->post('allready_exist', TRUE) ){
                unset($data[$this->attach_col_name()]);
            }
            //log_message('error', print_r($data, true));  

            $this->Usual_crud_model->update($this->input->post($this->primary_key(), TRUE), $data, $this->table(), $this->primary_key());
            $this->session->set_flashdata('message', 'Enr modifié');
            redirect(site_url($this->ctrl_name().$_POST['callback_url']));
        }
    }

    public function delete_file($id) {        
        unlink(FCPATH.'uploads/'.$_GET['name']);

        $data = array (
            $this->attach_col_name() => '',
        );
        $this->Usual_crud_model->update($id, $data, $this->table(), $this->primary_key());            
        
        $this->update($id);
    }
    
    public function delete($id) 
    {
        if(!$this->delete_allowed()){
            $this->session->set_flashdata('message', 'Suppression non autorisé.');
            redirect(site_url($this->ctrl_name()."/index"));
        }

       $row = $this->Usual_crud_model->get_by_id($id, $this->table(), $this->primary_key());
        
        if ($row) {
            $this->Usual_crud_model->delete($id, $this->table(), $this->primary_key());
            $this->session->set_flashdata('message', 'Enr supprimé');
            redirect(site_url($this->ctrl_name().'/index/'.$this->uri->segment(5, ' ').'/'.$this->uri->segment(6, ' ')));
        } else {
            $this->session->set_flashdata('message', 'Enr non trouvé');
            redirect(site_url($this->ctrl_name().'/index/'.$this->uri->segment(5, ' ').'/'.$this->uri->segment(6, ' ')));
        }
    }

    public function _create_rules() 
    {
        $table_infos =  $this->Usual_crud_model->get_table_infos($this->table());            
        foreach ($table_infos as $no => $infos ){
            if($infos->COLUMN_NAME != $this->attach_col_name() AND ($infos->COLUMN_KEY == 'PRI' OR in_array($infos->COLUMN_NAME, $this->create_fields())) ){
                if(is_array($this->create_validations()) AND array_key_exists($infos->COLUMN_NAME, $this->create_validations())){
                    //echo '<pre>';print_r($this->create_validations()) . '<br>'; 
                    $field = $this->create_validations()[$infos->COLUMN_NAME]['field'];
                    $label = $this->create_validations()[$infos->COLUMN_NAME]['label'];
                    $rules = $this->create_validations()[$infos->COLUMN_NAME]['rules'];
                    $errors = $this->create_validations()[$infos->COLUMN_NAME]['errors'];

                    $this->form_validation->set_rules($field, $label, $rules, $errors);                    
                } else if ($infos->COLUMN_KEY != 'PRI' AND $infos->COLUMN_NAME != $this->attach_col_name()) {
                    $this->form_validation->set_rules($infos->COLUMN_NAME, $infos->COLUMN_NAME, 'trim|required');                   
                }  else if ( !empty($this->rel_table_config()) AND $infos->COLUMN_KEY == 'PRI' AND $infos->COLUMN_NAME == $this->rel_table_config()[0]) {
                    $this->form_validation->set_rules($infos->COLUMN_NAME, $infos->COLUMN_NAME, 'trim|required|is_unique['.$infos->TABLE_NAME.'.'.$infos->COLUMN_NAME.']', array('is_unique' => 'L\'enregistrement a été annulé éviter un doublon dans les données.'));                   
                }                            
            }
        };
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function _update_rules() 
    {
        $table_infos =  $this->Usual_crud_model->get_table_infos($this->table());            
        foreach ($table_infos as $no => $infos ){
            if($infos->COLUMN_NAME != $this->attach_col_name() AND in_array($infos->COLUMN_NAME, $this->update_fields())){
                if(is_array($this->create_validations()) AND array_key_exists($infos->COLUMN_NAME, $this->update_validations())){
                    //echo '<pre>';print_r($this->create_validations()) . '<br>'; 
                    $field = $this->create_validations()[$infos->COLUMN_NAME]['field'];
                    $label = $this->create_validations()[$infos->COLUMN_NAME]['label'];
                    $rules = $this->create_validations()[$infos->COLUMN_NAME]['rules'];
                    $errors = $this->create_validations()[$infos->COLUMN_NAME]['errors'];

                    $this->form_validation->set_rules($field, $label, $rules, $errors);                    
                } else {
                    $this->form_validation->set_rules($infos->COLUMN_NAME, $infos->COLUMN_NAME, 'trim|required');                   
                }           
            } else if ( !empty($this->rel_table_config()) AND $infos->COLUMN_KEY == 'PRI' /*AND $infos->COLUMN_NAME == $this->rel_table_config()[0]*/) {
                $this->form_validation->set_rules($infos->COLUMN_NAME, $infos->COLUMN_NAME, 'trim|required|is_unique['.$infos->TABLE_NAME.'.'.$infos->COLUMN_NAME.']', array('is_unique' => 'L\'enregistrement a été annulé éviter un doublon dans les données.'));                   
            }  
        };
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    /*
    public function _rules($fields) 
    {
        $table_infos =  $this->Usual_crud_model->get_table_infos($this->table());            
        foreach ($table_infos as $no => $infos ){
            if($infos->COLUMN_KEY != 'PRI' AND $infos->COLUMN_NAME != $this->attach_col_name() AND in_array($infos->COLUMN_NAME, $fields)){
                if(in_array($infos->COLUMN_NAME, $this->validations())){
                    $this->form_validation->set_rules($this->validations()[$infos->COLUMN_NAME]);
                } else {
                    $this->form_validation->set_rules($infos->COLUMN_NAME, $infos->COLUMN_NAME, 'trim|required');
                }            
            }
        };
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }  
    */  

}





/* End of file .php */
/* Location: ./application/controllers/.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2021-04-25 14:46:34 */
/* http://harviacode.com */