<?php

use App\Helpers\BalanceManagerConstant;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

// function mfsList($access="manual")
// {
//     // Static list from your existing function
//     $staticList = listOfIbotOp();
//     $staticList = listOfIbotOpP2C();

//     // Fetch from external API
//     $dynamicList = fetchMfsApi();

//     // Create a map from the static list for easy access
//     $staticMap = collect($staticList)->keyBy(function ($item) {
//         return strtolower($item['deposit_method']);
//     });

//     // Create a map from the dynamic list
//     $dynamicMap = collect($dynamicList)->keyBy(function ($item) {
//         return strtolower($item['type']);
//     });

//     // List of all unique methods (e.g., bkash, nagad, rocket)
//     $allMethods = collect(array_merge($staticMap->keys()->toArray(), $dynamicMap->keys()->toArray()))->unique();

//     // Combine them randomly
//     $finalList = $allMethods
//         ->map(function ($method) use ($staticMap, $dynamicMap) {
//             $useStatic = rand(0, 1) === 1;

//             if ($useStatic && $staticMap->has($method)) {
//                 return $staticMap->get($method);
//             } elseif ($dynamicMap->has($method)) {
//                 return [
//                     'deposit_method' => strtolower($dynamicMap[$method]['type']),
//                     'deposit_number' => $dynamicMap[$method]['phone'],
//                     'icon' => 'https://ibotbd.com/payments/' . strtolower($dynamicMap[$method]['type']) . '.png',
//                     'type' => 'P2A',
//                 ];
//             } elseif ($staticMap->has($method)) {
//                 return $staticMap->get($method);
//             }

//             return null;
//         })
//         ->filter()
//         ->values();

//     return $finalList;
// }

// function mfsList($access = null)
// {
//     // Static P2A list
//     $staticList = listOfIbotOp();

//     // Fetch from external API (dynamic)
//     $dynamicList = fetchMfsApi();

//     // Create maps for merging
//     $staticMap = collect($staticList)->keyBy(fn($item) => strtolower($item['deposit_method']));
//     $dynamicMap = collect($dynamicList)->keyBy(fn($item) => strtolower($item['type']));

//     // Combine static + dynamic methods
//     $allMethods = collect(array_merge(
//         $staticMap->keys()->toArray(),
//         $dynamicMap->keys()->toArray()
//     ))->unique();

//     // Base merged list (P2A + API)
//     $finalList = $allMethods
//         ->map(function ($method) use ($staticMap, $dynamicMap) {
//             $useStatic = rand(0, 1) === 1;

//             if ($useStatic && $staticMap->has($method)) {
//                 return $staticMap->get($method);
//             } elseif ($dynamicMap->has($method)) {
//                 return [
//                     'deposit_method' => strtolower($dynamicMap[$method]['type']),
//                     'deposit_number' => $dynamicMap[$method]['phone'],
//                     'icon' => 'https://ibotbd.com/payments/' . strtolower($dynamicMap[$method]['type']) . '.png',
//                     'type' => 'P2A',
//                 ];
//             } elseif ($staticMap->has($method)) {
//                 return $staticMap->get($method);
//             }

//             return null;
//         })
//         ->filter()
//         ->values();

//     // ✅ Only add P2C data if NOT manual mode
//     if ($access !== 'manual') {
//         $p2cList = listOfIbotOpP2C();

//         // Tag each P2C record with action = "automatic"
//         $p2cList = collect($p2cList)->map(function ($item) {
//             $item['action'] = 'automatic';
//             return $item;
//         });

//         // Append P2C list
//         $finalList = $finalList->concat($p2cList)->values();
//     }

//     return $finalList;
// }

// function mfsList($access = null)
// {
//     // Static P2A list
//     $staticList = listOfIbotOp();



//     // Fetch from external API (dynamic)
//     $dynamicList = fetchMfsApi();

