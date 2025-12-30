<?php

use App\Models\Admin;
use App\Models\AppConfig;
use App\Models\Customer;
use App\Models\Ipblock;
use App\Models\IpManage;
use App\Models\Modem;
use App\Models\PaymentRequest;
use App\Models\ServiceRequest;
use App\Models\PaymentMethod;
use App\Models\SmsSetting;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Ejarnutowski\LaravelApiKey\Models\ApiKey;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as MessagingNotification;
use App\Helpers\BalanceManagerConstant;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ConnectException;


use App\Models\Merchant;
//user table username

function getUsername($id)
{
    $udata = User::where('id', $id)->first();
    return $udata->member_code;
}

function mStatus($send)
{
    if ($send == 0) {
        $status = "<span style='color:#FF9500'>Not Connected</span>";
    }
    if ($send == 1) {
        $status = "<span style='color:green'>Connected</span>";
    }
    return $status;
}

function startsWith($haystack, $needle)
{
    return !strncmp($haystack, $needle, strlen($needle));
}

function random_numbers($digits)
{
    $min = pow(10, $digits - 1);
    $max = pow(10, $digits) - 1;
    return mt_rand($min, $max);
}

function showing($limit, $ofset, $rows)
{
    $from = $ofset;
    if ($from == 0) {
        $from = 1;
    }
    $to = $limit + $ofset;

    if ($to > $rows) {
        $to = $rows;
    }

    return "Showing $from to $to of $rows records";
}

function lastdays($days)
{
    $output = [];
    $month = date('m');
    $day = date('d');
    $year = date('Y');
    for ($i = 0; $i <= $days; $i++) {
        $output[] = date('Y-m-d', mktime(0, 0, 0, $month, $day - $i, $year));
    }
    return $output;
}

// if (!function_exists('generateSecretAndPublicKey')) {
//     /**
//      * @param string $suffix
//      * @return array
//      */
//     function generateSecretAndPublicKey(string $suffix = ''): array
//     {
//         $apiKey = new ApiKey();
//         $apiKey->name = 'test' . rand(1, 190000);
//         $apiKey->key = ApiKey::generateKey();
//         $apiKey->secret = '';

//         if (config('apikey.enable_secret_key') === true) {
//             $apiKey->secret = ApiKey::generateSecret();
//         }

//         $key = $apiKey->key;
//         $secret = $apiKey->secret;

//         $apiKey->save(); // the ApiKeyObserver will hash the secret
//         return [
//             'key' => $key,
//             'secret' => $secret,
//         ];
//     }
// }

/**
 * Generate an API key and secret (if enabled).
 *
 * @param string $suffix
 * @return array
 */
function generateSecretAndPublicKey(string $suffix = ''): array
{
    $apiKey = new ApiKey();
    $apiKey->name = 'test' . rand(1, 190000);

    // Generate the API key manually
    $apiKey->key = Str::random(60); // Generate a random string as API key

    // Initialize secret as empty
    $apiKey->secret = '';

    // Check if secret key is enabled and generate if true
    if (config('apikey.enable_secret_key') === true) {
        $apiKey->secret = Str::random(64); // Generate a random string as secret key
    }

    $key = $apiKey->key;
    $secret = $apiKey->secret;

    // Save the API key and secret to the database
    $apiKey->save(); // The ApiKeyObserver should hash the secret key

    return [
        'key' => $key,
        'secret' => $secret,
    ];
}

function sluggify($url)
{
    # Prep string with some basic normalization
    $url = strtolower($url);
    $url = strip_tags($url);
    $url = stripslashes($url);
    $url = html_entity_decode($url);

    # Remove quotes (can't, etc.)
    $url = str_replace('\'', '', $url);

    # Replace non-alpha numeric with hyphens
    $match = '/[^a-z0-9]+/';
    $replace = '-';
    $url = preg_replace($match, $replace, $url);

    $url = trim($url, '-');

    return $url;
}

function ipblock()
{
    $myip = $_SERVER['REMOTE_ADDR'];
    $iplist = Ipblock::where('ip_address', 'LIKE', "%{$myip}%")->count();

    if ($iplist > 0) {
        return true;
    } else {
        return false;
    }
}

function sendPostData($url, $post)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    $result = curl_exec($ch);
    curl_close($ch); // Seems like good practice
    return $result;
}

function get_url($url)
{
    $ch = curl_init();
    $timeout = 5;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

function app_config($value)
{
    $conf = AppConfig::where('setting', $value)->first();
    if (!empty($conf)) {
        return $conf->value;
    }
}

if (!function_exists('translate')) {
    function translate($word)
    {
        $return = ucwords(str_replace('_', ' ', $word));

        return $return;
    }
}

function bdtime($dat)
{
    //echo date('l jS \of F Y h:i:s A', strtotime($request_data->created_at)
    return date('j-F-Y h:i:s a', strtotime($dat));
}

function setLoginCookie()
{
    $cookie_name = 'browserid';
    $cookie_value = uuidv4();
    $response2 = setcookie($cookie_name, $cookie_value, time() + 86400 * 30, '/'); // 86400 = 1 day
    $response = $_COOKIE[$cookie_name];

    return $response;
}

function getCookie()
{
    $value = $_COOKIE['browserid'];
    return $value;
}

function uuidv4()
{
    return implode('-', [bin2hex(random_bytes(4)), bin2hex(random_bytes(2)), bin2hex(chr((ord(random_bytes(1)) & 0x0f) | 0x40)) . bin2hex(random_bytes(1)), bin2hex(chr((ord(random_bytes(1)) & 0x3f) | 0x80)) . bin2hex(random_bytes(1)), bin2hex(random_bytes(6))]);
}

function datetoDiff($end)
{
    $start_ts = strtotime(date('Y-m-d'));
    $end_ts = strtotime($end);
    $diff = $end_ts - $start_ts;
    return round($diff / 86400);
}

function getBrowser()
{
    $u_agent = $_SERVER['HTTP_USER_AGENT'];
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version = '';

    // First get the platform?
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';
    } elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'mac';
    } elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'windows';
    }

    // Next get the name of the useragent yes seperately and for good reason
    if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
        $bname = 'Internet Explorer';
        $ub = 'MSIE';
    } elseif (preg_match('/Firefox/i', $u_agent)) {
        $bname = 'Mozilla Firefox';
        $ub = 'Firefox';
    } elseif (preg_match('/Chrome/i', $u_agent)) {
        $bname = 'Google Chrome';
        $ub = 'Chrome';
    } elseif (preg_match('/Safari/i', $u_agent)) {
        $bname = 'Apple Safari';
        $ub = 'Safari';
    } elseif (preg_match('/Opera/i', $u_agent)) {
        $bname = 'Opera';
        $ub = 'Opera';
    } elseif (preg_match('/Netscape/i', $u_agent)) {
        $bname = 'Netscape';
        $ub = 'Netscape';
    }

    // finally get the correct version number
    $known = ['Version', $ub, 'other'];
    $pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
        // we have no matching number just continue
    }

    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($u_agent, 'Version') < strripos($u_agent, $ub)) {
            $version = $matches['version'][0];
        } else {
            $version = $matches['version'][1];
        }
    } else {
        $version = $matches['version'][0];
    }

    // check if we have a number
    if ($version == null || $version == '') {
        $version = '?';
    }

    return [
        'userAgent' => $u_agent,
        'name' => $bname,
        'version' => $version,
        'platform' => $platform,
        'pattern' => $pattern,
    ];
}

function genCode()
{
    $alphanum = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

    $code1 = substr(str_shuffle($alphanum), 0, 50);

    $code2 = substr(str_shuffle($alphanum), 0, 50);

    $code3 = substr(str_shuffle($alphanum), 0, 50);

    $code4 = substr(str_shuffle($alphanum), 0, 50);

    $code5 = substr(str_shuffle($alphanum), 0, 50);

    $code6 = substr(str_shuffle($alphanum), 0, 50);

    $code7 = substr(str_shuffle($alphanum), 0, 50);

    $code_01 = $code1 . $code2 . $code3 . $code4 . $code5 . $code6 . $code7;

    $gen_code1 = substr(str_shuffle($code_01), 0, 50);

    $code_01 = $code1 . $code2 . $code3 . $code4 . $code5 . $code6 . $code7;

    $gen_code2 = substr(str_shuffle($code_02), 0, 50);

    $gen_code = $gen_code1 . $gen_code2;

    $pin_gen_code = substr(str_shuffle($gen_code), 0, 60);

    return $pin_gen_code;
}

