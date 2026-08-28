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

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Log;

//// General System Helpers
if (!function_exists('getSetting')) {
    function getSetting($key, $setting = null)
    {
        if ($setting) {
            $value = $setting->where('key', $key)->first()->value ?? '';
            return $value;
        }
        if (is_array($key)) {
            $records = App\Models\Setting::select('group', 'key', 'value')->whereIn('group', $key)->get();
            $settings = [];
            foreach ($records as $key => $record) {
                $settings[$record->key] = $record->value;
            }
        } else {
            $settings = App\Models\Setting::where('key', $key)->first()->value ?? '';
        }
        return $settings;
    }
}

if (!function_exists('UserRole')) {
    function UserRole($id)
    {
        $user = App\Models\User::find($id);

        if ($user && $user->roles && isset($user->roles[0])) {
            return $user->roles[0];
        }

        return null;
    }
}


if (!function_exists('getAdminId')) {
    function getAdminId()
    {
        return App\Models\User::whereRoleIs(['Admin'])->value('id');
    }
}


if (!function_exists('getSeoData')) {
    function getSeoData($code)
    {
        return App\Models\SeoTag::where('code', $code)->first() ?? '';
    }
}

if (!function_exists('getUserCountByRole')) {
    function getUserCountByRole($role)
    {
        return App\Models\User::whereRoleIs([$role])->count() ?? '';
    }
}

if (!function_exists('getOtherUsersCountByRole')) {
    function getOtherUsersCountByRole($role)
    {
        return \App\Models\User::where('id', '!=', auth()->id())
            ->whereRoleIs([$role])
            ->count();
    }
}


if (!function_exists('getBackendLogo')) {
    function getBackendLogo($img_name)
    {
        return asset($img_name);
    }
}

if (!function_exists('AuthRole')) {
    function AuthRole()
    {
        return ucWords(auth()->user()->roles[0]->name ?? '');
    }
}

if (!function_exists('unlinkFile')) {
    function unlinkFile($filepath, $filename)
    {
        if ($filename != null) {
            $file = $filepath . '/' . $filename;
            if (file_exists($file)) {
                unlink($file);
            }
        }
    }
}

if (!function_exists('activeClassIfRoutes')) {
    function activeClassIfRoutes($routes, $output = 'active', $fallback = '')
    {
        if (in_array(Route::currentRouteName(), $routes)) {
            return $output;
        } else {
            return $fallback;
        }
    }
}
if (!function_exists('activeClassIfRoute')) {
    function activeClassIfRoute($route, $output = 'active', $fallback = '')
    {
        if (Route::currentRouteName() == $route) {
            return $output;
        } else {
            return $fallback;
        }
    }
}

if (!function_exists('format_price')) {
    function format_price($price)
    {
        // Convert $price to a float if it's not already numeric
        $price = is_numeric($price) ? floatval($price) : 0.0; // Default to 0.0 if $price is not numeric

        // Fetch settings once to improve performance
        $decimal_separator_setting = App\Models\Setting::where('key', 'decimal_separator')->first()->value;
        $decimal_count_setting = App\Models\Setting::where('key', 'no_of_decimal')->first()->value;
        $currency_position_setting = App\Models\Setting::where('key', 'currency_position')->first()->value;

        // Determine decimal and thousand separators based on the setting
        $decimal_separator = $decimal_separator_setting == 1 ? '.' : ',';
        $thousands_separator = $decimal_separator_setting == 1 ? ',' : '.';

        // Format price with the appropriate separators
        $formatted_price = number_format($price, $decimal_count_setting, $decimal_separator, $thousands_separator);

        // Determine currency position based on the setting
        $currency_symbol = getSetting('app_currency');
        if ($currency_position_setting == 'before') {
            return $currency_symbol . '' . $formatted_price;
        } else {
            return $formatted_price . ' ' . $currency_symbol;
        }
    }
}

if (!function_exists('getPublishStatus')) {
    function getPublishStatus($id = -1)
    {
        if ($id == -1) {
            return [
                ['id' => 0, 'name' => "Unpublished", 'color' => "danger"],
                ['id' => 1, 'name' => "Published", 'color' => "success"],
            ];
        } else {
            foreach (getPublishStatus() as $row) {
                if ($id == $row['id']) {
                    return $row;
                }
            }
            return ['id' => 0, 'name' => ''];
        }
    }
}


if (!function_exists('getHelp')) {
    function getHelp($message)
    {
        return '<i class="ik ik-help-circle text-muted" title="' . $message . '"></i>';
    }
}

if (!function_exists('getCategoriesByCode')) {
    function getCategoriesByCode($code, $parent = null)
    {
        $chk = App\Models\CategoryType::whereCode($code)->first();
        if ($chk) {
            if ($parent != null) {
                return App\Models\Category::select(
                    'id',
                    'name',
                    'category_type_id',
                    'parent_id',
                    'icon'
                )->whereCategoryTypeId($chk->id)
                    ->where('parent_id', $parent)->orderBy('name', 'ASC')->get();
            }
            return App\Models\Category::select('id', 'name', 'slug', 'category_type_id', 'parent_id', 'icon')
                ->whereCategoryTypeId($chk->id)
                ->where(function ($query) {
                    $query->whereNull('parent_id')
                        ->orWhere('parent_id', 0);
                })
                ->latest()
                ->get();
        }
        return [];
    }
}

if (!function_exists('getSlidersByCode')) {
    function getSlidersByCode($codes)
    {
        $response = [];
        if (is_array($codes)) {
            foreach ($codes as $key => $code) {
                $chk = App\Models\SliderType::where('is_published', 1)
                    ->whereCode($code)
                    ->select('id', 'title', 'code', 'short_text', 'is_published')
                    ->first();


                if ($chk) {
                    $childrens = App\Models\Slider::where('status', 1)->select(
                        'id',
                        'title',
                        'slider_type_id',
                        'image',
                        'description',
                        'status'
                    )->whereSliderTypeId($chk->id)->orderBy('title', 'ASC')->get();


                    $response[$code]['slider'] = $chk;
                    $response[$code]['childrens'] = $childrens;
                }
            }
            return $response;
        } else {
            $chk = App\Models\SliderType::whereCode($codes)->first();
            return App\Models\Slider::select('id', 'title', 'slider_type_id', 'image', 'description')->whereSliderTypeId($chk->id)->latest()->get();
        }
    }
}

