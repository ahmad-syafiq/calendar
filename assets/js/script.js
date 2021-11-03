$(document).ready(function() {
	$('[data-toggle="popover"]').popover();

	$("[data-admin]").click(function () {
    $("[name='add_ondate']").val($(this).data("date"));
    $(".modal-title").html($(this).data("date"));

    $("#myModal").modal("show");  
    $("[data-toggle='popover']").popover("hide");
	});

  $('.js-daterangepicker-clear').daterangepicker({
    autoUpdateInput: false,
    locale: {
      cancelLabel: 'Clear'
    }
  });

  $('.js-daterangepicker-clear').on('apply.daterangepicker', function(ev, picker) {
    $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
  });

  $('.js-daterangepicker-clear').on('cancel.daterangepicker', function(ev, picker) {
    $(this).val('');
  });
});