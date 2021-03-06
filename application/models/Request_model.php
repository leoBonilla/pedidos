<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Request_model extends CI_Model {

   private $table = 'requests';

	 public function __construct()
        {
                parent::__construct();
                
        }

    public function save($data){
       $this->db->insert($this->table, $data);
        if ( $this->db->affected_rows() == '1' ) {
                     return TRUE;
                  }
                return FALSE;
}

  public function _list(){
   
    $result = $this->db->get($this->table);
    if($result->num_rows() > 0 ){
      return $result->result();
    }
    return false;
  }


  public function newRequest($id_client, $data){
       $this->db->trans_start();
              $this->db->insert($this->table, array('date' => date('Y-m-d H:i:s'), 'id_client' => $id_client));
              $request_id = $this->db->insert_id();
               foreach ($data as $row) {
                 $this->db->insert('sweets_request', array('id_request' => $request_id , 'id_sweet' => $row['id'], 'quantity' => $row['cant']));
               }
          // $this->db->query('AN SQL QUERY...');
          // $this->db->query('ANOTHER QUERY...');

          $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE)
            {
                    // generate an error... or use the log_message() function to log your error
              return false;
            }
          return $request_id;
  }


  public function detail($id){
      $this->db->where('id_request', $id);
      $result = $this->db->get('requests_detail_view');
      if($result->num_rows() > 0 ){
      return $result->result();
    }
    return false;
  }


  public function confirm($id){
  $this->db->where('id_request' , $id);
  $this->db->update($this->table, array('state' => 1));
   $this->descontar($id);

   return ($this->db->affected_rows() > 0);
  }

  public function despachar($id){
  $this->db->where('id_request' , $id);
  $this->db->update($this->table, array('state' => 2));

   return ($this->db->affected_rows() > 0);
  }



  public function descontar($id){
    $this->db->where('id_request', $id);
      $result = $this->db->get('sweets_request');
      foreach ($result->result() as $row) {
         //descontar 
           $producto = $row->id_sweet;
           $cantidad = $row->quantity;
           $this->db->where('id', $producto);
           $this->db->set('stock', 'stock - '.$cantidad, FALSE);
           $this->db->update('sweets');

      }
  }

     

}
