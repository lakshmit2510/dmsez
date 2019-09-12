<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller 
{

	function __construct()
	{
	   parent::__construct();
    if(!$this->session->userdata('is_loggin')){ redirect(base_url('Login')); }
     $this->load->model('Dashboard_model');
     $this->load->model('Booking_model');
	   $this->load->model('User_model');
	}

	public function index()
  {
    $data['Title'] = 'Dashboard'; 
    $data['Page'] = 'active';
    $this->load->view('dashboard',$data);
  }

  public function Profile()
	{
	  $data['Title'] = 'Profile';	
	  $data['Page'] = 'profile';
    $data['Profile'] = $this->User_model->getUserdetails($this->session->userdata('UserUID'));
	  $this->load->view('profile',$data);
	}

  public function Help()
  {
    $data['Title'] = 'Help';
    $data['Page'] = 'help';  
    $this->load->view('help',$data);
  }

  public function Manual()
  {
    if(in_array($this->session->userdata('Role'),array(1,3)))
    {
      $data['file'] = base_url('assets/SATSDMS-UserManualCompany.pdf');  
    } else {
      $data['file'] = base_url('assets/SATSDMS-UserManual.pdf');  
    }
    $this->load->view('doc_help',$data);
  }


}
