app = angular.module 'helpdesk', ['mgcrea.ngStrap']

app.controller 'TicketShowPageController', [
  '$scope', '$http', '$timeout', '$filter', '$q', '$modal'
  ($scope, $http, $timeout, $filter, $q, $modal) ->
    $scope.closeAsDup = ->
      throw new Error 'Not implemented'

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
