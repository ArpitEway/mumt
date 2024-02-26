<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->
<head><base href="">
	<meta charset="utf-8" />
  <title>MMYVV STUDENT</title>
  <meta name="description" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <!--begin::Fonts-->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
  <!--end::Fonts-->
  <!--begin::Page Vendors Styles(used by this page)-->
  <link href="<?=base_url()?>assets/plugins/custom/fullcalendar/fullcalendar.bundle.css" rel="stylesheet" type="text/css" />
  <!--end::Page Vendors Styles-->
  <!--begin::Global Theme Styles(used by all pages)-->
  <link href="<?=base_url()?>assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
  <link href="<?=base_url()?>assets/plugins/custom/prismjs/prismjs.bundle.css" rel="stylesheet" type="text/css" />
  <link href="<?=base_url()?>assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
  <link href="<?=base_url()?>assets/css/theme.css" rel="stylesheet" type="text/css" />
  <!--end::Global Theme Styles-->
  <!--begin::Layout Themes(used by all pages)-->
  <!--end::Layout Themes-->
  <link rel="shortcut icon" href="<?=base_url()?>assets/images/maskgroup/MaskGroup1.png" />
  <link href="https:////cdn.materialdesignicons.com/5.4.55/css/materialdesignicons.min.css" rel="stylesheet" type="text/css" />

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script type="text/javascript">
    var BASE_URL = "<?php echo base_url();?>";
  </script>

</head>
<body id="student">

  <!--begin::Main-->
  <!--begin::Header Mobile-->
  <div id="kt_header_mobile" class="header-mobile header-mobile-fixed justify-content-end">
    <!--begin::Toolbar-->
    <div class="d-flex align-items-center">
      <button class="btn p-0 burger-icon burger-icon-left ml-4" id="kt_header_mobile_toggle">
        <span></span>
      </button>
    </div>
    <!--end::Toolbar-->
  </div>  
  <!--end::Header Mobile-->
  <div class="d-flex flex-column flex-root">
    <!--begin::Page-->
    <div class="d-flex flex-row flex-column-fluid page">
      <!--begin::Wrapper-->
      <div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">
        <!--begin::Header-->
        <div id="kt_header" class="header flex-column header-fixed">
          <!--begin::Top-->
          <div class="header-top" style="height: 50px;">
            <!--begin::Container-->
            <div class="container">
              <!--begin::Header Menu Wrapper-->
              <div class="header-navs header-navs-left" id="kt_header_navs">
                <!--begin::Tab Content-->
                <div class="tab-content">
                  <!--begin::Tab Pane-->
                  <div class="tab-pane pb-5 p-lg-0 show active" id="kt_header_tab_1">
                    <!--begin::Menu-->
                    <div id="kt_header_menu" class="header-menu header-menu-mobile header-menu-layout-default">
                      <!--begin::Nav-->
                      <?php 
                      $studentData = $this->Common_model->getRecordById('student','student_id',$this->session->student_id);
                      ?>
                      <ul class="menu-nav p-0">
                        <li class="menu-item <?= ($page_slug=='') ? 'menu-item-active' : ''; ?>" aria-haspopup="true">
                          <a href="<?=base_url()?>" class="menu-link">
                            <span class="menu-text">Home</span>
                          </a>
                        </li>
                        <?php if($studentData->admission_by=='web' && $studentData->form_fees=='N'){  ?>
                        <li class="menu-item <?= ($page_slug=='Admission Form') ? 'menu-item-active' : ''; ?>" aria-haspopup="true">
                          <a href="<?=base_url('admission_form')?>" class="menu-link">
                            <span class="menu-text">Admission Form</span>
                          </a>
                        </li>
                        <?php } ?>
                        <li class="menu-item <?= ($page_slug=='profile') ? 'menu-item-active' : ''; ?>" aria-haspopup="true">
                          <a href="<?=base_url('profile')?>" class="menu-link">
                            <span class="menu-text">Profile</span>
                          </a>
                        </li>
                       
                        <li class="menu-item <?= ($page_slug=='student_model_paper') ? 'menu-item-active' : ''; ?>" aria-haspopup="true">
                          <a href="<?=base_url('student_model_paper')?>" class="menu-link">
                            <span class="menu-text">Model Paper</span>
                          </a>
                        </li>
                        
                        <?php 
                        
                        $whereClass = array('class_id' => $studentData->class_id,
                          'exam_permission' => 'Y',
                        );
                        $timeTableData = $this->Common_model->getRecordByWhere('time_table',$whereClass);
                        ?>
                        <?php if ((count($timeTableData)!=0) && ($studentData->new_exam_form=='Y')): ?>
                          <li class="menu-item <?= ($page_slug=='exam_paper') ? 'menu-item-active' : ''; ?>" aria-haspopup="true">
                            <a href="<?=base_url('exam_paper')?>" class="menu-link">
                              <span class="menu-text">Exam Paper</span>
                            </a>
                          </li>
                        <?php endif ?>
                        <li class="menu-item" aria-haspopup="true">
                          <a href="<?=base_url('logout')?>" class="menu-link">
                            <span class="menu-text">Log Out</span>
                          </a>
                        </li>
                      </ul>
                      <!--end::Nav-->
                    </div>
                    <!--end::Menu-->
                  </div>
                  <!--begin::Tab Pane-->
                </div>
                <!--end::Tab Content-->
              </div>
              <!--end::Header Menu Wrapper-->
              <div class="topbar bg-primary">

              </div>
              <!--end::Topbar-->
            </div>
            <!--end::Container-->

          </div>
          <div style="background: #fff; box-shadow: 1px 4px 15px -14px black; height: 100px; z-index: 999;">
    <div class="container">
      <img src="<?=base_url('assets/images/center/logo.png')?>" alt="" style="
      padding: 15px 0px;
      ">
      <img src="<?=base_url('assets/images/center/name.png')?>" alt="" class="img2">
    </div>
  </div>
          <!--end::Top-->
        </div>
          <!--end::Header-->
<div class="container mt-5" style="max-width: 100%;">
      <div class="card card-custom gutter-b example example-compact mb-10">
        <div class="card-body " style="min-height:300px;">
          <div class="content-head row   justify-content-between mb-3">
            <h3 class="text-primary"><?php echo (isset($title)) ? $title : ''; ?></h3>
          </div>