<section ng-controller="TicketsPageController">
  <h1>
    Мои заявки 2.0
  </h1>

  <div class="btn-toolbar">
    <div class="btn-group">
      <a href="/frontend_dev.php/tickets/new" class="btn">
        <i class="icon icon-plus"></i>
        Добавить заявку
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
                      <input type="checkbox" ng-model="filter.without_periodicals"> Скрыть повторяющиеся работы
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

  <?php if ($sf_request->getParameter('dev', false)): ?>
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

  <table ng-show="tickets.length > 0" class="table table-hover1 tickets20" ng-cloak>
    <thead>
      <tr>
        <th class="id">
          <a href="" ng-click="orderBy('id')">
            №
            <span ng-show="tableSorter.orderByField == 'id'"><span ng-show="!tableSorter.reverseSort">^</span><span ng-show="tableSorter.reverseSort">v</span></span>
          </a>
        </th>
        <th class="name">Тема</th>
        <th class="date">
          <a href="" ng-click="orderBy('created_at')">
            Дата
            <span ng-show="tableSorter.orderByField == 'created_at'"><span ng-show="!tableSorter.reverseSort">^</span><span ng-show="tableSorter.reverseSort">v</span></span>
          </a>
        </th>
        <th class="category">
          <a href="" ng-click="orderBy('Category.name')">
            Категория
            <span ng-show="tableSorter.orderByField == 'Category.name'"><span ng-show="!tableSorter.reverseSort">^</span><span ng-show="tableSorter.reverseSort">v</span></span>
          </a>
        </th>
        <th class="creator">
          <a href="" ng-click="orderBy(['Creator.username', 'ToCompany.name'])">
            Пользователь@Компания
            <span ng-show="tableSorter.orderByField.toString() == ['Creator.username', 'ToCompany.name'].toString()"><span ng-show="!tableSorter.reverseSort">^</span><span ng-show="tableSorter.reverseSort">v</span></span>
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
        <td class="date">{{ticket.created_at | moment | date:'dd.MM.yyyy HH:mm'}}</td>
        <td class="category">{{ticket.Category ? ticket.Category.name : ''}}</td>
        <td class="creator">{{ticket.Creator.username}}@{{ticket.ToCompany ? ticket.ToCompany.name : '—'}}</td>
        <td class="state">
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
        </td>
        <td class="comments"><span class="badge" ng-class="{ 'badge-warning': ticket.ReadedComments.length !== ticket.Comments.length }">{{ticket.Comments.length}}</span></td>
      </tr>
    </tbody>
  </table>

  <h4 ng-show="!ticketsLoading && !ticketsLoadError && tickets.length === 0" ng-cloak>
    Нет заявок.
  </h4>
</section>

<style>
  .tickets20 {}
  .tickets20 th {
    white-space: nowrap;
    font-size: 1.2em;
    text-align: center;
  }
  .tickets20 td {
    white-space: nowrap;
    background-color: #f5f5f5;
  }
  .tickets20 td.name a {
    display: block;
    text-overflow: ellipsis;
    width: 400px;
    overflow: hidden;
  }
  .tickets20 .unread {
    font-weight: bolder;
  }
  .tickets20 .unread td {
    background-color: #fff;
  }
  .tickets20 ul {
    margin-bottom: 0;
  }
  .tickets20 tr:hover td {
    background-color: #dff0d8;
  }
</style>

