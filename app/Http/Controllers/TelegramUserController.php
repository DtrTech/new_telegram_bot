<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Spatie\Browsershot\Browsershot;
use Illuminate\Http\Request;
use App\Models\TelegramUser;
use Bouncer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Api;
use GuzzleHttp\Client;

class TelegramUserController extends Controller
{
    public function index(Request $request)
    {
        $telegram_user = TelegramUser::all();

        return view('telegram_user.index')->with('telegram_user',$telegram_user);
    }

    public function load(Request $request)
    {
        $draw = $request->input('draw');
        $searchValue = $request->input('search.value');
        $start = $request->input('start', 0);
        $length = $request->input('length', 10); // Number of records per page
        $orderByColumn = $request->input('columns')[$request->input('order.0.column')]['data'];
        $orderByDirection = $request->input('order.0.dir');

        $validColumns = ['id', 'telegram_bot_id', 'telegram_id', 'first_name', 'last_name', 'username', 'contact_no', 'is_active', 'bot_names', 'group_names'];
        if (!in_array($orderByColumn, $validColumns)) {
            $orderByColumn = 'id'; // Default to sorting by 'id'
        }

        // Start the query
        $query = TelegramUser::with(['bots.telegram_bot', 'groups.group_dt']) // Eager load groups and their group_dt relationship
            ->select(
                'telegram_users.id',
                'telegram_users.telegram_bot_id',
                'telegram_users.telegram_id',
                'telegram_users.first_name',
                'telegram_users.last_name',
                'telegram_users.username',
                'telegram_users.contact_no',
                'telegram_users.is_active'
            );

        // Search filter
        if (!empty($searchValue)) {
            $query->where(function ($query) use ($searchValue) {
                $query->where('telegram_id', 'like', "%$searchValue%")
                    ->orWhere('first_name', 'like', "%$searchValue%")
                    ->orWhere('last_name', 'like', "%$searchValue%")
                    ->orWhere('username', 'like', "%$searchValue%")
                    ->orWhere('contact_no', 'like', "%$searchValue%")
                    ->orWhereHas('bots', function ($query) use ($searchValue) {
                        $query->whereHas('telegram_bot', function ($query) use ($searchValue) {
                            $query->where('bot_name', 'like', "%$searchValue%");
                        });
                    })
                    ->orWhereHas('groups', function ($query) use ($searchValue) {
                        $query->whereHas('group_dt', function ($query) use ($searchValue) {
                            $query->where('group_name', 'like', "%$searchValue%");
                        });
                    });
            });
        }

        // Sorting by bot names
        if ($orderByColumn === 'bot_names') {
            $query->leftJoin('telegram_user_bots', 'telegram_user_bots.telegram_user_id', '=', 'telegram_users.id')
                ->leftJoin('telegram_bots', 'telegram_bots.id', '=', 'telegram_user_bots.telegram_bot_id')
                ->selectRaw('GROUP_CONCAT(telegram_bots.bot_name ORDER BY telegram_bots.bot_name ASC) as bot_names')
                ->groupBy('telegram_users.id');  // Group by user to combine bots

            // Sorting by bot names
            $query->orderByRaw('GROUP_CONCAT(telegram_bots.bot_name ORDER BY telegram_bots.bot_name ASC) ' . $orderByDirection);
        }

        // Sorting by group names
        if ($orderByColumn === 'group_names') {
            // Use LEFT JOIN to include users without any group
            $query->leftJoin('telegram_joins', 'telegram_joins.telegram_user_id', '=', 'telegram_users.id')
                ->leftJoin('telegram_groups', 'telegram_groups.id', '=', 'telegram_joins.telegram_group_id')
                ->selectRaw('GROUP_CONCAT(telegram_groups.group_name ORDER BY telegram_groups.group_name ASC) as group_names')
                ->groupBy('telegram_users.id');  // Group by user to combine groups

            // Sorting by group names
            $query->orderByRaw('GROUP_CONCAT(telegram_groups.group_name ORDER BY telegram_groups.group_name ASC) ' . $orderByDirection);
        }

        // Default sorting for other columns
        if ($orderByColumn !== 'bot_names' && $orderByColumn !== 'group_names') {
            $query->orderBy("telegram_users.$orderByColumn", $orderByDirection);
        }

        // Clone the query for filtered count (for recordsFiltered)
        $queryFiltered = clone $query;

        // Get the data (actual records)
        $data = $query->offset($start)->limit($length)->get();

        // Get the total count of filtered records
        $recordsFiltered = $queryFiltered->count();

        // Attach bot names and group names (group names are already fetched using GROUP_CONCAT)
        foreach ($data as $d) {
            $d->bot_names = $d->bot_names ?? null;
            $d->group_names = $d->group_names ?? null; // Already set by GROUP_CONCAT
        }

        // Return the response with the necessary pagination and filtered data
        return response()->json([
            'draw' => $draw,
            'recordsTotal' => TelegramUser::count(),
            'recordsFiltered' => $recordsFiltered, // Use the correct filtered count
            'data' => $data,
        ]);
    }

