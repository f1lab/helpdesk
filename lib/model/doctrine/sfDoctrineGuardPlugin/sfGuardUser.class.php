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
    $return = [];

    $return['created-by-me'] = Doctrine_Query::create()
      ->from('Ticket t')
      ->leftJoin('t.ReadedTickets read with read.user_id = ?', $this->getId())
      ->leftJoin('t.ReadedComments read2 with read2.user_id = ?', $this->getId())
      ->leftJoin('t.Category')
      ->leftJoin('t.ToCompany')
      ->leftJoin('t.Comments')
      ->leftJoin('t.CommentsAgain applier with applier.changed_ticket_state_to = ?', 'applied')
      ->leftJoin('applier.Creator')
      ->leftJoin('t.Creator')
      ->leftJoin('t.Responsibles r')
      ->where('t.created_by = ?', $this->getId())
      ->orderBy('t.created_at desc')
    ;

    $return['assigned-to-me'] = Doctrine_Query::create()
      ->from('Ticket t')
      ->leftJoin('t.ReadedTickets read with read.user_id = ?', $this->getId())
      ->leftJoin('t.ReadedComments read2 with read2.user_id = ?', $this->getId())
      ->leftJoin('t.Category')
      ->leftJoin('t.ToCompany')
      ->leftJoin('t.Comments')
      ->leftJoin('t.CommentsAgain applier with applier.changed_ticket_state_to = ?', 'applied')
      ->leftJoin('applier.Creator')
      ->leftJoin('t.Creator')
      ->leftJoin('t.Responsibles r')
      ->andWhere('r.id = ?', $this->getId())
      ->orderBy('t.created_at desc')
    ;

    $return['observed-by-me'] = Doctrine_Query::create()
      ->from('Ticket t')
      ->leftJoin('t.ReadedTickets read with read.user_id = ?', $this->getId())
      ->leftJoin('t.ReadedComments read2 with read2.user_id = ?', $this->getId())
      ->leftJoin('t.Category')
      ->leftJoin('t.ToCompany')
      ->leftJoin('t.Comments')
      ->leftJoin('t.CommentsAgain applier with applier.changed_ticket_state_to = ?', 'applied')
      ->leftJoin('applier.Creator')
      ->leftJoin('t.Creator')
      ->leftJoin('t.Observers o')
      ->leftJoin('t.Responsibles r')
      ->addWhere('o.id = ?', $this->getId())
      ->orderBy('t.created_at desc')
    ;

    $query = "
      SELECT s3.id AS id
      FROM sf_guard_user s
      LEFT JOIN ref_company_responsible r ON (s.id = r.user_id)
      LEFT JOIN sf_guard_group s2 ON s2.id = r.group_id
      LEFT JOIN sf_guard_user_group s4 ON (s2.id = s4.group_id)
      LEFT JOIN sf_guard_user s3 ON s3.id = s4.user_id
      WHERE (s.id = " . $this->getId() . ")
      ORDER BY id
    ";
    $users = Doctrine_Manager::connection()
      ->execute($query)
      ->fetchAll(PDO::FETCH_COLUMN)
    ;

    $seesCategories = Doctrine_Query::create()
      ->from('RefUserCategory ref')
      ->select('ref.category_id')
      ->addWhere('ref.user_id = ?', $this->getId())
      ->execute([], Doctrine_Core::HYDRATE_SINGLE_SCALAR)
    ;

    $return['auto-assigned-to-me'] = Doctrine_Query::create()
      ->from('Ticket t')
      ->leftJoin('t.ReadedTickets read with read.user_id = ?', $this->getId())
      ->leftJoin('t.ReadedComments read2 with read2.user_id = ?', $this->getId())
      ->leftJoin('t.Responsibles r')
      ->leftJoin('t.ToCompany')
      ->leftJoin('t.Comments')
      ->leftJoin('t.CommentsAgain applier with applier.changed_ticket_state_to = ?', 'applied')
      ->leftJoin('applier.Creator')
      ->leftJoin('t.Category')
      ->leftJoin('t.Creator')
      ->andWhereIn('t.created_by', $users)
      ->andWhereIn('t.category_id', $seesCategories)
      ->orderBy('t.created_at desc')
    ;

    return $return;
  }

  public function getTicketsOpened()
  {
    $prepared = $this->getPreparedQueriesForTickets();
    $return = [];
    foreach ($prepared as $name => $query) {
      $return[ $name ] = $query
        ->andWhere('t.isClosed = ?', false)
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
        ->andWhere('t.isClosed = ?', true)
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