if (!function_exists('getParagraphContent')) {
    function getParagraphContent($group)
    {
        if (is_array($group)) {
            $records = App\Models\ParagraphContent::select('code', 'value', 'group')->whereIn('group', $group)->get();
            $content = [];
            foreach ($records as $key => $record) {
                $content[$record->code] = $record->value;
            }
        } else {
            $content = App\Models\ParagraphContent::select('code', 'value')->where('code', $group)->first();
        }
        return $content;
    }
}

if (!function_exists('pushOnSiteNotification')) {
    function pushOnSiteNotification($data)
    {
        // Checking User Onsite Notification Condition
        $is_allowed = 1;
        $userRole = UserRole($data['user_id']);
        $userRole = $userRole ? $userRole['name'] : null;

        if (in_array($userRole, ['member', 'user'])) {
            $user = App\Models\User::where('id', $data['user_id'])
                ->select('id', 'setting_payload')
                ->first();
            if ($user && isset($user->setting_payload['onsite_notification_alert'])) {
                if ($user->setting_payload['onsite_notification_alert'] == 0) {
                    $is_allowed = 0;
                }
            }
        }
        // End Checking User Onsite Notification Condition

        // Check if notification enable
        if ($is_allowed) {
            if (getSetting('notification') == 1) {
                $notification = App\Models\Notification::create(
                    [
                        'user_id' => $data['user_id'],
                        'title' => $data['title'],
                        'link' => $data['link'],
                        'notification' => $data['notification'],
                        'is_read' => 0, // unseen
                    ]
                );
                return $notification;
            }
        }
    }
}

if (!function_exists('DynamicMailTemplateFormatter')) {
    function DynamicMailTemplateFormatter($body, $variable_names, $var_list)
    {
        // Make it Foreachable
        $variable_names = explode(', ', $variable_names);
        $i = 1;
        $data = "";
        foreach ($variable_names as $item) {
            if ($i == 1) {
                if (array_key_exists($item, $var_list)) {
                    $data =  str_replace($item, $var_list[$item], $body);
                    $i += 1;
                }
            } else {
                if (array_key_exists($item, $var_list)) {
                    $data =  str_replace($item, $var_list[$item], $data);
                }
            }
        }
        return $data;
    }
}

if (!function_exists('getGreetingBasedOnTime')) {
    function getGreetingBasedOnTime()
    {
        $utc_time = auth()->user()->timezone;
        $timezone = $utc_time != null && $utc_time != 0 ? $utc_time : 'Asia/Kolkata';
        $dat = new DateTime('now', new DateTimeZone($timezone));
        $hour = $dat->format('H');
        if ($hour >= 20) {
            $greetings =  __('ui.good_night');
        } elseif ($hour > 17) {
            $greetings =  __('ui.good_evening');
        } elseif ($hour > 11) {
            $greetings = __('ui.good_afternoon');
        } elseif ($hour < 12) {
            $greetings = __('ui.good_morning');
        }
        return $greetings;
    }
}

if (!function_exists('getOrderStatusCount')) {
    function getOrderStatusCount($status)
    {
        return $count = App\Models\Order::where('status', $status)->count();
    }
}


if (!function_exists('getLeadStatusCount')) {
    function getLeadStatusCount($status)
    {
        return $count = App\Models\Lead::where('status', $status)->count();
    }
}
if (!function_exists('getSupportTicketStatusCount')) {
    function getSupportTicketStatusCount($status)
    {
        return $count = App\Models\SupportTicket::where('status', $status)->count();
    }
}


if (!function_exists('getEnquiryStatusCount')) {
    function getEnquiryStatusCount($status)
    {
        return $count = App\Models\WebsiteEnquiry::where('status', $status)->count();
    }
}

if (!function_exists('getSliderData')) {
    function getSliderData($code)
    {
        return $sliderType = App\Models\SliderType::where('code', $code)->with('sliders')->first();
    }
}



