<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_model extends CI_Model 
{

  function getBarChartData($keyword,$fdate,$tdate,$month_year,$flt)
  {
    $where = $this->getwhere($flt);
    if(!empty($where))
    {
      $where = ' AND '.$where;
    }
    foreach($month_year as $Y_m)
    { 

      if($keyword == '30Days')
      {
        $case_when = "DATE(CheckIn) = '$Y_m'";
      } else {
        $mon = explode('-', $Y_m);  
        $case_when = "MONTH(CheckIn) = '".$mon[1]."' AND YEAR(CheckIn) = '".$mon[0]."'";
      }

       $sql = "select COALESCE(COUNT(BookingID),0) as booking from booking where $case_when ".$where."";
       $sqlquery = $this->db->query($sql);
       $result[] = $sqlquery->result(); 
    }
    return $result;
  }

  function getDoughChartData($fdate,$tdate,$flt)
  {
    $where = $this->getwhere($flt);
    if(!empty($where))
    {
      $where = 'where '.$where;
    }
    $sql = "select COUNT(CASE WHEN(DATE(CheckIn) < DATE(NOW()) AND Active = 1) THEN 1 END) as Past,COUNT(CASE WHEN(DATE(CheckIn) > DATE(NOW())) THEN 1 END) as Upcoming,COUNT(CASE WHEN(Active = 0) THEN 1 END) as Cancel from booking ".$where."";
    $sqlquery = $this->db->query($sql);
    $result = $sqlquery->row(); 
    return $result;
  }

  function getwhere($filter)
  {
    $where = array_filter($filter);
    if(empty($where))
    {
      return '';
    }

    if(in_array($this->session->userdata('Role'),array(3,4)))
    {
      $role = ','.$this->session->userdata('UserUID');
    } else {
      $role = '';
    }

    $sql = array();
    if(!empty($where['CompanyUID']))
    {
      $sql[] = 'CompanyUID = '.$where['CompanyUID'];
    }
    
    if(!empty($where['supplier']) && !empty($where['subcontractor']))
    {
      $sql[] = 'CreatedBy IN ('.$where['supplier'].','.$where['subcontractor'].''.$role.')';
    } else {
      if(!empty($where['supplier']))
      {
        $sql[] = 'CreatedBy IN ('.$where['supplier'].''.$role.')';
      }

      if(!empty($where['subcontractor']))
      {
        $sql[] = 'CreatedBy IN ('.$where['subcontractor'].''.$role.')';
      }
    }

    return implode(' AND ',$sql);
  }

}
