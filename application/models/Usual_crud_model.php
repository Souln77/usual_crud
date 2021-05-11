<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Usual_crud_model extends CI_Model
{

    //public $table = 'personnel';
    //public $id = 'id';
    //public $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    // get all
    function get_all($table, $primiry_key)
    {
        $this->db->order_by($primiry_key, "ASC");
        return $this->db->get($table)->result();
    }

    // get data by id
    function get_by_id($id, $table, $primiry_key)
    {
        $this->db->where($primiry_key, $id);
        return $this->db->get($table)->row();
    }
    
    // get total rows
    function total_rows($cols, $table, $primay_key, $table_infos, $q = NULL, $foreign_col_name = null, $foreign_col_id = null, $additonnal_query=null) {
        if(is_array($cols) AND !in_array($primay_key, $cols)){
            $cols = array_merge([$primay_key], $cols);
        } else {
            $cols = [];
            foreach ($table_infos as $no => $infos ){
                array_push($cols, $infos->COLUMN_NAME); 
            };                                  
        }

        if(!empty($foreign_col_name) AND !empty($foreign_col_id)){            
            $this->db->where($foreign_col_name, $foreign_col_id);
        }

        if( !empty($additonnal_query) ){
            $this->db->where($additonnal_query['where_col'], $additonnal_query['where_val']);            
        }

        $this->db->select($cols);

        

        if(!empty($q)){
            $nbcols = COUNT($cols);
            $this->db->group_start();
            for($i=0; $i < $nbcols; $i++){        
                if($i == 0){
                    if(empty($cols[$i])){
                        break;
                    }
                    $this->db->like($cols[$i], $q);                    
                } else {
                    if(!empty($cols[$i])){
                        $this->db->or_like($cols[$i], $q);
                    }                    
                }            
            }; 
            $this->db->group_end(); 
        }  
	    $this->db->from($table);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data( $cols, $table, $primay_key, $table_infos, $limit, $start = 0, $q = NULL, $foreign_col_name = null, $foreign_col_id = null, $additonnal_query=null) {
        
        if(is_array($cols)){
            if( !in_array($primay_key, $cols)){
                $cols = array_merge([$primay_key], $cols);
            }                         
        } else {
            $cols = [];            
            foreach ($table_infos as $infos){
                array_push($cols, $infos->COLUMN_NAME); 
            };                                  
        }

        //echo 'foreign' . $foreign_col_name . ' ' . $foreign_col_id;
        if(!empty($foreign_col_name) AND !empty($foreign_col_id)){  
            $this->db->where($foreign_col_name, $foreign_col_id);
        }

        if( !empty($additonnal_query) ){
            $this->db->where($additonnal_query['where_col'], $additonnal_query['where_val']);            
        }

        $this->db->select($cols);
        $this->db->from($table);
        //$this->db->join('comments', 'comments.id = blogs.id');
        
        
        $this->db->order_by($primay_key, 'DESC');

        if(!empty($q)){
            $nbcols = COUNT($cols);
            $this->db->group_start();
            for($i=0; $i < $nbcols; $i++){        
                if($i == 0){
                    if(empty($cols[$i])){
                        break;
                    }
                    $this->db->like($cols[$i], $q);                    
                } else {
                    if(!empty($cols[$i])){
                        $this->db->or_like($cols[$i], $q);
                    }                    
                }            
            }; 
            $this->db->group_end();
        }       
	    $this->db->limit($limit, $start);
        return $this->db->get()->result();
    }

    // insert data
    function insert($data, $table)
    {
        $this->db->insert($table, $data);
    }

    // update data
    function update($id, $data, $table, $primary_key)
    {        
        $this->db->where($primary_key, $id);
        $this->db->update($table, $data);
    }

    // delete data
    function delete($id, $table, $primary_key)
    {
        $this->db->where($primary_key, $id);
        $this->db->delete($table);
    }

    // getting column_name
    function get_table_infos($table)
    {
        $this->db->close();
        $this->load->database('information_schema');
        $col_table = "COLUMNS";

        $this->db->where('TABLE_NAME', $table);
        $this->db->order_by('ORDINAL_POSITION', 'ASC');
        $result = $this->db->get($col_table)->result();

        $this->db->close();
        $this->load->database('default');

        return $result;
    }

    // getting pri column_name
    function get_primary_key($table)
    {
        $this->db->close();
        $this->load->database('information_schema');
        $col_table = "COLUMNS";

        $this->db->where('TABLE_NAME', $table);
        $this->db->where('COLUMN_KEY', 'PRI');
        //$this->db->order_by('ORDINAL_POSITION', 'ASC');
        $result = $this->db->get($col_table)->result();

        $this->db->close();
        $this->load->database('default');

        return $result;
    }

    // getting attach column_name
    function get_attach_col_name($table, $attach_col_name)
    {
        $this->db->close();
        $this->load->database('information_schema');
        $col_table = "COLUMNS";

        $this->db->where('TABLE_NAME', $table);
        $this->db->where('COLUMN_NAME', $attach_col_name);
        $this->db->order_by('ORDINAL_POSITION', 'ASC');
        $result = $this->db->get($col_table)->result();

        $this->db->close();
        $this->load->database('default');

        return $result;
    }

    // getting join query column_name
    function get_join($table, $src_col, $dest_table, $dest_select, $primary_key, $foreign_key, $separator, $additonnal_query=null)
    {
        // "CONCAT('first_name', '|', 'last_name') AS " . $src_col;
        $select = '';
        $dest_select = explode(",", $dest_select);
        $dest_select_count = COUNT($dest_select);
        for($i=0; $i<$dest_select_count; $i++){
            if($i == $dest_select_count - 1){
                $select.=$dest_table.".".$dest_select[$i];
            } else {
                $select.=$dest_table.".".$dest_select[$i].", '".$separator."', ";
            }
            
        }
        $this->db->select($dest_table.".".$foreign_key . " as id, CONCAT(" . $select. ") AS " . $src_col);
        $this->db->from($table);
        $this->db->join($dest_table, $dest_table.".".$foreign_key."=".$table.".".$primary_key, 'right');         
        
        if( !empty($additonnal_query) AND $src_col == $additonnal_query['where_col']){
            $this->db->where($dest_table.".".$foreign_key, $additonnal_query['where_val']);            
        }

        $this->db->order_by($primary_key, 'ASC');
        
        return $this->db->get()->result();
    }

}

/* End of file Personnel_model.php */
/* Location: ./application/models/Personnel_model.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2021-04-25 14:46:34 */
/* http://harviacode.com */