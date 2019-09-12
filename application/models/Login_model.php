<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_Model 
{

	function CheckAuthendication($data)
	{
	   $this->db->select('*'); 
     $this->db->where('(UserName = "'.$data['UserId'].'" OR EmailAddress1 = "'.$data['UserId'].'" OR UniqueID = "'.$data['UserId'].'")');
     $this->db->where('Password', $data['Password']);
     $this->db->where('IsApproved', 1);
     $this->db->where('Active', 1);
	   $q = $this->db->get('users');
	   if($q->num_rows()>0)
	   {
	   	 return $q->row();
	   } else {
	   	 return 0;
	   }
	}

  function checkEmailExist($Email)
  {
      $this->db->select('*'); 
      $this->db->where('(EmailAddress1 = "'.$Email.'" OR EmailAddress2 = "'.$Email.'")');
      $this->db->where('IsApproved', 1);
      $this->db->where('Active', 1);
      $q = $this->db->get('users');
      if($q->num_rows()>0)
      {
       return $q->row();
     } else {
       return 0;
     } 
  }

}
