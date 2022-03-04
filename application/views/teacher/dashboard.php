<div class="row mt-2">
    <div class="col-12 col-md-3 col-sm-12 menu-background p-3" >
        <ul class="nav flex-column nav-pills">
            <li class="nav-item mb-2">
                <a class="nav-link active show border" id="Enrollment-tab" data-toggle="tab" href="#Enrollment">
                    <span class="nav-text">Teacher </span>
                    <span class="nav-icon flot-right" >
                        <i class="flaticon2-fast-next"></i>
                    </span>
                </a>
            </li>
            
          
        </ul>
    </div>
    <div class="col-md-8 col-12 col-sm-12 menu-background p-3">
        <div class="tab-content">
            <div class="tab-pane fade active show" id="Enrollment" role="tabpanel" aria-labelledby="Enrollment-tab">
                <div class="row">

                  

                    <a class="border-0 custom-menu-item" href="<?=base_url('Teacher/account_transection_details');?>">
                        <div>
                            <span class="nav-text">Teacher Bank Details</span>
                        </div>
                    </a>
                   
                     <a class="border-0 custom-menu-item" href="<?=base_url('Teacher/Teacher_answersheet_checked_count');?>">
                        <div>
                            <span class="nav-text">Teacher Count  Details</span>
                        </div>
                    </a>
                   
                    
                  
                </div>
            </div>
          
            <div class="tab-pane fade" id="exam" role="tabpanel" aria-labelledby="exam-tab">
                <div class="row">
   
                
                   
                </div>
            </div> 
        </div>
    </div>
</div>