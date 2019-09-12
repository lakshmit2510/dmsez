<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends CI_Controller 
{

  function __construct()
  {
     parent::__construct();
     $this->load->model('Booking_model');
     $this->load->model('User_model');
     $this->load->model('Report_model');
    if(!$this->session->userdata('is_loggin')){ redirect(base_url('Login')); }
    if(!in_array($this->session->userdata('Role'), array(1,2,3))) { redirect('Dashboard'); exit; }
  }

  public function index($filter='')
  {
    $data['Title'] = 'Reports'; 
    $data['Page'] = 'Reports';
    $data['company'] = $this->Common_model->getTableData('company','Active');
    $data['mode'] = $this->Common_model->getTableData('bookingmode','Active');
    $data['booking'] = $this->Booking_model->getBookingDetail();
    $data['subcontractor'] = $this->User_model->getUserByRole('4');
    $data['supplier'] = $this->User_model->getUserByRole('2');
    $this->load->view('report',$data);
  }
   
  function FetchChartData()
  { 
    $data = array();
    
    $flt['CompanyUID'] = $this->input->post('company'); 
    $flt['supplier'] = $this->input->post('supplier'); 
    $flt['subcontractor'] = $this->input->post('subcontractor'); 

    $date = explode('-', $this->input->post('date'));        
    $fdate = $date[0];
    $tdate = $date[1];
    
    // Get Chart Label
    $datediff = strtotime($tdate) - strtotime($fdate);
    $datediff = floor($datediff / (60 * 60 * 24));
    for ($i = 0; $i < $datediff + 1; $i++) 
    {
      $labels[] = date("M-Y", strtotime($fdate . ' + ' . $i . 'day'));
      $days[] = date("Y-m", strtotime($fdate . ' + ' . $i . 'day'));
    }

    if(sizeof(array_unique($labels)) == 1) 
    {
      $data = array();
      $datediff = strtotime($tdate) - strtotime($fdate);
      $datediff = floor($datediff / (60 * 60 * 24));
      for ($i = 0; $i < $datediff + 1; $i++) {
        $lbl[] = date("m/d/Y", strtotime($fdate . ' + ' . $i . 'day'));
        $dys[] = date("Y-m-d", strtotime($fdate . ' + ' . $i . 'day'));
      }
      $month_year = array_unique($dys);
      $data['label'] = array_values($lbl);
      $keyword = '30Days';
    } else {
      $data['label'] = array_values(array_unique($labels));
      $month_year = array_unique($days);
      $keyword = 'Month';
    }

    $result = $this->Report_model->getBarChartData($keyword,$fdate,$tdate,$month_year,$flt);
    $booking = $this->Report_model->getDoughChartData($fdate,$tdate,$flt);

    // Single Month set start point 0
    if(sizeof($result) == 1) {
      $data['booking'][] = 0;
    }

  if(count($result) > 0) 
  {
    foreach ($result as $value) {
        foreach ($value as $row) {
          $data['booking'][] = $row->booking;
        }
      }
  } 
  
  if(sizeof($result) == 0)
  {
    $data['Book'][] = 0;
  } else {
    $data['Book'][] = $booking->Past;
    $data['Book'][] = $booking->Upcoming;
    $data['Book'][] = $booking->Cancel;
  }

  echo json_encode($data, JSON_NUMERIC_CHECK);

}

}
