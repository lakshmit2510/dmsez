<?php
defined('BASEPATH') OR exit('No direct script access allowed');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
class Users extends CI_Controller 
{

	function __construct()
	{
	   parent::__construct();
	   $this->load->model('User_model');
     $this->load->model('Booking_model');
	  if(!$this->session->userdata('is_loggin')){ redirect(base_url('Login')); }
    if(!in_array($this->session->userdata('Role'),array(1,2,3,5))) { redirect(base_url()); }
	}

  public function index()
  {
    if($this->session->userdata('Role') == 2)
    {
      $data['Title'] = 'List of Sub-contractors';
      $Role = 4;
    } else {
      $data['Title'] = 'List of Suppliers';
      $Role = 2;
    }
    $data['Page'] = 'listuser'; 
    $data['Users'] = $this->User_model->GetUsers($Role);
    $this->load->view('List-users',$data);
  }

	public function update()
  {
    if(!in_array($this->session->userdata('Role'), array(1,2,3))) { redirect('Dashboard'); exit; }
    if($this->session->userdata('Role') == 2)
    {
      $data['Title'] = 'Update Sub-contractors Information';
      $Role = 4;
    } else {
      $data['Title'] = 'Update Suppliers Information';
      $Role = 2;
    }
    $data['Page'] = 'add_edit_users_list';
    $data['SupplierGroup'] = $this->Common_model->getSupplierGroupdetails();
    $data['slottype'] = $this->Common_model->getTableData('slottypes','Active');
    $data['Users'] = $this->User_model->GetUsers($Role);
    $this->load->view('add_edit_users_list',$data);
  }

  function updateUsersGroup(){
      $selectedSuppliers = $this->input->post('selectedSuppliers');
      $selectedGroup =  $this->input->post('selectedGroup');
      $dockType = $this->input->post('dockType');
      foreach ($selectedSuppliers as $value) {
      $data['SupplierGroupID'] =  $selectedGroup;
      $this->User_model->updateSupplierGroup($data, $value);
        }
        $this->updateSupplierGroupTime();
      echo json_encode(array("success"=>'ok',"message"=>"Supplier Group Successfully Updated."));
  }

  function supplierGroupDetails()
  {
    $data['Title'] = 'List All Supplier Groups';
    $data['Page'] = 'ListSupplierGroup';
    $data['suppliergrouplist'] = $this->Common_model->getSupplierGroupInfo();
    $this->load->view('list-supplier-groups',$data);   
  }
  function validateGroupName($groupName){
    $isExists = $this->Common_model->checkGroupName($groupName);
    if($isExists && count($isExists) > 0){
      echo json_encode(array("success"=>'ok', "isExists" => true,"message"=>"Supplier Group Already Exists."));
    } else {
      echo json_encode(array("success"=>'ok', "isExists" => false, "message"=>"New Supplier Group."));
    }
  }

  function addNewSupplierGroup()
  {
    $data['Title'] = 'Add New Supplier Group';
    $data['Page'] = 'AddNewGroup';  
    $data['slottype'] = $this->Common_model->getTableData('slottypes','Active');
    $this->load->view('add-new-group-to-list',$data);   
  }

  function saveGroup()
  {
    $data['SupplierGroup'] = $this->input->post('supplierGroup');
    $data['DockTypeID'] = $this->input->post('dockType');
    $data['AvailableTimings'] = $this->input->post('availableTimings');
    $this->Common_model->insertSupplierGroup($data);
    echo json_encode(array("success"=>'ok', "isSaved" => true,"message"=>"Supplier Group Created Successfully."));
    // $this->session->set_flashdata('msg',$data['SupplierGroup'].' has been Created Successfully');
    // $this->session->set_flashdata('type','done');
    // redirect($_SERVER['HTTP_REFERER']); 
  }

  function editSupplierGroup($GroupID)
  {
    $data['Title'] = 'Edit Supplier Group Details';
    $data['Page'] = 'ListSupplierGroup';
    $data['slottype'] = $this->Common_model->getTableData('slottypes','Active');
    $data['suppliergrouplist'] = $this->Common_model->getSupplierById($GroupID);
    $this->load->view('edit-Supplier-Group',$data);   
  }

  function updateSupplierGroupTime(){
    $data['AvailableTimings'] = $this->input->post('availableTimings');
    $data['DockTypeID'] = $this->input->post('dockType');
    $selectedGroup =  $this->input->post('selectedGroup');
    $this->Common_model->updateSupplierGroup($data, $selectedGroup);
  }

