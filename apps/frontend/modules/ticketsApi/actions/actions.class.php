<?php

/**
 * ticketsApi actions.
 *
 * @package    helpdesk
 * @subpackage ticketsApi
 * @author     Anatoly Pashin
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ticketsApiActions extends sfActions
{
  static protected $filter = null;

  static private function fillFilter($request)
  {
    self::$filter = json_decode(urldecode($request->getParameter('filter', '{}')), true);
    // var_dump(self::$filter);die;
  }

  static private function returnJson($data)
  {
    die(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
  }

  static private function addFilterParametersToQuery($request, $query)
  {
    if (self::$filter === null) {
      self::fillFilter($request);
    }

    $query
      ->addWhere('t.isClosed = ?', self::$filter['closed'])
      ->andWhereIn('t.category_id', self::$filter['category_id'])
      ->andWhereIn('t.company_id', self::$filter['company_id'])
    ;

    return $query;
  }

  public function executeGetCounters(sfWebRequest $request)
  {
    $queries = $this->getUser()->getGuardUser()->getPreparedQueriesForTickets();
    $counters = array_map(function($query) use ($request) {
      return self::addFilterParametersToQuery($request, $query)->count();
    }, $queries);

    self::returnJson($counters);
  }

  public function executeGetTickets(sfWebRequest $request)
  {
    $this->fillFilter($request);
    $tab = self::$filter['tab'];
    $this->forward404Unless($tab);

    $queries = $this->getUser()->getGuardUser()->getPreparedQueriesForTickets();
    $query = $queries[ $tab ];
    $this->forward404Unless($query);

    self::addFilterParametersToQuery($request, $query);

    $tickets = $query->execute([], Doctrine_Core::HYDRATE_ARRAY);
    self::returnJson($tickets);
  }
}
