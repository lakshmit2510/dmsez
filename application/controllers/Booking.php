<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
class Booking extends CI_Controller 
{

  function __construct()
  {
     parent::__construct();
    if(!$this->session->userdata('is_loggin')){ redirect(base_url('Login')); }
     $this->load->model('Booking_model');
     $this->load->model('User_model');
  }

  public function index($filter='')
  {
    $data['Title'] = 'Booking'; 
    $data['Page'] = 'Booking';
    $data['booking'] = $this->Booking_model->getBookingDetail();
    $this->load->view('list_booking',$data);
  }

  public function Today()
  {
    if(!in_array($this->session->userdata('Role'), array(1,2,3))) { redirect('Dashboard'); exit; }
    $data['Title'] = "Today's Shipments"; 
    $data['Page'] = 'Today';
    $data['booking'] = $this->Booking_model->getBookingDetail('Today');
    $this->load->view('list_booking',$data);
  }

  public function DeliveryFailed()
  {
    if(!in_array($this->session->userdata('Role'), array(1,2,3))) { redirect('Dashboard'); exit; }
    $data['Title'] = "Delivery Failed Shipments"; 
    $data['Page'] = 'Delivery Failed';
    $data['booking'] = $this->Booking_model->getBookingDetail('DeliveryFailed');
    $this->load->view('list_booking',$data);
  }

  public function Past()
  {
    if(!in_array($this->session->userdata('Role'), array(1,2,3))) { redirect('Dashboard'); exit; }
    $data['Title'] = 'Past Shipments'; 
    $data['Page'] = 'Past';
    $data['booking'] = $this->Booking_model->getBookingDetail('Past');
    $this->load->view('list_booking',$data);
  }

  public function Tomorrow()
  {
    if(!in_array($this->session->userdata('Role'), array(1,2,3))) { redirect('Dashboard'); exit; }
    $data['Title'] = 'Tomorrow Shipments'; 
    $data['Page'] = 'Tomorrow';
    $data['booking'] = $this->Booking_model->getBookingDetail('Tomorrow');
    $this->load->view('list_booking',$data);
  }

  public function Upcoming()
  {
    $data['Title'] = 'Upcoming Shipments'; 
    $data['Page'] = 'Upcoming';
    $data['booking'] = $this->Booking_model->getBookingDetail('Upcoming');
    $this->load->view('list_booking',$data);
  }
  
  public function Security()
  {
    if($this->session->userdata('Role') <> 5) { redirect('Dashboard'); exit; }
    $data['Title'] = 'Security Check Booking'; 
    $data['Page'] = 'BookingList';
    $data['booking'] = $this->Booking_model->getBookingDetail('Security');
    $this->load->view('security_booking',$data);
  }

  public function QCcheck()
  {
    if($this->session->userdata('Role') <> 6) { redirect('Dashboard'); exit; }
    $data['Title'] = 'Checked-In Booking'; 
    $data['Page'] = 'Today';
    $data['booking'] = $this->Booking_model->getBookingDetail('QC');
    $this->load->view('qc_booking',$data);
  }

  public function Realtime()
  {
    if(!in_array($this->session->userdata('Role'), array(1,3))) { redirect('Dashboard'); exit; }
    $data['Title'] = 'RealTime'; 
    $data['Page'] = 'RealTime';
    $data['booking'] = $this->Booking_model->getBookingDetail('RealTime');
    $this->load->view('real-time',$data);
  }


  function Add()
  {
    if(!in_array($this->session->userdata('Role'), array(1,2,3))) { redirect('Dashboard'); exit; }
    $data['Title'] = 'Add New Booking'; 
    $data['Page'] = 'Add';
    // $data['vtype'] = $this->Common_model->getTableData('vechicletype','Active');
    $data['vnumber'] = $this->Common_model->getVehcileNo();
    $data['slottype'] = $this->Common_model->getTableData('slottypes','Active');
    $data['company'] = $this->Common_model->getTableData('company','Active');
    $data['mode'] = $this->Common_model->getTableData('bookingmode','Active');
    $data['area'] = $this->Common_model->getTableData('area','Active');
    $this->load->view('add_booking',$data);
  }

