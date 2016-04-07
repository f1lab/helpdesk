<section ng-controller="TicketsPageController">
  <h1>
    Мои заявки 2.0
  </h1>

  <div class="btn-toolbar">
    <div class="btn-group">
      <a href="<?php echo url_for('tickets/new') ?>" class="btn">
        <i class="icon icon-plus"></i>
        Добавить заявку
      </a>
      <a href="<?php echo url_for('ticketRepeater/new') ?>" class="btn">
        <i class="icon icon-plus"></i>
        Добавить регламентную работу
      </a>
    </div>

    <div class="btn-group">
      <a href="" class="btn" ng-click="refresh()">
        <i class="icon icon-refresh"></i>
        Обновить список заявок
      </a>
    </div>
  </div>

  <div class="accordion" style="margin-top: 20px;">
    <div class="accordion-group">
      <div class="accordion-heading" style="margin: 0;">
        <label class="accordion-toggle checkbox" style="margin-bottom: 0; padding-left: 30px;">
          <input type="checkbox" ng-model="filter.enabled"> Использовать фильтр
        </label>
      </div>
      <div id="collapseOne" class="accordion-body collapse" ng-class="{ in: filter.enabled }">
        <div class="accordion-inner">
          <form action="" class="form-horizontal" style="margin-bottom: 0;">
            <div class="control-group">
              <label class="control-label">Тема</label>
              <div class="controls">
                <input type="text" ng-model="filter.name" class="input-block-level" style="max-width: 900px">
              </div>
            </div>

            <div class="row">
              <div class="span6">
                <div class="control-group">
                  <label class="control-label">Компании</label>
                  <div class="controls">
                    <select
                      ng-model="filter.company_id" class="chzn-select" multiple
                      ng-options="company.id as company.name for company in filterSelects.companies"
                    ></select>
                  </div>
                </div>

                <div class="control-group">
                  <label class="control-label">Категории</label>
                  <div class="controls">
                    <select
                      ng-model="filter.category_id" class="chzn-select" multiple
                      ng-options="category.id as category.name for category in filterSelects.categories"
                    ></select>
                  </div>
                </div>

                <div class="control-group">
                  <label class="control-label">Ответственный за выполнение</label>
                  <div class="controls">
                    <select
                      ng-model="filter.responsible_id" class="chzn-select" multiple
                      ng-options="responsible.id as (responsible.first_name + ' ' + responsible.last_name + ' (' + responsible.username + ')') for responsible in filterSelects.responsibles"
                    ></select>
                  </div>
                </div>
              </div>

              <div class="span6">
                <div class="control-group">
                  <div class="controls">
                    <label class="checkbox">
                      <input type="checkbox" ng-model="filter.closed"> Только закрытые заявки
                    </label>
                  </div>
                </div>

                <div class="control-group">
                  <div class="controls">
                    <label class="checkbox">
                      <input type="checkbox" ng-model="filter.without_responsibles"> Не назначен ответственный
                    </label>
                  </div>
                </div>

                <div class="control-group">
                  <div class="controls">
                    <label class="checkbox">
                      <input type="checkbox" ng-model="filter.without_appliers"> Не принята в работу
                    </label>
                  </div>
                </div>

                <div class="control-group">
                  <div class="controls">
                    <label class="checkbox">
                      <input type="checkbox" ng-model="filter.without_periodicals"> Скрыть заявки по регламентным работам
                    </label>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <?php if (sfConfig::get('sf_environment', 'prod') === 'dev'): ?>
    <pre>{{filter | json}}</pre>
  <?php endif ?>

  <ul class="nav nav-tabs" ng-cloak>
    <li ng-repeat="tab in tabs" ng-class="{ active: tab.id === filter.tab }">
      <a href="" ng-click="selectTab(tab.id)">
        {{tab.name}}
        <span class="badge" ng-class="{ 'badge-warning': tab.count !== 0 }">{{tab.count}}</span>
      </a>
    </li>
  </ul>

  <div class="alert alert-info" ng-show="ticketsLoading">Загружаю заявки…</div>
  <div class="alert alert-warning" ng-show="ticketsLoadError" ng-cloak>Ошибка загрузки заявок. Попробуйте <a href="" ng-click="reloadPage()">обновить страницу</a>.</div>

  <table ng-show="tickets.length > 0" class="table tickets20" ng-cloak>
    <thead>
      <tr>
        <th class="id">
          <a href="" ng-click="orderBy('id')">
            №
            <span ng-show="tableSorter.orderByField == 'id'"><span ng-show="!tableSorter.reverseSort">^</span><span ng-show="tableSorter.reverseSort">v</span></span>
          </a>
        </th>
        <th class="name">Тема</th>
        <th class="repeated-every" ng-if="filter.tab === 'ticket-repeaters'">
          <a href="" ng-click="orderBy('repeated_every_days')">
            Период повторения
            <span ng-show="tableSorter.orderByField == 'repeated_every_days'"><span ng-show="!tableSorter.reverseSort">^</span><span ng-show="tableSorter.reverseSort">v</span></span>
          </a>
        </th>
        <th class="date">
          <a href="" ng-click="orderBy('created_at')" ng-if="filter.tab !== 'ticket-repeaters'">
            Дата
            <span ng-show="tableSorter.orderByField == 'created_at'"><span ng-show="!tableSorter.reverseSort">^</span><span ng-show="tableSorter.reverseSort">v</span></span>
          </a>
          <a href="" ng-click="orderBy('next_start')" ng-if="filter.tab === 'ticket-repeaters'">
            Следующее выполнение
            <span ng-show="tableSorter.orderByField == 'next_start'"><span ng-show="!tableSorter.reverseSort">^</span><span ng-show="tableSorter.reverseSort">v</span></span>
          </a>
        </th>
        <th class="deadline" ng-if="filter.tab !== 'ticket-repeaters'">
          <a href="" ng-click="orderBy('deadline')">
            Дедлайн
            <span ng-show="tableSorter.orderByField == 'deadline'"><span ng-show="!tableSorter.reverseSort">^</span><span ng-show="tableSorter.reverseSort">v</span></span>
          </a>
        </th>
        <th class="category">
          <a href="" ng-click="orderBy('Category.name')">
            Категория
            <span ng-show="tableSorter.orderByField == 'Category.name'"><span ng-show="!tableSorter.reverseSort">^</span><span ng-show="tableSorter.reverseSort">v</span></span>
          </a>
        </th>
        <th class="creator">
          <a href="" ng-click="orderBy(['Creator.username', 'Company.name'])" ng-if="filter.tab !== 'ticket-repeaters'">
            Пользователь@Компания
            <span ng-show="tableSorter.orderByField.toString() == ['Creator.username', 'Company.name'].toString()"><span ng-show="!tableSorter.reverseSort">^</span><span ng-show="tableSorter.reverseSort">v</span></span>
          </a>
          <a href="" ng-click="orderBy(['Initiator.username', 'Company.name'])" ng-if="filter.tab === 'ticket-repeaters'">
            Инициатор@Компания
            <span ng-show="tableSorter.orderByField.toString() == ['Initiator.username', 'Company.name'].toString()"><span ng-show="!tableSorter.reverseSort">^</span><span ng-show="tableSorter.reverseSort">v</span></span>
          </a>
        </th>
        <th class="state">Статус</th>
        <th class="comments"></th>
      </tr>
    </thead>
    <tbody>
      <tr ng-repeat="ticket in tickets | orderBy:tableSorter.orderByField:tableSorter.reverseSort" ng-class="{ 'unread': ticket.ReadedTickets.length === 0 }">
        <td class="id">{{ticket.id}}</td>
        <td class="name"><a href="<?php echo url_for('@tickets-show?id=') ?>{{ticket.id}}{{filter.tab === 'ticket-repeaters' ? '?repeater=true' : ''}}" title="{{ticket.name}}">
          {{ticket.name}}
        </a></td>
        <td class="repetaed-every" ng-if="filter.tab === 'ticket-repeaters'">{{ticket.repeated_every_days}} дней</td>

        <td class="date" ng-if="filter.tab !== 'ticket-repeaters'">{{ticket.created_at | moment | date:'dd.MM.yyyy HH:mm'}}</td>
        <td class="date" ng-if="filter.tab === 'ticket-repeaters'">{{ticket.next_start | moment | date:'dd.MM.yyyy HH:mm'}}</td>

        <td class="deadline" ng-if="filter.tab !== 'ticket-repeaters'">
          <span ng-if="ticket.deadline">{{ticket.deadline | moment | date:'dd.MM.yyyy HH:mm'}}</span>
          <span ng-if="!ticket.deadline">—</span>
        </td>

        <td class="category">{{ticket.Category ? ticket.Category.name : ''}}</td>
        <td class="creator">{{ticket[filter.tab === 'ticket-repeaters' ? 'Initiator' : 'Creator'].username}}@{{ticket.Company ? ticket.Company.name : '—'}}</td>
        <td class="state">
          <div ng-if="filter.tab !== 'ticket-repeaters'">
            <span ng-if="ticket.CommentsAgain.length === 0">
              не в работе<span ng-if="ticket.Responsibles.length === 1">, ответственный: {{ticket.Responsibles[ticket.Responsibles.length - 1].username}}</span
              ><span ng-if="ticket.Responsibles.length > 1">, ответственные: <ul>
                <li ng-repeat="responsible in ticket.Responsibles">{{responsible.username}}</li>
              </ul></span>
            </span>

            <span ng-if="ticket.CommentsAgain.length !== 0" ng-init="applier = ticket.CommentsAgain[ticket.CommentsAgain.length - 1]">
              в работе с {{applier.created_at | moment | date:'dd.MM.yyyy HH:mm'}}
              у {{applier.Creator.username}}
            </span>
          </div>

          <div ng-if="filter.tab === 'ticket-repeaters'">
            <div ng-if="ticket.Responsibles.length === 1">Ответственный: {{ticket.Responsibles[ticket.Responsibles.length - 1].username}}</div>
            <div ng-if="ticket.Responsibles.length > 1">Ответственные: <ul>
              <li ng-repeat="responsible in ticket.Responsibles">{{responsible.username}}</li>
            </ul></div>

            <div ng-if="ticket.Observers.length === 1">Наблюдатель: {{ticket.Observers[ticket.Observers.length - 1].username}}</div>
            <div ng-if="ticket.Observers.length > 1">Наблюдатели: <ul>
              <li ng-repeat="observer in ticket.Observers">{{observer.username}}</li>
            </ul></div>
          </div>
        </td>
        <td class="comments">
          <span ng-if="filter.tab !== 'ticket-repeaters'" class="badge" ng-class="{ 'badge-warning': ticket.ReadedComments.length !== ticket.Comments.length }">
            {{ticket.Comments.length}}
          </span>
        </td>
      </tr>
    </tbody>
  </table>

  <h4 ng-show="!ticketsLoading && !ticketsLoadError && tickets.length === 0" ng-cloak>
    Нет заявок.
  </h4>
