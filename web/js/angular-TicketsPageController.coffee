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

    $scope.filterSelects = selectsOptions

    $scope.filter = $sessionStorage.$default
      name: ''
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

      if $scope.tickets.length > 0
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
