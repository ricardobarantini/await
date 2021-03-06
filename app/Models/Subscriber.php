<?php

namespace App\Models;

use App\Utils\MoveElement;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $referrer
 * @property int $modifier
 */
class Subscriber extends Model
{
    protected $table = 'subscribers';

    public function getPosition(): int
    {
        $subscribers = Subscriber::all()->sortBy('created_at');

        foreach ($subscribers as $subscriber) {
            $subscriber->modifier = $subscribers
                ->where('referrer_id', $subscriber->id)
                ->count();
        }

        $subscribers = $subscribers->toArray();

        foreach ($subscribers as $key => $subscriber) {
            MoveElement::moveElement($subscribers, $key, $key - $subscriber['modifier']);
        }

        return array_key_first(
                collect($subscribers)
                    ->where('id', $this->id)
                    ->toArray()
            ) + 1;
    }
}