  function updateSupplierGroupDetails(){
    if($this->input->post())
    {
      $selectedGroup =  $this->input->post('group_Id');
      $data['AvailableTimings'] = $this->input->post('availableTimings');
      $data['DockTypeID'] = $this->input->post('dockType');
      $this->Common_model->updateSupplierGroup($data, $selectedGroup);
      $this->session->set_flashdata('msg', 'Supplier Group has been Updated Successfully');
      $this->session->set_flashdata('type','done');
    }
    redirect($_SERVER['HTTP_REFERER']); 
  }

  function supplierGroupById($id){
    $groupDetails = $this->Common_model->getSupplierById($id);
    echo json_encode($groupDetails);
  }

  function deleteGroup($GroupID)
  {
    if(empty($GroupID)) { redirect(base_url('Dashboard')); }
    $data['detail'] = $this->Common_model->deleteSupplierGroupById($GroupID);
    echo json_encode(array("success"=>'ok',"message"=>"Group Successfully Deleted."));
  }

  function fetchAttachments(){
      $userId = $this->input->get('userId');
      $data = $this->Booking_model->getAttachments($userId);
      echo json_encode($data);
  }
  function downloadFile(){
      $this->load->helper('download');
      $filePath = $this->input->get('filePath');
      $root_folder = $this->config->item('upload_file_path');
      $fullFilePath = $root_folder.$filePath;
      $data = file_get_contents($fullFilePath); // Read the file's contents
      $name = explode('/', $filePath);
      force_download($name[count($name)-1], $data);
      exit;
  }

  function Add()
  {
    if(!in_array($this->session->userdata('Role'), array(1,2,3))) { redirect('Dashboard'); exit; }
    if($this->session->userdata('Role') == 2)
    {
      $data['Title'] = 'Create New Sub-contractor';
    } else {
      $data['Title'] = 'Create New Supplier';
    }
    $data['Page'] = 'adduser'; 
    $data['SupplierGroup'] = $this->Common_model->getSupplierGroupdetails();
    $data['vtype'] = $this->Common_model->getTableData('vechicletype','Active');
    $data['company'] = $this->Common_model->getTableData('company','Active');
    $this->load->view('Add-users',$data);   
  }

  public function Security()
  {

    if(!in_array($this->session->userdata('Role'), array(1,3,5))) { redirect('Dashboard'); exit; }
    $data['Title'] = 'List of Security Check';
    $data['Page'] = 'list_security';  
    $data['Users'] = $this->User_model->GetUsers(5);
    $this->load->view('list_security',$data);
  }

  public function QC()
  {
    if(!in_array($this->session->userdata('Role'), array(1,3))) { redirect('Dashboard'); exit; }
    $data['Title'] = 'List of QC Check';
    $data['Page'] = 'list_qc';  
    $data['Users'] = $this->User_model->GetUsers(6);
    $this->load->view('list_qcchecker',$data);
  }

  public function Approval()
  {
    if(!in_array($this->session->userdata('Role'), array(1,3))) { redirect('Dashboard'); exit; }
    $data['Title'] = 'List of Approvals';
    $data['Page'] = 'Approval';  
    $data['Users'] = $this->User_model->getApprovalPending();
    $this->load->view('list_approval',$data);
  }

  function AddSecurity()
  {
    if(!in_array($this->session->userdata('Role'), array(1,3,5))) { redirect('Dashboard'); exit; }
    $data['Title'] = 'Add New Security Check';
    $data['Page'] = 'AddSecurity';  
    $data['company'] = $this->Common_model->getTableData('company','Active');
    $this->load->view('Add-security',$data);   
  }

  function AddQc()
  {
    if(!in_array($this->session->userdata('Role'), array(1,3))) { redirect('Dashboard'); exit; }
    $data['Title'] = 'Add New QC Check';
    $data['Page'] = 'AddQc';  
    $data['company'] = $this->Common_model->getTableData('company','Active');
    $this->load->view('Add-qcchecker',$data);   
  }

  function Changepassword()
  {
    $data['Title'] = 'Change Password';
    $data['Page'] = 'changepassword';  
	  $this->load->view('changepassword',$data); 	
	}

  function Edit($UserUID)
  {
    if(!in_array($this->session->userdata('Role'), array(1,2,3))) { redirect('Dashboard'); exit; }
    if(empty($UserUID)) { redirect(base_url('Dashboard')); }
    if($this->session->userdata('Role') == 2)
    {
      $data['Title'] = 'Edit Sub-contractors';
    } else {
      $data['Title'] = 'Edit Suppliers';
    }
    $data['Page'] = 'listuser'; 
    $data['SupplierGroup'] = $this->Common_model->getSupplierGroupdetails();
    $data['vtype'] = $this->Common_model->getTableData('vechicletype','Active');
    $data['userdetail'] = $this->User_model->GetUsersDetailsByUserID($UserUID);
    $data['company'] = $this->Common_model->getTableData('company','Active');
    $this->load->view('Edit-users',$data);  
  }