function mime2ext($mime)
{
    $all_mimes = '{"png":["image\/png","image\/x-png"],"bmp":["image\/bmp","image\/x-bmp","image\/x-bitmap","image\/x-xbitmap","image\/x-win-bitmap","image\/x-windows-bmp","image\/ms-bmp","image\/x-ms-bmp","application\/bmp","application\/x-bmp","application\/x-win-bitmap"],"gif":["image\/gif"],"jpeg":["image\/jpeg","image\/pjpeg"],"xspf":["application\/xspf+xml"],"vlc":["application\/videolan"],"wmv":["video\/x-ms-wmv","video\/x-ms-asf"],"au":["audio\/x-au"],"ac3":["audio\/ac3"],"flac":["audio\/x-flac"],"ogg":["audio\/ogg","video\/ogg","application\/ogg"],"kmz":["application\/vnd.google-earth.kmz"],"kml":["application\/vnd.google-earth.kml+xml"],"rtx":["text\/richtext"],"rtf":["text\/rtf"],"jar":["application\/java-archive","application\/x-java-application","application\/x-jar"],"zip":["application\/x-zip","application\/zip","application\/x-zip-compressed","application\/s-compressed","multipart\/x-zip"],"7zip":["application\/x-compressed"],"xml":["application\/xml","text\/xml"],"svg":["image\/svg+xml"],"3g2":["video\/3gpp2"],"3gp":["video\/3gp","video\/3gpp"],"mp4":["video\/mp4"],"m4a":["audio\/x-m4a"],"f4v":["video\/x-f4v"],"flv":["video\/x-flv"],"webm":["video\/webm"],"aac":["audio\/x-acc"],"m4u":["application\/vnd.mpegurl"],"pdf":["application\/pdf","application\/octet-stream"],"pptx":["application\/vnd.openxmlformats-officedocument.presentationml.presentation"],"ppt":["application\/powerpoint","application\/vnd.ms-powerpoint","application\/vnd.ms-office","application\/msword"],"docx":["application\/vnd.openxmlformats-officedocument.wordprocessingml.document"],"xlsx":["application\/vnd.openxmlformats-officedocument.spreadsheetml.sheet","application\/vnd.ms-excel"],"xl":["application\/excel"],"xls":["application\/msexcel","application\/x-msexcel","application\/x-ms-excel","application\/x-excel","application\/x-dos_ms_excel","application\/xls","application\/x-xls"],"xsl":["text\/xsl"],"mpeg":["video\/mpeg"],"mov":["video\/quicktime"],"avi":["video\/x-msvideo","video\/msvideo","video\/avi","application\/x-troff-msvideo"],"movie":["video\/x-sgi-movie"],"log":["text\/x-log"],"txt":["text\/plain"],"css":["text\/css"],"html":["text\/html"],"wav":["audio\/x-wav","audio\/wave","audio\/wav"],"xhtml":["application\/xhtml+xml"],"tar":["application\/x-tar"],"tgz":["application\/x-gzip-compressed"],"psd":["application\/x-photoshop","image\/vnd.adobe.photoshop"],"exe":["application\/x-msdownload"],"js":["application\/x-javascript"],"mp3":["audio\/mpeg","audio\/mpg","audio\/mpeg3","audio\/mp3"],"rar":["application\/x-rar","application\/rar","application\/x-rar-compressed"],"gzip":["application\/x-gzip"],"hqx":["application\/mac-binhex40","application\/mac-binhex","application\/x-binhex40","application\/x-mac-binhex40"],"cpt":["application\/mac-compactpro"],"bin":["application\/macbinary","application\/mac-binary","application\/x-binary","application\/x-macbinary"],"oda":["application\/oda"],"ai":["application\/postscript"],"smil":["application\/smil"],"mif":["application\/vnd.mif"],"wbxml":["application\/wbxml"],"wmlc":["application\/wmlc"],"dcr":["application\/x-director"],"dvi":["application\/x-dvi"],"gtar":["application\/x-gtar"],"php":["application\/x-httpd-php","application\/php","application\/x-php","text\/php","text\/x-php","application\/x-httpd-php-source"],"swf":["application\/x-shockwave-flash"],"sit":["application\/x-stuffit"],"z":["application\/x-compress"],"mid":["audio\/midi"],"aif":["audio\/x-aiff","audio\/aiff"],"ram":["audio\/x-pn-realaudio"],"rpm":["audio\/x-pn-realaudio-plugin"],"ra":["audio\/x-realaudio"],"rv":["video\/vnd.rn-realvideo"],"jp2":["image\/jp2","video\/mj2","image\/jpx","image\/jpm"],"tiff":["image\/tiff"],"eml":["message\/rfc822"],"pem":["application\/x-x509-user-cert","application\/x-pem-file"],"p10":["application\/x-pkcs10","application\/pkcs10"],"p12":["application\/x-pkcs12"],"p7a":["application\/x-pkcs7-signature"],"p7c":["application\/pkcs7-mime","application\/x-pkcs7-mime"],"p7r":["application\/x-pkcs7-certreqresp"],"p7s":["application\/pkcs7-signature"],"crt":["application\/x-x509-ca-cert","application\/pkix-cert"],"crl":["application\/pkix-crl","application\/pkcs-crl"],"pgp":["application\/pgp"],"gpg":["application\/gpg-keys"],"rsa":["application\/x-pkcs7"],"ics":["text\/calendar"],"zsh":["text\/x-scriptzsh"],"cdr":["application\/cdr","application\/coreldraw","application\/x-cdr","application\/x-coreldraw","image\/cdr","image\/x-cdr","zz-application\/zz-winassoc-cdr"],"wma":["audio\/x-ms-wma"],"vcf":["text\/x-vcard"],"srt":["text\/srt"],"vtt":["text\/vtt"],"ico":["image\/x-icon","image\/x-ico","image\/vnd.microsoft.icon"],"csv":["text\/x-comma-separated-values","text\/comma-separated-values","application\/vnd.msexcel"],"json":["application\/json","text\/json"]}';
    $all_mimes = json_decode($all_mimes, true);
    foreach ($all_mimes as $key => $value)
        if (array_search($mime, $value) !== false) return $key;
    return false;
}


if (!function_exists('secureToken')) {
    function secureToken($id, $mode = 'encrypt')
    {
        if (env('SECURE_ENDPOINT') == 0) {
            return $id;
        }
        if ($mode == 'encrypt') {
            return encrypt($id);
        } else {
            return decrypt($id);
        }
    }
}

if (!function_exists('tableLimits')) {
    function tableLimits()
    {
        return [
            ['option' => 10],
            ['option' => 50],
            ['option' => 100],
            ['option' => 500],
            ['option' => 1000],
            ['option' => 2000]
        ];
    }
}

if (!function_exists('logUserActivity')) {
    function logUserActivity($activity)
    {
        return App\Models\UserLog::create(
            [
                'user_id' => $activity['user_id'],
                'ip_address' => $activity['ip_address'],
                'activity' => $activity['model'],
                'activity_id' => $activity['model_id'] ?? null,
                'name' => $activity['incident'],
                'version' => $activity['version'],
                'platform' => $activity['platform'],
            ]
        );
    }
}

if (!function_exists('getRequestVersion')) {
    function getRequestVersion($request)
    {
        return 'Chrome v11';
    }
}

if (!function_exists('getRequestPlatform')) {
    function getRequestPlatform($request)
    {
        return 'Mac OS X';
    }
}

function insertCustomMarkup($string, $customMarkup = [])
{
    // Default markup patterns (URL, bold, italic)
    $defaultMarkup = [
        '/\*\*(.*?)\*\*/' => '<strong>$1</strong>',
        '/``(.*?)``/' => '<em>$1</em>'
    ];

    // URL regex pattern
    $urlPattern = '/(?<!\S)((?:https?|ftp):\/\/\S+?\/?\S+?)(?!\S)/i';

    // Merge default and custom markup patterns
    $markup = array_merge($defaultMarkup, $customMarkup);

    // Apply each markup pattern to the string
    foreach ($markup as $pattern => $replacement) {
        $string = preg_replace($pattern, $replacement, $string);
    }

    // Replace URLs with anchor tags
    $string = preg_replace_callback($urlPattern, function ($matches) {
        $url = $matches[1];
        return "<a target='_blank' href='$url'>$url</a>";
    }, $string);


    return nl2br($string);
}



if (!function_exists('generatedCartSessionId')) {
    function generatedCartSessionId()
    {
        if (!session()->has('cart_session_id') || empty(session()->get('cart_session_id')) || session()->get('cart_session_id') == null) {
            if (auth()->check()) {
                $cart =  App\Models\Cart::where('user_id', auth()->id())->where('session_id', '!=', null)->latest()->first();
                if ($cart && !empty($cart->session_id))
                    session()->put('cart_session_id', $cart->session_id);
                else
                    session()->put('cart_session_id', random_int(1000000000000000, 9999999999999999));
            } else
                session()->put('cart_session_id', random_int(1000000000000000, 9999999999999999));
        }
        return session()->get('cart_session_id');
    }
}

function responseOrRedirect($request, $responseData)
{
    // Check if the request is an AJAX request
    if ($request->ajax()) {
        return response()->json($responseData);
    }
    if (isset($responseData['redirect_route'])) {
        return redirect($responseData['redirect_route'])->with($responseData['status'], $responseData['title'])->withInput($request->all());
    } else {
        return back()->with($responseData['status'], $responseData['title'])->withInput($request->all());
    }
}