function getStringBetween($str, $from = '', $to = '')
{
    $sub = substr($str, strpos($str, $from) + strlen($from), strlen($str));
    if (!empty($to)) {
        return substr($sub, 0, strpos($sub, $to));
    } else {
        return substr($sub, strpos($sub, $to));
    }
}



function geoloaction($long, $lat)
{
    $url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $lat . ',' . $long . '&sensor=true&key=AIzaSyALblVQFpwxYcfUfNlmkjZ9Wx_ukblPAiU';
    $api_response = get_url($url);
    return $api_response;
}

function myclientIP()
{
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif (isset($_SERVER['HTTP_X_FORWARDED'])) {
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    } elseif (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    } elseif (isset($_SERVER['HTTP_FORWARDED'])) {
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    } else {
        $ipaddress = 'UNKNOWN';
    }
    return $ipaddress;
}

function geoloactionip($ip)
{
    //http://www.geoplugin.net/json.gp?ip=27.147.205.40
    //http://ip-api.com/json/27.147.205.40
    //https://ipinfo.io/221.134.25.160?token=94889c5cb30940

    $url = 'http://ip-api.com/json/' . $ip;
    $api_response = get_url($url);
    return $api_response;
}

function is_ascii($string = '')
{
    return (bool) !preg_match('/[\\x80-\\xff]+/', $string);
    //mb_detect_encoding($str, 'ASCII', true)
}

function money($amount)
{
    return number_format($amount, 2);
}

function dateDiff($end)
{
    $start_ts = strtotime(date('Y-m-d'));
    $end_ts = strtotime($end);
    $diff = $end_ts - $start_ts;
    return round($diff / 86400);
}

function userData($id, $type = null)
{
    $udata = User::where('id', $id)->first();
    return $udata;
}

function ipinfo($ip_address)
{
    $ipcount = IpManage::where('ip_address', $ip_address)->count();
    if ($ipcount == 0) {
        $ipresponse = ip2data($ip_address);
        $country = $ipresponse->country;
        $countryCode = $ipresponse->countryCode;
        $regionName = $ipresponse->regionName;
        $city = $ipresponse->city;
        $zip = $ipresponse->zip;
        $timezone = $ipresponse->timezone;
        $isp = $ipresponse->isp;

        IpManage::create([
            'ip_address' => $ip_address,
            'country' => $country,
            'countryCode' => $countryCode,
            'regionName' => $regionName,
            'city' => $city,
            'zip' => $zip,
            'timezone' => $timezone,
            'isp' => $isp,
        ]);
    }

    $ipdata = IpManage::where('ip_address', $ip_address)->first();

    return $ipdata;
}

function ip2data($ip)
{
    $url = 'http://ip-api.com/json/' . $ip;
    $result = get_url($url);
    $apists = json_decode($result);
    $responsests = $apists->status;
    return $apists;
}

function getTime()
{
    $a = explode(' ', microtime());
    return (float) $a[0] + $a[1];
}

function send_email($to, $subject, $message)
{
    //$to_name = 'TO_NAME';
    $to = $to;
    $subject = $subject;
    $txt = $message;
    $headers = 'From: <support@rnvuk.co>' . "\r\n";
    $headers .= 'Reply-To: <noreply@rnvuk.co>' . "\r\n";
    $headers .= 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type:text/html;charset=UTF-8' . "\r\n";

    mail($to, $subject, $txt, $headers);
}

function skrealsends($mobile, $amount, $refno)
{
    //	$message = urlencode($message);

    $url = 'https://joobo.xyz/real/index.php?mobile=' . $mobile . '&amount=' . $amount . '&ref=' . $refno;
    $api_response = get_url($url);
    return $api_response;
}

/* @function __status()  @version v1.2  @since 1.0 */
if (!function_exists('__status')) {
    function __status($name, $get)
    {
        $all_status = [
            'pending' => (object) [
                'icon' => 'progress',
                'text' => 'Progress',
                'status' => 'info',
            ],
            'missing' => (object) [
                'icon' => 'pending',
                'text' => 'Missing',
                'status' => 'warning',
            ],
            'approved' => (object) [
                'icon' => 'approved',
                'text' => 'Approved',
                'status' => 'success',
            ],
            'rejected' => (object) [
                'icon' => 'canceled',
                'text' => 'Rejected',
                'status' => 'danger',
            ],
            'canceled' => (object) [
                'icon' => 'canceled',
                'text' => 'Canceled',
                'status' => 'danger',
            ],
            'deleted' => (object) [
                'icon' => 'canceled',
                'text' => 'Deleted',
                'status' => 'danger',
            ],
            'onhold' => (object) [
                'icon' => 'pending',
                'text' => 'On Hold',
                'status' => 'info',
            ],
            'suspend' => (object) [
                'icon' => 'canceled',
                'text' => 'Suspended',
                'status' => 'danger',
                'null' => null,
            ],
            'active' => (object) [
                'icon' => 'success',
                'text' => 'Active',
                'status' => 'success',
                'null' => null,
            ],
            'default' => (object) [
                'icon' => 'pending',
                'text' => 'Pending',
                'status' => 'info',
                'null' => null,
            ],
            'purchase' => (object) [
                'icon' => 'purchase',
                'text' => 'Purchase',
                'status' => 'success',
                'null' => null,
            ],
            'bonus' => (object) [
                'icon' => 'bonus',
                'text' => 'Bonus',
                'status' => 'warning',
                'null' => null,
            ],
            'referral' => (object) [
                'icon' => 'referral',
                'text' => 'Referral',
                'status' => 'primary',
                'null' => null,
            ],
            'refund' => (object) [
                'icon' => 'referral',
                'text' => 'Refund',
                'status' => 'danger',
                'null' => null,
            ],
            // New
            'deposit' => (object) [
                'icon' => 'deposit',
                'text' => 'Deposit',
                'status' => 'primary',
                'null' => null,
            ],
            'withdraw' => (object) [
                'icon' => 'withdraw',
                'text' => 'Withdraw',
                'status' => 'warning',
                'null' => null,
            ],
            'profit' => (object) [
                'icon' => 'profit',
                'text' => 'Profit',
                'status' => 'success',
                'null' => null,
            ],
        ];
        return isset($all_status[$name]) ? $all_status[$name]->$get : (isset($all_status['default']->$get) ? $all_status['default']->$get : $all_status['default']->null);
    }
}

/* @function _date()  @version v1.1  @since 1.0 */
if (!function_exists('_date')) {
    function _date($date, $format = null, $dateonly = false, $zone = true)
    {
        if (empty($date)) {
            return;
        }

        if (!($date instanceof Carbon)) {
            if (1 === preg_match('~^[1-9][0-9]*$~', $date)) {
                $date = Carbon::createFromTimestamp($date);
            } else {
                $date = Carbon::parse($date);
            }
        }

        $_format = empty($format) ? get_setting('site_date_format', 'd M Y') : $format;

        if (!$dateonly && empty($format)) {
            $_format .= ' ' . get_setting('site_time_format', 'h:iA');
        }

        if ($zone == true) {
            $timezone = get_setting('site_timezone', 'Asia/Dhaka');
            return $date->timezone($timezone)->format($_format);
        }

        return $date->format($_format);
    }

    /**
     * @throws Exception
     */
}
if (!function_exists('generatePaymentRequestTrx')) {
    /**
     * @param $length
     * @return string
     * @throws Exception
     */
    function generatePaymentRequestTrx($length = 20)
    {
        $bytes = random_bytes($length);
        return bin2hex($bytes);
    }
}

if (!function_exists('generateTrxId')) {
    function generateTrxId($length = 20)
    {
        $length = 10; // Length of the unique string in bytes
        $randomBytes = random_bytes($length);
        return bin2hex($randomBytes);
    }
}

