<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Clientes_model extends Datatables_Model {


protected $table = 'clients';
protected $column_order = array('id','name','address','rut'); //set column field database for datatable orderable
protected $column_search = array('id','name','address','rut'); //set column field database for datatable searchable 
protected $order = array('id' => 'desc'); // default order 


public function __construct(){
    parent::__construct();
}

}