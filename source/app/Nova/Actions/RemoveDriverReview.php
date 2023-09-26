<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RemoveDriverReview extends Action
{
  use InteractsWithQueue, Queueable;

  /**
   * The displayable name of the action.
   *
   * @var string
   */
  public $name = 'Remove driver reviews';

  /**
   * Perform the action on the given models.
   *
   * @param  \Laravel\Nova\Fields\ActionFields  $fields
   * @param  \Illuminate\Support\Collection  $models
   * @return mixed
   */
  public function handle(ActionFields $fields, Collection $models)
  {
    foreach ($models as $model) {
      $model->driver_rating = null;
      $model->driver_comment = null;
      $model->save();
    }

    return Action::message('Reviews are removed successfully!');
  }

  /**
   * Get the fields available on the action.
   *
   * @return array
   */
  public function fields()
  {
    return [];
  }
}