function ensureDirectoryExists($directory)
{
    if (!is_dir($directory)) {
        mkdir($directory, 0777, true);
    }
}

function copyFile($sourcePath, $destinationPath, $data = null, $viewsName = null)
{
    // Read the content of the source file
    $fileContent = file_get_contents($sourcePath);
    $source = $data['authRole'];
    $destination = $data['uc_role'];
    $fileContent = str_replace($source, $destination, $fileContent);
    if ($viewsName) {
        $source = '.' . $data['lower_authRole'] . '.';
        $destination = '.' . $data['lower_role'] . '.';
        $fileContent = str_replace($source, $destination, $fileContent);
    }

    // Write the modified content to the destination file
    if (file_put_contents($destinationPath, $fileContent) === false) {
        throw new \Exception('Failed to copy the file.');
    }

    $response['rollbackFiles'] = $destinationPath;

    return $response;
}

function copyFilesInDirectory($sourceDir, $destinationDir, $data, $viewsName)
{
    $iterator = new \FilesystemIterator($sourceDir, \FilesystemIterator::SKIP_DOTS);

    foreach ($iterator as $fileInfo) {
        if ($fileInfo->isFile()) {
            $sourceFilePath = $fileInfo->getPathname();
            $destinationFilePath = $destinationDir . '/' . $fileInfo->getFilename();
            copyFile($sourceFilePath, $destinationFilePath, $data, $viewsName);
        }
    }
    return true;
}

function rollbackGeneratedFiles($rollbackFiles)
{
    foreach ($rollbackFiles as $file) {
        if (File::exists($file)) {
            File::delete($file);
        }
    }
    $rollbackFiles = [];
}

function appendRoute($sourcePaths, $destinationPaths, $data)
{
    $routeFileContents = file_get_contents($sourcePaths['route']);
    $groupName = $data['lower_model'] . 's';
    // Adjust the regex to capture the entire group including nested content
    $startPattern = "/Route::group\s*\(\s*\['prefix'\s*=>\s*'{$groupName}'[^]]*\],\s*function\s*\(\)\s*{/";
    $endPattern = "/}\s*\);/";


    if (preg_match($startPattern, $routeFileContents, $startMatches, PREG_OFFSET_CAPTURE)) {
        $startPos = $startMatches[0][1];
        $remainingContent = substr($routeFileContents, $startPos);

        if (preg_match($endPattern, $remainingContent, $endMatches, PREG_OFFSET_CAPTURE)) {
            $endPos = $endMatches[0][1] + strlen($endMatches[0][0]);
            $routeGroupDefinition = substr($remainingContent, 0, $endPos);

            $destRouteFilePath = $destinationPaths['route'];
            $fileContent = file_get_contents($destRouteFilePath);

            $startMarker = "// Start Clone Compiler";
            $endMarker = "// End Clone Compiler";
            $startIndex = strpos($fileContent, $startMarker);
            $endIndex = strpos($fileContent, $endMarker);

            if ($startIndex !== false && $endIndex !== false) {
                $comment = "\n\n     " . $routeGroupDefinition . "\n\n";
                // Replace a portion of the file content with the comment at the position of the end marker
                $updatedContent = substr_replace($fileContent, $comment, $endIndex, 0);
                // Write the updated content back to the file
                file_put_contents($destRouteFilePath, $updatedContent);
            }
        }
    }
    return true;
}



function getAuthDashboardRoute()
{
    $role = AuthRole(); // Adjust according to your role retrieval method

    switch ($role) {
        case 'Admin':
            $route = route('panel.admin.dashboard.index');
            break;

        case 'User':
            $route = route('panel.user.dashboard.index');
            break;

        default:
            $route = url('/'); // Default or fallback route
            break;
    }
    return $route;
}

if (! function_exists('renderAddress')) {
    function renderAddress($address, $format = "grid")
    {
        if (!is_array($address))
            $address = (array)$address;
        if ($format == 'grid') {
            $html = '<div class="">';
            if (@$address['name'])
                $html .= '<i class="mr-1 ik ik-user"></i> ' . @$address['name'];
            if (@$address['phone'])
                $html .= '<br><i class="mr-1 ik ik-phone"></i> ' . @$address['phone'];
            if (@$address['address_1'])
                $html .= '<br> <i class="mr-1 ik ik-home"></i> ' . @$address['address_1'];
            if (@$address['address_2'])
                $html .= '' . @$address['address_2'];
            if (@$address['city_id']) {
                $html .= '<br>' . getCountryStateCity($address['city_id'], 'city');
            }
            if (@$address['pincode_id']) {
                $html .= '(' . @$address['pincode_id'] . ')';
            }
            if (@$address['state_id']) {
                $html .= '<br>' . getCountryStateCity($address['state_id'], 'state');
            }
            if (@$address['country_id']) {
                $html .= '' . getCountryStateCity($address['country_id'], 'country');
            }
            $html .= '</div>';
        } else if ($format == 'inline') {
            $html = '<div class="">';
            if (@$address['name'])
                $html .= '<i class="ik ik-user"></i> ' . @$address['name'];
            if (@$address['phone'])
                $html .= ' <span>(' . @$address['phone'] . ')</span>';
            if (@$address['address_1'])
                $html .= '<br><i class="ik ik-home"></i> <span class="checkout-address"> ' . @$address['address_1'] . ',</span>';
            if (@$address['address_2'])
                $html .= ' <span>' . @$address['address_2'] . '</span>';
            if (@$address['city_id']) {
                $html .= ' <span>' . getCountryStateCity($address['city_id'], 'city') . '</span>';
            }
            if (@$address['pincode_id']) {
                $html .= ' <span>(' . @$address['pincode_id'] . ')</span>';
            }
            if (@$address['state_id']) {
                $html .= ' <span>' . getCountryStateCity($address['state_id'], 'state') . ',</span> ';
            }
            if (@$address['country_id']) {
                $html .= ' <span>' . getCountryStateCity($address['country_id'], 'country') . '</span>';
            }
            $html .= '</div>';
        }
        return $html;
    }
}