  function editSecurity($UserUID)
  {
    if(!in_array($this->session->userdata('Role'), array(1,2,3,5))) { redirect('Dashboard'); exit; }
    if(empty($UserUID)) { redirect(base_url('Dashboard')); }
    $data['Title'] = 'Edit Security Checker';
    $data['Page'] = 'listsecurity'; 
    $data['detail'] = $this->User_model->GetUsersDetailsByUserID($UserUID);
    $data['company'] = $this->Common_model->getTableData('company','Active');
    $this->load->view('Edit-security',$data);   
  }

    function deleteSecurity($UserUID)
    {
        if(empty($UserUID)) { redirect(base_url('Dashboard')); }
        $data['detail'] = $this->User_model->deleteUsersDetailsByUserID($UserUID);
        echo json_encode(array("success"=>'ok',"message"=>"User Successfully Deleted."));
    }

	function editQc($UserUID)
	{
    if(!in_array($this->session->userdata('Role'), array(1,2,3))) { redirect('Dashboard'); exit; }
    if(empty($UserUID)) { redirect(base_url('Dashboard')); }
	  $data['Title'] = 'Edit QC Checker';
    $data['Page'] = 'listqc';	
	  $data['detail'] = $this->User_model->GetUsersDetailsByUserID($UserUID);
    $data['company'] = $this->Common_model->getTableData('company','Active');
	  $this->load->view('Edit-qcchecker',$data); 	
	}

  function Approve($UserUID)
  {
    if(empty($UserUID)) { redirect(base_url('Dashboard')); }
    $data['IsApproved'] = 1;
    $approved = $this->User_model->ProcessUpdate($UserUID,$data);
    if($approved==1)
    {
      $usr = $this->User_model->GetUsersDetailsByUserID($UserUID);
      $data['UserName'] = $usr->UserName;
      $data['EmailAddress1'] = $usr->EmailAddress1;
      $data['UniqueID'] = $usr->UniqueID;
      $data['Password'] = $usr->Password;
      $data['url'] = base_url();
      $this->config_email();
      $data['mail_title'] = 'Your Login Details - SATS Dock Management System';
      $from_email = "support@satsez.com"; 
      $this->email->from($from_email,'Sastsez.com'); 
      $this->email->to($data['EmailAddress1']); #$Old->EmailAddress1;
      if(!empty($data['EmailAddress2']))
      {
        $this->email->cc($data['EmailAddress2']);
      }
      $this->email->subject('Thank you. Your Account is Created in SATS Dock Management System'); 
      $mes_body=$this->load->view('email/user-template.php',$data,true);// load html templates
      $this->email->message($mes_body); 
      $this->email->send();
      $this->session->set_flashdata('done',1);
      $this->session->set_flashdata('msg',$data['UserName'].' Approved successfully, Login details send to registerd email address.');
    } else {
      $this->session->set_flashdata('msg',$data['UserName'].' Approved error, Try again!.');
      $this->session->set_flashdata('error',1);
    }
    redirect($_SERVER['HTTP_REFERER']);
  }

  function Reject($UserUID)
  {
    if(empty($UserUID)) { redirect(base_url('Dashboard')); }
    $reject = $this->User_model->DeleteUser($UserUID);
    if($reject==1)
    {
      $this->session->set_flashdata('done',1);
      $this->session->set_flashdata('msg','User Rejected successfully.');
    } else {
      $this->session->set_flashdata('msg',$data['UserName'].' reject error, Try again!.');
      $this->session->set_flashdata('error',1);
    }
    redirect($_SERVER['HTTP_REFERER']);
  }

