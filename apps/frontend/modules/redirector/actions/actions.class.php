<?php

/**
 * fuck actions.
 *
 * @package    helpdesk
 * @subpackage fuck
 * @author     Anatoly Pashin
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class redirectorActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->redirect('homepage', 301);
  }

  public function executeError404(sfWebRequest $request)
  {

  }
}
