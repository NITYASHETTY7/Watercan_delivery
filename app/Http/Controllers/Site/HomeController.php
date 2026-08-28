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

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Blog;

class HomeController extends Controller
{
    public function index()
    {
        $metas = (object)[
            'title' => 'Online Water Delivery & Booking Service | Book My Water',
            'description' => 'Easily order water cans online with Book My Water. We offer quick, reliable, and affordable water delivery services. Choose your quantity, pay online, and get pure and safe drinking water delivered right to your doorstep.',
            'keywords' => 'water can delivery platform, online water delivery, water can orders, delivery management system, subscription-based water delivery, customer dashboard, driver app, real-time delivery tracking, order management, zone management, branch management, delivery scheduling, payment integration, logistics integration, stock management, delivery performance reports, digital intermediary platform, water delivery app, delivery tracking system, recurring water orders'
        ];
        $app_settings = getSetting(['app_core', 'social_link', 'app_info']);
        return view('site.home.index', compact('metas', 'app_settings'));
    }
}