  function BPrint($refno)
  {
    $data['Title'] = 'Print Booking Details'; 
    $data['Page'] = 'Add';
    $data['RefNo'] = $refno;
    $this->load->view('print_booking',$data);
  }
   
  function save()
  {
    $this->load->library('ciqrcode');
    $booked = $this->Booking_model->getMax();
    $checkin = $this->input->post('CheckInDate');
    $CheckOut = date('Y-m-d H:i', strtotime($checkin. ' +1 hour'));
    $fileUploaded = $this->upload_file('Booking',false,'upload_file');
//    if(count($fileUploaded) === 0){
//        return false;
//    }
    $data['BookingRefNo'] = 'SATS'.date('Y').str_pad($booked, 4, '0', STR_PAD_LEFT);
    // $data['UserType'] = $this->input->post('UserType');
    // $data['AreaUID'] = $this->input->post('Area');
    $data['CompanyUID'] = $this->input->post('DeliveryTo');
    $data['DriverName'] = $this->input->post('Driver');
    $data['VType'] = $this->input->post('VType');
    $data['AttachedFiles'] = $fileUploaded['file_path'];
    $data['VNo'] = $this->input->post('VNumber');
    $data['PONumber'] = $this->input->post('PONumber');
    $data['DONumber'] = $this->input->post('DONumber');
    $data['CheckIn'] = $checkin;
    $data['CheckOut'] = $CheckOut;
    $data['BuildingName'] = $this->input->post('BuildingName');
    $data['DeliveryTo'] = $this->input->post('DeliveryTo');
    $data['BookMode'] = $this->input->post('Mode');
    $data['SlotType'] = $this->input->post('SlotType');
    $data['SlotNos'] = $this->input->post('SlotNos');
    $data['BillNo'] = $this->input->post('BillNo');
    $data['BLNo'] = $this->input->post('BLNo');
    // $data['BuildingAddress'] = $this->input->post('Address');
    $data['CreatedBy'] = $this->session->userdata('UserUID');
    $data['status'] = 1;
    $slot = $this->input->post('Docks');
  

    $params['data'] = 'Job Order No : '.$data['BookingRefNo'];
    $params['level'] = 'H';
    $params['size'] = 10;
    if(!file_exists('assets/QRCode'))
    {
      mkdir('assets/QRCode', 0777, true);
    } 
    $params['savename'] = 'assets/QRCode/QR'.$data['BookingRefNo'].'.png';
    $this->ciqrcode->generate($params);
    $data['QRCode'] = $params['savename'];
    $store = $this->Booking_model->SaveBooking($data,$slot);
    if(!empty($store))
    {
      $this->session->set_flashdata('msg',$data['BookingRefNo'].' has been Created Successfully');
      $this->session->set_flashdata('type','done');

      $Old = $this->User_model->getUserdetails($this->session->userdata('UserUID'));
      $this->config_email();
      $data['mail_title'] = 'Your Booking Details - SATS Dock Management System';
      $data['RefNo'] = $data['BookingRefNo'];
      $from_email = "support@satsez.com"; 
      $this->email->from($from_email,'Satsez.com'); 
      $this->email->to($Old->EmailAddress1); #$Old->EmailAddress1;
      if(!empty($Old->EmailAddress2))
      {
       $this->email->cc($Old->EmailAddress2);
      }
      $this->email->subject('Thank you. Your Booking Details - SATS Dock Management System'); 
      $mes_body=$this->load->view('email/email-template.php',$data,true);// load html templates
      $this->email->message($mes_body); 
      $this->email->send(); 

    } else {
      $this->session->set_flashdata('msg','Booking system error. Try again!.');
      $this->session->set_flashdata('type','error');
    } 
    redirect(base_url('Booking/Confirm/'.$data['BookingRefNo']));
  }
    public function upload_file($sub_folder,$extensions,$name){
        $uploadData = array();
        if(!empty($_FILES[$name]['name'])){
            $filesCount = count($_FILES[$name]);
            $root_folder = $this->config->item('upload_file_path');
            $root_extensions = $this->config->item('upload_file_extensions');
            $root_file_size = $this->config->item('upload_file_size');
//            $_FILES['upload_File']['name'] = $_FILES[$name]['name'];
//            $_FILES['upload_File']['type'] = $_FILES[$name]['type'];
//            $_FILES['upload_File']['tmp_name'] = $_FILES[$name]['tmp_name'];
//            $_FILES['upload_File']['error'] = $_FILES[$name]['error'];
//            $_FILES['upload_File']['size'] = $_FILES[$name]['size'];
            $config = array(
                'upload_path' => $root_folder.$sub_folder,
                'allowed_types' => $extensions ? $extensions : $root_extensions,
                'overwrite' => TRUE,
                'remove_spaces'=> FALSE,
                'max_size' => $root_file_size,
            );
            $this->load->library('upload', $config);
//            $this->upload->initialize($config);
            if($this->upload->do_upload($name)){
                $fileData = $this->upload->data();
                if($fileData){
                    $uploadData['file_path'] = $sub_folder.'/'.$_FILES[$name]['name'];
                }
            }else{
                $error = array('error' => $this->upload->display_errors());
            }
//            for($i = 0; $i < $filesCount; $i++){
//                $_FILES['upload_File']['name'] = $_FILES[$name]['name'][$i];
//                $_FILES['upload_File']['type'] = $_FILES[$name]['type'][$i];
//                $_FILES['upload_File']['tmp_name'] = $_FILES[$name]['tmp_name'][$i];
//                $_FILES['upload_File']['error'] = $_FILES[$name]['error'][$i];
//                $_FILES['upload_File']['size'] = $_FILES[$name]['size'][$i];
//                $config = array(
//                    'upload_path' => $root_folder.$sub_folder,
//                    'allowed_types' => $extensions ? $extensions : $root_extensions,
//                    'overwrite' => TRUE,
//                    'max_size' => $root_file_size,
//                );
//                $this->load->library('upload', $config);
//                $this->upload->initialize($config);
//                if($this->upload->do_upload('upload_File')){
//                    $fileData = $this->upload->data();
//                    if($fileData){
//                        $uploadData[$i]['file_name'] = ['file_name'];
//                    }
//                }
//            }
        }
        return $uploadData;
    }

