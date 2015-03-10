app = angular.module 'helpdesk', ['ngStorage', 'ui.select']

app.directive 'RepeatedEveryDays', ($compile) ->
  return {
    restrict: 'C'
    link: ($scope, element) ->
      $scope.days = 1
      $scope.repeats = +element.val()
      element.attr 'type', 'hidden'

      template = angular.element """
        <input type="number" min="0" step="1" ng-model="repeats">

        <select ng-model="days">
          <option value="1">дней</option>
          <option value="7">недель</option>
          <option value="30">месяцев</option>
        </select>'
      """

      $compile(template) $scope, (cloned) ->
        cloned.insertAfter element

      watcher = ->
        repeats = +$scope.repeats || 0
        days = +$scope.days || 0
        val = repeats * days

        element.val val

      $scope.$watch 'days', watcher
      $scope.$watch 'repeats', watcher

  }

angular.element(document).ready -> angular.bootstrap document, ['helpdesk']
