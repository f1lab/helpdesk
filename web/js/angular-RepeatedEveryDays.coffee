app = angular.module 'helpdesk', []

app.directive 'RepeatedEveryDays', ($compile) ->
  template = angular.element """
    <input type="number" min="0" step="1" ng-model="repeats">

    <select ng-model="days">
      <option value="1">дней</option>
      <option value="7">недель</option>
      <option value="30">месяцев</option>
    </select>'
  """

  return {
    restrict: 'C'
    scope:
      days: '=?'
      repeats: '=?'
    link: ($scope, element) ->
      $scope.days = 1
      $scope.repeats = +element.val()
      element.attr 'type', 'hidden'

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

try angular.element(document).ready -> angular.bootstrap document, ['helpdesk']
