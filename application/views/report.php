<?php $this->load->view('template/header'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/lib/daterangepicker/css/daterangepicker.css"/>
<div class="be-content">
  <div class="main-content container-fluid be-loading">
    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-default panel-border-color panel-border-color-primary">
          <div class="panel-body">
            <div class="col-sm-3">
              <h4>Date Filter</h4>
              <input type="text" name="daterange" id="Date" value="<?php echo date('m/d/Y', strtotime('-7 days')).' - '.date('m/d/Y')?>" readonly="true" class="form-control daterange filter">
            </div>
            <div class="col-sm-2">
              <h4>Company</h4>
              <select class="form-control filter" required="true" id="company">
                <option value="">-- Choose Company --</option>
                <?php 
                foreach ($company as $key => $value) {
                  echo '<option value="'.$value->CompanyUID.'">'.$value->CompanyName.'</option>';
                }
                ?>
              </select>
            </div>
            <div class="col-sm-3">
              <h4>Supplier</h4>
              <select class="form-control filter" required="true" id="Supplier">
                <option value="">-- Supplier --</option>
                <?php 
                foreach ($supplier as $key => $value) 
                {
                  echo '<option value="'.$value->UserUID.'">'.$value->UserName.'</option>';
                }
                ?>
              </select>
            </div>
          <!-- <div class="col-sm-3">
             <h4>Sub Contractor</h4>
             <select class="form-control filter" required="true" id="Sub">
                <option value="">-- Sub Contractor --</option>
                <?php 
                foreach ($subcontractor as $key => $value) 
                {
                  echo '<option value="'.$value->UserUID.'">'.$value->UserName.'</option>';
                }
                ?>
              </select>
           </div> -->
           <div class="col-sm-1" style="margin-top: 40px;">
            <a href="" class="btn btn-space btn-primary btn-xl"><i class="icon mdi mdi-refresh"></i> Reset</a>
           </div>
         </div>
        </div>
      </div>
      <div class="col-md-7">
        <div class="panel panel-default panel-border-color panel-border-color-primary">
          <div class="panel-heading panel-heading-divider">
            <span class="title">Number of Bookings By Date</span>
          </div>
          <div class="panel-body">
            <canvas id="bar-chart"></canvas>
          </div>
        </div>
      </div>
      <div class="col-md-5">
        <div class="panel panel-default panel-border-color panel-border-color-primary">
          <div class="panel-heading panel-heading-divider">
            <span class="title">Overview of Past/Upcoming and Cancelled Bookings</span>
          </div>
          <div class="panel-body">
            <canvas id="doughnut" height="220"></canvas>
          </div>
        </div>
      </div>
      <div class="be-spinner">
        <svg width="50px" height="50px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
          <circle fill="none" stroke-width="5" stroke-linecap="round" cx="33" cy="33" r="30" class="circle"></circle>
        </svg>
      </div>
    </div>
  </div>
</div>  
<?php $this->load->view('template/footer'); ?>
<script src="<?php echo base_url()?>assets/lib/chartjs/Chart.min.js" type="text/javascript"></script>
<script src="<?php echo base_url()?>assets/lib/daterangepicker/js/daterangepicker.js" type="text/javascript"></script>
<script type="text/javascript">
  $(document).ready(function()
  {

    $('select').select2();
    
    $(".daterange").daterangepicker();

    $('.filter').change(function()
    {
      load_chart();
   });

   load_chart();  

   function load_chart() 
   {
      var date = $('#Date').val();
      var company = $('#company').val();
      var supplier = $('#Supplier').val();
      var subcontractor = $('#Sub').val();

     return new Promise(function (resolve, reject) {  

      $.ajax ({
        type:'POST',
        url:'<?php echo base_url();?>Reports/FetchChartData/',
        dataType: 'JSON',
        data: {'date': date, 'company': company, 'supplier': supplier,'subcontractor': subcontractor},
        beforeSend: function() {
          // $('.be-loading').addClass('be-loading be-loading-active');
        },
        success:function(chartdata)
        { 
          $('.be-loading').removeClass('be-loading-active');   
          if(window.line != undefined)
            window.line.destroy();

          window.line = new Chart(document.getElementById("bar-chart"),
          {
            "type":"bar",
            "data":
            {
              "labels": chartdata.label,
              "datasets":[
              {
                "label": "Booking",
                "data": chartdata.booking,
                "backgroundColor": "rgba(66, 183, 244, 0.8)",
                "borderColor": "rgb(14, 79, 187,0.8)",
                "borderWidth": 1
              }
              ]
            },
            "options":{ 
              "scales": {
                "yAxes":[{"ticks":{"beginAtZero":true,callback: function(value) {if (value % 1   === 0) {return value;}}}}]
              }
            }
          });

          if(window.dough != undefined)
            window.dough.destroy();

          window.dough = new Chart(document.getElementById("doughnut"),
          {
            "type":"doughnut",
            "data":
            {
              "labels":["Past","Upcoming","Cancelled"],
              "datasets":[
              {
                "label":"Booking",
                "data": chartdata.Book,
                "backgroundColor":["#fac70b","#0047ab","#df0e62"]
              }
              ]
            },
            options: {
              animation: {
                animateScale: true,
                animateRotate: true
              },
              tooltips: {
                callbacks: {
                  label: function(tooltipItem, data) {
                    var dataset = data.datasets[tooltipItem.datasetIndex];
                    var total = dataset.data.reduce(function(previousValue, currentValue, currentIndex, array) {
                      return previousValue + currentValue;
                    });
                    var currentValue = dataset.data[tooltipItem.index];
                    var percentage = Math.floor(((currentValue/total) * 100)+0.5);         
                    return ''+ percentage + "%";
                  }
                }
              }
            }
          });
          resolve(chartdata);
        }, error: function(err){
          reject(err);
        }
      });
    });
   }

  });
</script>
