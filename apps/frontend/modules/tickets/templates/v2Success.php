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
                    <ui-select multiple ng-model="filter.company_id" style="width: 300px;">
                      <ui-select-match placeholder="Выберите компании">{{$item.name}}</ui-select-match>
                      <ui-select-choices repeat="company.id as company in filterSelects.companies | filter:$select.search">
                        {{company.name}}
                      </ui-select-choices>
                    </ui-select>
                  </div>
                </div>

                <div class="control-group">
                  <label class="control-label">Категории</label>
                  <div class="controls">
                    <ui-select multiple ng-model="filter.category_id" style="width: 300px;">
                      <ui-select-match placeholder="Выберите категории…">{{$item.name}}</ui-select-match>
                      <ui-select-choices repeat="category.id as category in filterSelects.categories | filter:$select.search">
                        {{category.name}}
                      </ui-select-choices>
                    </ui-select>
                  </div>
                </div>

                <div class="control-group">
                  <label class="control-label">Ответственный за выполнение</label>
                  <div class="controls">
                    <ui-select multiple ng-model="filter.responsible_id" style="width: 300px;">
                      <ui-select-match placeholder="Выберите ответственных…">{{$item.first_name}} {{$item.last_name}} ({{$item.username}})</ui-select-match>
                      <ui-select-choices repeat="responsible.id as responsible in filterSelects.responsibles | filter:$select.search">
                        {{responsible.first_name}} {{responsible.last_name}} ({{responsible.username}})
                      </ui-select-choices>
                    </ui-select>
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
                      <input type="checkbox" ng-model="filter.without_periodicals"> Скрыть регламентные работы
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
    <pre>{{filter}}</pre>
  <?php endif ?>

  <ul class="nav nav-tabs">
    <li ng-repeat="tab in tabs" ng-class="{ active: tab.id === filter.tab }">
      <a href="" ng-click="selectTab(tab.id)">
        {{tab.name}}
        <span class="badge" ng-class="{ 'badge-warning': tab.count !== 0 }">{{tab.count}}</span>
      </a>
    </li>
  </ul>

  <div class="alert alert-info" ng-show="ticketsLoading">Загружаю заявки…</div>

  <table ng-show="!ticketsLoading && tickets.length > 0" class="table table-hover1 tickets20">
    <thead>
      <tr>
        <th class="span1">
          <a href="" ng-click="orderBy('id')">
            №
            <span ng-show="tableSorter.orderByField == 'id'"><span ng-show="!tableSorter.reverseSort">^</span><span ng-show="tableSorter.reverseSort">v</span></span>
          </a>
        </th>
        <th class="span12">
          <a href="" ng-click="orderBy('name')">
            Тема
            <span ng-show="tableSorter.orderByField == 'name'"><span ng-show="!tableSorter.reverseSort">^</span><span ng-show="tableSorter.reverseSort">v</span></span>
          </a>
        </th>
        <th>
          <a href="" ng-click="orderBy('created_at')">
            Дата
            <span ng-show="tableSorter.orderByField == 'created_at'"><span ng-show="!tableSorter.reverseSort">^</span><span ng-show="tableSorter.reverseSort">v</span></span>
          </a>
        </th>
        <th>
          <a href="" ng-click="orderBy('Category.name')">
            Категория
            <span ng-show="tableSorter.orderByField == 'Category.name'"><span ng-show="!tableSorter.reverseSort">^</span><span ng-show="tableSorter.reverseSort">v</span></span>
          </a>
        </th>
        <th>
          <a href="" ng-click="orderBy(['Creator.username', 'ToCompany.name'])">
            Пользователь@Компания
            <span ng-show="tableSorter.orderByField.toString() == ['Creator.username', 'ToCompany.name'].toString()"><span ng-show="!tableSorter.reverseSort">^</span><span ng-show="tableSorter.reverseSort">v</span></span>
          </a>
        </th>
        <th>Статус</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <tr ng-repeat="ticket in tickets | orderBy:tableSorter.orderByField:tableSorter.reverseSort" ng-class="{ 'unread': ticket.ReadedTickets.length === 0 }">
        <td>{{ticket.id}}</td>
        <td><a href="<?php echo url_for('@tickets-show?id=') ?>{{ticket.id}}" style="display: block;">
          {{ticket.name}}
        </a></td>
        <td>{{ticket.created_at | moment | date:'dd.MM.yyyy HH:mm:ss'}}</td>
        <td>{{ticket.Category ? ticket.Category.name : ''}}</td>
        <td>{{ticket.Creator.username}}@{{ticket.ToCompany ? ticket.ToCompany.name : '—'}}</td>
        <td>
          <span ng-if="ticket.CommentsAgain.length === 0">
            не в работе<span ng-if="ticket.Responsibles.length === 1">, ответственный: {{ticket.Responsibles[ticket.Responsibles.length - 1].username}}</span
            ><span ng-if="ticket.Responsibles.length > 1">, ответственные: <ul>
              <li ng-repeat="responsible in ticket.Responsibles">{{responsible.username}}</li>
            </ul></span>
          </span>

          <span ng-if="ticket.CommentsAgain.length !== 0" ng-init="applier = ticket.CommentsAgain[ticket.CommentsAgain.length - 1]">
            в работе с {{applier.created_at | moment | date:'dd.MM.yyyy HH:mm:ss'}}
            у {{applier.Creator.username}}
          </span>
        </td>
        <td><span class="badge" ng-class="{ 'badge-warning': ticket.ReadedComments.length !== ticket.Comments.length }">{{ticket.Comments.length}}</span></td>
      </tr>
    </tbody>
  </table>

  <h4 ng-show="!ticketsLoading && tickets.length === 0">
    Нет заявок.
  </h4>
