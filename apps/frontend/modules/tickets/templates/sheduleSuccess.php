<div id='wrap'>
  <div class="row-fluid">
    <div class="span3">
      <div id='external-events' class = "well well-small ">
        <h4>Нераспределенные заявки</h4>
        <?php foreach($unpartitioned as $ticket):?>
          <div class='external-event fc-event fc-event-vert fc-event-draggable fc-event-start fc-event-end  event-completed-not ui-draggable ui-resizable' style = 'margin-bottom: 5px;overflow: hidden;' data-id="<?php echo $ticket[0] ?>" >
            <span>
              <?php echo $ticket[0];?> : <?php echo $ticket[1];?><br>
              До : <?php echo $ticket[9];?>
            </span>
          </div>
        <?php endforeach;?>
      </div>
    </div>
    <div class="span9">
      <div id='calendar'></div>
      <div style='clear:both'></div>
    </div>
  </div>
</div>

<script>
	$(document).ready(function() {
		$('#external-events div.external-event').each(function() {
      //event
			var eventObject = {
				title: $.trim($(this).text()), // use the element's text as the event title
        id: $(this).data('id'),
        allDay: false
			};
			// store the Event Object in the DOM element so we can get to it later
			$(this).data('eventObject', eventObject);
			// make the event draggable using jQuery UI
			$(this).draggable({
				zIndex: 999,
				revert: true,      // will cause the event to go back to its
				revertDuration: 0  //  original position after the drag
			});	
		});
    
    var clickHandler = function(event, e) {
      e.preventDefault();
      var modalNode = $('#event-details')
        .find('.modal-body')
          .html('<p>Загружаю…</p>')
          .end()
        .modal()
        $.get('<?php echo url_for('@shedule-modal?id=') ?>' + event.id)
          .done(function(data) {
            modalNode
              .find('.modal-body')
                .html(data)
          })
          .fail(function() {
            modalNode
              .find('.modal-body')
                .html('<div class="alert alert-error">Ошибка получения данных :(</div>')
          })
          return false;
      }
  
    changeHandler = function(event) {
      $.post('<?php echo url_for('/tickets/sheduleEvent') ?>', {
      event: $.extend({}, event, {
        start: (new Date(event.start)).getTime()/1000
        , end: (new Date(event.end)).getTime()/1000
        })
      })
      .fail(function() {
        alert('Ошибка установления даты для работы :(');
      })
      .always(function() {
        $('#calendar').fullCalendar('refetchEvents');
      })
    }
	
		/* initialize the calendar
		-----------------------------------------------------------------*/
		$('#calendar').fullCalendar({
			editable: true,
      selectable: true,
			selectHelper: true,
			select: function(start, end, allDay) {
				var title = prompt('Тема:');
				if (title) {
          $.post('<?php echo url_for('/tickets/sheduleEventNew') ?>', {
          event: $.extend({},{
            title: title,
            start: (new Date(start)).getTime()/1000,
              end: (new Date(end)).getTime()/1000
            })
          })
          .fail(function() {
            alert('Ошибка создания события :(');
          })
          .always(function() {
            $('#calendar').fullCalendar('refetchEvents');
          })
        }
      },
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
      defaultView: 'agendaWeek',
      timeFormat: 'H:mm',
      axisFormat: 'H:mm',
      allDaySlot: false,
      monthNames: ['Январь','Февраль','Март','Апрель','Май','οюнь','οюль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
      monthNamesShort: ['Янв.','Фев.','Март','Апр.','Май','οюнь','οюль','Авг.','Сент.','Окт.','Ноя.','Дек.'],
      dayNames: ["Воскресенье","Понедельник","Вторник","Среда","Четверг","Пятница","Суббота"],
      dayNamesShort: ["ВС","ПН","ВТ","СР","ЧТ","ПТ","СБ"],
      buttonText: {
      prev: "&nbsp;&#9668;&nbsp;",
        next: "&nbsp;&#9658;&nbsp;",
        prevYear: "&nbsp;&lt;&lt;&nbsp;",
        nextYear: "&nbsp;&gt;&gt;&nbsp;",
        today: "Сегодня",
        month: "Месяц",
        week: "Неделя",
        day: "День",
      },
      resizeble: true,
			droppable: true, // this allows things to be dropped onto the calendar !!!
      eventSources: [
        '<?php echo url_for('tickets/eventsource') ?>'
      ],
      
			drop: function(date) {
        var eventObject = $.extend($(this).data('eventObject'), {start: date})
        $('#calendar').fullCalendar('renderEvent', eventObject);

        $.post('<?php echo url_for('/tickets/sheduleEvent') ?>', {
          event: $.extend({}, eventObject, {
            start: (new Date(eventObject.start)).getTime()/1000
          })
        })
        .fail(function() {
          alert('Ошибка установления даты для работы :(');
        })
        .always(function() {
          $('#calendar').fullCalendar('refetchEvents');
        })

        $(this).remove();
      },
      eventDrop: changeHandler,
      eventResize: changeHandler,
      eventClick: clickHandler
		});
	});
</script>

<div class="modal hide" id="event-details">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3>Подробнее!</h3>
  </div>
  <div class="modal-body"></div>
  <div class="modal-footer hide">
    <a href="#" class="btn btn-success">Работа выполнена</a>
  </div>
</div>
