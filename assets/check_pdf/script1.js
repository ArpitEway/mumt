var pdf_url =  $('#pdf_url').val();

var pdf = new PDFAnnotate('pdf-container', pdf_url, {
  
  onPageUpdated(page, oldData, newData) {
    console.log(page, oldData, newData);
  
  },

  ready() {
       Plugin_initialized();  
    pdf.setBrushSize(7);
    pdf.setFontSize(18);
  console.log('Plugin initialized successfully');
  if($('#old_json_data').length>0){
    //console.log($('#old_json_data').val()); 
    pdf.loadFromJSON(JSON.parse($('#old_json_data').val()));

  }
  
  },
  scale: 1.5,
  pageImageCompression: 'MEDIUM', // FAST, MEDIUM, SLOW(Helps to control the new PDF file size)
});




function changeActiveTool(event) {
  var element = $(event.target).hasClass('tool-button')
    ? $(event.target)
    : $(event.target).parents('.tool-button').first();
  $('.tool-button.active').removeClass('active');
  $(element).addClass('active');
}

function enableSelector(event) {
  event.preventDefault();
  changeActiveTool(event);
  pdf.enableSelector();
}

function enablePencil(event) {
  event.preventDefault();
  changeActiveTool(event);
  pdf.enablePencil();
}

// function enableAddText(event) {
//   event.preventDefault();
//   changeActiveTool(event);
//   pdf.enableAddText();
// }

// function enableAddArrow(event) {
//   event.preventDefault();
//   changeActiveTool(event);
//   pdf.enableAddArrow(function () {
//     $('.tool-button').first().find('i').click();
//   });
// }

// function addImage(event) {
//   event.preventDefault();
//   pdf.addImageToCanvas();
// }

// function enableRectangle(event) {
//   event.preventDefault();
//   changeActiveTool(event);
//   pdf.setColor('rgba(255, 0, 0, 0.3)');
//   pdf.setBorderColor('blue');
//   pdf.enableRectangle();
// }

// function deleteSelectedObject(event) {
//   event.preventDefault();
//   pdf.deleteSelectedObject();
// }

function savePDF() {
  // pdf.savePdf();
  var file_name = $('#file_name').val();
  pdf.savePdf(file_name); // save with given file name
}

function clearPage() {
  pdf.clearActivePage();
}

function showPdfData() {

  pdf.serializePdf(function (string) {

 var csrfName = $('.csrfname').attr('name');
    var csrfHash = $('.csrfname').val(); 
    
    var upload_exam_ans_sheet_id = $('#upload_exam_ans_sheet_id').val();

    var data = {upload_exam_ans_sheet_id:upload_exam_ans_sheet_id,
      json_data: JSON.stringify(JSON.parse(string), null, 4),
      [csrfName]:csrfHash,
      };


    $.ajax({


            url:BASE_URL + "admin/ExamController/student_marks_entry_update",
            type: 'POST',
            dataType: 'json',
            data: data,
            success: function (data) {
      alert('Answersheet Checked');

             window.location = window.close();


       if(data.success){
            toastr.success(data.success);

          }else{
            toastr.error(data.error);
          }
                //$(self).parent().html(data.data);
            },
        });
  });
}


function Plugin_initialized() {

   pdf.serializePdf(function (initializeData) {

   var csrfName = $('.csrfname').attr('name');
   var csrfHash = $('.csrfname').val(); 

   var upload_exam_ans_sheet_id = $('#upload_exam_ans_sheet_id').val();

   var data = {upload_exam_ans_sheet_id:upload_exam_ans_sheet_id,
    initialize_json_data: JSON.stringify(JSON.parse(initializeData), null, 4),
    [csrfName]:csrfHash,
  };


  $.ajax({


    url:BASE_URL + "admin/ExamController/Plugin_initialized_entry_update",
    type: 'POST',
    dataType: 'json',
    data: data,
    success: function (data) {
      
      if(data.success){
            toastr.success(data.success);

          }else{
            toastr.error(data.success);
          }
            
              },
            });
 });
}

// $(function () {
//   $('.color-tool').click(function () {
//     $('.color-tool.active').removeClass('active');
//     $(this).addClass('active');
//     color = $(this).get(0).style.backgroundColor;
//     pdf.setColor(color);
//   });

  // $('#brush-size').change(function () {
  //   var width = $(this).val();
  //   pdf.setBrushSize(width);
  // });

//   $('#font-size').change(function () {
//     var font_size = $(this).val();
//     pdf.setFontSize(font_size);
//   });
// });