//Get Country , State or City by Id
if (!function_exists('getCountryStateCity')) {
    function getCountryStateCity($id, $type = null)
    {

        if ($type) {
            if ($type == 'country') {
                $country = App\Models\Country::whereId($id)->first();
                $country_name = $country ? $country->name : '';
                return $country_name;
            }
            if ($type == 'state') {
                $state = App\Models\State::whereId($id)->first();
                $state_name = $state ? $state->name : '';
                return $state_name;
            }
            if ($type == 'city') {
                $city = App\Models\City::whereId($id)->first();
                $city_name = $city ? $city->name : '';
                return $city_name;
            }
        }
        return 'N/A';
    }
}

// get load URL
if (!function_exists('getCurrentUrlWithParams')) {
    function getCurrentUrlWithParams()
    {
        return url()->full();
    }
}

if (!function_exists('getRoleData')) {
    function getRoleData($slug)
    {
        foreach (App\Models\Faq::ROLES as $key => $details) {
            if ($slug == $details['slug'])
                return $details;
        }
    }
}
if (!function_exists('getRoleDetails')) {
    function getRoleDetails()
    {
        $data = [];
        foreach (App\Models\Faq::ROLES as $key => $details) {
            $data[] = [
                "id" => $details['id'],
                "label" => $details['label'],
                "color" => $details['color'],
                "description" => $details['description'],
                "slug" => $details['slug'],
            ];
        }

        return $data;
    }
}

if (!function_exists('getRoleLabel')) {
    function getRoleLabel($slug)
    {
        foreach (App\Models\Faq::ROLES as $key => $details) {
            if ($slug == $details['slug'])
                return $details['label'];
        }
    }
}


function logActivityData($record, $actionKey)
{
    $user = auth()->user();
    $userId = $user ? $user->getPrefix() : null;
    $userName = $user ? $user->full_name : 'Unknown';

    $action = ucfirst($actionKey); // Created / Updated / Deleted

    // Determine model type (e.g. Order, Product)
    $modelName = class_basename($record);

    // Try to find a descriptive subject for the record
    $subject = $record->getPrefix()
        ?? $record->txn_no;

    // Make subject readable
    $subjectText = "{$modelName} ({$subject})";

    // Customize wording based on action
    switch ($actionKey) {
        case 'created':
            $incident = "{$action} - New {$subjectText} created by {$userName} ({$userId})";
            break;
        case 'updated':
            $incident = "{$action} - {$subjectText} updated by {$userName} ({$userId})";
            break;
        case 'deleted':
            $incident = "{$action} - {$subjectText} deleted by {$userName} ({$userId})";
            break;
        default:
            $incident = "{$action} - {$subjectText} action by {$userName} ({$userId})";
    }

    // Build log data
    $activity = [
        'user_id'    => $userId,
        'ip_address' => request()->ip(),
        'model'      => get_class($record),
        'model_id'   => $record->id,
        'incident'   => $incident,
        'version'    => getRequestVersion(request()),
        'platform'   => getRequestPlatform(request()),
    ];

    logUserActivity($activity);
}


// get total issues count
function getTotalCount($issuesByType)
{
    $total = 0;
    foreach ($issuesByType as $type => $issues) {
        $total += count($issues); // Add count of issues at this level
    }
    return $total;
}


// Helper function to check for valid naming conventions
function isValidName($name)
{
    return preg_match('/^[a-z0-9_]+$/', $name);
}

function askGpt($prompt_content, $gpt_modal = 'gpt-4')
{
    try {
        if ($prompt_content != null) {
            $prompt = $prompt_content;
            $messages = [
                [
                    'role' => 'system',
                    'content' => $prompt,
                ],
                [
                    'role' => 'user',
                    'content' => '',
                ],
            ];
        }

        $response = Http::withToken(env('CHATGPT_API_KEY'))
            ->timeout(600)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => $gpt_modal,
                'messages' => $messages,
                'max_tokens' => 5000,
                'temperature' => 0.8,
            ])
            ->throw()
            ->json();

        return $response;
    } catch (\Illuminate\Http\Client\RequestException $e) {
        $responseError = $e->response->json()['error'] ?? 'Unknown error occurred';
        $data = [
            'status' => 'error',
            'message' => $responseError
        ];
        return $data;
    }
}

// Media Helper
function getFileType($fileExtension)
{
    $imageExtensions = ["jpg", "jpeg", "png", "gif", "bmp", 'svg'];
    $pptExtensions = ["ppt", "pptx"];
    $pdfExtensions = ["pdf"];
    $docxExtensions = ["doc", "docx",];
    $excelExtensions = ["xls", "xlsx", 'csv'];
    $videoExtensions = ["mp4", "avi", "mkv", "mov"];
    $audioExtensions = ["mp3", "wav", "ogg"];

    if (in_array($fileExtension, $imageExtensions)) {
        return "image";
    } elseif (in_array($fileExtension, $pptExtensions)) {
        return "ppt";
    } elseif (in_array($fileExtension, $pdfExtensions)) {
        return "pdf";
    } elseif (in_array($fileExtension, $docxExtensions)) {
        return "docx";
    } elseif (in_array($fileExtension, $excelExtensions)) {
        return "excel";
    } elseif (in_array($fileExtension, $videoExtensions)) {
        return "video";
    } elseif (in_array($fileExtension, $audioExtensions)) {
        return "audio";
    } else {
        return "image"; // If the extension is not recognized
    }
}
function getFileNameByUrl($path)
{
    return basename($path);
}
function getFilePathByUrl($path)
{
    if (in_array(getFileExtByUrl($path), ['txt'])) {
        $fileType = 'txt';
    } else {
        $fileType = getFileType(getFileExtByUrl($path));
    }
    return route('preview.' . $fileType, ['path' => urlencode($path)]);
}
function getFileExtByUrl($path)
{
    return pathinfo($path, PATHINFO_EXTENSION);
}

/**
 * Generate a transaction code based on user ID and random number.
 *
 * @param int $user_id
 * @return string
 */
function getTxnCode($user_id)
{
    return date('Ymd') . '-' . 'UID' . $user_id . '-' . rand(0, 9999);
}


/**
 * Format a string by replacing underscores with spaces and capitalizing each word.
 *
 * @param string $string
 * @return string
 */
function formatDisplayName($string)
{
    // Replace underscores with spaces
    $string = str_replace('_', ' ', $string);

    // Convert the first letter of each word to uppercase
    return ucwords($string);
}

/**
 * Validate if the email and phone are unique in the users table.
 *
 * @param Request $request
 * @param int|null $id
 * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|null
 */
