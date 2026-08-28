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

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Traits\CanSendMail;
use App\Traits\HasResponse;
use App\Traits\CanManageFiles;
use App\Traits\ControlOrder;
use App\Traits\ImageIntervation;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;
    use CanManageFiles;
    use CanSendMail;
    use HasResponse;
}
