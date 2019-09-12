<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="<?php echo base_url();?>assets/img/logo-fav.png">
    <title>Forgot Your Password | Dock Management System</title>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/lib/perfect-scrollbar/css/perfect-scrollbar.min.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/lib/material-design-icons/css/material-design-iconic-font.min.css"/><!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/style.css" type="text/css"/>
    <style>
     body{ background: url('../assets/img/pic2.jpg') no-repeat center center fixed; background-size: cover; }
    .panel-border-color-primary {
     border-top-color: #4285f4;
     box-shadow: -0px 3px 8px 2px #737171;
    }
    .slogo {
      position: absolute;
      top: 77px;
      text-align: center;
      font-size: 16px;
      text-transform: uppercase;
      left: 93px;
      color: #b5121b;
      font-weight: 500;
      width: 280px;
    }
    </style>
  </head>
  <body class="be-splash-screen">
    <div class="be-wrapper be-login">
      <div class="be-content">
        <div class="main-content container-fluid">
          <div class="splash-container">
            <div class="panel panel-default panel-border-color panel-border-color-primary be-loading">
              <div class="panel-heading"><img src="<?php echo base_url('assets/img/logo1.png');?>" style="display: block;" width="100"><br>
                <strong>Dock Management System <br> Forgot Your Password</strong>
                <span class="slogo">Integrated Supply Chain</span>
              </div>
              <div class="panel-body" style="padding-top: 5px;">
                <?php if($this->session->flashdata('error')==1) { ?>
                <div role="alert" class="alert alert-danger alert-icon alert-icon-border alert-dismissible">
                   <div class="icon"><span class="mdi mdi-close-circle-o"></span></div>
                   <div class="message">
                     Your Email not <b>Registered</b> in our system.
                   </div>
                </div>
                <?php } if($this->session->flashdata('done')==1) { ?>
                <div role="alert" class="alert alert-success alert-icon alert-icon-border">
                   <div class="icon"><span class="mdi mdi-check"></span></div>
                   <div class="message">
                     Your login details has been send to your email. Please check your e-mail Inbox.
                   </div>
                </div>
                <?php } ?>
                <form action="<?php echo base_url('Login/resetpassword');?>" method="post">
                  <div class="login-form">
                    <p>Don't worry, we'll send you an email to your login details. Enter your registered email address here.</p>
                    <div class="form-group">
                        <div class="input-group input-group-md xs-mb-15">
                          <span class="input-group-addon"><i class="icon mdi mdi-account"></i></span>
                          <input type="email" placeholder="Your Email" name="Email" required="true" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row login-submit" style="padding: 0;">
                      <div class="col-xs-12">
                        <button type="submit" class="btn btn-primary btn-lg">Send E-mail</button>
                      </div>
                    </div>
                  </div>
                </form><hr>
                <div class="splash-footer"><p><strong>&copy; 2019 Elizabeth-Zion Asia Pacific Pte Ltd. All Rights Reserved. Singapore Co. Reg.No 201207152E</strong></p></div>
              </div>
              <div class="be-spinner">
                <svg width="50px" height="50px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
                  <circle fill="none" stroke-width="5" stroke-linecap="round" cx="33" cy="33" r="30" class="circle"></circle>
                </svg>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="<?php echo base_url();?>assets/lib/jquery/jquery.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>assets/lib/perfect-scrollbar/js/perfect-scrollbar.jquery.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>assets/js/main.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>assets/lib/bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
    <script type="text/javascript">
      $(document).ready(function(){
      	//initialize the javascript
      	App.init();
      	
      	$('form').submit(function(){
          $('.be-loading').addClass('be-loading-active');
        });
        
      });
    </script>
  </body>
</html>