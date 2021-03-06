<?php

/**
 * Category form.
 *
 * @package    helpdesk
 * @subpackage form
 * @author     Anatoly Pashin
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class CategoryForm extends BaseCategoryForm
{
  public function configure()
  {
    unset (
      $this['sees_categories_list']
    );

    $this->getWidgetSchema()
      ->setLabels([
        'name' => 'Название',
      ])
    ;
  }
}