function validateUniqueEmailAndPhone(Request $request, $id = null)
{
    $existingUserByEmail = App\Models\User::where('email', $request->email)
        ->when($id, function ($query, $id) {
            $query->where('id', '!=', $id); // Ignore this user ID
        })
        ->first();

    if ($existingUserByEmail) {
        return $request->wantsJson()
            ? response()->json(['error' => __('ui.email_must_be_unique')], 500)
            : back()->with('error', __('ui.email_must_be_unique'))->withInput();
    }

    $existingUserByPhone = App\Models\User::where('phone', $request->phone)
        ->when($id, function ($query, $id) {
            $query->where('id', '!=', $id); // Ignore this user ID
        })
        ->first();

    if ($existingUserByPhone) {
        return $request->wantsJson()
            ? response()->json(['error' => __('ui.phone_must_be_unique')], 500)
            : back()->with('error', __('ui.phone_must_be_unique'))->withInput();
    }

    return null; // No issues found
}


/**
 * Format all Blade component self-closing tags in the panel views directory.
 *
 * @return string
 * @throws \Exception
 */
function formatAllBladeComponents()
{
    $directoryPath = base_path() . '/resources/views/panel';
    if (!File::exists($directoryPath)) {
        throw new \Exception("Directory not found: $directoryPath");
    }

    $bladeFiles = File::allFiles($directoryPath);

    foreach ($bladeFiles as $file) {
        if ($file->getExtension() !== 'php') {
            continue;
        }

        $content = File::get($file->getRealPath());

        // Match self-closing tags like <x-... />
        $pattern = '/<x-[^>\/]+(?:\s[^>]*)?\/>/m';

        $formattedContent = preg_replace_callback($pattern, function ($matches) {
            $singleLine = preg_replace('/\s+/', ' ', $matches[0]); // collapse all whitespace
            return trim($singleLine);
        }, $content);

        File::put($file->getRealPath(), $formattedContent);
    }

    return "All Blade component tags formatted successfully.";
}


/**
 * Check if one or multiple request keys exist and are not null.
 *
 * @param string|array $keys
 * @return bool
 */


function checkRequestKey($keys)
{
    if (is_array($keys)) {
        foreach ($keys as $key) {
            if (!request()->has($key) || request()->get($key) == null) {
                return false;
            }
        }
        return true;
    }

    return request()->has($keys) && request()->get($keys) != null;
}


function convertToSlug($text)
{
    // Convert to lowercase
    $text = strtolower($text);

    // Replace spaces with hyphens
    $text = str_replace(' ', '-', $text);

    // Remove all non-word characters except hyphens
    $text = preg_replace('/[^\w-]+/', '', $text);

    return $text;
}

/**
 * Convert specified columns in a collection of models to given data types.
 *
 * @param \Illuminate\Support\Collection|\Illuminate\Database\Eloquent\Collection $data
 * @param array $format Associative array where key is the column name and value is the data type
 *                      Supported types: int, float, double, bool, string, date, datetime, timestamp, array, json, null
 *
 * @return \Illuminate\Support\Collection
 */
function apiResponseConverter($data, array $format)
{
    return $data->map(function ($item) use ($format) {
        foreach ($format as $column => $type) {
            if (!isset($item->$column)) continue;

            $value = $item->$column;

            $item->$column = match ($type) {
                'int'       => (int) $value,
                'float',
                'double'    => (float) $value,
                'bool'      => (bool) $value,
                'string'    => (string) $value,
                'date'      => \Carbon\Carbon::parse($value)->toDateString(),
                'datetime'  => \Carbon\Carbon::parse($value)->toDateTimeString(),
                'timestamp' => \Carbon\Carbon::parse($value)->timestamp,
                'array'     => is_array($value) ? $value : json_decode($value, true),
                'json'      => json_encode($value),
                'null'      => null,
                default     => $value,
            };
        }
        return $item;
    });
}


/**
 * Format a date string into a specified format using Carbon.
 *
 * @param string $dateString The input date string.
 * @param string $format     The desired output format (default: 'Y-m-d').
 *
 * @return string Formatted date string or empty string if invalid.
 */
function formatDate($dateString, $format = 'Y-m-d')
{
    if (empty($dateString)) {
        return '';
    }

    try {
        return Carbon::parse($dateString)->format($format);
    } catch (\Exception $e) {
        return ''; // Or return $dateString if you want to fallback
    }
}

if (!function_exists('getBranchZoneByPincode')) {
    function getBranchZoneByPincode($pincode)
    {
        if (!$pincode) {
            return null;
        }

        $zonePincode = \App\Models\ZonePincode::with(['branch', 'zone'])
            ->where('pincode', $pincode)
            ->first();

        if (!$zonePincode) {
            return null;
        }

        return [
            'branch_id' => $zonePincode->branch_id,
            'branch_name' => $zonePincode->branch?->name,
            'zone_id' => $zonePincode->zone_id,
            'zone_name' => $zonePincode->zone?->name,
            'zone_pincode_id' => $zonePincode->id,
        ];
    }
}

if (!function_exists('getBranchAddressById')) {
    function getBranchAddressById($branchId)
    {
        if (!$branchId) {
            return null;
        }

        $branch = \App\Models\Branch::find($branchId);
        return $branch?->address ?? null;
    }
}


if (!function_exists('getCountryName')) {
    function getCountryName($countryId)
    {
        if (!$countryId) {
            return '';
        }

        return \App\Models\Country::where('id', $countryId)->value('name') ?? '';
    }
}

if (!function_exists('getStateName')) {
    function getStateName($stateId)
    {
        if (!$stateId) {
            return '';
        }

        return \App\Models\State::where('id', $stateId)->value('name') ?? '';
    }
}

if (!function_exists('getCityName')) {
    function getCityName($cityId)
    {
        if (!$cityId) {
            return '';
        }

        return \App\Models\City::where('id', $cityId)->value('name') ?? '';
    }
}



if (!function_exists('formatUserAddress')) {
    function formatUserAddress($userAddress)
    {
        if (!$userAddress || empty($userAddress->details)) {
            return '';
        }

        $details = $userAddress->details;
        $typeName = isset($details['type']) && $details['type'] == 1 ? 'Office' : 'Home';

        $cityName = optional($userAddress->city)->name;
        $stateName = optional($userAddress->state)->name;
        $countryName = optional($userAddress->country)->name;

        // Combine type and first address line together (to avoid extra comma)
        $firstPart = trim("{$typeName} - " . ($details['address_1'] ?? ''));

        // Remaining parts
        $parts = array_filter([
            $firstPart,
            $details['address_2'] ?? '',
            $cityName,
            $stateName,
            $countryName,
            $details['pincode'] ?? '',
        ]);

        return implode(', ', $parts);
    }
}

