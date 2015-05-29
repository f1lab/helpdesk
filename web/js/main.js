$(function() {
  $.fn.datepicker.dates['ru'] = {
		days: ["Воскресенье", "Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота", "Воскресенье"],
		daysShort: ["Вск", "Пнд", "Втр", "Срд", "Чтв", "Птн", "Суб", "Вск"],
		daysMin: ["Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб", "Вс"],
		months: ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"],
		monthsShort: ["Янв", "Фев", "Мар", "Апр", "Май", "Июн", "Июл", "Авг", "Сен", "Окт", "Ноя", "Дек"],
		today: "Сегодня"
	};

  $('.datetimepickable').datetimepicker({
    'language': 'ru',
    'weekStart': 1
  });

  $('table.tickets tbody')
    .find('tr')
      .each(function() {
        $(this)
          .click(function() {
            document.location.href = $(this).find('a').eq(0).attr('href');
          })
        ;
      })
  ;

  $('form.add-comment')
    .find('.ticket-close')
      .click(function() {
        $('#comment_changed_ticket_state_to')
          .val('closed')
        ;
        return true;
      })
      .end()
    .find('.ticket-open')
      .click(function() {
        $('#comment_changed_ticket_state_to')
          .val('opened')
        ;
        return true;
      })
      .end()
  ;

  $('*[rel="tooltip"]')
    .tooltip()
  ;

  $('.comment-deleter, .ticket-deleter')
    .each(function() {
      $(this)
        .click(function() {
          if (confirm('Sure? Not undoable.')) {
            window.location.href = $(this).data('delete-uri');
          }

          return false;
        })
      ;
    })
  ;

  $.fn.wysihtml5.locale["ru-RU"] = {
    font_styles: {
        normal: "Обычный текст",
        h1: "Заголовок 1",
        h2: "Заголовок 2",
        h3: "Заголовок 3"
    },
    emphasis: {
        bold: "Полужирный",
        italic: "Курсив",
        underline: "Подчёркнутый"
    },
    lists: {
        unordered: "Маркированный список",
        ordered: "Нумерованный список",
        outdent: "Уменьшить отступ",
        indent: "Увеличить отступ"
    },
    link: {
        insert: "Вставить ссылку",
        cancel: "Отмена"
    },
    image: {
        insert: "Вставить изображение",
        cancel: "Отмена"
    },
    html: {
        edit: "HTML код"
    },
    colours: {
        black: "Чёрный",
        silver: "Серебряный",
        gray: "Серый",
        maroon: "Коричневый",
        red: "Красный",
        purple: "Фиолетовый",
        green: "Зелёный",
        olive: "Оливковый",
        navy: "Тёмно-синий",
        blue: "Синий",
        orange: "Оранжевый"
    }
};

  $('.wysiwyg')
    .wysihtml5({
      "font-styles": false, //Font styling, e.g. h1, h2, etc. Default true
      "emphasis": true, //Italics, bold, etc. Default true
      "lists": true, //(Un)ordered lists, e.g. Bullets, Numbers. Default true
      "html": true, //Button which allows you to edit the generated HTML. Default false
      "link": true, //Button to insert a link. Default true
      "image": true, //Button to insert an image. Default true,
      "color": false, //Button to change color of font
      'locale': 'ru-RU'
    });
  ;

  $('.chzn-select').chosen();
  $('.chzn-select-deselect').chosen({ allow_single_deselect: true });

  $('.tablesorter').tablesorter({
    textExtraction: function(node) {
      var $node = $(node)
        , value = $node.find('.tablesorter-value')
        , text
      ;

      if (value.length === 1) {
        text = value.text();
      } else {
        // fallback to default
        if (node.textContent) {
          text = node.textContent;
        } else {
          if (node.childNodes[0] && node.childNodes[0].hasChildNodes()) {
            text = node.childNodes[0].innerHTML;
          } else {
            text = node.innerHTML;
          }
        }
      }

      return text;
    }
  });

  (function addAcceleratorsToChosenSelects() {
    var $fill = $('<a href="#" class="select-accelerator">добавить всё</a>')
    var $reset = $('<a href="#" class="select-accelerator">очистить всё</a>')
    $('.chzn-select').each(function(id, select) {
      var $select = $(select)
        , container = $select.parent()
        , fill = $fill.clone()
        , reset = $reset.clone()
      ;

      fill.click(function(event) {
        event.preventDefault();
        $select
          .find('option')
            .prop('selected', true)
            .end()
          .trigger('liszt:updated')
          .trigger('change')
        ;
      });

      reset.click(function(event) {
        event.preventDefault();
        $select
          .find('option')
            .prop('selected', false)
            .end()
          .trigger('liszt:updated')
          .trigger('change')
        ;
      });

      container.append(fill);
      container.append(reset);
    });
  })();
});
