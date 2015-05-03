app = angular.module 'helpdesk', ['mgcrea.ngStrap']

app.controller 'TicketShowPageController', [
  '$scope', '$http', '$timeout', '$filter', '$q', '$modal'
  ($scope, $http, $timeout, $filter, $q, $modal) ->
    canceler = null

    $scope.closeAsDup = (ticketId) ->
      $scope.ticketId = ticketId

      modalOptions =
        scope: $scope
        template: '/close-as-dup.html'
        show: true
      modal = $modal modalOptions

      $scope.tickets = []

      if canceler?
        canceler.resolve()

      $scope.error = false
      canceler = $q.defer()
      get = $http.get API.getTicketsList, {
        timeout: canceler.promise
        params:
          of: $scope.ticketId
      }

      get.success (tickets) ->
        $scope.error = false
        $scope.tickets = tickets

      get.error (data, statusCode) ->
        if statusCode > 0
          $scope.error = true

    $scope.confirmClose = (parentId) ->
      return if not parentId > 0

      console.log parentId

      get = $http.get API.closeAsDuplicate, {
        params:
          id: $scope.ticketId
          parent_id: parentId
      }

      get.success -> window.location.reload()
      get.error -> $scope.error = true

    $scope.iAmNotResponsibleForThis = (ticketId) ->
      $scope.ticketId = ticketId
      modalOptions =
        scope: $scope
        template: '/i-am-not-responsible.html'
        show: true
      modal = $modal modalOptions

    $scope.confirmDecline = (reason) ->
      return if !reason?

      post = $http.post API.iAmNotResponsibleForThis,
        ticketId: $scope.ticketId
        reason: reason
      post.finally ->
        document.location.reload()
]

angular.element(document).ready -> angular.bootstrap document, ['helpdesk']