<script type="text/coffeescript">
  app = angular.module 'helpdesk', ['ngStorage']

  app.controller 'TicketsPageController', [
    '$scope', '$http', '$timeout', '$filter', '$q', '$sessionStorage', '$localStorage'
    ($scope, $http, $timeout, $filter, $q, $sessionStorage, $localStorage) ->
      $scope.tableSorter = $localStorage.$default
        orderByField: 'id'
        reverseSort: false

      $scope.orderBy = (column) ->
        sameColumnSelected = $scope.tableSorter.orderByField.toString() is column.toString()
        $scope.tableSorter.orderByField = column

        $scope.tableSorter.reverseSort = if sameColumnSelected then !$scope.tableSorter.reverseSort else false

      $scope.reloadPage = ->
        document.location.reload()

      $scope.filterSelects =
        categories: [{id: null, name: 'Без категории'}].concat(
          <?php echo json_encode(Doctrine_Query::create()
            ->select('c.id, c.name')
            ->from('Category c')
            // ->leftJoin('c.RefUserCategory ref')
            // ->addWhere('ref.user_id = ?', $sf_user->getGuardUser()->getId())
            ->addOrderBy('c.name')
            ->execute([], Doctrine_Core::HYDRATE_ARRAY)
          ) ?>
        )

        companies: [{id: null, name: 'Без компании'}].concat(
          <?php echo json_encode(Doctrine_Query::create()
            ->select('g.id, g.name')
            ->from('sfGuardGroup g')
            ->leftJoin('g.RefCompanyResponsible ref')
            ->addWhere('ref.user_id = ?', $sf_user->getGuardUser()->getId())
            ->addOrderBy('g.name')
            ->execute([], Doctrine_Core::HYDRATE_ARRAY)
          ) ?>
        )

        responsibles: [].concat(
          <?php echo json_encode(Doctrine_Query::create()
            ->select('u.id, u.first_name, u.last_name, u.username')
            ->from('sfGuardUser u')
            ->addWhere('u.type = ?', 'it-admin')
            ->addOrderBy('u.first_name, u.last_name')
            ->execute([], Doctrine_Core::HYDRATE_ARRAY)
          ) ?>
        )

      $scope.filter = $sessionStorage.$default
        enabled: false
        tab: null
        closed: false
        company_id: []
        category_id: []
        responsible_id: []
        without_responsibles: false
        without_appliers: false
        without_periodicals: false
        refresh: 0

      delete $scope.filter.$default
      delete $scope.filter.$reset

      $scope.refresh = -> $scope.filter.refresh++

      $scope.tabs = [
        {id: 'created-by-me', name: 'Созданы мной', count: 0}
        {id: 'assigned-to-me', name: 'Я назначен ответственным', count: 0}
        {id: 'observed-by-me', name: 'Я назначен наблюдателем', count: 0}
        {id: 'auto-assigned-to-me', name: 'От моих компаний', count: 0}
        {id: 'ticket-repeaters', name: 'Регламентные', count: 0}
      ]

      $scope.tickets = []
      $scope.ticketsLoading = false
      $scope.ticketsLoadError = false

      $scope.selectTab = (id) ->
        $scope.filter.tab = id

      window.a = ticketsCache = {}

      currentGetCanceller = null
      $scope.$watch 'filter', (newFilter, oldFilter) ->
        $scope.getCounters()
        return if not newFilter?

        $scope.ticketsLoadError = false
        $scope.ticketsLoading = true

        # put to cache old tab tickets
        if ticketsCache[oldFilter.tab]?
          ticketsCache[oldFilter.tab].splice 0
        ticketsCache[oldFilter.tab] = $scope.tickets.splice 0

        # get from cache new tab tickets
        if ticketsCache[newFilter.tab]?.length > 0
          $scope.tickets = ticketsCache[newFilter.tab]

        if currentGetCanceller?
          currentGetCanceller.resolve()

        currentGetCanceller = $q.defer()
        get = $http.get API.getTickets, {
          timeout: currentGetCanceller.promise
          params: filter: $scope.filter
        }

        get.success (tickets) ->
          $scope.tickets = tickets
          $scope.ticketsLoadError = false
          $scope.ticketsLoading = false

        get.error (data, statusCode) ->
          if statusCode > 0
            $scope.ticketsLoadError = true
            $scope.ticketsLoading = false

      , true

      currentGetCountersCanceller = null
      $scope.getCounters = ->
        if currentGetCountersCanceller?
          currentGetCountersCanceller.resolve()

        currentGetCountersCanceller = $q.defer()
        get = $http.get API.getCounters, {
          timeout: currentGetCountersCanceller.promise
          params: filter: $scope.filter
        }

        get.success (counters) ->
          angular.forEach counters, (count, id) ->
            $filter('filter')($scope.tabs, id: id, true)[0]?.count = count

      if not $scope.filter.tab?
        $scope.selectTab $scope.tabs[0].id

      $timeout -> angular.element('.chzn-select').trigger('liszt:updated')
  ]

  app.filter 'moment', ->
    (datetime) -> moment(datetime).toDate()

  angular.element(document).ready -> angular.bootstrap document, ['helpdesk']
</script>
