<?php

namespace App\Nova\Actions;

use App\Models\Job;
use App\Models\FulfilledJob;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class MarkFulfilledJob extends Action
{
  use InteractsWithQueue, Queueable;

  /**
   * The displayable name of the action.
   *
   * @var string
   */
  public $name = 'Mark as fulfilled';

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
      $fulfilledJob = $model->fulfilledJob;
      $fulfilledJob->fulfilled = true;
      $fulfilledJob->save();
    }

    return Action::message('Marked as fulfilled successfully!');
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