if (!function_exists('generateInvoiceNumber')) {
    function generateInvoiceNumber($length = 10)
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $invoiceNumber = '';

        for ($i = 0; $i < $length; $i++) {
            $randomIndex = rand(0, strlen($characters) - 1);
            $invoiceNumber .= $characters[$randomIndex];
        }

        return $invoiceNumber;
    }
}

function url_encrypt($string)
{
    $ENCRYPTION_KEY = '4736d52f85bdb63e46bf7d6d41bbd551af36e1bfb7c68164bf81e2400d291319';

    return base64_encode(openssl_encrypt($string, 'AES-256-CBC', $ENCRYPTION_KEY, 0, str_pad(substr($ENCRYPTION_KEY, 0, 16), 16, '0', STR_PAD_LEFT)));
}

function url_decrypt($string)
{
    $ENCRYPTION_KEY = '4736d52f85bdb63e46bf7d6d41bbd551af36e1bfb7c68164bf81e2400d291319';

    return openssl_decrypt(base64_decode($string), 'AES-256-CBC', $ENCRYPTION_KEY, 0, str_pad(substr($ENCRYPTION_KEY, 0, 16), 16, '0', STR_PAD_LEFT));
}

function CustomerInfo($id)
{
    $customer = Customer::find($id);
    return $customer;
}

function getPaymentStatus($status)
{
    return match ($status) {
        0 => 'pending',
        1 => 'completed',
        2 => 'accepted',
        3 => 'rejected',
    };
}

function getPaymenType($sim_id)
{
    $get_method = \App\Models\payment_method::where('sim_id', $sim_id)->value('type');

    if ($get_method) {
        return match ($get_method) {
            'agent' => 'Cashout',
            'personal' => 'Send Money',
            'customer' => 'Payment',
        };
    }
}

function getServiceStatus($status)
{
    return match ($status) {
        0 => 'Pending',
        1 => 'Waiting',
        2 => 'Success',
        3 => 'Approved',
        4 => 'Rejected',
    };
}

function update_app_config($key, $value)
{
    AppConfig::updateOrCreate(['setting' => $key], ['value' => $value]);
}

function getLiveStatus($sim_id)
{
    $getTime = Modem::where('sim_number', $sim_id)->first()->up_time;

    // $time = time();
    // $time_check = $time - 5;
    // if ($getTime >= $time_check) {
    //     $result = '<span style="color:green;">Online</span>';
    // } else {
    //     $result = '<span style="color:red;">Offline</span>';
    // }
    if ($getTime >= time() - env('PAYMENT_TIME')) {
        $result = '<span style="color:green;">Online</span>';
    } else {
        $result = '<span style="color:red;">Offline</span>';
    }

    return $result;
}


function gettingModemStatus($userId, $operator)
{
    $userInfo = User::find($userId);

    if (!$userInfo) {
        return false;
    }

    $getModemsList = Modem::where('member_code', $userInfo->member_code)
        ->where('db_status', 'live')
        ->where(function ($query) use ($operator) {
            $query->where('operator', $operator)
                  ->orWhere('operator', 'LIKE', "%{$operator}%");
        })
        ->whereIn('operating_status', [1, 3])
        ->whereIn('operator_service', ['on', $operator])
        ->select('updated_at', 'up_time')
        ->get();

    if ($getModemsList->isNotEmpty()) {
        $time_check = time() - 10;
        foreach ($getModemsList as $item) {

            return true;

            // if ($item->up_time >= $time_check) {
            //     return true; // One active modem found
            // }
        }
    }

    return false; // None active or no modems
}

function getRandom($requestAmount, $operator)
{
    $filteredAgents = User::where('user_type', 'agent')
        ->where('status', 1)
        ->where('auto_active_agent', 1)
        ->pluck('id')
        ->toArray();

    $arrayAgent = [];

    foreach ($filteredAgents as $userId) {
        $agent = User::find($userId);
        if ($agent->balance > $requestAmount) {
            $arrayAgent[] = $userId;
        }
    }

    $storeTempNumber = [];
    $countNumber = count($arrayAgent);

    if ($countNumber > 0) {
        while (count($storeTempNumber) < $countNumber) {
            $availableKeys = array_diff(array_keys($arrayAgent), $storeTempNumber);
            if (empty($availableKeys)) {
                return null;
            }

            $randomKey = array_rand($availableKeys);
            $randomUserId = $arrayAgent[$randomKey];

            // Log::info("Checking agent ID: " . $randomUserId);

            if (gettingModemStatus($randomUserId, $operator)) {
                // Log::info("Active modem found for agent ID: " . $randomUserId);
                return $randomUserId;
            }

            $storeTempNumber[] = $randomKey;
        }

        return null; // All checked but no active modem found
    }

    return null; // No eligible agents
}


function getModemLive($sim_id)
{
    $getTime = Modem::where('sim_number', $sim_id)
        ->whereIn('operating_status', [2, 3])
        ->first()->up_time;

    return $getTime;
}

function findAgentBalance($userId)
{
    $user = User::find($userId);
    $sumOfCreditAmount = PaymentRequest::where('agent', $user->member_code)
        ->whereIn('status', [1, 2])
        ->sum('amount');

    $totalPendingPayment = PaymentRequest::where('agent', $user->member_code)
        ->whereIn('status', [0])
        ->sum('amount');

    $sumOfDebitAmount = ServiceRequest::where('agent_id', $userId)
        ->whereIn('status', [0, 1, 2, 3, 5, 6])
        ->sum('amount');

    $adminCreditAmount = DB::table('transactions')->where('user_type', 'agent')->where('user_id', $userId)->where('status', 2)->where('wallet_type', 'admin')->where('trx_type', 'credit')->sum('amount');
    $adminDebitAmount = DB::table('transactions')->where('user_type', 'agent')->where('user_id', $userId)->where('status', 2)->where('wallet_type', 'admin')->where('trx_type', 'debit')->sum('amount');

    // $sumOfCreditAmount = Transaction::where('user_id', $userId)->where('user_type', $userType)->where('trx_type', 'credit')->where('status', '2')->sum('amount');
    // $sumOfDebitAmount = Transaction::where('user_id', $userId)->where('user_type', $userType)->where('trx_type', 'debit')->where('status', '2')->sum('amount');

    $totalPendingMfs = ServiceRequest::where('agent_id', $userId)
        ->whereIn('status', [0, 1, 5, 6])
        ->sum('amount');

    $mainBalance = $sumOfCreditAmount - $sumOfDebitAmount + $adminCreditAmount - $adminDebitAmount;

    // dd($sumOfCreditAmount);

    $data = [
        'sumOfCreditAmount' => $sumOfCreditAmount ? $sumOfCreditAmount : 0.0,
        'sumOfDebitAmount' => $sumOfDebitAmount ? $sumOfDebitAmount : 0.0,
        'mainBalance' => $mainBalance ? $mainBalance : 0.0,
        'adminCreditAmount' => $adminCreditAmount,
        'adminDebitAmount' => $adminDebitAmount,
        'totalPendingMfs' => $totalPendingMfs,
        'totalPendingPayment' => $totalPendingPayment,
    ];

    return $data;
}

function getSimInfo($modem_id)
{
    $simInfo = Modem::where('id', $modem_id)->first()->sim_number;
    if ($simInfo) {
        return $simInfo;
    }
}