//     // Create maps for merging
//     $staticMap = collect($staticList)->keyBy(fn($item) => strtolower($item['deposit_method']));
//     $dynamicMap = collect($dynamicList)->keyBy(fn($item) => strtolower($item['type']));

//     // Combine static + dynamic methods
//     $allMethods = collect(array_merge(
//         $staticMap->keys()->toArray(),
//         $dynamicMap->keys()->toArray()
//     ))->unique();

//     // Build the combined base list (P2A + API)
//     $finalList = $allMethods
//         ->map(function ($method) use ($staticMap, $dynamicMap) {
//             $useStatic = rand(0, 1) === 1;

//             if ($useStatic && $staticMap->has($method)) {
//                 return $staticMap->get($method);
//             } elseif ($dynamicMap->has($method)) {
//                 return [
//                     'deposit_method' => strtolower($dynamicMap[$method]['type']),
//                     'deposit_number' => $dynamicMap[$method]['phone'],
//                     'icon'           => 'https://ibotbd.com/payments/' . strtolower($dynamicMap[$method]['type']) . '.png',
//                     'type'           => 'P2A',
//                 ];
//             } elseif ($staticMap->has($method)) {
//                 return $staticMap->get($method);
//             }

//             return null;
//         })
//         ->filter()
//         ->values();

//     // If manual → no P2C data
//     if ($access === 'manual') {
//         return $finalList;
//     }

//     // Otherwise (automatic mode)
//     $p2cList = listOfIbotOpP2C();
//     $p2pList = listOfIbotOpP2P();

//     // Tag only P2C items with "action" => "automatic"
//     $p2cList = collect($p2cList)->map(function ($item) {
//         $item['action'] = 'automatic';
//         return $item;
//     });

//     // Tag others (static + dynamic) with "action" => null
//     $finalList = $finalList->map(function ($item) {
//         $item['action'] = null;
//         return $item;
//     });

//     // Merge all lists
//     return $finalList->concat($p2cList)->values();
// }

function mfsList($access = null)
{
    // Static P2A list
    $staticList = listOfIbotOp();

    // Fetch from external API (dynamic)
    $dynamicList = fetchMfsApi();

    // Create maps for merging
    $staticMap = collect($staticList)->keyBy(fn($item) => strtolower($item['deposit_method']));
    $dynamicMap = collect($dynamicList)->keyBy(fn($item) => strtolower($item['type']));

    // Combine static + dynamic methods
    $allMethods = collect(array_merge(
        $staticMap->keys()->toArray(),
        $dynamicMap->keys()->toArray()
    ))->unique();

    // Build the combined base list (P2A + API)
    $finalList = $allMethods
        ->map(function ($method) use ($staticMap, $dynamicMap) {
            $useStatic = rand(0, 1) === 1;

            if ($useStatic && $staticMap->has($method)) {
                return $staticMap->get($method);
            } elseif ($dynamicMap->has($method)) {
                return [
                    'deposit_method' => strtolower($dynamicMap[$method]['type']),
                    'deposit_number' => $dynamicMap[$method]['phone'],
                    'icon' => 'https://' . $_SERVER['HTTP_HOST'] . '/payments/' . strtolower($dynamicMap[$method]['type']) . '.png',
                    'type'           => 'P2A',
                ];
            } elseif ($staticMap->has($method)) {
                return $staticMap->get($method);
            }

            return null;
        })
        ->filter()
        ->values();

    // If manual → no P2C or P2P data
    if ($access === 'manual') {
        return $finalList;
    }

    // Otherwise (automatic mode)
    $p2cList = listOfIbotOpP2C();
    $p2pList = listOfIbotOpP2P();

    // Tag each category with 'action' property
    $finalList = $finalList->map(function ($item) {
        $item['action'] = null; // P2A and dynamic (manual)
        return $item;
    });

    $p2cList = collect($p2cList)->map(function ($item) {
        $item['action'] = 'automatic';
        $item['type'] = 'P2C';
        return $item;
    });

    $p2pList = collect($p2pList)->map(function ($item) {
        $item['action'] = 'peer';
        $item['type'] = 'P2P';
        return $item;
    });

    // Merge all lists
    return $finalList->concat($p2cList)->concat($p2pList)->values();
}




