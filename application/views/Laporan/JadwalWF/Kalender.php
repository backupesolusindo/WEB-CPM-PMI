<div class="card-body b-l calender-sidebar">
    <div id="calendar"></div>
</div>
<script type="text/javascript">

!function($) {
    "use strict";

    var CalendarApp = function() {
        this.$body = $("body")
        this.$calendar = $('#calendar'),
        this.$event = ('#calendar-events div.calendar-events'),
        this.$categoryForm = $('#add-new-event form'),
        this.$extEvents = $('#calendar-events'),
        this.$modal = $('#my-event'),
        this.$saveCategoryBtn = $('.save-category'),
        this.$calendarObj = null
    };

    /* Initializing */
    CalendarApp.prototype.init = function() {
        /*  Initialize the calendar  */
        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();
        var form = '';
        var today = new Date($.now());

        var defaultEvents =  [
          <?php foreach ($jadwal->result() as $value):
            $title = "Belum Mengisi Jadwal";
            $color = "bg-primary";
            if ($value->wf == 1) {
              $title = "WFO";
              $color = "bg-info";
            }else if ($value->wf == 2) {
              $title = "WFH";
              $color = "bg-success";
            }
            ?>
            {
              title: '<?php echo $title ?>',
              start: new Date("<?php echo date('Y', strtotime($value->tanggal)) ?>","<?php echo date('m', strtotime($value->tanggal)) - 1 ?>","<?php echo date('d', strtotime($value->tanggal)) ?>"),
              className: '<?php echo $color ?>'
            },
          <?php endforeach; ?>
          ];

        var $this = this;
        $this.$calendarObj = $this.$calendar.fullCalendar({
            slotDuration: '00:15:00', /* If we want to split day time each 15minutes */
            minTime: '08:00:00',
            maxTime: '19:00:00',
            defaultView: 'month',
            handleWindowResize: true,

            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month'
            },
            events: defaultEvents,
            editable: false,
            droppable: false, // this allows things to be dropped onto the calendar !!!
            eventLimit: false, // allow "more" link when too many events
            selectable: false,
        });

    },
   //init CalendarApp
    $.CalendarApp = new CalendarApp, $.CalendarApp.Constructor = CalendarApp

}(window.jQuery),

//initializing CalendarApp
function($) {
    "use strict";
    $.CalendarApp.init()
}(window.jQuery);

</script>