	function save_user()
  {
    if($this->input->post())
    { 
     $supplier = $this->Common_model->getMax('users');
     // $data['UserType'] = $this->input->post('UserType');
     $data['CompanyUID'] = $this->input->post('Company');
     /*$data['Name'] = $this->input->post('Name');*/
     $data['EmailAddress1'] = $this->input->post('EmailAddress1');
     $data['EmailAddress2'] = $this->input->post('EmailAddress2');
     $data['PhoneNumber'] = $this->input->post('PhoneNumber');
     $data['UserName'] = $this->input->post('UserName');
     $data['Password'] = $this->input->post('Password');
     /*$data['VNo'] = $this->input->post('VNo');
     $data['VType'] = $this->input->post('VType');*/
     $data['SupplierGroupID'] = $this->input->post('SupplierGroup');
     if($this->session->userdata('Role') == 2)
     {
       $data['Role'] = 4;
     } else {
       $data['Role'] = $this->input->post('Role');
     }
     $data['UAN'] = $this->input->post('UAN');
     if($data['Role'] == 2)
     {
       $data['UniqueID'] = 'SS0000'.$supplier; # 'S'.str_pad($supplier, 5, '0', STR_PAD_LEFT);
     } else if($data['Role'] == 4) {
       $data['UniqueID'] = 'EP0000'.$supplier;
     }
     $data['CreatedBy'] = $this->session->userdata('UserUID');
     $data['IsApproved'] = 1;
     $store = $this->User_model->SaveUser($data);
     if($store==1)
     {
       $this->session->set_flashdata('msg',$data['UserName'].' has been Created Successfully');
       $this->session->set_flashdata('type','done');

       if(empty($data['UniqueID'])) { $data['UniqueID'] = ''; }
       $data['url'] = base_url();
       $this->config_email();
       $data['mail_title'] = 'Your Login Details - SATS Dock Management System';
       $from_email = "support@satsez.com"; 
       $this->email->from($from_email,'Satsez.com'); 
       $this->email->to($data['EmailAddress1']); #$Old->EmailAddress1;
       if(!empty($data['EmailAddress2']))
       {
         $this->email->cc($data['EmailAddress2']);
       }
       $this->email->subject('Thank you. Your Account is Created in SATS Dock Management System'); 
       $mes_body=$this->load->view('email/user-template.php',$data,true);// load html templates
       $this->email->message($mes_body); 
       $this->email->send();

     } else {
      if($store!=2)
      {
        $this->session->set_flashdata('msg',$data['UserName'].' has been Create Error');
      } else {
        $this->session->set_flashdata('msg','Email Already Exit. Try again!.');
      }
      $this->session->set_flashdata('type','error');
    }
    }
    redirect(base_url('Users/Add'));
  }

  function save_security()
	{
	  if($this->input->post())
	  {	
     $data['UserType'] = 'Internal';
     $data['UAN'] = $this->input->post('UAN');
     $data['CompanyUID'] = $this->input->post('Company');
     $data['EmailAddress1'] = $this->input->post('EmailAddress1');
     $data['EmailAddress2'] = $this->input->post('EmailAddress2');
     $data['PhoneNumber'] = $this->input->post('PhoneNumber');
     $data['UserName'] = $this->input->post('UserName');
     $data['Password'] = $this->input->post('Password');
     // $data['Supplier'] = $this->input->post('Security');
     $data['Role'] = $this->input->post('Role');
     $data['CreatedBy'] = $this->session->userdata('UserUID');
     $data['IsApproved'] = 1;
     $store = $this->User_model->SaveUser($data);
		 if($store==1)
		 {
		   $this->session->set_flashdata('msg',$data['UserName'].' has been Created Successfully');
		   $this->session->set_flashdata('type','done');
       $data['url'] = base_url();
       $this->config_email();
       $data['mail_title'] = 'Your Login Details - SATS Dock Management System';
       $from_email = "support@satsez.com";  
       $this->email->from($from_email,'Satsez.com'); 
       $this->email->to($data['EmailAddress1']); #$Old->EmailAddress1;
       $this->email->subject('Thank you. Your Account is Created in SATS Dock Management System'); 
       $mes_body=$this->load->view('email/user-template.php',$data,true);// load html templates
       $this->email->message($mes_body); 
       $this->email->send();

		 } else {
		  if($store!=2)
		  {
		  	$this->session->set_flashdata('msg',$data['UserName'].' has been Create Error');
		  }	else {
		  	$this->session->set_flashdata('msg','Email Already Exit. Try again!.');
		  }
		  $this->session->set_flashdata('type','error');
		}
	  }
	  redirect($_SERVER['HTTP_REFERER']);   
	}