if (!function_exists('formatCompanyAddress')) {
    function formatCompanyAddress()
    {
        // Static company address (Bengaluru)
        $companyAddress = [
            'company_name' => 'Watercane Delivery Pvt. Ltd.',
            'address_line_1' => '123, MG Road, Near Trinity Metro Station',
            'address_line_2' => 'Ashok Nagar',
            'city' => 'Bengaluru',
            'state' => 'Karnataka',
            'country' => 'India',
            'pincode' => '560001',
        ];

        // Combine and format neatly
        $parts = array_filter([
            $companyAddress['company_name'],
            $companyAddress['address_line_1'],
            $companyAddress['address_line_2'],
            $companyAddress['city'],
            $companyAddress['state'],
            $companyAddress['country'],
            'Pincode: ' . $companyAddress['pincode'],
        ]);

        return "{$companyAddress['company_name']}\n"
            . "{$companyAddress['address_line_1']}\n"
            . "{$companyAddress['address_line_2']}\n"
            . "{$companyAddress['city']}, {$companyAddress['state']}\n"
            . "{$companyAddress['country']} - {$companyAddress['pincode']}";
    }
}




if (!function_exists('getAccessToken')) {
    function getAccessToken($file_path)
    {

        // Otherwise, generate a new token
        $serviceAccountFile = base_path($file_path);

        if (!file_exists($serviceAccountFile)) {
            return response()->json(['status' => 'error', 'message' => 'Error: Service account file not found.']);
        }

        $serviceAccount = json_decode(file_get_contents($serviceAccountFile), true);
        if (!isset($serviceAccount['client_email'], $serviceAccount['private_key'], $serviceAccount['token_uri'])) {
            return response()->json(['status' => 'error', 'message' => 'Error: Missing required fields in service account JSON.']);
        }

        $now = time();
        $header = [
            'alg' => 'RS256',
            'typ' => 'JWT'
        ];
        $claimSet = [
            'iss' => $serviceAccount['client_email'],
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            'aud' => $serviceAccount['token_uri'],
            'exp' => $now + 3600,
            'iat' => $now
        ];

        $base64UrlHeader = rtrim(strtr(base64_encode(json_encode($header)), '+/', '-_'), '=');
        $base64UrlClaimSet = rtrim(strtr(base64_encode(json_encode($claimSet)), '+/', '-_'), '=');
        $dataToSign = $base64UrlHeader . '.' . $base64UrlClaimSet;

        $privateKey = openssl_pkey_get_private($serviceAccount['private_key']);
        if ($privateKey === false) {
            return response()->json(['status' => 'error', 'message' => 'Error: Unable to load private key.']);
        }

        // Sign the data
        if (!openssl_sign($dataToSign, $signature, $privateKey, OPENSSL_ALGO_SHA256)) {
            return response()->json(['status' => 'error', 'message' => 'Error: Unable to sign the JWT.']);
        }

        $base64UrlSignature = rtrim(strtr(base64_encode($signature), '+/', '-_'), '=');
        $jwt = $base64UrlHeader . '.' . $base64UrlClaimSet . '.' . $base64UrlSignature;

        // Request the access token
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $serviceAccount['token_uri']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt
        ]));
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // Enable in production
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Enable in production

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($response === false) {
            return response()->json(['status' => 'error', 'message' => 'Curl Error.']);
            return null;
        }

        curl_close($ch);

        if ($httpCode === 200) {
            $responseData = json_decode($response, true);
            return $responseData['access_token'];
        } else {
            return response()->json(['status' => 'error', 'message' => "Error retrieving access token: HTTP $httpCode - $response" . PHP_EOL]);
        }
    }
}

if (!function_exists('sendNotificationToUser')) {
    function sendNotificationToUser($deviceToken, $title, $message, $data)
    {

        $key = 'user_auth_token';
        $project_id = env('USER_FIREBASE_PROJECT_ID');
        $setting = App\Models\Setting::where('key', $key)
            ->where('group', 'firebase')
            ->first();

        $accessToken = null;
        if ($setting) {
            $payload = is_array($setting->value) ? $setting->value : json_decode($setting->value, true);
            $accessToken = $payload['token'] ?? null;
        }

        if (!$accessToken) {
            return response()->json(['status' => 'error', 'message' => 'Error: Could not retrieve access token.']);
        }

        // Validate device token format
        if (empty($deviceToken) || !is_string($deviceToken) || strlen($deviceToken) < 140) {
            return;
        }

        $fcmUrl = 'https://fcm.googleapis.com/v1/projects/' . $project_id . '/messages:send';

        $notification = [
            'title' => $title,
            'body' => $message,
        ];

        $fcmPayload = [
            'message' => [
                'token' => $deviceToken,
                'notification' => $notification,
                'data' => $data,
            ],
        ];

        $headers = [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json',
        ];

        // Initialize cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $fcmUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmPayload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // Enable in production
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Enable in production

        // Execute cURL request
        $response = curl_exec($ch);

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Handle response
        if ($response === false) {
            return response()->json(['status' => 'error', 'message' => 'cURL Error: ' . curl_error($ch)]);
        }

        return response()->json(['status' => 'success', 'message' => 'Notification message send successfully.']);
        curl_close($ch);
    }
}

if (!function_exists('latLongAddress')) {
    function latLongAddress($lat, $lng)
    {
        $apiKey = env('GOOGLE_MAP_API_KEY');
        if (!$apiKey) {
            return 'Google API Key not configured.';
        }

        $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng={$lat},{$lng}&key={$apiKey}";

        $response = Http::get($url);
        if ($response->failed()) {
            return 'Unable to connect to Google Maps API.';
        }

        return $data = $response->json();

        if (!empty($data['results'][0]['formatted_address'])) {
            return $data['results'][0]['formatted_address'];
        }
    }
}