    public function view_message(TelegramUser $telegram_user)
    {
        return view('telegram_user.view_message')->with('telegram_user',$telegram_user);
    }

    public function load_message(Request $request, TelegramUser $telegram_user)
    {
        $draw = $request->input('draw');
        $searchValue = $request->input('search.value');
        $start = $request->input('start', 0);
        $length = $request->input('length', 10); // Number of records per page
        $orderByColumn = $request->input('columns')[$request->input('order.0.column')]['data'];
        $orderByDirection = $request->input('order.0.dir');

        $validColumns = ['id', 'message_id', 'message_time', 'chat_type', 'text'];
        if (!in_array($orderByColumn, $validColumns)) {
            $orderByColumn = 'id'; // Default to sorting by 'id'
        }

        // Start the query
        $query = $telegram_user->messages()
            ->select(
                'message_id',
                'datetime',
                'send_to_chat_id',
                'send_from_chat_id',
                'text',
                'telegram_bot_id'
            );

        // Search filter
        if (!empty($searchValue)) {
            $query->where(function ($query) use ($searchValue) {
                $query->where('message_id', 'like', "%$searchValue%")
                    ->orWhere('text', 'like', "%$searchValue%")
                    ->orWhere('datetime', 'like', "%$searchValue%")
                    ->orWhereRaw("CASE WHEN send_to_chat_id = send_from_chat_id THEN (SELECT bot_name FROM telegram_bots WHERE telegram_bots.id = messages.telegram_bot_id LIMIT 1) ELSE (SELECT group_name FROM telegram_groups WHERE group_telegram_id = send_from_chat_id LIMIT 1) END LIKE ?", ["%$searchValue%"]);
            });
        }

        // Clone the query for filtered count (for recordsFiltered)
        $queryFiltered = clone $query;

        // Sorting logic
        if ($orderByColumn === 'message_time') {
            $query->orderBy('datetime', $orderByDirection);
        } elseif ($orderByColumn === 'chat_type') {
            $query->leftJoin('telegram_groups', 'telegram_groups.group_telegram_id', '=', 'messages.send_from_chat_id')
                ->leftJoin('telegram_bots', 'telegram_bots.id', '=', 'messages.telegram_bot_id')
                ->orderByRaw("CASE WHEN messages.send_to_chat_id = messages.send_from_chat_id THEN COALESCE(telegram_bots.bot_name, 'Bot') ELSE COALESCE(telegram_groups.group_name, '') END $orderByDirection");
        } elseif ($orderByColumn === 'message_id') {
            // Cast message_id to integer for sorting numerically
            $query->orderByRaw("CAST(messages.message_id AS UNSIGNED) $orderByDirection");
        } else {
            // Default sorting by other columns (message_id, text, etc.)
            $query->orderBy("messages.$orderByColumn", $orderByDirection);
        }

        // Get the data (actual records)
        $data = $query->offset($start)->limit($length)->get();

        // Get the total count of filtered records
        $recordsFiltered = $queryFiltered->count();

        // Convert data attributes for message_time and chat_type
        foreach ($data as $message) {
            $message->message_time = $message->message_time; // Uses accessor for formatted time
            $message->chat_type = $message->chat_type; // Uses accessor to determine bot name or group name
            $message->makeHidden('telegram_bot');
        }

        // Return the response with the necessary pagination and filtered data
        return response()->json([
            'draw' => $draw,
            'recordsTotal' => TelegramUser::count(),
            'recordsFiltered' => $recordsFiltered, // Use the correct filtered count
            'data' => $data,
        ]);
    }

    public function update(Request $request, TelegramUser $telegram_user)
    {

        return back()->withSuccess('Data updated');
    }

    public function toggleStatus(Request $request)
    {
        $user_id = $request->user_id;
        $checked = $request->checked;

        $user = TelegramUser::find($user_id);
        if($checked) {
            $user->is_active = 1;
        } else {
            $user->is_active = 0;
        }
        $user->save();

        return response()->json(['success' => true, 'data' => null, 'message' => 'Update status successfully.'], 200);
    }
}