  function cancel($id)
  {
    if(empty($id)) { redirect($_SERVER['HTTP_REFERER']); };
    $data['Active'] = 0;
    $data['status'] = 6;
    $cancel = $this->Booking_model->updateBooking($data, $id);
    $this->session->set_flashdata('done', 'Booking has been Cancelled Successfully');
    redirect($_SERVER['HTTP_REFERER']);   
  }

  function CheckIn($id)
  {
    if(empty($id)) { redirect($_SERVER['HTTP_REFERER']); };

    $book = $this->Booking_model->getBookingDetailID($id);
    $now = date('Y-m-d H:i:s');
    $datetime1 = strtotime($book->CheckIn);
    $datetime2 = strtotime($now);
    if($now > $book->CheckIn) // Late CheckIn
    {
      $interval  = $datetime2 - $datetime1;
      $minutes   = round($interval / 60);
      if($minutes > 15)
      {
        $this->session->set_flashdata('ErrorCheckIn',1);
        $this->session->set_flashdata('MsgCheckIn','<b style="color:red">"LATE ARRIVAL"</b> REFER TO SATS PURCHASING.');
        redirect($_SERVER['HTTP_REFERER']);
        exit;
      }
    } else {  // Earliy CheckIn
      $interval  = $datetime1 - $datetime2;
      $minutes   = round($interval / 60);
      if($minutes > 15)
      {
       $this->session->set_flashdata('ErrorCheckIn',1); 
       $this->session->set_flashdata('MsgCheckIn','<b style="color:red">"TOO EARLIY"</b> PLEASE JOIN THE QUEUE LATER.'); 
       redirect($_SERVER['HTTP_REFERER']);
       exit;
      }
    }

    $data['ActualCheckIn'] = date('Y-m-d H:i:s');
    $data['status'] = 2;
    $cancel = $this->Booking_model->updateBooking($data, $id);
    $this->session->set_flashdata('done', 'Booking has been Checked-In Successfully');
    redirect($_SERVER['HTTP_REFERER']);   
  }

