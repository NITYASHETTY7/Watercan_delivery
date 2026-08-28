<?php

namespace App\Traits;

/**
 * Trait LogsActivity
 *
 * Automatically logs user activities for model events such as creation, update, and deletion.
 * To use this trait, simply include it in the model where you want to log activities.
 *
 * Example:
 * ```php
 * use App\Traits\LogsActivity;
 *
 * class MyModel extends Model
 * {
 *     use LogsActivity;
 * }
 * ```
 */
trait LogsActivity
{
    /**
     * Boot the LogsActivity trait.
     *
     * This method is automatically called when the model is booted. It attaches event listeners
     * to the `created`, `updated`, and `deleted` events of the model to log user activity.
     *
     * @return void
     */
    public static function bootLogsActivity()
    {
        // Define the model events to be logged and their corresponding action keys
        if(auth()->user() ){
            $events = [
                'created' => 'created',
                'updated' => 'updated',
                'deleted' => 'deleted',
            ];  
        }
        // Exclude specific routes from logging activities

        if(auth()->user() ){
        // Attach event listeners dynamically
            foreach ($events as $event => $action) {
                static::$event(function ($record) use ($action) {
                    // Log activity for the specified event
                    logActivityData($record, $action);
                });
            }
        }
    }
}
