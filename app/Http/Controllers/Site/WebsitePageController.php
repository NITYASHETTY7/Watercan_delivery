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
use App\Models\WebsitePage;

class WebsitePageController extends Controller
{
    public function page($slug = null)
    {
        if ($slug != null) {
            $page = WebsitePage::where('slug', '=', $slug)->whereStatus(1)->first();
            if (!$page) {
                abort(404);
            }
        } else {
            $page = null;
        }
        
        $app_settings = getSetting(['app_core','social_link','app_info']);
        $pages = WebsitePage::get();
        return view('site.page.index', compact('page', 'app_settings', 'pages'));
    }
}