  function CheckOut($id)
  {
    if(empty($id)) { redirect($_SERVER['HTTP_REFERER']); };
    $data['ActualCheckOut'] = date('Y-m-d H:i:s');
    $data['status'] = 3;
    $cancel = $this->Booking_model->updateBooking($data, $id);
    $this->session->set_flashdata('done', 'Booking has been Checked-Out Successfully');
    redirect($_SERVER['HTTP_REFERER']);   
  }

  function QCApprove($id)
  {
    if(empty($id)) { redirect($_SERVER['HTTP_REFERER']); };
    $data['status'] = 4;
    $cancel = $this->Booking_model->updateBooking($data, $id);
    $this->session->set_flashdata('done', 'Booking has been QC-Completed Successfully');
    redirect($_SERVER['HTTP_REFERER']);   
  }

  function QCReject($id)
  {
    if(empty($id)) { redirect($_SERVER['HTTP_REFERER']); };
    $data['status'] = 5;
    $cancel = $this->Booking_model->updateBooking($data, $id);
    $this->session->set_flashdata('done', 'Booking has been QC-Rejected Successfully');
    redirect($_SERVER['HTTP_REFERER']);   
  }

  function Verify()
  {
    $RefNo = $this->input->post('RefNo');
    $detail = $this->Booking_model->getBookingDetailID($RefNo,'RefNo');
    if(is_object($detail))
    {
      if(empty($detail->ActualCheckIn) || $detail->ActualCheckIn == NULL)
      {
        $now = date('Y-m-d H:i:s');
        $datetime1 = strtotime($detail->CheckIn);
        $datetime2 = strtotime($now);
        if($now > $detail->CheckIn) // Late CheckIn
        {
          $interval  = $datetime2 - $datetime1;
          $minutes   = round($interval / 60);
          if($minutes > 15)
          {
            $msg = array('error'=>100,'Msg'=>'<b style="color:red">"LATE ARRIVAL"</b>&nbsp;&nbsp;REFER TO SATS PURCHASING. The Job Order No : <b>'.$RefNo.'</b>');
            echo json_encode($msg);
            exit;
          }
        } else {  // Earliy CheckIn
          $interval  = $datetime1 - $datetime2;
          $minutes   = round($interval / 60);
          if($minutes > 15)
          {
           $msg = array('error'=>100,'Msg'=>'<b style="color:red">"TOO EARLIY"</b> PLEASE JOIN THE QUEUE LATER. The Job Order No : <b>'.$RefNo.'</b>');
           echo json_encode($msg);
           exit;
         }
        }
        $data['ActualCheckIn'] = $now;
        $data['status'] = 2;
        $this->Booking_model->updateBooking($data, $detail->BookingID);
        $msg = array('error'=>0,'status'=>2);
      } else if((!empty($detail->ActualCheckIn) || !$detail->ActualCheckIn == NULL) && empty($detail->ActualCheckOut) || $detail->ActualCheckOut == NULL) 
      {
        $now = date('Y-m-d H:i:s');
        $datetime1 = strtotime($now);
        $datetime2 = strtotime($detail->ActualCheckIn);
        $interval  = $datetime1 - $datetime2;
        $minutes   = round($interval / 60);
        if($minutes < 10) // Eairly CheckOut
        {
          $msg = array('error'=>100,'Msg'=>'<b style="color:red">"TOO EARLIY CHECKOUT"</b> PLEASE JOIN THE QUEUE LATER. The Job Order No : <b>'.$RefNo.'</b>');
          echo json_encode($msg);
          exit;
        }
        $data['ActualCheckOut'] = date('Y-m-d H:i:s');
        $data['status'] = 3;
        $this->Booking_model->updateBooking($data, $detail->BookingID);
        $msg = array('error'=>0,'status'=>3); 
      } else {
        $msg = array('error'=>1);
      }
      echo json_encode($msg);
    } else {
      echo json_encode(array('error'=>1));
    }
  }

