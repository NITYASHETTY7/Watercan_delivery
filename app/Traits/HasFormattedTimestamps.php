<?php
/**
 *
 * @category ZStarter
 *
 * @ref     Book My Water product
 * @author  <Book My Water info@bookmywater.come>
 * @license <https://watercane-dev.dze-labs.in Book My Water>
 * @version <zStarter: 202402-V2.0>
 * @link    <https://watercane-dev.dze-labs.in>
 */

namespace App\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Auth;

trait HasFormattedTimestamps
{
    public function formattedCreatedAt(): Attribute
    {
        return Attribute::make(
            get: function () {
                $format = getSetting('date_format') ?? 'jS M, Y H:i A';
                $timezone = auth()->user()->timezone ?? 'UTC';
                $createdAt = $this->created_at;

                if ($createdAt) {
                    if (Auth::check() && auth()->user()->timezone) {
                        $createdAt = $createdAt->setTimezone($timezone);
                    }

                    return $createdAt->format($format);
                }

                return null;
            }
        );
    }

    public function formattedUpdatedAt(): Attribute
    {
        return Attribute::make(
            get: function () {
                $format = getSetting('date_format') ?? 'jS M, Y H:i A';
                $timezone = auth()->user()->timezone ?? 'UTC';
                $updatedAt = $this->updated_at;

                if ($updatedAt) {
                    if (Auth::check() && auth()->user()->timezone) {
                        $updatedAt = $updatedAt->setTimezone($timezone);
                    }

                    return $updatedAt->format($format);
                }

                return 'N/A';
            }
        );
    }

}