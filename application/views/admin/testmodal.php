<div class="card card-custom">
 <div class="card-header">
  <h3 class="card-title">
   Bootstrap Date Picker Examples
  </h3>
 </div>
 <!--begin::Form-->
 <form class="form">
  <div class="card-body">
   <div class="form-group row">
    <label class="col-form-label text-right col-lg-3 col-sm-12">Minimum Setup</label>
    <div class="col-lg-4 col-md-9 col-sm-12">
     <input type="text" class="form-control" readonly placeholder="Select date"/>
    </div>
   </div>
   <div class="form-group row">
    <label class="col-form-label text-right col-lg-3 col-sm-12">Input Group Setup</label>
    <div class="col-lg-4 col-md-9 col-sm-12">
     <div class="input-group date">
      <input type="text" class="form-control" readonly  placeholder="Select date"/>
      <div class="input-group-append">
       <span class="input-group-text">
        <i class="la la-calendar-check-o"></i>
       </span>
      </div>
     </div>
    </div>
   </div>
   <div class="form-group row">
    <label class="col-form-label text-right col-lg-3 col-sm-12">Enable Helper Buttons</label>
    <div class="col-lg-4 col-md-9 col-sm-12">
     <div class="input-group date" >
      <input type="text" class="form-control" readonly  value="05/20/2017" id="kt_datepicker_3"/>
      <div class="input-group-append">
       <span class="input-group-text">
        <i class="la la-calendar"></i>
       </span>
      </div>
     </div>
     <span class="form-text text-muted">Enable clear and today helper buttons</span>
    </div>
   </div>
   <div class="form-group row">
    <label class="col-form-label text-right col-lg-3 col-sm-12">Orientation</label>
    <div class="col-lg-4 col-md-9 col-sm-12">
     <div class="input-group date mb-2" >
      <input type="text" class="form-control" placeholder="Top left" id="kt_datepicker_4_1"/>
      <div class="input-group-append">
      <span class="input-group-text">
       <i class="la la-bullhorn"></i>
       </span>
      </div>
     </div>

     <div class="input-group date mb-2">
      <input type="text" class="form-control" placeholder="Top right" id="kt_datepicker_4_2"/>
      <div class="input-group-append">
       <span class="input-group-text">
       <i class="la la-clock-o"></i>
       </span>
      </div>
     </div>

     <div class="input-group date mb-2">
      <input type="text" class="form-control" placeholder="Bottom left"  id="kt_datepicker_4_3"/>
      <div class="input-group-append">
       <span class="input-group-text">
       <i class="la la-check"></i>
       </span>
      </div>
     </div>

     <div class="input-group date">
      <input type="text" class="form-control" placeholder="Bottom right" id="kt_datepicker_4_4"/>
      <div class="input-group-append">
       <span class="input-group-text">
       <i class="la la-check-circle-o"></i>
       </span>
      </div>
     </div>
    </div>
   </div>
   <div class="form-group row">
    <label class="col-form-label text-right col-lg-3 col-sm-12">Range Picker</label>
    <div class="col-lg-4 col-md-9 col-sm-12">
     <div class="input-daterange input-group" id="kt_datepicker_5">
      <input type="text" class="form-control" name="start"/>
      <div class="input-group-append">
       <span class="input-group-text"><i class="la la-ellipsis-h"></i></span>
      </div>
      <input type="text" class="form-control" name="end"/>
     </div>
     <span class="form-text text-muted">Linked pickers for date range selection</span>
    </div>
   </div>
   <div class="form-group row">
    <label class="col-form-label text-right col-lg-3 col-sm-12">Inline Mode</label>
    <div class="col-lg-4 col-md-9 col-sm-12">
     <div class id="kt_datepicker_6"></div>
    </div>
   </div>
   <div class="form-group row">
    <label class="col-form-label text-right col-lg-3 col-sm-12">Modal Demos</label>
    <div class="col-lg-4 col-md-9 col-sm-12">
     <a href="#" class="btn font-weight-bold btn-light-primary" data-toggle="modal" data-target="#kt_datepicker_modal">Launch modal datepickers</a>
    </div>
   </div>
  </div>
  <div class="card-footer">
   <div class="form-group row">
    <div class="col-lg-9 ml-lg-auto">
     <button type="reset" class="btn btn-primary mr-2">Submit</button>
     <button type="reset" class="btn btn-secondary">Cancel</button>
    </div>
   </div>
  </div>
 </form>
 <!--end::Form-->
