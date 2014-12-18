<?php

/**
 * sfGuardUser
 *
 * This class has been auto-generated by the Doctrine ORM Framework
 *
 * @package    helpdesk
 * @subpackage model
 * @author     Anatoly Pashin
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class sfGuardUser extends PluginsfGuardUser
{
  public function getPreparedQueriesForTickets()
  {
    $return = array (
      'created_by_me' => Doctrine_Core::getTable('Ticket')->createQuery('a, a.Comments b, a.Creator c')
        ->where('a.created_by = ?', $this->getId())
        ->orderBy('a.created_at desc')
    );

    if (true || sfContext::getInstance()->getUser()->getGuardUser()->getGroups()->getFirst()->getIsExecutor()) {
      $query = "
        SELECT `s3`.`id` AS `id`
        FROM `sf_guard_user` `s`
        LEFT JOIN `ref_company_responsible` `r` ON (`s`.`id` = `r`.`user_id`)
        LEFT JOIN `sf_guard_group` `s2` ON `s2`.`id` = `r`.`group_id`
        LEFT JOIN `sf_guard_user_group` `s4` ON (`s2`.`id` = `s4`.`group_id`)
        LEFT JOIN `sf_guard_user` `s3` ON `s3`.`id` = `s4`.`user_id`
        WHERE (`s`.`id` = " . $this->getId() . ")
        ORDER BY id
      ";
      $users = Doctrine_Manager::connection()
        ->execute($query)
        ->fetchAll(PDO::FETCH_COLUMN)
      ;

      $return['assigned_to_me'] = Doctrine_Query::create()
        ->from('Ticket a, a.Comments b, a.Creator c')
        ->leftJoin('a.Category')
        ->andWhere('a.id in (select ticket_id from ref_ticket_responsible where user_id = ?)', $this->getId())
        ->orderBy('a.created_at desc')
      ;

      $seesCategories = Doctrine_Query::create()
        ->from('RefUserCategory ref')
        ->select('ref.category_id')
        ->addWhere('ref.user_id = ?', $this->getId())
        ->execute([], Doctrine_Core::HYDRATE_SINGLE_SCALAR)
      ;

      $return['auto_assigned_to_me'] = Doctrine_Query::create()
        ->from('Ticket a, a.Comments b, a.Creator c')
        ->leftJoin('a.Category')
        ->andWhereIn('a.created_by', $users)
        ->andWhereIn('a.category_id', $seesCategories)
        ->orderBy('a.created_at desc')
      ;
    }

    return $return;
  }

  public function getTicketsOpened()
  {
    $prepared = $this->getPreparedQueriesForTickets();
    $return = [];
    foreach ($prepared as $name => $query) {
      $return[ $name ] = $query
        ->andWhere('a.isClosed = ?', false)
        ->execute()
      ;
    }

    return $return;
  }

  public function getTicketsClosed()
  {
    $prepared = $this->getPreparedQueriesForTickets();
    $return = [];
    foreach ($prepared as $name => $query) {
      $return[ $name ] = $query
        ->andWhere('a.isClosed = ?', true)
        ->execute()
      ;
    }

    return $return;
  }

  public function getTIcketsAll()
  {
    $prepared = $this->getPreparedQueriesForTickets();
    $return = [];
    foreach ($prepared as $name => $query) {
      $return[ $name ] = $query->execute();
    }

    return $return;
  }

  public function getFullName() {
    return implode(' ', [$this->getFirstName(), $this->getLastName()]);
  }

}
