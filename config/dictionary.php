<?php

return [
    // Core Laravel Concepts
    'artisan', 'eloquent', 'migration', 'seeder', 'tinker', 'route', 'controller', 'middleware',
    'model', 'view', 'blade', 'component', 'policy', 'provider', 'command', 'queue', 'job', 'event',
    'listener', 'broadcast', 'notification', 'session', 'request', 'response', 'validator', 'cache',
    'config', 'console', 'log', 'env', 'debug', 'schedule', 'task', 'mail', 'resource',

    // Database & Query
    'database', 'table', 'schema', 'relationship', 'foreign', 'key', 'index', 'unique', 'nullable',
    'timestamps', 'query', 'builder', 'raw', 'join', 'pluck', 'aggregate', 'transaction', 'pivot',

    // Blade Templates
    'blade', 'directive', 'layout', 'slot', 'stack', 'yield', 'section', 'include', 'foreach',
    'for', 'if', 'else', 'isset', 'empty', 'component', 'props', 'form', 'csrf', 'errors', 'old',
    'auth', 'guest', 'route', 'url', 'asset', 'class', 'style',

    // Authentication & Security
    'auth', 'login', 'register', 'logout', 'password', 'email', 'guard', 'hash', 'bcrypt', 'token',
    'session', 'csrf', 'encryption', 'authorization', 'permission', 'policy', 'role', 'acl',

    // HTTP & APIs
    'http', 'request', 'response', 'redirect', 'url', 'session', 'cookie', 'api', 'resource', 'json',
    'paginate', 'throttle', 'rate', 'limit', 'header', 'bearer', 'token', 'oauth', 'sanctum', 'passport',

    // Testing
    'test', 'phpunit', 'dusk', 'assert', 'mock', 'fake', 'spy', 'seed', 'factory', 'refresh',
    'database', 'setup', 'teardown', 'unit', 'feature', 'integration',

    // Frontend & Assets
    'mix', 'webpack', 'npm', 'yarn', 'sass', 'scss', 'css', 'javascript', 'vue', 'react',
    'tailwindcss', 'bootstrap', 'jquery', 'ajax', 'component', 'template',

    // Packages & Tools
    'composer', 'package', 'dependency', 'package.json', 'service', 'facade', 'helper', 'trait', 
    'namespace', 'autoload', 'classmap', 'singleton', 'binding',

    // Error Handling & Debugging
    'exception', 'error', 'debug', 'log', 'stacktrace', 'dump', 'dd', 'abort', 'try', 'catch',
    'throw', 'validation',

    // Deployment & Optimization
    'cache', 'config', 'route', 'optimize', 'serve', 'production', 'local', 'environment', 'key',
    'storage', 'symlink', 'log', 'queue', 'worker', 'horizon', 'forge', 'deployment',

    // Laravel Ecosystem
    'nova', 'horizon', 'passport', 'sanctum', 'socialite', 'telescope', 'spark', 'cashier', 'scout',
    'fortify', 'breeze', 'valet', 'sail',

    // Miscellaneous
    'helper', 'macro', 'path', 'directory', 'alias', 'namespace', 'command', 'job', 'event',
    'listener', 'broadcast', 'notification', 'observer', 'factory', 'seed', 'middleware', 'queue',
    'worker', 'scheduler', 'task',

    'php','app','traits',

    // Newly Added Core Laravel Terms
    'singleton', 'broadcasting', 'schedule', 'gates', 'token', 'passport', 'sanctum', 'websockets',
    'hashing', 'encryption', 'logging', 'debugging', 'exception',

    // Newly Added Common Laravel Tools & Packages
    'nova', 'breeze', 'fortify', 'scout', 'spark', 'lumen',

    // Newly Added Related PHP Terms
    'psr', 'trait', 'closure', 'json', 'xml', 'nullable', 'strict', 'autoloader', 
    'reflection', 'iterator',

    // Newly Added DevOps & Deployment
    'docker', 'kubernetes', 'ci', 'cd', 'pipeline', 'github', 'gitlab', 'bitbucket', 'ssh', 
    'staging', 'rollback', 'scheduler', 'cronjobs',

    // Newly Added Cloud & Hosting
    'aws', 'extends', 'layouts', 'meta_data', 'meta_title', 'metas', 'title', 'admin', 'meta_description', 
    'description', 'meta_keywords', 'keyword', 'meta_motto', 'app_settings', 'site_motto', 'meta_abstract', 
    'meta_author_name', 'app_name', 'watercane', 'meta_author_email', 'frontend_footer_email', 'dev', 'com', 
    'meta_reply_to', 'meta_img', 'endphp', 'endsection', 'field', 'icon', 'float', 'right', 'margin', '7px', 
    'top', '34px', 'position', 'relative', 'z', '2', 'alert', 'padding', '0px', '15px', 'important', 'danger', 
    'color', '842029', 'background', 'f8d7da', 'border', 'f5c2c7', 'input', 'webkit', 'outer', 'spin', 'button', 
    'inner', 'appearance', 'none', '0', 'type', 'number', 'text', 'align', 'center', 'font', 'weight', '600', 
    'media', 'max', 'width', '700px', 'custom', 'input_box', '25px', 'height', '30px', 'bottom', '1px', 'solid', 
    '817d7d', '0rem', 'firefox', 'moz', 'textfield', '3rem', '300px', 'btn', 'auto', 'display', 'block', 'content', 
    'bg', 'home', '75vh', 'div', 'container', 'row', 'mt', '10', 'col', 'lg', '7', 'xl', '5', 'xxl', 'md', '8', 
    'mx', 'card', 'body', 'p', '6', 'getsetting', 'authentication_mode', '1', 'white', 'signin', 'method', 'post', 
    'action', 'mb', 'autocomplete', 'off', 'a', 'href', 'img', 'src', 'getbackendlogo', 'app_logo', 'd', '100px', 
    'alt', 'h1', 'my', '3', 'fs', '18', 'sign', 'in', 'to', 'panel', 'dismissible', 'fade', 'show', 'close', 'data', 
    'dismiss', 'aria', 'label', 'endif', 'any', 'all', 'as', 'endforeach', 'floating', 'name', 'control', 'is', 
    'invalid', 'enderror', 'id', 'floatinginput', 'placeholder', 'example', 'value', 'required', 'autofocus', 'address', 
    'span', 'feedback', 'strong', 'message', 'toggle', 'fa', 'fw', 'eye', 'recaptcha', 'recapcha', 'enter', 'flex', 
    'justify', 'between', 'check', 'checkbox', 'option1', 'flexcheckdefault', 'item_checkbox', 'normal', 'remember', 
    'me', 'forgot', 'pass', 'forget', '14', 'hover', 'primary', 'rounded', 'pill', 'w', '100', 'submit', 'secure', 
    'muted', 'script', 'document', 'write', 'new', 'date', 'getfullyear', 'py', 'shadow', 'validate', 'digit', 'group', 
    'digits', 'autosubmit', 'false', 'avatar', 'small', '4', 'has', 'phone', 'box', 'next', 'maxlength', '9', 'previous', 
    '11', 'please', 'verify', 'by', 'otp', 'push', 'start', 'init', 'ready', 'function', 'on', 'async', 'e', 'hashed', 
    'btoa', 'val', 'end', 'fill', 'find', 'each', 'this', 'attr', 'keyup', 'var', 'parent', 'keycode', '37', 'prev', 
    'length', 'select', '48', '57', '96', '105', 'paste', 'click', 'input_val', 'slice', 'hide', 'toggleclass', 'slash', 
    'endpush','user', 'strength', 'meter', '200px', '6px', 'lightgray', '10px', 'transition', '3s', 'ease', 'weak', 'red', 
    'medium', 'orange', 'green', 'iti', 'inline', 'dropdown', 'iti__dropdown', 'and', '12', 'pattern', 'regex', 'minlength', 
    'common_name', 'mandatory', 'first', 'first_name', 'minlegth', 'last', 'last_name', '50', 'common_email', 'common_emali', 'floatingemail', 
    'hidden', 'countrycodeinput', 'country_code', 'tel', 'feild', 'phone_number', 'floatingphone', 'common_phone_number', 'floatingpassword', 
    'oninput', 'checkpasswordstrength', 'onclick', 'showpasswordstrengthmeter', 'confirm', 'password_confirmation', 'ln', 'clicking', 'up', 'you',
    'agree', 'our', 'terms', 'privacy', 'cookies', 'may', 'receive', 'sms', 'notifications', 
    'from', 'us', 'can', 'opt', 'out', 'at', 'time', 'complete', 'registration', 'dark', 
    'already', 'have', 'an', 'account', 'site', 'assets', 'js', 'minified', 'require', 'bcryptjs', 'min', 'countrycode', 'selector', 'addeventlistener', 
    'domcontentloaded', 'const', 'queryselector', 'window', 'intltelinput', 'initialcountry', 'separatedialcode', 'true', 'geoiplookup', 'callback', 
    'fetch','https','ipapi','co','then','res','utilsscript','country','code','utils','updatecountrycode', 
    'selectedcountrydata', 'getselectedcountrydata', 'dialcode', 'countryChange', 'change', 'prevent', 'spaces', 'the', 'replace',
    'remove','settimeout','dispatchevent','300','getelementbyid','getelementsbytagname','strengthtext','minimum','uppercase','letters', 
    'lowercase', 'numbers', 'no', 'allowed', 'special', 'characters', '_', 'update', 'based', 'classname'

];