</section>

<style>
  .tickets20 {}
  .tickets20 th {
    white-space: nowrap;
    font-size: 1.2em;
  }
  .tickets20 td {
    white-space: nowrap;
    background-color: #f5f5f5;
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
  URL =
    getCounters: '<?php echo url_for('ticketsApi/getCounters') ?>'
    getTickets: '<?php echo url_for('ticketsApi/getTickets') ?>'

  app = angular.module 'helpdesk', ['ngStorage', 'ui.select']

  app.config ['uiSelectConfig', (uiSelectConfig) ->
    uiSelectConfig.theme = 'select2'
  ]

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
        category_id: []
        company_id: []
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
      ]

      $scope.tickets = [
        {id: 1, name:'test'}
        {id: 2, name:'test2'}
      ]
      $scope.ticketsLoading = false

      $scope.selectTab = (id) ->
        $scope.filter.tab = id

      currentGetCanceller = null
      $scope.$watch 'filter', (newFilter) ->
        $scope.getCounters()
        return if not newFilter?

        $scope.ticketsLoading = true
        $scope.tickets.splice 0

        if currentGetCanceller?
          currentGetCanceller.resolve()

        currentGetCanceller = $q.defer()
        get = $http.get URL.getTickets, {
          timeout: currentGetCanceller.promise
          params: filter: $scope.filter
        }

        get.success (tickets) ->
          $scope.tickets = tickets

        get.then ->
          $scope.ticketsLoading = false
      , true

      $scope.getCounters = ->
        get = $http.get URL.getCounters, params: filter: $scope.filter
        get.success (counters) ->
          angular.forEach counters, (count, id) ->
            $filter('filter')($scope.tabs, id: id, true)[0]?.count = count

      if not $scope.filter.tab?
        $scope.selectTab $scope.tabs[0].id
  ]

  app.filter 'moment', ->
    (datetime) -> moment(datetime).toDate()

  angular.element(document).ready -> angular.bootstrap document, ['helpdesk']
</script>
