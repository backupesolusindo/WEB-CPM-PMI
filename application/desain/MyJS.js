function kembali() {
  alert("kembali");
  window.history.back();
}

function MyValid() {
  $('.uang').mask('000.000.000.000', {
    reverse: true
  });

  $(".angkasaja").keypress(function(data) {
    if (data.which != 8 && data.which != 46 && data.which != 0 && (data.which < 48 || data.which > 57)) {
      // alert(data.which);
      return false;
    }
  });
  $(".hurufsaja").keypress(function(data) {
    if (data.which != 8 && data.which != 0 && (data.which <= 65 || data.which == 32 || data.which >= 90) && (data.which <= 97 || data.which >= 122)) {
      return false;
    }
  });
  $(".spasinone").keypress(function(data) {
    // alert(data.which);

    if (data.which == 32) {
      return false;
    }
  });
  $(".inputnone").keypress(function(data) {
    if (data.which == null || data.which != null) {
      return false;
    }
  });
}

/* Fungsi formatRupiah */
function formatRupiahh(angka) {
  var hasil = angka;
  return hasil.toLocaleString().replace(/\,/g, '.');
}

function ClearCurrent(angka) {
  var hasil = angka;
  return hasil.replace(/\./g, '');
}




$(document).ready(function() {
  MyValid();
  // --------------------------------------------------DROPIFY ------------------------------------------------------------------------
  // Basic
  $('.dropify').dropify();

  // Translated
  $('.dropify-fr').dropify({
    messages: {
      default: 'Glissez-déposez un fichier ici ou cliquez',
      replace: 'Glissez-déposez un fichier ou cliquez pour remplacer',
      remove: 'Supprimer',
      error: 'Désolé, le fichier trop volumineux'
    }
  });

  // Used events
  var drEvent = $('#input-file-events').dropify();

  drEvent.on('dropify.beforeClear', function(event, element) {
    return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
  });

  drEvent.on('dropify.afterClear', function(event, element) {
    alert('File deleted');
  });

  drEvent.on('dropify.errors', function(event, element) {
    console.log('Has Errors');
  });

  var drDestroy = $('#input-file-to-destroy').dropify();
  drDestroy = drDestroy.data('dropify')
  $('#toggleDropify').on('click', function(e) {
    e.preventDefault();
    if (drDestroy.isDropified()) {
      drDestroy.destroy();
    } else {
      drDestroy.init();
    }
  })
  // ---------------------------------------------------------------------------------------------------------------------------------

  // ----------------------------------------------- DATA TABLE ----------------------------------------------------------------------

  $('#optgroup').multiSelect({
    selectableOptgroup: true
  });
  $('#public-methods').multiSelect();
  // select2
  $('.select2').select2();
  $('#myTable').DataTable();
  $(document).ready(function() {
    var table = $('#example_group').DataTable({
      "columnDefs": [{
        "visible": false,
        "targets": 2
      }],
      "order": [
        [2, 'asc']
      ],
      "displayLength": 25,
      "drawCallback": function(settings) {
        var api = this.api();
        var rows = api.rows({
          page: 'current'
        }).nodes();
        var last = null;
        api.column(2, {
          page: 'current'
        }).data().each(function(group, i) {
          if (last !== group) {
            $(rows).eq(i).before('<tr class="group"><td colspan="5">' + group + '</td></tr>');
            last = group;
          }
        });
      }
    });
    // Order by the grouping
    $('#example tbody').on('click', 'tr.group', function() {
      var currentOrder = table.order()[0];
      if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
        table.order([2, 'desc']).draw();
      } else {
        table.order([2, 'asc']).draw();
      }
    });
  });
});

$('.print-view').DataTable({
  dom: 'Bfrtip',
  buttons: ['excel', 'pdf'],
});
$('.print-excel').DataTable({
  dom: 'Bfrtip',
  buttons: ['excel'],
});
$('#example').DataTable();


// ---------------------------------------------------------------------------------------------------------------------------------------------

// -------------------------------------------------------CLOCK AND TIME PICKER ---------------------------------------------------------------

$('#mdate').bootstrapMaterialDatePicker({
  weekStart: 0,
  time: false
});
$('#timepicker').bootstrapMaterialDatePicker({
  format: 'HH:mm',
  time: true,
  date: false
});
$('#date-format').bootstrapMaterialDatePicker({
  format: 'dddd DD MMMM YYYY - HH:mm'
});

$('#min-date').bootstrapMaterialDatePicker({
  format: 'DD/MM/YYYY HH:mm',
  minDate: new Date()
});
// Clock pickers
$('#single-input').clockpicker({
  placement: 'bottom',
  align: 'left',
  autoclose: true,
  'default': 'now'
});

$('.waktu-input').clockpicker({
  placement: 'bottom',
  align: 'left',
  autoclose: true,
  'default': 'now'
});
$('.clockpicker').clockpicker({
  donetext: 'Done',
}).find('input').change(function() {
  console.log(this.value);
});
$('#check-minutes').click(function(e) {
  // Have to stop propagation here
  e.stopPropagation();
  input.clockpicker('show').clockpicker('toggleView', 'minutes');
});
if (/mobile/i.test(navigator.userAgent)) {
  $('input').prop('readOnly', true);
}
// Colorpicker
$(".colorpicker").asColorPicker();
$(".complex-colorpicker").asColorPicker({
  mode: 'complex'
});
$(".gradient-colorpicker").asColorPicker({
  mode: 'gradient'
});
// Date Picker
jQuery('.mydatepicker, #datepicker').datepicker({
  format: 'dd-mm-yyyy'
});
jQuery('#datepicker-autoclose').datepicker({
  autoclose: true,
  todayHighlight: true
});
jQuery('#date-range').datepicker({
  toggleActive: true,
  format: 'dd-mm-yyyy',
});
jQuery('#datepicker-inline').datepicker({
  todayHighlight: true
});
// Daterange picker
$('.input-daterange-datepicker').daterangepicker({
  buttonClasses: ['btn', 'btn-sm'],
  applyClass: 'btn-danger',
  cancelClass: 'btn-inverse'
});
$('.input-daterange-timepicker').daterangepicker({
  timePicker: true,
  format: 'MM/DD/YYYY h:mm A',
  timePickerIncrement: 30,
  timePicker12Hour: true,
  timePickerSeconds: false,
  buttonClasses: ['btn', 'btn-sm'],
  applyClass: 'btn-danger',
  cancelClass: 'btn-inverse'
});
$('.input-limit-datepicker').daterangepicker({
  format: 'MM/DD/YYYY',
  minDate: '06/01/2015',
  maxDate: '06/30/2015',
  buttonClasses: ['btn', 'btn-sm'],
  applyClass: 'btn-danger',
  cancelClass: 'btn-inverse',
  dateLimit: {
    days: 6
  }
});

// ----------------------------------------------------------------------------------------------------------------------------------------------