function getMerchantBalance($merchantId)
{
    $paymentTotalAmount = PaymentRequest::where('merchant_id', $merchantId)
        ->whereIn('status', [1, 2])
        ->sum('merchant_main_amount');


    $totalPendingPayment = PaymentRequest::where('merchant_id', $merchantId)
        ->whereIn('status', [0])
        ->sum('merchant_main_amount');

    $totalPendingMfs = ServiceRequest::where('merchant_id', $merchantId)
        ->whereIn('status', [0, 1, 5, 6])
        ->sum('merchant_main_amount');

    $serviceRequestTotalAmount = ServiceRequest::where('merchant_id', $merchantId)
        ->whereIn('status', [0, 1, 2, 3, 6])
        ->sum('merchant_main_amount');

    $serviceRequestTotalCount = ServiceRequest::where('merchant_id', $merchantId)
        ->whereIn('status', [0, 1, 2, 3, 6])
        ->count();

    $adminCreditAmount = DB::table('transactions')->where('user_type', 'merchant')->where('user_id', $merchantId)->where('status', 2)->where('wallet_type', 'admin')->where('trx_type', 'credit')->sum('amount');
    $adminDebitAmount = DB::table('transactions')->where('user_type', 'merchant')->where('user_id', $merchantId)->where('status', 2)->where('wallet_type', 'admin')->where('trx_type', 'debit')->sum('amount');

    $subMerchantCreditAmount = DB::table('transactions as tr')
        ->join('merchants as mr', 'tr.user_id','=','mr.id')
        ->where('mr.create_by', $merchantId)
        ->where('user_type', 'sub_merchant')
        ->where('tr.status', 2)
        ->where('wallet_type', 'admin')
        ->where('trx_type', 'credit')
        ->sum('amount');



     $subMerchantDebitAmount = DB::table('transactions as tr')
        ->join('merchants as mr', 'tr.user_id','=','mr.id')
        ->where('mr.create_by', $merchantId)
        ->where('user_type', 'sub_merchant')
        ->where('tr.status', 2)
        ->where('wallet_type', 'admin')
        ->where('trx_type', 'debit')
        ->sum('amount');

        $subMerchantCreditAmountMerchant = DB::table('transactions as tr')
        ->join('merchants as mr', 'tr.user_id','=','mr.id')
        ->where('mr.create_by', $merchantId)
        ->where('user_type', 'sub_merchant')
        ->where('tr.status', 2)
        ->where('wallet_type', 'merchant')
        ->where('creator_type', 'merchant')
        ->where('trx_type', 'credit')
        ->sum('amount');

        $subMerchantDebitAmountMerchant = DB::table('transactions as tr')
        ->join('merchants as mr', 'tr.user_id','=','mr.id')
        ->where('mr.create_by', $merchantId)
        ->where('user_type', 'sub_merchant')
        ->where('tr.status', 2)
        ->where('wallet_type', 'merchant')
        ->where('creator_type', 'merchant')
        ->where('trx_type', 'debit')
        ->sum('amount');

    // $adminDebitAmount = DB::table('transactions as tr')
    // ->join('merchants as mr', 'tr.user_id','=','mr.id')
    // ->where('mr.create_by', $merchantId)
    // ->where('user_type', 'sub_merchant')
    // ->where('tr.status', 2)
    // ->where('wallet_type', 'merchant')
    // ->where('trx_type', 'debit')
    // ->sum('amount');


    $balance = $paymentTotalAmount + $adminCreditAmount - $adminDebitAmount - $serviceRequestTotalAmount +$subMerchantCreditAmount -  $subMerchantDebitAmount;
    $availableBalance =  $paymentTotalAmount - $serviceRequestTotalAmount + $adminCreditAmount - $adminDebitAmount - $subMerchantCreditAmountMerchant + $subMerchantDebitAmountMerchant  ;

    $data = [
        'balance' => $balance ?? 0.0,
        'paymentTotalAmount' => $paymentTotalAmount ?? 0.0,
        'serviceRequestTotalAmount' => $serviceRequestTotalAmount ?? 0.0,
        'adminCreditAmount' => $adminCreditAmount ?? 0.0,
        'adminDebitAmount' => $adminDebitAmount ?? 0.0,
        'totalPendingPayment' => $totalPendingPayment,
        'totalPendingMfs' => $totalPendingMfs,
        'serviceRequestTotalCount' => $serviceRequestTotalCount,
        'availableBalance'=>$availableBalance
    ];

    return $data;

    // if($balance){
    //     return $balance ?;
    // }
    // return 0;
}

function subMerchantBalance($merchantId)
{
    $paymentTotalAmount = PaymentRequest::where('sub_merchant', $merchantId)
        ->whereIn('status', [1, 2])
        ->sum('amount');

    $totalPendingPayment = PaymentRequest::where('sub_merchant', $merchantId)
        ->whereIn('status', [0])
        ->sum('amount');

    $totalPendingMfs = ServiceRequest::where('sub_merchant', $merchantId)
        ->whereIn('status', [0, 1, 5, 6])
        ->sum('amount');

    $serviceRequestTotalAmount = ServiceRequest::where('sub_merchant', $merchantId)
        ->whereIn('status', [0, 1, 2, 3, 6])
        ->sum('amount');

    $serviceRequestTotalCount = ServiceRequest::where('sub_merchant', $merchantId)
        ->whereIn('status', [0, 1, 2, 3, 6])
        ->count();
    $adminCreditAmount  = DB::table('transactions')->where('user_type', 'sub_merchant')->where('user_id', $merchantId)->where('status', 2)->where('trx_type', 'credit')->sum('amount');
    $adminDebitAmount = DB::table('transactions')->where('user_type', 'sub_merchant')->where('user_id', $merchantId)->where('status', 2)->where('trx_type', 'debit')->sum('amount');

    $balance = $paymentTotalAmount + $adminCreditAmount - $adminDebitAmount - $serviceRequestTotalAmount;

    $data = [
        'balance' => $balance ?? 0.0,
        'paymentTotalAmount' => $paymentTotalAmount ?? 0.0,
        'serviceRequestTotalAmount' => $serviceRequestTotalAmount ?? 0.0,
        'adminCreditAmount' => $adminCreditAmount ?? 0.0,
        'adminDebitAmount' => $adminDebitAmount ?? 0.0,
        'totalPendingPayment' => $totalPendingPayment,
        'totalPendingMfs' => $totalPendingMfs,
        'serviceRequestTotalCount' => $serviceRequestTotalCount,
    ];

    return $data;
}

function SendSMS($type, $membercode, $customer_number, $trxid, $amount, $sms_date)
{
    $member_info = User::where('member_code', $membercode)->first();
    $provider = SmsSetting::where('provider', 'sms_city')->first();

    $text = 'you have a' . $type . ' message please check and verify quickly Sender: bkash Customer number:' . $customer_number . ' TRXID:' . $trxid . ' amount: ' . $amount . ' date:' . $sms_date;

    $post_data = [
        'contact' => [
            [
                'number' => '88' . $member_info->mobile,
                'message' => $text,
            ],
        ],
    ];

    SMS_SEND($post_data);

    // $response = Http::withHeaders([
    //     'Api-key' => $provider->access_token,
    //     'Content-Type' => 'application/json',
    // ])->post('https://smscity.net/api/whatsapp/send', $post_data);
}

function SendCronPaymentSms($membercode, $method, $customer_number, $trxid, $amount)
{
    $member_info = User::where('member_code', $membercode)->first();
    $provider = SmsSetting::where('provider', 'sms_city')->first();

    $text = 'You have a Cashout pending on payment request. Please check and verify quickly. Method: ' . $method . ', TRXID: ' . $trxid . ', Amount: ' . $amount . '.';

    $post_data = [
        'contact' => [
            [
                'number' => $member_info->mobile,
                'message' => $text,
            ],
        ],
    ];

    SMS_SEND($post_data);

    // $response = Http::withHeaders([
    //     'Api-key' => $provider->access_token,
    //     'Content-Type' => 'application/json',
    // ])->post('https://smscity.net/api/whatsapp/send', $post_data);
}

function sendServiceRequestSms($phone, $method, $amount)
{
    $admin = Admin::first();
    $provider = SmsSetting::where('provider', 'sms_city')->first();

    if ($admin && $admin->mobile) {
        $text = 'You have a pending ' . $method . ' request. Please check and verify quickly. Number: ' . $phone . ', Amount: ' . $amount . '.';

        $post_data = [
            'contact' => [
                [
                    'number' => $admin->mobile,
                    'message' => $text,
                ],
            ],
        ];

        SMS_SEND($post_data);
    }
}