function fetchMfsApi()
{
    try {
        $response = Http::withToken(BalanceManagerConstant::token_key)->get(BalanceManagerConstant::URL . '/api/available-methods');

        if ($response->successful()) {
            $data = $response->json();

            $grouped = collect($data)->groupBy('type');
            $result = [];

            foreach ($grouped as $type => $items) {
                $previousPhone = Cache::get("last_used_phone_{$type}");

                // If there's more than 1 item, avoid reusing the same phone
                if ($items->count() > 1 && $previousPhone) {
                    $filtered = $items->filter(function ($item) use ($previousPhone) {
                        return $item['phone'] !== $previousPhone;
                    });

                    // If all items were filtered out, fall back to original list
                    $finalItems = $filtered->isNotEmpty() ? $filtered : $items;
                } else {
                    $finalItems = $items;
                }

                // Pick one random item
                $selected = $finalItems->random();

                // Cache the current phone number
                Cache::put("last_used_phone_{$type}", $selected['phone'], now()->addMinutes(10));

                $result[] = $selected;
            }

            return $result;
        }
    } catch (\Exception $e) {
        Log::error('Failed to fetch MFS API: ' . $e->getMessage());
    }

    return [];
}

function listOfOp()
{
    $onlineCheckingTime = (int) env('PAYMENT_TIME'); // Cast to integer
    $data = []; // Initialize the data array

    foreach (getOpNameList() as $operator) {
        $modemServiceList = DB::table('modems')
            ->whereIn('operating_status', [2, 3])
            ->whereIn('operator_service', ['on', $operator])
            ->where('up_time', '>=', time() - $onlineCheckingTime)
            ->where('operator', 'LIKE', '%' . $operator . '%')
            ->pluck('sim_id')
            ->toArray();

        if (empty($modemServiceList)) {
            continue; // Skip this operator if no SIMs available
        }

        // Determine image path
        $imagePath = null;
        if ($operator == 'bkash') {
            $imagePath = asset('payments/bkash.png');
        } elseif ($operator == 'nagad') {
            $imagePath = asset('payments/nagad.png');
        } elseif ($operator == 'rocket') {
            $imagePath = asset('payments/rocket.png');
        }

        $data[] = [
            'payment_method' => $operator,
            'sim_number' => $modemServiceList[array_rand($modemServiceList)],
            'icon' => $imagePath,
        ];
    }

    return $data;
}

function getOpNameList()
{
    $onlineCheckingTime = env('PAYMENT_TIME'); // Cast to integer

    $modemServiceList = DB::table('modems')
        ->whereIn('operating_status', [2, 3])
        ->where('up_time', '>=', time() - $onlineCheckingTime)
        ->pluck('operator_service')
        ->toArray();

    $modemList = DB::table('modems')->whereIn('operator_service', $modemServiceList)->whereNotNull('operator')->get();

    $all_operators = [];
    foreach ($modemList as $row) {
        if ($row->operator_service == 'on') {
            // Split the 'operator' field into an array if it's comma-separated
            $operators = explode(',', $row->operator);
            // Merge these operators into the main array
            $all_operators = array_merge($all_operators, $operators);
        } elseif ($row->operator_service != 'off') {
            // Directly append non-off operator_service to the array
            $all_operators[] = $row->operator_service; // Correct way to append an element
        }
    }

    // Get unique operators using array_unique()
    $distinct_operators = array_unique($all_operators);

    // dd($distinct_operators);

    return $distinct_operators;
}

