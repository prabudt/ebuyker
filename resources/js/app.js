require('./bootstrap');

import $ from 'jquery';
window.$ = window.jQuery = $;

import 'admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js';
import 'admin-lte/plugins/datatables/jquery.dataTables.min.js';
import 'admin-lte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js';
import 'admin-lte/plugins/datatables-responsive/js/dataTables.responsive.min.js';
import 'admin-lte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js';
import 'admin-lte/plugins/datatables-buttons/js/dataTables.buttons.min.js';
import 'admin-lte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js';
import 'admin-lte/plugins/jszip/jszip.min.js';
import 'admin-lte/plugins/pdfmake/pdfmake.min.js';
import 'admin-lte/plugins/pdfmake/vfs_fonts.js';
import 'admin-lte/plugins/datatables-buttons/js/buttons.html5.min.js';
import 'admin-lte/plugins/datatables-buttons/js/buttons.print.min.js';
import 'admin-lte/plugins/datatables-buttons/js/buttons.colVis.min.js';
import 'admin-lte/plugins/select2/js/select2.full.min.js';
import 'admin-lte/plugins/daterangepicker/daterangepicker.js';
import 'admin-lte/plugins/toastr/toastr.min.js';

//Don't forgot to put code also same as below otherwise it will not working


//Datatable
$("#example1").DataTable({
    "responsive": true, "lengthChange": false, "autoWidth": false,
    "buttons": ["csv", "print", "colvis"]
  }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

//Initialize Select2 Elements
$('.select2').select2()

//Initialize Select2 Elements
$('.select2bs4').select2({
    theme: 'bootstrap4'
})

$('#date_range').daterangepicker({
  autoUpdateInput: false,
  locale: {
      cancelLabel: 'Clear'
  }
})

$('input[id="date_range"]').on('apply.daterangepicker', function(ev, picker) {
  $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
});

$('input[id="date_range"]').on('cancel.daterangepicker', function(ev, picker) {
  $(this).val('');
});

$(window).on('beforeunload', function(){
    
  $('#pageLoader').show();

});

$(function () {

  $('#pageLoader').hide();
})
// ..........similarly other scripts comes