function sendServiceRequestSmsAgent($phone, $method, $amount, $agentNumber)
{
    $text = 'You have a pending ' . $method . ' request. Please check and verify quickly. Number: ' . $phone . ', Amount: ' . $amount . '.';

    $post_data = [
        'contact' => [
            [
                'number' => $agentNumber,
                'message' => $text,
            ],
        ],
    ];

    SMS_SEND($post_data);

    // $response = Http::withHeaders([
    //     'Api-key' => $provider->access_token,
    //     'Content-Type' => 'application/json',
    // ])->post('https://smscity.net/api/whatsapp/send', $post_data);
}

// function SMS_SEND($post_data)
// {
//     $provider = SmsSetting::where('provider', 'sms_city')->first();
//     $response = Http::withHeaders([
//         'Api-key' => $provider->access_token,
//         'Content-Type' => 'application/json',
//     ])->post('https://smscity.net/api/whatsapp/send', $post_data);
// }

function SMS_SEND($post_data)
{
    try {
        $provider = SmsSetting::where('provider', 'sms_city')->first();

        if (!$provider) {
            return 1; // Skip if provider not found
        }

        $response = Http::withHeaders([
            'Api-key' => $provider->access_token,
            'Content-Type' => 'application/json',
        ])->post('https://smscity.net/api/whatsapp/send', $post_data);
    } catch (\Exception $e) {
        // You can optionally log the error here if needed
        // Log::error('SMS send error: ' . $e->getMessage());
    }

    return 1;
}

function adminBalance()
{
    $totalPendingPayment = PaymentRequest::whereNotNull('payment_method')
        ->whereNotNull('payment_method_trx')
        ->whereIn('status', [0])
        ->sum('amount');
    $totalPendingMfs = ServiceRequest::whereIn('status', [0, 1, 5, 6])->sum('amount');

    return [
        'totalPendingPayment' => floatval($totalPendingPayment),
        'totalPendingMfs' => $totalPendingMfs,
    ];
}

function activeModem()
{
    $paymentTime = time() - 10;

    // Fetch only the modems that are online directly in the query
    $onlineModems = Modem::where('status', 1)->where('operating_status', '!=', '0')->where('operator_service', '!=', 'off')->where('db_status', 'live')->whereNotNull('operator')->where('up_time', '>=', $paymentTime)->get();

    $onlineCount = $onlineModems->count();


    return [
        'online_count' => $onlineCount,
        'online_modems' => $onlineModems,
    ];
}

function activeMfsApi()
{
    try {
        $response = Http::withToken(BalanceManagerConstant::token_key)
            ->get(BalanceManagerConstant::URL . '/api/available-methods');

        if ($response->successful()) {
            return $response->json();
        }
    } catch (\Exception $e) {
        Log::error('Failed to fetch MFS API: ' . $e->getMessage());
    }

    return [];
}

// function dsoBalance($dsoId=null){

//     $agents = User::where('dso', $get_user->id)
//             ->where('user_type', 'agent')
//             ->get();

// }

// function allAgentBalance()
// {
//     $sumOfCreditAmount = PaymentRequest::whereNotNull('agent')
//         ->whereIn('status', [1, 2])
//         ->sum('amount');

//     $totalPendingPayment = PaymentRequest::whereNotNull('agent')
//         ->whereIn('status', [0])
//         ->sum('amount');

//     $sumOfDebitAmount = ServiceRequest::whereNotNull('agent_id')
//         ->whereIn('status', [0, 1, 2, 3, 5, 6])
//         ->sum('amount');

//     $adminCreditAmount = DB::table('transactions')->where('user_type', 'agent')->where('status', 2)->where('wallet_type', 'admin')->where('trx_type', 'credit')->sum('amount');
//     $adminDebitAmount = DB::table('transactions')->where('user_type', 'agent')->where('status', 2)->where('wallet_type', 'admin')->where('trx_type', 'debit')->sum('amount');

//     // $sumOfCreditAmount = Transaction::where('user_id', $userId)->where('user_type', $userType)->where('trx_type', 'credit')->where('status', '2')->sum('amount');
//     // $sumOfDebitAmount = Transaction::where('user_id', $userId)->where('user_type', $userType)->where('trx_type', 'debit')->where('status', '2')->sum('amount');

//     // $totalPendingMfs = ServiceRequest::where('agent_id', $userId)
//     //     ->whereIn('status', [0, 1, 5, 6])
//     //     ->sum('amount');

//     $allAgentBalance = $sumOfCreditAmount - $sumOfDebitAmount + $adminCreditAmount - $adminDebitAmount;

//     return ['allAgentBalance' => $allAgentBalance, 'sumOfCreditAmount' => $sumOfCreditAmount, 'sumOfDebitAmount' => $sumOfDebitAmount, 'adminCreditAmount' => $adminCreditAmount, 'adminDebitAmount' => $adminDebitAmount];

//     // return  $allAgentBalance;
// }

function allAgentBalance()
{
    // ==== All Time ====
    $sumOfCreditAmount = PaymentRequest::whereNotNull('agent')
        ->whereIn('status', [1, 2])
        ->sum('amount');

    $totalPendingPayment = PaymentRequest::whereNotNull('agent')
        ->whereIn('status', [0])
        ->sum('amount');

    $sumOfDebitAmount = ServiceRequest::whereNotNull('agent_id')
        ->whereIn('status', [0, 1, 2, 3, 5, 6])
        ->sum('amount');

    $adminCreditAmount = DB::table('transactions')
        ->where('user_type', 'agent')
        ->where('status', 2)
        ->where('wallet_type', 'admin')
        ->where('trx_type', 'credit')
        ->sum('amount');

    $adminDebitAmount = DB::table('transactions')
        ->where('user_type', 'agent')
        ->where('status', 2)
        ->where('wallet_type', 'admin')
        ->where('trx_type', 'debit')
        ->sum('amount');

    $allAgentBalance = $sumOfCreditAmount - $sumOfDebitAmount + $adminCreditAmount - $adminDebitAmount;

    // ==== Today ====
    $sumOfCreditAmountToday = PaymentRequest::whereNotNull('agent')
        ->whereIn('status', [1, 2])
        ->whereDate('created_at', Carbon::today())
        ->sum('amount');

    $totalPendingPaymentToday = PaymentRequest::whereNotNull('agent')
        ->whereIn('status', [0])
        ->whereDate('created_at', Carbon::today())
        ->sum('amount');

    $sumOfDebitAmountToday = ServiceRequest::whereNotNull('agent_id')
        ->whereIn('status', [0, 1, 2, 3, 5, 6])
        ->whereDate('created_at', Carbon::today())
        ->sum('amount');

    $adminCreditAmountToday = DB::table('transactions')
        ->where('user_type', 'agent')
        ->where('status', 2)
        ->where('wallet_type', 'admin')
        ->where('trx_type', 'credit')
        ->whereDate('created_at', Carbon::today())
        ->sum('amount');

    $adminDebitAmountToday = DB::table('transactions')
        ->where('user_type', 'agent')
        ->where('status', 2)
        ->where('wallet_type', 'admin')
        ->where('trx_type', 'debit')
        ->whereDate('created_at', Carbon::today())
        ->sum('amount');

    $todayAgentBalance = $sumOfCreditAmountToday - $sumOfDebitAmountToday + $adminCreditAmountToday - $adminDebitAmountToday;

    return compact(
        'allAgentBalance',
        'sumOfCreditAmount',
        'sumOfDebitAmount',
        'adminCreditAmount',
        'adminDebitAmount',
        'totalPendingPayment',

        'todayAgentBalance',
        'sumOfCreditAmountToday',
        'sumOfDebitAmountToday',
        'adminCreditAmountToday',
        'adminDebitAmountToday',
        'totalPendingPaymentToday'
    );
}

// function allMerchantBalance()
// {
//     $paymentTotalAmount = PaymentRequest::whereNotNull('merchant_id')
//         ->whereIn('status', [1, 2])
//         ->sum('amount');

//     $totalPendingPayment = PaymentRequest::whereNotNull('merchant_id')
//         ->whereIn('status', [0])
//         ->sum('amount');

//     // $totalPendingMfs = ServiceRequest::where('merchant_id', $merchantId)
//     //     ->whereIn('status', [0, 1, 5, 6])
//     //     ->sum('amount');