  function update_user()
  {
   if($this->input->post())
    { 
     // $data['UserType'] = $this->input->post('UserType');
     $data['CompanyUID'] = $this->input->post('Company');
     $UserUID = $this->input->post('UserUID');
     /*$data['Name'] = $this->input->post('Name');*/
     $data['EmailAddress1'] = $this->input->post('EmailAddress1');
     $data['EmailAddress2'] = $this->input->post('EmailAddress2');
     $data['PhoneNumber'] = $this->input->post('PhoneNumber');
     $data['UserName'] = $this->input->post('UserName');
     $data['SupplierGroupID'] = $this->input->post('SupplierGroup');
     /*$data['VNo'] = $this->input->post('VNo');
     $data['VType'] = $this->input->post('VType');*/
     $data['Supplier'] = $this->input->post('Supplier');
     if($this->session->userdata('Role') == 2)
     {
      $data['Role'] = 4;
     } else {
      $data['Role'] = $this->input->post('Role');
     }
     $data['UAN'] = $this->input->post('UAN');
    $store = $this->User_model->UpdateUser($data, $UserUID);
    if($store==1)
    {
      $this->session->set_flashdata('msg',$data['UserName'].' has been Updated Successfully');
      $this->session->set_flashdata('type','done');
    } else {
      if($store!=2)
      {
        $this->session->set_flashdata('msg',$data['UserName'].' has been Update Error');
      } else {
        $this->session->set_flashdata('msg','UserName Already Exit. Try again!.');
      }
      $this->session->set_flashdata('type','error');
    }
    }
    redirect($_SERVER['HTTP_REFERER']);   
  }

	function update_security()
  {
   if($this->input->post())
    { 
     $UserUID = $this->input->post('UserUID');
     $data['UserType'] = $this->input->post('UserType');
     $data['CompanyUID'] = $this->input->post('Company');
     $data['EmailAddress1'] = $this->input->post('EmailAddress1');
     $data['EmailAddress2'] = $this->input->post('EmailAddress2');
     $data['PhoneNumber'] = $this->input->post('PhoneNumber');
     $data['UserName'] = $this->input->post('UserName');
     $data['Supplier'] = $this->input->post('Supplier');
     $data['Role'] = $this->input->post('Role');
     $data['UAN'] = $this->input->post('UAN');
     $store = $this->User_model->UpdateUser($data, $UserUID);
     if($store==1)
     {
       $this->session->set_flashdata('msg',$data['UserName'].' has been Updated Successfully');
       $this->session->set_flashdata('type','done');
     } else {
      if($store!=2)
      {
        $this->session->set_flashdata('msg',$data['UserName'].' has been Update Error');
      } else {
        $this->session->set_flashdata('msg','UserName Already Exit. Try again!.');
      }
      $this->session->set_flashdata('type','error');
      }
    }
    redirect($_SERVER['HTTP_REFERER']);   
  }

  function updatepassword()
	{
	 if($this->input->post())
	 {	
     $UserUID = $this->session->userdata('UserUID');
     $Password = $this->input->post('Current');
     $Pass = $this->input->post('Password');
     $Npass = $this->input->post('NPassword');
     $Old = $this->User_model->getUserdetails($UserUID);
     
     if($Old->Password != $Password)
     {
       $this->session->set_flashdata('msg','Current Password do not match.');
       $this->session->set_flashdata('type','error');
       redirect($_SERVER['HTTP_REFERER']);        
     } else {
      if($Pass != $Npass) 
      {
        $this->session->set_flashdata('msg','Confirm Password do not match.');
        $this->session->set_flashdata('type','error');
        redirect($_SERVER['HTTP_REFERER']);        
      }
     }

     $data['Password'] = $Npass;
		 $store = $this->User_model->updatepassword($data, $UserUID);
		 if($store==1)
		 {
		   $this->session->set_flashdata('msg','Password changed successfully');
		   $this->session->set_flashdata('type','done');
		 } else {
		   $this->session->set_flashdata('msg',' Cannot reset your Password. Try again sometime');
		   $this->session->set_flashdata('type','error');
		 }
	 }
	 redirect($_SERVER['HTTP_REFERER']); 	
	}

  public function config_email()
  {
    $config = Array( 
      'protocol' => 'smtp', 
      'smtp_host' => 'email-smtp.us-east-1.amazonaws.com', 
      'smtp_port' => 587, 
      'smtp_user' => 'AKIA3SSJBQUNC5FHZTP7', 
      'smtp_pass' => 'BNDs7tsY4Jzt7g6af5qdxzPKXXyEDmhBN3SfRKSQKbBY',
      'smtp_crypto' => 'tls',
      'mailtype'  => 'html', 
      'charset'   => 'iso-8859-1',
      'newline' => '\r\n',
      'starttls'  => true,
      'wordwrap'  =>  true ); 
    $emailconf = $this->load->library('email', $config);
    $this->email->set_newline("\r\n");
    return $emailconf;
  }

}
