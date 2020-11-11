<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    */

    'name' => env('APP_NAME', 'Wallet'),

    'currency' => '',
    'country' => '',

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services the application utilizes. Set this in your ".env" file.
    |
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | your application so that it is used when running Artisan tasks.
    |
    */

    'url' => env('APP_URL', 'http://localhost'),

    'admin_url' => env('APP_ADMIN_URL', 'http://localhost'),

    'asset_url' => env('ASSET_URL', null),

    'domain' => env('DOMAIN', 'http://localhost'),
    
    'admin_domain' => env('ADMIN_DOMAIN', 'http://localhost'),

    /**
     * Role name for super admin 
     */
    'super_admin_role' => 'manager',
    
    'check_edit_currency_permission' => true,

    /**
     * Developer emails to be sent whenever error exception occured 
     */
    'dev_email' => env('DEV_EMAIL', ''),

    /**
     * Proxy with username & password
     */
    'proxy' => env('PROXY', ''),

    /**
     * S-Sport
     */
    'ssport_agent' => env('SSPORT_AGENT', ''),
    'ssport_secret' => env('SSPORT_SECRET', ''),
    'ssport_api_url' => env('SSPORT_API_URL', ''),
    'ssport_api_get_ticket_url' => env('SSPORT_API_GET_TICKET_URL', ''),

    /**
     * PlayTech
     */
    'pt_env' => env('PT_ENV', 'UAT'),

    'pt_IDR_name' => env('PT_IDR_NAME', ''),
    'pt_IDR_cloud_location' => env('PT_IDR_CLOUD_LOCATION', ''),
    'pt_IDR_virtual_database' => env('PT_IDR_VIRTUAL_DATABASE', ''),
    'pt_IDR_mobile_hub' => env('PT_IDR_MOBILE_HUB', ''),
    'pt_IDR_system_id' => env('PT_IDR_SYSTEM_ID', ''),
    'pt_IDR_brand_code' => env('PT_IDR_BRAND_CODE', ''),
    'pt_IDR_api_url' => env('PT_IDR_API_URL', ''),
    'pt_IDR_entity_key' => env('PT_IDR_ENTITY_KEY', ''),

    /**
     * AllBet
     */
    'allbet_IDR_agent' => env('ALLBET_IDR_AGENT', ''),
    'allbet_IDR_property_id' => env('ALLBET_IDR_PROPERTY_ID', ''),
    'allbet_IDR_des_key' => env('ALLBET_IDR_DES_KEY', ''),
    'allbet_IDR_md5_key' => env('ALLBET_IDR_MD5_KEY', ''),
    'allbet_IDR_api_url' => env('ALLBET_IDR_API_URL', ''),
    
    'allbet_SGD_agent' => env('ALLBET_SGD_AGENT', ''),
    'allbet_SGD_property_id' => env('ALLBET_SGD_PROPERTY_ID', ''),
    'allbet_SGD_des_key' => env('ALLBET_SGD_DES_KEY', ''),
    'allbet_SGD_md5_key' => env('ALLBET_SGD_MD5_KEY', ''),
    'allbet_SGD_api_url' => env('ALLBET_SGD_API_URL', ''),

    /**
     * DreamGame
     */
    'dg_agent' => env('DG_AGENT', ''),
    'dg_api_key' => env('DG_API_KEY', ''),
    'dg_api_url' => env('DG_API_URL', ''),

    /**
     * HKB Gaming
     */
    'hkb_operator_id' => env('HKB_OPERATOR_ID', ''),
    'hkb_prefix' => env('HKB_PREFIX', ''),
    'hkb_api_secret_key' => env('HKB_API_SECRET_KEY', ''),
    'hkb_api_url' => env('HKB_API_URL', ''),
    'hkb_game_url' => env('HKB_GAME_URL', ''),
    'hkb_ftp_url' => env('HKB_FTP_URL', ''),
    'hkb_ftp_username' => env('HKB_FTP_USERNAME', ''),
    'hkb_ftp_password' => env('HKB_FTP_PASSWORD', ''),

    /**
    * SBO
    */
    'sbo_company_url_name' => env('SBO_COMPANY_URL_NAME', ''),
    'sbo_account' => env('SBO_ACCOUNT', ''),
    'sbo_admin_domain' => env('SBO_ADMIN_DOMAIN', ''),
    'sbo_api_url' => env('SBO_API_URL', ''),
    'sbo_game_url' => env('SBO_GAME_URL', ''),
    'sbo_api_key' => env('SBO_API_KEY', ''),
    'sbo_server_id' => env('SBO_SERVER_ID', ''),
    'sbo_IDR_agent' => env('SBO_IDR_AGENT', ''),
    'sbo_MYR_agent' => env('SBO_MYR_AGENT', ''),

    /**
    * AGGB
    */
    'aggb_api_url' => env('AGGB_API_URL', ''), 
    'aggb_vendor_id' => env('AGGB_VENDOR_ID', ''), 
    'aggb_operator_id' => env('AGGB_OPERATOR_ID', ''), 
    'aggb_odd_type' => env('AGGB_ODD_TYPE', ''), 

    /**
    * IDNPLAY
    */
    'idnplay_IDR_agent' => env('IDNPLAY_IDR_AGENT', ''),
    'idnplay_IDR_api_url' => env('IDNPLAY_IDR_API_URL', ''),
    'idnplay_IDR_secret_key' => env('IDNPLAY_IDR_SECRET_KEY', ''),

    /**
     * AWC
     */
    'awc_api_url' => env('AWC_API_URL', ''),
    'awc_report_api_url' => env('AWC_REPORT_API_URL', ''),

    'awc_IDR_agent_id' => env('AWC_IDR_AGENT_ID', ''),
    'awc_IDR_cert' => env('AWC_IDR_CERT', ''),

    'awc_SGD_agent_id' => env('AWC_SGD_AGENT_ID', ''),
    'awc_SGD_cert' => env('AWC_SGD_CERT', ''),
    
    /**
     * MGP
     */
    'mgp_token_api_url' => env('MGP_TOKEN_API_URL', ''), 
    'mgp_api_url' => env('MGP_API_URL', ''), 
    
    'mgp_IDR_client_id' => env('MGP_IDR_CLIENT_ID', ''), 
    'mgp_IDR_client_secret' => env('MGP_IDR_CLIENT_SECRET', ''), 

    /**
    * AvengersX
    */
    'avengersx_api_url' => env('AVENGERSX_API_URL', ''),

    'avengersx_IDR_operator' => env('AVENGERSX_IDR_OPERATOR', ''),
    'avengersx_IDR_secret_key' => env('AVENGERSX_IDR_SECRET_KEY', ''),

    'avengersx_SGD_operator' => env('AVENGERSX_SGD_OPERATOR', ''),
    'avengersx_SGD_secret_key' => env('AVENGERSX_SGD_SECRET_KEY', ''),

    /**
    * CMD
    */
    'cmd_api_url' => env('CMD_API_URL', ''), 
    'cmd_login_url' => env('CMD_LOGIN_URL', ''), 
    'cmd_partner_key' => env('CMD_PARTNER_KEY', ''), 

    /**
    * Ezugi
    */
    'ezugi_api_url' => env('EZUGI_API_URL', ''), 
    'ezugi_api_salt' => env('EZUGI_API_SALT', ''), 
    'ezugi_bo_api_url' => env('EZUGI_BO_API_URL', ''), 
    'ezugi_bo_api_id' => env('EZUGI_BO_API_ID', ''), 
    'ezugi_bo_api_username' => env('EZUGI_BO_API_USERNAME', ''), 
    'ezugi_bo_api_access' => env('EZUGI_BO_API_ACCESS', ''), 

    'ezugi_IDR_agent_id' => env('EZUGI_IDR_AGENT_ID', ''), 
    'ezugi_IDR_operator_id' => env('EZUGI_IDR_OPERATOR_ID', ''), 
    'ezugi_IDR_agent_username' => env('EZUGI_IDR_AGENT_USERNAME', ''), 

    'ezugi_SGD_agent_id' => env('EZUGI_SGD_AGENT_ID', ''), 
    'ezugi_SGD_operator_id' => env('EZUGI_SGD_OPERATOR_ID', ''), 
    'ezugi_SGD_agent_username' => env('EZUGI_SGD_AGENT_USERNAME', ''), 

    /**
    * GD
    */
    'gd_merchant_id' => env('GD_MERCHANT_ID', ''), 
    'gd_ewallet_api_url' => env('GD_EWALLET_API_URL', ''),
    'gd_report_api_url' => env('GD_REPORT_API_URL', ''),
    'gd_login_game_url' => env('GD_LOGIN_GAME_URL'),
    'gd_access_key' => env('GD_ACCESS_KEY'),

    /**
    * PragmaticPlay
    */
    'pragmaticplay_api_url' => env('PRAGMATICPLAY_API_URL'), 
    'pragmaticplay_securelogin' => env('PRAGMATICPLAY_SECURELOGIN'), 
    'pragmaticplay_securelogin_hash' => env('PRAGMATICPLAY_SECURELOGIN_HASH'), 

    /**
    * Habanero
    */
    'habanero_api_url' => env('HABANERO_API_URL'), 
    'habanero_game_url' => env('HABANERO_GAME_URL'), 
    'habanero_brand_id' => env('HABANERO_BRAND_ID'), 
    'habanero_api_key' => env('HABANERO_API_KEY'), 

    /**
    * Trilion Isin4D
    */
    // 'trilion_isin4d_api_url' => env('TRILION_ISIN4D_API_URL',''), 
    // 'trilion_isin4d_game_url' => env('TRILION_ISIN4D_GAME_URL',''), 
    // 'trilion_isin4d_brand_id' => env('TRILION_ISIN4D_BRAND_ID',''), 
    // 'trilion_isin4d_api_key' => env('TRILION_ISIN4D_API_KEY',''), 

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. We have gone
    | ahead and set this to a sensible default for you out of the box.
    |
    */

    'timezone' => 'Asia/Singapore',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by the translation service provider. You are free to set this value
    | to any of the locales which will be supported by the application.
    |
    */

    'locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Application Fallback Locale
    |--------------------------------------------------------------------------
    |
    | The fallback locale determines the locale to use when the current one
    | is not available. You may change the value to correspond to any of
    | the language folders that are provided through your application.
    |
    */

    'fallback_locale' => 'en',

    /**
     * List of alternative languages (not including the one specified as 'locale')
     */
    'alt_locale' => array ('en', 'cn'),

    /**
     *  System locale during runtime  - leave empty (set in runtime)
     */
    'runtime_locale' => '',

    /**
     *  Prefix of locale during runtime  - leave empty (set in runtime)
     */
    'prefix_locale' => '',

    /*
    |--------------------------------------------------------------------------
    | Faker Locale
    |--------------------------------------------------------------------------
    |
    | This locale will be used by the Faker PHP library when generating fake
    | data for your database seeds. For example, this will be used to get
    | localized telephone numbers, street address information and more.
    |
    */

    'faker_locale' => 'en_US',

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is used by the Illuminate encrypter service and should be set
    | to a random, 32 character string, otherwise these encrypted strings
    | will not be safe. Please do this before deploying an application!
    |
    */

    'key' => env('APP_KEY'),

    'cipher' => 'AES-256-CBC',

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

    'providers' => [

        /*
         * Laravel Framework Service Providers...
         */
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
        Illuminate\Bus\BusServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Mail\MailServiceProvider::class,
        Illuminate\Notifications\NotificationServiceProvider::class,
        Illuminate\Pagination\PaginationServiceProvider::class,
        Illuminate\Pipeline\PipelineServiceProvider::class,
        Illuminate\Queue\QueueServiceProvider::class,
        Illuminate\Redis\RedisServiceProvider::class,
        Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,

        /*
         * Package Service Providers...
         */
        Spatie\Permission\PermissionServiceProvider::class,
        Barryvdh\Debugbar\ServiceProvider::class,
        Jenssegers\Agent\AgentServiceProvider::class,
        Maatwebsite\Excel\ExcelServiceProvider::class,
        Yajra\DataTables\DataTablesServiceProvider::class,

        /*
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        App\Providers\BroadcastServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
        App\Providers\ExcelServiceProvider::class,

    ],

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
    */

    'aliases' => [

        'App' => Illuminate\Support\Facades\App::class,
        'Arr' => Illuminate\Support\Arr::class,
        'Artisan' => Illuminate\Support\Facades\Artisan::class,
        'Auth' => Illuminate\Support\Facades\Auth::class,
        'Blade' => Illuminate\Support\Facades\Blade::class,
        'Broadcast' => Illuminate\Support\Facades\Broadcast::class,
        'Bus' => Illuminate\Support\Facades\Bus::class,
        'Cache' => Illuminate\Support\Facades\Cache::class,
        'Config' => Illuminate\Support\Facades\Config::class,
        'Cookie' => Illuminate\Support\Facades\Cookie::class,
        'Crypt' => Illuminate\Support\Facades\Crypt::class,
        'DB' => Illuminate\Support\Facades\DB::class,
        'Eloquent' => Illuminate\Database\Eloquent\Model::class,
        'Event' => Illuminate\Support\Facades\Event::class,
        'File' => Illuminate\Support\Facades\File::class,
        'Gate' => Illuminate\Support\Facades\Gate::class,
        'Hash' => Illuminate\Support\Facades\Hash::class,
        'Lang' => Illuminate\Support\Facades\Lang::class,
        'Log' => Illuminate\Support\Facades\Log::class,
        'Mail' => Illuminate\Support\Facades\Mail::class,
        'Notification' => Illuminate\Support\Facades\Notification::class,
        'Password' => Illuminate\Support\Facades\Password::class,
        'Queue' => Illuminate\Support\Facades\Queue::class,
        'Redirect' => Illuminate\Support\Facades\Redirect::class,
        'Redis' => Illuminate\Support\Facades\Redis::class,
        'Request' => Illuminate\Support\Facades\Request::class,
        'Response' => Illuminate\Support\Facades\Response::class,
        'Route' => Illuminate\Support\Facades\Route::class,
        'Schema' => Illuminate\Support\Facades\Schema::class,
        'Session' => Illuminate\Support\Facades\Session::class,
        'Storage' => Illuminate\Support\Facades\Storage::class,
        'Str' => Illuminate\Support\Str::class,
        'URL' => Illuminate\Support\Facades\URL::class,
        'Validator' => Illuminate\Support\Facades\Validator::class,
        'View' => Illuminate\Support\Facades\View::class,
        'Admin' => App\Admin::class,
        'User' => App\User::class,
        'UserRank' => App\UserRank::class,
        'UserBank' => App\UserBank::class,
        'UserRebate' => App\UserRebate::class,
        'UserProductAPI' => App\UserProductAPI::class,
        'UserWallet' => App\UserWallet::class,
        'UserWalletHistory' => App\UserWalletHistory::class,
        'LastLogin' => App\LastLogin::class,
        'Bank' => App\Bank::class,
        'Bet' => App\Bet::class,
        'BetsRebate' => App\BetsRebate::class,
        'Deposit' => App\Deposit::class,
        'Withdrawal' => App\Withdrawal::class,
        'WalletTransfer' => App\WalletTransfer::class,
        'ManualAdjustment' => App\ManualAdjustment::class,
        'Promotion' => App\Promotion::class,
        'PromotionContent' => App\PromotionContent::class,
        'UserPromotion' => App\UserPromotion::class,
        'Product' => App\Product::class,
        'ProductGame' => App\ProductGame::class,
        'ProductGameAPI' => App\ProductGameAPI::class,
        'ProductTransaction' => App\ProductTransaction::class,
        'Helper' => App\Helper::class,
        'System' => App\System::class,
        'Cron' => App\Cron::class,
        'CMS' => App\CMS::class,
        'CMSContent' => App\CMSContent::class,
        'Banner' => App\Banner::class,
        'Announcement' => App\Announcement::class,
        'Campaign' => App\Campaign::class,
        'Gift' => App\Gift::class,
        'SpinWheelResult' => App\SpinWheelResult::class,
        'CustomEmail' => App\CustomEmail::class,
        'Debugbar' => Barryvdh\Debugbar\Facade::class,
        'Agent' => Jenssegers\Agent\Facades\Agent::class,
        'Excel' => Maatwebsite\Excel\Facades\Excel::class,
        'DataTables' => Yajra\DataTables\Facades\DataTables::class,
        'ProductInterface' => App\ProductInterface::class,
        'Transaction' => App\Transaction::class,

    ],

    /*
    |--------------------------------------------------------------------------
    | Config option for whoops blacklist 
    |--------------------------------------------------------------------------
    |
    | It allows you to blacklist certain variables in config/app.php under the key debug_blacklist.
    | When an exception is thrown, whoops will mask these values with asterisks * for each character.
    |
    */
    'debug_blacklist' => [
        // '_COOKIE' => array_keys($_COOKIE),
        '_SERVER' => array_keys($_SERVER),
        '_ENV' => array_keys($_ENV),        
    ],

];