//     $serviceRequestTotalAmount = ServiceRequest::whereNotNull('merchant_id')
//         ->whereIn('status', [0, 1, 2, 3, 6])
//         ->sum('amount');

//     // $serviceRequestTotalCount = ServiceRequest::where('merchant_id', $merchantId)
//     //     ->whereIn('status', [0, 1, 2, 3, 6])
//     //     ->count();

//     $adminCreditAmount = DB::table('transactions')->where('user_type', 'merchant')->where('status', 2)->where('wallet_type', 'admin')->where('trx_type', 'credit')->sum('amount');
//     $adminDebitAmount = DB::table('transactions')->where('user_type', 'merchant')->where('status', 2)->where('wallet_type', 'admin')->where('trx_type', 'debit')->sum('amount');

//     $balance = $paymentTotalAmount + $adminCreditAmount - $adminDebitAmount - $serviceRequestTotalAmount;

//     return ['balance' => $balance, 'paymentTotalAmount' => $paymentTotalAmount, 'adminCreditAmount' => $adminCreditAmount, 'adminDebitAmount' => $adminDebitAmount, 'serviceRequestTotalAmount' => $serviceRequestTotalAmount];

//     // return  $balance;
// }



function allMerchantBalance()
{
    // ===== Overall =====
    $paymentTotalAmount = PaymentRequest::whereNotNull('merchant_id')
        ->whereIn('status', [1, 2])
        ->sum('amount');

    $totalPendingPayment = PaymentRequest::whereNotNull('merchant_id')
        ->whereIn('status', [0])
        ->sum('amount');

    $serviceRequestTotalAmount = ServiceRequest::whereNotNull('merchant_id')
        ->whereIn('status', [0, 1, 2, 3, 6])
        ->sum('amount');

    $adminCreditAmount = DB::table('transactions')
        ->where('user_type', 'merchant')
        ->where('status', 2)
        ->where('wallet_type', 'admin')
        ->where('trx_type', 'credit')
        ->sum('amount');

    $adminDebitAmount = DB::table('transactions')
        ->where('user_type', 'merchant')
        ->where('status', 2)
        ->where('wallet_type', 'admin')
        ->where('trx_type', 'debit')
        ->sum('amount');

    $balance = $paymentTotalAmount + $adminCreditAmount - $adminDebitAmount - $serviceRequestTotalAmount;


    // ===== Today =====
    $todayPaymentTotalAmount = PaymentRequest::whereNotNull('merchant_id')
        ->whereIn('status', [1, 2])
        ->whereDate('created_at', Carbon::today())
        ->sum('amount');

    $todayServiceRequestTotalAmount = ServiceRequest::whereNotNull('merchant_id')
        ->whereIn('status', [0, 1, 2, 3, 6])
        ->whereDate('created_at', Carbon::today())
        ->sum('amount');

    $todayAdminCreditAmount = DB::table('transactions')
        ->where('user_type', 'merchant')
        ->where('status', 2)
        ->where('wallet_type', 'admin')
        ->where('trx_type', 'credit')
        ->whereDate('created_at', Carbon::today())
        ->sum('amount');

    $todayAdminDebitAmount = DB::table('transactions')
        ->where('user_type', 'merchant')
        ->where('status', 2)
        ->where('wallet_type', 'admin')
        ->where('trx_type', 'debit')
        ->whereDate('created_at', Carbon::today())
        ->sum('amount');

    $todayBalance = $todayPaymentTotalAmount + $todayAdminCreditAmount - $todayAdminDebitAmount - $todayServiceRequestTotalAmount;


    return [
        'balance' => $balance,
        'paymentTotalAmount' => $paymentTotalAmount,
        'adminCreditAmount' => $adminCreditAmount,
        'adminDebitAmount' => $adminDebitAmount,
        'serviceRequestTotalAmount' => $serviceRequestTotalAmount,

        'todayBalance' => $todayBalance,
        'todayPaymentTotalAmount' => $todayPaymentTotalAmount,
        'todayAdminCreditAmount' => $todayAdminCreditAmount,
        'todayAdminDebitAmount' => $todayAdminDebitAmount,
        'todayServiceRequestTotalAmount' => $todayServiceRequestTotalAmount,
    ];
}




//Get Modem Details from service request;
function modemDetails($srId)
{
    $findServiceRequest = ServiceRequest::find($srId);
    $modem = Modem::find($findServiceRequest->modem_id)->sim_number;
    return $modem;
}

function partnerBalance($id = null)
{
    // If $id is provided, find the partner by $id, else get the authenticated partner
    $partner = $id ? User::find($id) : auth('web')->user();

    // Find agents related to the partner
    $findPartnersAgent = User::where('user_type', 'agent')
        ->where('partner', $partner->id)
        ->get();
    $agentMemberCodes = $findPartnersAgent->pluck('member_code')->toArray();
    $agentIDs = $findPartnersAgent->pluck('id')->toArray();

    // Calculate total payment requests
    $total_payment_request = App\Models\PaymentRequest::whereIn('agent', $agentMemberCodes)
        ->whereIn('status', [1, 2])
        ->sum('amount');

    $total_payment_request_today = App\Models\PaymentRequest::whereIn('agent', $agentMemberCodes)
        ->whereIn('status', [1, 2])
        ->whereDate('created_at', now())
        ->sum('amount');

    $total_payment_request_transaction = App\Models\PaymentRequest::whereIn('agent', $agentMemberCodes)->count();

    // Calculate total MFS requests
    $total_mfs_request = App\Models\ServiceRequest::whereIn('agent_id', $agentIDs)
        ->whereIn('status', [2, 3])
        ->sum('amount');

    $today_total_mfs_request = App\Models\ServiceRequest::whereIn('agent_id', $agentIDs)
        ->whereIn('status', [2, 3])
        ->whereDate('created_at', now())
        ->sum('amount');

    $total_mfs_transaction = App\Models\ServiceRequest::whereIn('agent_id', $agentIDs)->count();

    // Initialize the data array
    $data = [
        'sumOfCreditAmount' => 0.0,
        'sumOfDebitAmount' => 0.0,
        'mainBalance' => 0.0,
        'adminCreditAmount' => 0.0,
        'adminDebitAmount' => 0.0,
        'totalPendingMfs' => 0.0,
        'totalPendingPayment' => 0.0,
    ];

    // Loop through each agent to calculate their balances
    foreach ($findPartnersAgent as $agent) {
        $balance = findAgentBalance($agent->id);
        $data['sumOfCreditAmount'] += $balance['sumOfCreditAmount'];
        $data['sumOfDebitAmount'] += $balance['sumOfDebitAmount'];
        $data['mainBalance'] += $balance['mainBalance'];
        $data['adminCreditAmount'] += $balance['adminCreditAmount'];
        $data['adminDebitAmount'] += $balance['adminDebitAmount'];
        $data['totalPendingMfs'] += $balance['totalPendingMfs'];
        $data['totalPendingPayment'] += $balance['totalPendingPayment'];
    }

    // Assign the totals to the data array
    $data['total_payment_request'] = $total_payment_request;
    $data['total_payment_request_today'] = $total_payment_request_today;
    $data['total_payment_request_transaction'] = $total_payment_request_transaction;
    $data['total_mfs_request'] = $total_mfs_request;
    $data['today_total_mfs_request'] = $today_total_mfs_request;
    $data['total_mfs_transaction'] = $total_mfs_transaction;

    // Return the data array
    return $data;
}

function myDd($var)
{
    dd($var);
}

// function listOfOp()
// {
//     $onlineCheckingTime = env('PAYMENT_TIME'); // Cast to integer
//     $data = []; // Initialize the data array

//     // Loop through each operator name returned by getOpNameList
//     foreach (getOpNameList() as $operator) {
//         // Fetch the list of SIM IDs for the current operator
//         $modemServiceList = DB::table('modems')
//             ->whereIn('operating_status', [2, 3])
//             ->whereIn('operator_service', ['on', $operator])
//             ->where('up_time', '>=', time() - $onlineCheckingTime)
//             ->where('operator', 'LIKE', '%' . $operator . '%') // Use 'LIKE' with wildcards
//             ->pluck('sim_id')
//             ->toArray();