  function Sendmail($id)
  {
    $this->config_email();
    $book = $this->Booking_model->getBookingDetailID($id);
    $Old = $this->User_model->getUserdetails($book->BookedBy);
    $data['RefNo'] = $book->BookingRefNo;
    $data['mail_title'] = 'Your Booking Details - SATS Dock Management System';
    $from_email = "support@satsez.com"; 
    $this->email->from($from_email,'Satsez.com'); 
    $this->email->to($Old->EmailAddress1); #$Old->EmailAddress1;
    if(!empty($Old->EmailAddress2))
    {
      $this->email->cc($Old->EmailAddress2);
    }
    $this->email->subject('Your Booking Details - SATS Dock Management System'); 
    $mes_body=$this->load->view('email/email-template.php',$data,true);// load html templates
    $this->email->message($mes_body); 
    if($this->email->send())
    {
      $this->session->set_flashdata('done',$data['RefNo'].' email send Successfully. Please Check booked email address inbox (or) spam.');
      $this->session->set_flashdata('type','done');
    } else {
      $this->session->set_flashdata('error','Cannot able to send mail. Try again!.');
    } 
    redirect($_SERVER['HTTP_REFERER']);
  }

  function getAvailableDocks()
  {
    $type = $this->input->post('SlotType');
    $mode = $this->input->post('Mode');
    $building = $this->input->post('BuildingName');

    $CheckIn = date('Y-m-d H:i', strtotime($this->input->post('CheckIn')));
    $CheckOut = date('Y-m-d H:i', strtotime($CheckIn. ' +1 hour'));

    if(empty($type)) { echo ''; exit(); }
    $getslot = $this->Booking_model->getSlots($type);
    $booked = $this->Booking_model->bookedSlot($type, $CheckIn, $CheckOut);
 
    $slot = '<h3 align="center" style="margin-top: 0;">Docks Information</h3>';
    $slot.='<div class="col-sm-12 border-dotted"><div id="dockslots-div">';

    $class = 'dockslot';

    foreach ($getslot as $key => $val) 
    {
      if(in_array($val->SlotID, $booked))
      {
        $disable = 'disabled="true"';
        $class = 'dockslot';
      } else {
        $disable = '';
      }
      $slot.= '<input type="checkbox" name="Docks[]" value="'.$val->SlotID.'" '.$disable.' class="freeslots" id="docsk'.$val->SlotID.'" /><label class="'.$class.'" for="docsk'.$val->SlotID.'">'.$val->SlotName.'</label>';
    }
    $slot.='</div></div><div class="docklegend"><span class="free"> Available</span><span class="booked">Booked</span><span class="select">Selected</span></div>';
    echo $slot;
  }

  function getVehicleInfo()
  {
    $vno = $this->input->post('VNumber');
    if(empty($vno)) { echo json_encode(array()); exit; }
    $info = $this->Common_model->getVehicleInfo($vno);
    if(!empty($info)) {
      $data = json_encode($info);
    } else {
      $data = json_encode(array());
    }
    echo $data;
  }

  function getDriverInfo()
  {
    $id = $this->input->post('Driver');
    if(empty($id)) { echo json_encode(array()); exit; }
    $info = $this->Common_model->getDriverInfo($id);
    if(!empty($info)) {
      $data = json_encode($info);
    } else {
      $data = json_encode(array());
    }
    echo $data;
  }

  function Confirm($book='')
  {
    if(empty($book)) { redirect('Booking'); exit; }
    $data['Title'] = 'Booking'; 
    $data['Page'] = 'Booking';
    $data['RefNo'] = $book;
    $this->load->view('booking_confirmed',$data);
  }  

  function Verified($book='',$status)
  {
    $data['Title'] = 'Booking'; 
    $data['Page'] = 'Booking';
    $data['QR'] = 'Yes';
    $data['RefNo'] = $book;
    $data['status'] = $this->Common_model->getStatusById($status);
    $this->load->view('booking_confirmed',$data);
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