if (!function_exists('calculateExpectedDeliveryDate')) {
    function calculateExpectedDeliveryDate($orderId)
    {

        $today = Carbon::today();

        $order = \App\Models\Order::where('id', $orderId)->first();

        // --- EXPRESS ORDER ---
        if ($order->type == \App\Models\Order::TYPE_EXPRESS) {
            if ($order->date) {
                $expressDate = Carbon::parse($order->date);
                return $expressDate->isSameDay($today) || $expressDate->isFuture()
                    ? $expressDate
                    : $today->copy();
            }
            return $today->copy();
        }

        // --- SUBSCRIPTION ORDER ---
        if ($order->type == \App\Models\Order::TYPE_SUBSCRIPTION) {
            $startDate = Carbon::parse($order->start_date);
            $endDate = $order->end_date ? Carbon::parse($order->end_date) : null;

            $scheduleValue = is_array($order->schedule_value) ? $order->schedule_value : [];
            $firstScheduleItem = count($scheduleValue) > 0 ? array_values(array_filter($scheduleValue))[0] ?? null : null;

            // --- DAILY ---
            if ($order->schedule_type == \App\Models\Order::SCHEDULE_TYPE_DAILY) {
                // Next delivery starts from start_date (if future) or next day after start_date
                return $startDate->isFuture() ? $startDate : $startDate->copy();
            }

            // --- WEEKLY ---
            if ($order->schedule_type == \App\Models\Order::SCHEDULE_TYPE_WEEKLY && $firstScheduleItem) {
                $dayMap = [
                    'Sun' => 'Sunday',
                    'Mon' => 'Monday',
                    'Tue' => 'Tuesday',
                    'Wed' => 'Wednesday',
                    'Thu' => 'Thursday',
                    'Fri' => 'Friday',
                    'Sat' => 'Saturday'
                ];

                $targetDay = $dayMap[$firstScheduleItem] ?? null;

                if ($targetDay) {
                    // Start from the start date (NOT today)
                    $nextDate = $startDate->copy()->next($targetDay);

                    // If start_date itself matches the day, use it
                    if ($startDate->isSameDay($startDate->copy()->next($targetDay))) {
                        $nextDate = $startDate;
                    }

                    // Ensure within range
                    if (!$endDate || $nextDate->lessThanOrEqualTo($endDate)) {
                        return $nextDate;
                    }
                }
                return 'Check Schedule';
            }

            // --- MONTHLY ---
            if ($order->schedule_type == \App\Models\Order::SCHEDULE_TYPE_MONTHLY && $firstScheduleItem) {
                $targetDayOfMonth = (int) $firstScheduleItem;

                try {
                    // If the target day exists in the start month
                    if ($targetDayOfMonth >= $startDate->day) {
                        $nextDate = $startDate->copy()->day($targetDayOfMonth);
                    } else {
                        // Otherwise, move to next month’s target day
                        $nextDate = $startDate->copy()->addMonth()->day($targetDayOfMonth);
                    }
                } catch (\Exception $e) {
                    return 'Check Schedule';
                }

                if (!$endDate || $nextDate->lessThanOrEqualTo($endDate)) {
                    return $nextDate;
                }

                return 'Check Schedule';
            }
        }

        return 'Check Details';
    }
}
if (! function_exists('checkMobileViewActivated')) {
    function checkMobileViewActivated()
    {
        if (!session()->has('mobile_view_activated')) {
            return false;
        } else {
            return true;
        }
    }
}


if (!function_exists('calculateSubscriptionDeliveryDays')) {
    /**
     * Calculates the total number of deliveries (days) for a given subscription.
     *
     * @param string $start_date The subscription start date (e.g., 'YYYY-MM-DD').
     * @param string $end_date The subscription end date (e.g., 'YYYY-MM-DD').
     * @param int $schedule_type The subscription frequency (1: Daily, 2: Weekly, 3: Monthly).
     * @param array|string|null $schedule_value The schedule data (e.g., ['Mon', 'Fri'] or ['15', '30']).
     * @return int The total number of delivery days.
     */
    function calculateSubscriptionDeliveryDays(string $start_date, string $end_date, int $schedule_type, $schedule_value): int
    {
        $start = Carbon::parse($start_date)->startOfDay();
        $end = Carbon::parse($end_date)->endOfDay();
        $days = 0;

        // Ensure schedule_value is an array for comparison
        $schedule = is_array($schedule_value) ? $schedule_value : json_decode($schedule_value, true) ?? [];
        $schedule = array_filter($schedule); // Remove null/empty values

        // Frequency mapping based on your Order/Subscription model constants (assuming 1=Daily, 2=Weekly, 3=Monthly)
        switch ($schedule_type) {
            case 1: // Daily
                // If Daily, count every day between start and end (inclusive)
                $days = $start->diffInDays($end) + 1;
                break;

            case 2: // Weekly
                // Weekly Schedule: $schedule contains weekday abbreviations (e.g., ['Mon', 'Fri'])

                // If schedule is empty for Weekly, assume 'Every Day'
                if (empty($schedule)) {
                    $days = $start->diffInDays($end) + 1;
                    break;
                }

                $period = CarbonPeriod::create($start, $end);
                foreach ($period as $date) {
                    // Carbon's format('D') returns: Mon, Tue, Wed, Thu, Fri, Sat, Sun
                    if (in_array($date->format('D'), $schedule)) {
                        $days++;
                    }
                }
                break;

            case 3: // Monthly
                // Monthly Schedule: $schedule contains day numbers (e.g., ['1', '15', '30'])

                // If schedule is empty for Monthly, assume 'Every Day'
                if (empty($schedule)) {
                    $days = $start->diffInDays($end) + 1;
                    break;
                }

                $current = $start->copy();

                // Iterate month by month
                while ($current->lte($end)) {
                    $year = $current->year;
                    $month = $current->month;

                    foreach ($schedule as $day_number) {
                        $day_number = (int)$day_number;

                        // Check if the day number is valid for the current month
                        if (checkdate($month, $day_number, $year)) {
                            $delivery_date = Carbon::createFromDate($year, $month, $day_number);

                            // Only count if the delivery date falls within the subscription range (start to end)
                            if ($delivery_date->between($start, $end, true)) {
                                $days++;
                            }
                        }
                    }

                    // Move to the next month's start date
                    $current->addMonthNoOverflow()->startOfMonth();
                }
                break;

            default:
                // Fallback for unknown type
                $days = 0;
                break;
        }

        return $days;
    }
}


function checkPincodeExists($pincode)
{

    $exists = App\Models\ZonePincode::where('pincode', $pincode)->exists();
    if ($exists) {
        return true;
    } else {

        return false;
    }
}

 function sendWhatsappText($phone, $message)
{
    $sessionId = "5874a031-1c9d-426c-8d50-c17be30c5857";

    $url = "http://72.61.251.64:2785/api/sessions/{$sessionId}/messages/send-text";

    $response = Http::withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
        'X-API-Key' => 'owa_k1_2e30a9b88429e62658ba01cb7374d0c486f90971bc854664ecd7290ab49a115d',
    ])->post($url, [
        'chatId' => '91' . $phone . '@c.us',
        'text' => $message,
    ]);

    return [
        'status' => $response->status(),
        'body' => $response->json(),
    ];
}