//         if ($operator == 'bkash') {
//             $imagePath = asset('payments/bkash.png');
//         } elseif ($operator == 'nagad') {
//             $imagePath = asset('payments/nagad.png');
//         }

//         // Prepare an associative array for each operator
//         $data[] = [
//             'payment_method' => $operator, // Set operator name
//             'sim_number' => $modemServiceList[array_rand($modemServiceList)], // Add associated SIM IDs
//             'icon' => $imagePath,
//         ];
//     }

//     return $data; // Return the final array
// }






function checkAttempt($trxid, $amount, $payment_method)
{
    // return true;

    $existingRecord = DB::table('attempt_counts')->where('transaction_id', $trxid)->where('amount', $amount)->where('operator', $payment_method)->first();

    // If the record exists
    if ($existingRecord) {
        // Check if the counter is less than 3
        if ($existingRecord->counter < 1) {
            // Update the counter
            DB::table('attempt_counts')
                ->where('id', $existingRecord->id) // Use the existing record's ID
                ->update(['counter' => $existingRecord->counter + 1, 'updated_at' => now()]);

            return false; // Return false if not yet reached max attempts
        } else {
            // If counter reaches 3, delete the record
            DB::table('attempt_counts')
                ->where('id', $existingRecord->id)
                ->delete();

            return true; // Return true to indicate data should be inserted
        }
    } else {
        // If no record exists, insert a new record
        DB::table('attempt_counts')->insert([
            'transaction_id' => $trxid,
            'amount' => $amount,
            'operator' => $payment_method,
            'counter' => 1,
        ]);

        return false; // Return false as this is the first attempt
    }
}

function deleteAttempt($trxid, $amount, $payment_method)
{
    $existingRecord = DB::table('attempt_counts')->where('transaction_id', $trxid)->where('amount', $amount)->where('operator', $payment_method)->delete();

    return true;
}

function getPartnerFromAgent($memberCode)
{
    $findAgent = User::where('member_code', $memberCode)->first();

    if (!$findAgent || !$findAgent->partner) {
        return null;
    }

    return User::find($findAgent->partner);
}


function responseV2()
{
    return $possibleStatus = [
        [
            'status' => 'success',
            'name' => 'Transaction Success',
            'http_status_code' => 200,
            'description' => 'The transaction was successfully completed.',
        ],
        [
            'status' => 'pending',
            'name' => 'Transaction Pending',
            'http_status_code' => 200,
            'description' => 'The transaction is currently being processed.',
        ],
        [
            'status' => false,
            'name' => 'Missing Agent',
            'http_status_code' => 400,
            'description' => 'Agent information is missing or not found.',
        ],
        [
            'status' => false,
            'name' => 'Transaction ID Not Found',
            'http_status_code' => 404,
            'description' => 'The provided Transaction ID does not exist.',
        ],
        [
            'status' => 'error',
            'name' => 'Validation Error',
            'http_status_code' => 422,
            'description' => 'The request failed validation checks.',
        ],
        [
            'status' => 'error',
            'name' => 'Server Error',
            'http_status_code' => 520,
            'description' => 'An unexpected server error occurred.',
        ],
    ];
}
function sendFCMNotificationAgent($token)
{
    // Initialize the Firebase Factory

  return false;

    try{

    $factory = (new Factory())->withServiceAccount('ipaybd-b43fc-firebase-adminsdk-um1ii-5028ed129d.json'); // Update with the correct path

    // Create a Messaging instance
    $messaging = $factory->createMessaging();
    //$token = "fW5mQiljTjiIJzvqVSoIra:APA91bEpvZC2JFeRxbNIZz9qs8ghwJz9Jjzc6RQJvwYPaXSl7vhT0n1L_IYdBl-tWJmnLbepQeLXcRg4YA13VCoIgJeRMVs4MBJ_5_Yc4BfOtQVeld1vhuQKHIPxHzwsEOF9l0va46KZ";
    // Prepare the message
    $message = CloudMessage::withTarget('token', $token) // Use the passed $token instead of hardcoding
        ->withNotification(MessagingNotification::create('New Request', 'You have a new request pending'))
        ->withData([
            'custom_key' => 'custom_value'
        ])
        ->withAndroidConfig([
            'priority' => 'high',
            'notification' => [
                'sound' => 'default', // Play default sound on notification
                'channel_id' => 'alert_channel', // Use custom notification channel
            ],
        ]);

    // Send the message
    try {
        $output = $messaging->send($message);
        return $output; // Return the output for further processing
    } catch (\Kreait\Firebase\Exception\MessagingException $e) {
        // Handle exceptions if sending fails

        Log::error($e);
        // return response()->json(['status' => 'false', 'message' => 'Notification not sent: ' . $e->getMessage()], 500);
    }
    }catch (\Exception $e){
        return 1;

    }
}

function checkMerchantCode()
{
    do {
        $rand = rand(1111, 9999);
        $exists = Merchant::where('username', $rand)->exists();
    } while ($exists);

    return $rand;
}


function getPaymentMethodList()
{


    $paymentMethods = PaymentMethod::where('status', 1)->get();

    $all_operators = [];
    foreach ($paymentMethods as $row) {
            $all_operators = $row->mfs_operator()->first();
    }

    // Get unique operators using array_unique()
    $distinct_operators = ($all_operators);

    // dd($distinct_operators);

    return $distinct_operators;
}

function normalizeAmount($amount) {
    // Remove all non-numeric characters (commas, spaces, 'tk', etc.)
    return preg_replace('/[^0-9]/', '', $amount);
}


     function checkTransaction($trxId)
{
    $url = BalanceManagerConstant::URL . "/api/v2/transaction/{$trxId}";

    $token = BalanceManagerConstant::token_key;

    try {
        $response = Http::withToken($token)->get($url);

        if ($response->successful()) {
            $json = $response->json();

            if ($json['success'] === true && isset($json['data'])) {
                return [
                    'status' => 'success',
                    'message' => 'Transaction verified successfully',
                    'data' => $json['data'],
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => $json['message'] ?? 'Transaction not found',
                ];
            }
        } else {
            return [
                'status' => 'error',
                'message' => 'API request failed',
                'details' => $response->body(),
                'code' => $response->status(),
            ];
        }

    } catch (\Exception $e) {
        return [
            'status' => 'error',
            'message' => 'Exception during API request',
            'error' => $e->getMessage(),
        ];
    }
}

function checkAdminGuardPermission($permission){

    return auth('admin')->user()->can($permission);

}

function merchantWebHook($reference)
{
    Log::info($reference);
    $data = PaymentRequest::select(
            'request_id',
            'amount',
            'payment_method',
            'reference',
            'cust_name',
            'cust_phone',
            'note',
            'reject_msg',
            'payment_method_trx',
            'status',
            'callback_url',
            'webhook_url'
        )
        ->selectRaw("
            CASE
                WHEN status = 0 THEN 'pending'
                WHEN status IN (1, 2) THEN 'completed'
                WHEN status = 3 THEN 'rejected'
                ELSE 'unknown'
            END AS status_name
        ")
        ->where('reference', $reference)
        ->first();

    if (!$data) {
        Log::warning("PaymentRequest not found", ['reference' => $reference]);
        return null;
    }

    // Only send callback for completed or rejected payments
    if (!in_array($data->status, [1, 2, 3])) {
        Log::info("PaymentRequest status not eligible for callback", [
            'reference' => $reference,
            'status'    => $data->status
        ]);
        return null;
    }

    // Build payload
    $payload = [
        "status" => "true",
        "data" => [
            "request_id"         => $data->request_id,
            "amount"             => $data->amount,
            "payment_method"     => $data->payment_method,
            "reference"          => $data->reference,
            "cust_name"          => $data->cust_name,
            "cust_phone"         => $data->cust_phone,
            "note"               => $data->note,
            "reject_msg"         => $data->reject_msg,
            "payment_method_trx" => $data->payment_method_trx,
            "status"             => (string) $data->status,
            "status_name"        => $data->status_name,
        ]
    ];

    $client = new Client();

    // Build callback URL list (clean & unique)
    $callbackUrls = array_filter(array_unique([
        $data->callback_url ? rtrim($data->callback_url, '/') : null,
        'https://ibotbd.com/api/webhook',
        $data->webhook_url ? rtrim($data->webhook_url, '/') : null,
    ]));

    foreach ($callbackUrls as $url) {

        // Validate URL before sending
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            Log::warning("Invalid callback URL skipped", ['url' => $url]);
            continue;
        }

        try {

            Log::info("Sending Merchant Callback", [
                'url'     => $url,
                'payload' => $payload
            ]);

            $response = $client->post($url, [
                'json' => $payload,
                'headers' => [
                    'Accept'       => 'application/json',
                    'Content-Type' => 'application/json',
                    'User-Agent'   => 'PaymentGatewayCallback/1.0'
                ],
                'timeout' => 10,
            ]);

            Log::info("Merchant Callback Success", [
                'url'        => $url,
                'statusCode' => $response->getStatusCode(),
            ]);

        } catch (\Exception $e) {

            Log::error("Merchant Callback Failed", [
                'url'     => $url,
                'message' => $e->getMessage()
            ]);

            // Continue to next URL
            continue;
        }
    }

    return true;
}