</section>

<script>
  var selectsOptions = {
    "categories": [{id: null, name: 'Без категории'}].concat(
      <?php echo json_encode(Doctrine_Query::create()
        ->select('c.id, c.name')
        ->from('Category c')
        // ->leftJoin('c.RefUserCategory ref')
        // ->addWhere('ref.user_id = ?', $sf_user->getGuardUser()->getId())
        ->addOrderBy('c.name')
        ->execute([], Doctrine_Core::HYDRATE_ARRAY)
      ) ?>
    )

    , "companies": [{id: null, name: 'Без компании'}].concat(
      <?php echo json_encode(Doctrine_Query::create()
        ->select('g.id, g.name')
        ->from('sfGuardGroup g')
        ->leftJoin('g.RefCompanyResponsible ref')
        ->addWhere('ref.user_id = ?', $sf_user->getGuardUser()->getId())
        ->addOrderBy('g.name')
        ->execute([], Doctrine_Core::HYDRATE_ARRAY)
      ) ?>
    )

    , "responsibles": [].concat(
      <?php echo json_encode(Doctrine_Query::create()
        ->select('u.id, u.first_name, u.last_name, u.username')
        ->from('sfGuardUser u')
        ->addWhere('u.type = ?', 'it-admin')
        ->addOrderBy('u.first_name, u.last_name')
        ->execute([], Doctrine_Core::HYDRATE_ARRAY)
      ) ?>
    )
  }
</script>
<script type="text/coffeescript" src="/js/angular-TicketsPageController.coffee"></script>