function listOfIbotOp()
{
    $onlineCheckingTime = (int) env('PAYMENT_TIME'); // Cast to integer
    $data = []; // Initialize the data array

    foreach (getOpNameList() as $operator) {
        $modemServiceList = DB::table('modems')
            ->whereIn('operating_status', [2, 3])
            ->whereIn('operator_service', ['on', $operator])
            ->where('up_time', '>=', time() - $onlineCheckingTime)
            ->whereNotNull('operator')
            ->where('operator', 'LIKE', '%' . $operator . '%')
            ->where('transaction_type', 'P2A')
            ->pluck('sim_number')
            ->toArray();

        if (empty($modemServiceList)) {
            continue; // Skip this operator if no SIMs available
        }

        // Determine image path
        $imagePath = null;
        if ($operator == 'bkash') {
            $imagePath = asset('payments/bkash.png');
        } elseif ($operator == 'nagad') {
            $imagePath = asset('payments/nagad.png');
        } elseif ($operator == 'rocket') {
            $imagePath = asset('payments/rocket.png');
        } elseif ($operator == 'upay') {
            $imagePath = asset('payments/upay.png');
        }

        $data[] = [
            'deposit_method' => $operator,
            'deposit_number' => $modemServiceList[array_rand($modemServiceList)],
            'icon' => $imagePath,
            'type' => 'P2A',
        ];
    }

    return $data;
}

function listOfIbotOpP2C()
{
    $onlineCheckingTime = (int) env('PAYMENT_TIME'); // Cast to integer
    $data = []; // Initialize the data array

    foreach (getOpNameList() as $operator) {
        $modemServiceList = DB::table('modems')
            ->whereIn('operating_status', [2, 3])
            ->whereIn('operator_service', ['on', $operator])
            ->where('up_time', '>=', time() - $onlineCheckingTime)
            ->whereNotNull('operator')
            ->where('operator', 'LIKE', '%' . $operator . '%')
            ->where('transaction_type', 'P2C')
            ->pluck('sim_number')
            ->toArray();

        if (empty($modemServiceList)) {
            continue; // Skip this operator if no SIMs available
        }

        // Determine image path
        $imagePath = null;
        if ($operator == 'bkash') {
            $imagePath = asset('payments/bkash.png');
        } elseif ($operator == 'nagad') {
            $imagePath = asset('payments/nagad.png');
        } elseif ($operator == 'rocket') {
            $imagePath = asset('payments/rocket.png');
        } elseif ($operator == 'upay') {
            $imagePath = asset('payments/upay.png');
        }

        $data[] = [
            'deposit_method' => $operator,
            'deposit_number' => $modemServiceList[array_rand($modemServiceList)],
            'icon' => $imagePath,
            'type' => 'P2C',
        ];
    }

    return $data;
}

function listOfIbotOpP2P()
{
    $onlineCheckingTime = (int) env('PAYMENT_TIME'); // Cast to integer
    $data = []; // Initialize the data array

    foreach (getOpNameList() as $operator) {
        $modemServiceList = DB::table('modems')
            ->whereIn('operating_status', [2, 3])
            ->whereIn('operator_service', ['on', $operator])
            ->where('up_time', '>=', time() - $onlineCheckingTime)
            ->whereNotNull('operator')
            ->where('operator', 'LIKE', '%' . $operator . '%')
            ->where('transaction_type', 'P2P')
            ->pluck('sim_number')
            ->toArray();

        if (empty($modemServiceList)) {
            continue; // Skip this operator if no SIMs available
        }

        // Determine image path
        $imagePath = null;
        if ($operator == 'bkash') {
            $imagePath = asset('payments/bkash.png');
        } elseif ($operator == 'nagad') {
            $imagePath = asset('payments/nagad.png');
        } elseif ($operator == 'rocket') {
            $imagePath = asset('payments/rocket.png');
        } elseif ($operator == 'upay') {
            $imagePath = asset('payments/upay.png');
        }

        $data[] = [
            'deposit_method' => $operator,
            'deposit_number' => $modemServiceList[array_rand($modemServiceList)],
            'icon' => $imagePath,
            'type' => 'P2C',
        ];
    }

    return $data;
}
