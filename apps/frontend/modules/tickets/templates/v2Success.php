<section ng-controller="TicketsPageController">
  <div class="accordion">
    <div class="accordion-group">
      <h4 class="accordion-heading" style="margin: 0;">
        <a class="accordion-toggle" href="" ng-click="filter.collapsed = !filter.collapsed">
          Фильтр
        </a>
      </h4>
      <div id="collapseOne" class="accordion-body collapse" ng-class="{ in: filter.collapsed }">
        <div class="accordion-inner">
          <form action="" class="form-horizontal">
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
          </form>
        </div>
      </div>
    </div>
  </div>

  <pre>{{filter}}</pre>
  <ul class="nav nav-tabs">
    <li ng-repeat="tab in tabs" ng-class="{ active: tab.id === filter.tab }">
      <a href="" ng-click="selectTab(tab.id)">
        {{tab.name}}
        <span class="badge">{{tab.count}}</span>
      </a>
    </li>
  </ul>

  <div class="alert alert-info" ng-show="ticketsLoading">Загружаю тикеты…</div>
  <table ng-show="!ticketsLoading && tickets.length > 0" class="table table-bordered table-condensed tickets">
    <thead>
      <tr>
        <th>№</th>
        <th>Тема</th>
        <!--<?php if (isset($showCategories) and $showCategories): ?><th>Категория</th><?php endif ?>
        <?php if ($showDate): ?><th>Дата</th><?php endif ?>
        <?php if ($showCompanyName): ?><th>Компания</th><?php endif ?>
        <?php if ($showUserName): ?><th>Пользователь</th><?php endif ?>

        <?php if ($showDeadline): ?><th>Дедлайн</th><?php endif ?>
        <?php if (!isset($dontShowApply)): ?>
          <th>В работе</th>
        <?php endif ?>
        <th> </th>-->
      </tr>
    </thead>
    <tbody>
      <tr ng-repeat="ticket in tickets">
        <td>{{ticket.id}}</td>
        <td>{{ticket.name}}</td>
      </tr>
    </tbody>
  </table>
  <div class="alert alert-info" ng-show="!ticketsLoading && tickets.length === 0">
    Нет тикетов.
  </div>
</section>

<script type="text/coffeescript">
  URL =
    getCounters: '<?php echo url_for('ticketsApi/getCounters') ?>'
    getTickets: '<?php echo url_for('ticketsApi/getTickets') ?>'

  app = angular.module 'helpdesk', ['ngStorage', 'ui.select']

  app.config ['uiSelectConfig', (uiSelectConfig) ->
    uiSelectConfig.theme = 'select2'
  ]

  app.controller 'TicketsPageController', [
    '$scope', '$http', '$timeout', '$filter', '$q', '$sessionStorage'
    ($scope, $http, $timeout, $filter, $q, $sessionStorage) ->
      $scope.filterSelects =
        categories: [{id: null, name: 'Без категории'}].concat(
          <?php echo json_encode(Doctrine_Query::create()
            ->from('Category c')
            ->select('c.id, c.name')
            // ->leftJoin('c.RefUserCategory ref')
            // ->addWhere('ref.user_id = ?', $sf_user->getGuardUser()->getId())
            ->addOrderBy('c.name')
            ->execute([], Doctrine_Core::HYDRATE_ARRAY)
          ) ?>
        )

        companies: [{id: null, name: 'Без компании'}].concat(
          <?php echo json_encode(Doctrine_Query::create()
            ->from('sfGuardGroup g')
            ->select('g.id, g.name')
            ->addOrderBy('g.name')
            ->execute([], Doctrine_Core::HYDRATE_ARRAY)
          ) ?>
        )

        responsibles: [].concat(
          <?php echo json_encode(Doctrine_Query::create()
            ->from('sfGuardUser u')
            ->select('u.id, u.first_name, u.last_name, u.username')
            ->addWhere('u.type = ?', 'it-admin')
            ->addOrderBy('u.first_name, u.last_name')
            ->execute([], Doctrine_Core::HYDRATE_ARRAY)
          ) ?>
        )

      $scope.filter = $sessionStorage.$default
        collapsed: true
        tab: null
        closed: false
        category_id: []
        company_id: []
        responsible_id: []
        without_responsibles: false

      delete $scope.filter.$default
      delete $scope.filter.$reset

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

  angular.element(document).ready -> angular.bootstrap document, ['helpdesk']
</script>
