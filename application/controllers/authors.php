<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Authors extends CI_Controller {
	
	function __construct()
    {
        parent::__construct();
		$this->load->model('user'); //load MODEL
		$this->load->helper('text'); //for content limiter
		$this->load->library('form_validation'); //load form validate rules
		
		//mail config settings
		$this->load->library('email'); //load email library
		//$config['protocol'] = 'sendmail';
		//$config['mailpath'] = '/usr/sbin/sendmail';
		//$config['charset'] = 'iso-8859-1';
		//$config['wordwrap'] = TRUE;
		
		//$this->email->initialize($config);
    }
	
	public function index() {
		if($this->session->userdata('logged_in')==FALSE){ 
			redirect(base_url().'login/', 'location');
		}
		
		//check for update
		$get_id = $this->input->get('edit');
		if($get_id != ''){
			$gq = $this->user->query_rec_single('id', $get_id, 'bz_author');
			foreach($gq as $item){
				$data['e_id'] = $item->id;
				$data['e_name'] = $item->name;	
			}
		}
		
		//check record delete
		$del_id = $this->input->get('del');
		if($del_id != ''){
			if($this->user->delete_rec('id', $del_id, 'bz_author') > 0){
				$data['err_msg'] = '<div class="alert alert-info"><h5>Deleted</h5></div>';
			} else {
				$data['err_msg'] = '<div class="alert alert-info"><h5>There is problem this time. Try later</h5></div>';
			}
		}
		
		//check if ready for post
		if($_POST){
			$author_id = $_POST['author_id'];
			$name = $_POST['name'];
			
			//check for update
			if($author_id != ''){
				$upd_data = array(
					'name' => $name
				);
				
				if($this->user->update_rec('id', $author_id, 'bz_author', $upd_data) > 0){
					$data['err_msg'] = '<div class="alert alert-info"><h5>Successfully</h5></div>';
				} else {
					$data['err_msg'] = '<div class="alert alert-info"><h5>No Changes Made</h5></div>';
				}
			} else {
				$reg_data = array(
					'name' => $name
				);
				
				if($this->user->reg_rec('bz_author', $reg_data) > 0){
					$data['err_msg'] = '<div class="alert alert-info"><h5>Successfully</h5></div>';
				} else {
					$data['err_msg'] = '<div class="alert alert-info"><h5>There is problem this time. Try later</h5></div>';
				}
			}
		}
		
		//query uploads
		$data['allup'] = $this->user->query_rec('bz_author');
		
		$data['log_username'] = $this->session->userdata('log_username');
	  
	  	$data['title'] = 'Author';
		$data['page_act'] = 'setup';

	  	$this->load->view('designs/header', $data);
		$this->load->view('designs/leftmenu', $data);
	  	$this->load->view('author', $data);
	  	$this->load->view('designs/footer', $data);
	}
}