</div>
<div class="modal fade" id="kt_datepicker_modal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
									<div class="modal-dialog modal-lg" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title" id="exampleModalLabel">Bootstrap Date Picker Examples</h5>
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
													<i aria-hidden="true" class="ki ki-close"></i>
												</button>
											</div>
											<form class="form">
												<div class="modal-body">
													<div class="form-group row">
														<label class="col-form-label text-right col-lg-3 col-sm-12">Minimum Setup</label>
														<div class="col-lg-9 col-md-9 col-sm-12">
															<input type="text" class="form-control" id="kt_datepicker_1_modal" readonly="readonly" placeholder="Select date">
														</div>
													</div>
													<div class="form-group row">
														<label class="col-form-label text-right col-lg-3 col-sm-12">Input Group Setup</label>
														<div class="col-lg-9 col-md-9 col-sm-12">
															<div class="input-group date">
																<input type="text" class="form-control" readonly="readonly" placeholder="Select date" id="kt_datepicker_2_modal">
																<div class="input-group-append">
																	<span class="input-group-text">
																		<i class="la la-calendar-check-o"></i>
																	</span>
																</div>
															</div>
														</div>
													</div>
													<div class="form-group row">
														<label class="col-form-label text-right col-lg-3 col-sm-12">Enable Helper Buttons</label>
														<div class="col-lg-9 col-md-9 col-sm-12">
															<div class="input-group date">
																<input type="text" class="form-control" value="05/20/2017" id="kt_datepicker_3_modal">
																<div class="input-group-append">
																	<span class="input-group-text">
																		<i class="la la-calendar"></i>
																	</span>
																</div>
															</div>
															<span class="form-text text-muted">Enable clear and today helper buttons</span>
														</div>
													</div>
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-primary mr-2" data-dismiss="modal">Close</button>
													<button type="button" class="btn btn-secondary">Submit</button>
												</div>
											</form>
										</div>
									</div>
								</div>
<script>
	// Class definition

var KTBootstrapDatepicker = function () {

 var arrows;
 if (KTUtil.isRTL()) {
  arrows = {
   leftArrow: '<i class="la la-angle-right"></i>',
   rightArrow: '<i class="la la-angle-left"></i>'
  }
 } else {
  arrows = {
   leftArrow: '<i class="la la-angle-left"></i>',
   rightArrow: '<i class="la la-angle-right"></i>'
  }
 }

 // Private functions
 var demos = function () {
  // minimum setup
  $('#kt_datepicker_1').datepicker({
   rtl: KTUtil.isRTL(),
   todayHighlight: true,
   orientation: "bottom left",
   templates: arrows
  });

  // minimum setup for modal demo
  $('#kt_datepicker_1_modal').datepicker({
   rtl: KTUtil.isRTL(),
   todayHighlight: true,
   orientation: "bottom left",
   templates: arrows
  });

  // input group layout
  $('#kt_datepicker_2').datepicker({
   rtl: KTUtil.isRTL(),
   todayHighlight: true,
   orientation: "bottom left",
   templates: arrows
  });

  // input group layout for modal demo
  $('#kt_datepicker_2_modal').datepicker({
   rtl: KTUtil.isRTL(),
   todayHighlight: true,
   orientation: "bottom left",
   templates: arrows
  });

  // enable clear button
  $('#kt_datepicker_3, #kt_datepicker_3_validate').datepicker({
   rtl: KTUtil.isRTL(),
   todayBtn: "linked",
   clearBtn: true,
   todayHighlight: true,
   templates: arrows
  });

  // enable clear button for modal demo
  $('#kt_datepicker_3_modal').datepicker({
   rtl: KTUtil.isRTL(),
   todayBtn: "linked",
   clearBtn: true,
   todayHighlight: true,
   templates: arrows
  });

  // orientation
  $('#kt_datepicker_4_1').datepicker({
   rtl: KTUtil.isRTL(),
   orientation: "top left",
   todayHighlight: true,
   templates: arrows
  });

  $('#kt_datepicker_4_2').datepicker({
   rtl: KTUtil.isRTL(),
   orientation: "top right",
   todayHighlight: true,
   templates: arrows
  });

  $('#kt_datepicker_4_3').datepicker({
   rtl: KTUtil.isRTL(),
   orientation: "bottom left",
   todayHighlight: true,
   templates: arrows
  });

  $('#kt_datepicker_4_4').datepicker({
   rtl: KTUtil.isRTL(),
   orientation: "bottom right",
   todayHighlight: true,
   templates: arrows
  });

  // range picker
  $('#kt_datepicker_5').datepicker({
   rtl: KTUtil.isRTL(),
   todayHighlight: true,
   templates: arrows
  });

   // inline picker
  $('#kt_datepicker_6').datepicker({
   rtl: KTUtil.isRTL(),
   todayHighlight: true,
   templates: arrows
  });
 }

 return {
  // public functions
  init: function() {
   demos();
  }
 };
}();

jQuery(document).ready(function() {
 KTBootstrapDatepicker.init();
});
</script>