// function merchantWebHook($reference)
// {
//     $data = PaymentRequest::select(
//             'request_id',
//             'amount',
//             'payment_method',
//             'reference',
//             'cust_name',
//             'cust_phone',
//             'note',
//             'reject_msg',
//             'payment_method_trx',
//             'status',
//             'callback_url'
//         )
//         ->selectRaw("
//             CASE
//                 WHEN status = 0 THEN 'pending'
//                 WHEN status IN (1, 2) THEN 'completed'
//                 WHEN status = 3 THEN 'rejected'
//                 ELSE 'unknown'
//             END as status_name
//         ")
//         ->where('reference', $reference)
//         ->first();

//     if (!$data) {
//         Log::warning("PaymentRequest not found", ['reference' => $reference]);
//         return null;
//     }

//     // Only send callback if status is completed or rejected
//     if (!in_array($data->status, [1, 2, 3])) {
//         Log::info("PaymentRequest status not eligible for callback", ['status' => $data->status]);
//         return null;
//     }

//     $payload = [
//         "status" => "true",
//         "data" => [
//             "request_id"         => $data->request_id,
//             "amount"             => $data->amount,
//             "payment_method"     => $data->payment_method,
//             "reference"          => $data->reference,
//             "cust_name"          => $data->cust_name,
//             "cust_phone"         => $data->cust_phone,
//             "note"               => $data->note,
//             "reject_msg"         => $data->reject_msg,
//             "payment_method_trx" => $data->payment_method_trx,
//             "status"             => (string) $data->status,
//             "status_name"        => $data->status_name,
//         ]
//     ];

//     $client = new Client();

//     // List of callback URLs (merchant + ibotbd)
//     $callbackUrls = [
//         rtrim($data->callback_url, '/'),
//         'https://ibotbd.com/api/webhook',
//         rtrim($data->webhook_url, '/')
//     ];

//     foreach ($callbackUrls as $url) {
//         try {
//             Log::info("Sending Merchant Callback", ['url' => $url, 'payload' => $payload]);

//             $response = $client->post($url, [
//                 'json' => $payload,
//                 'headers' => [
//                     'Accept' => 'application/json',
//                     'Content-Type' => 'application/json',
//                 ],
//                 'timeout' => 10, // prevent hanging requests
//             ]);

//             Log::info("Merchant Callback Success", [
//                 'url'        => $url,
//                 'statusCode' => $response->getStatusCode(),
//             ]);
//         } catch (\Exception $e) {
//             // Log error but DO NOT stop execution
//             Log::error("Merchant Callback Failed", [
//                 'url' => $url,
//                 'message' => $e->getMessage()
//             ]);
//             continue; // move on to next callback URL
//         }
//     }

//     // $webhook_url = [
//     //     rtrim($data->webhook_url, '/')
//     // ];

//     // foreach ($webhook_url as $url) {
//     //     try {
//     //         Log::info("Sending Merchant Callback", ['url' => $url, 'payload' => $payload]);

//     //         $response = $client->post($url, [
//     //             'json' => $payload,
//     //             'headers' => [
//     //                 'Accept' => 'application/json',
//     //                 'Content-Type' => 'application/json',
//     //             ],
//     //             'timeout' => 10, // prevent hanging requests
//     //         ]);

//     //         Log::info("Merchant Callback Success", [
//     //             'url'        => $url,
//     //             'statusCode' => $response->getStatusCode(),
//     //         ]);
//     //     } catch (\Exception $e) {
//     //         // Log error but DO NOT stop execution
//     //         Log::error("Merchant Callback Failed", [
//     //             'url' => $url,
//     //             'message' => $e->getMessage()
//     //         ]);
//     //         continue; // move on to next callback URL
//     //     }
//     // }

//     return true;
// }



function merchantWebHookWithdraw($id)
{
    $get_data = ServiceRequest::select(
        'number',
        'mfs',
        'old_balance',
        'amount',
        'new_balance',
        'msg',
        'status',
        'get_trxid',
        'webhook_url',
        'trxid'
    )->find($id);

    if (!$get_data || !$get_data->webhook_url || !in_array($get_data->status, [2, 3, 4])) {
        return null;
    }

    // Map status
    $make_status = match ($get_data->status) {
        2, 3 => 'success',
        4    => 'rejected',
        default => 'pending',
    };

    // Final payload
    $payload = [
        'status' => 'true',
        'data' => [
            'withdraw_number' => $get_data->number,
            'mfs_operator'    => $get_data->mfs,
            'amount'          => $get_data->amount,
            'msg'             => $make_status === 'rejected' ? $get_data->msg : $get_data->get_trxid,
            'status'          => $make_status,
            'withdraw_id'   =>$get_data->trxid,
        ]
    ];

    $client = new Client();
    $url = rtrim($get_data->webhook_url, '/');

    try {
        $response = $client->post($url, [
            'json' => $payload,
            'headers' => [
                'Accept'       => 'application/json',
                'Content-Type' => 'application/json',
            ],
            'timeout' => 10, // prevent hanging forever
        ]);

         Log::info('calling end withdraaw');


        return [
            'success' => true,
            'response' => (string) $response->getBody(),
        ];

    } catch (ConnectException $e) {
        // Network/connection issue
        \Log::error("Webhook connection failed: " . $e->getMessage());
        return ['success' => false, 'error' => 'Connection failed'];
    } catch (RequestException $e) {
        // HTTP request failed
        \Log::error("Webhook request failed: " . $e->getMessage());
        return ['success' => false, 'error' => 'Request failed'];
    } catch (\Exception $e) {
        // Any other unexpected error
        \Log::error("Webhook error: " . $e->getMessage());
        return ['success' => false, 'error' => 'Unexpected error'];
    }
}

if (!function_exists('getStringBetweenForMassage')) {
    function getStringBetweenForMassage($string, $start, $end)
    {
        if (!$string || !$start || !$end) {
            return '';
        }

        $string = ' ' . $string;
        $ini = strpos($string, $start);

        if ($ini === false) {
            return '';
        }

        $ini += strlen($start);
        $endPos = strpos($string, $end, $ini);

        if ($endPos === false) {
            return '';
        }

        return substr($string, $ini, $endPos - $ini);
    }
}

/**
 * Check if the current admin has a specific permission
 */
if (!function_exists('can')) {
    function can($permission)
    {
        $admin = Auth::guard('admin')->user();

        if (!$admin) {
            return false;
        }

        return $admin->hasPermission($permission);
    }
}

/**
 * Check if the current admin has any of the given permissions
 */
if (!function_exists('canAny')) {
    function canAny($permissions)
    {
        $admin = Auth::guard('admin')->user();

        if (!$admin) {
            return false;
        }

        return $admin->hasAnyPermission($permissions);
    }
}

/**
 * Check if the current admin has all of the given permissions
 */
if (!function_exists('canAll')) {
    function canAll($permissions)
    {
        $admin = Auth::guard('admin')->user();

        if (!$admin) {
            return false;
        }

        return $admin->hasAllPermissions($permissions);
    }
}
