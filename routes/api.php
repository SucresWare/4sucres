<?php

use App\Models\DiscordEmoji;
use App\Models\DiscordGuild;
use App\Models\Notification;
use Illuminate\Support\Facades\Cache;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'auth:api', 'prefix' => 'v1'], function () {
    Route::get('/@me', function () {
        return user('api');
    });

    Route::get('/light-toggler', function () {
        switch (user()->getSetting('layout.theme', 'light-theme')) {
            case 'light-theme':
                user()->setSetting('layout.theme', 'dark-theme');

            break;
            case 'dark-theme':
                user()->setSetting('layout.theme', 'light-theme');

            break;
        }

        return ['success' => true];
    });

    Route::get('/notifications', function () {
        return Notification::query()
            ->where('notifiable_id', user()->id)
            ->where('read_at', null)
            ->orderBy('created_at', 'DESC')
            ->limit(10)
            ->get()
            ->transform(function ($notification) {
                $notification->presented_created_at = $notification->created_at->diffForHumans();

                return $notification;
            });
    });

    Route::post('/discord-guilds', function () {
        if (!user('api')->can('sync discord emojis')) {
            abort(403);
        }

        request()->validate([
            'id'   => ['required', 'integer'],
            'icon' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
        ]);

        $guild = new DiscordGuild([
            'id'   => request()->id,
            'icon' => request()->icon,
            'name' => request()->name,
        ]);

        try {
            file_get_contents($guild->icon_link);
        } catch (\Exception $e) {
            activity()
                ->causedBy(auth()->user('api'))
                ->withProperties([
                    'level'        => 'warning',
                    'method'       => __METHOD__,
                    'request'      => request()->all(),
                    'attributes'   => array_merge($guild->toArray(), ['link' => $guild->link]),
                ])
                ->log('DiscordGuildNotFound');

            abort(404);
        }

        $guild = DiscordGuild::updateOrCreate(['id'=> request()->id], [
            'icon' => request()->icon,
            'name' => request()->name,
        ]);

        DiscordGuild::findOrFail(request()->id)->users()->syncWithoutDetaching(user('api')); // <= Necessary beacuse `$guild->id` is set to 0 on creation (?!).

        Cache::tags('emojis')->forget('user:' . user('api')->id);

        return ['success' => true];
    });

    Route::post('/discord-emojis', function () {
        if (!user('api')->can('sync discord emojis')) {
            abort(403);
        }

        request()->validate([
            'id'             => ['required', 'integer'],
            'guild_id'       => ['required', 'integer', 'exists:discord_guilds,id'],
            'name'           => ['required', 'string', 'max:255'],
            'animated'       => ['boolean'],
            'deleted'        => ['boolean'],
            'require_colons' => ['boolean'],
        ]);

        $emoji = new DiscordEmoji([
            'id'                => request()->id,
            'name'              => request()->name,
            'animated'          => request()->input('animated', false),
            'deleted'           => request()->input('deleted', false),
            'require_colons'    => request()->input('require_colons', true),
            'discord_guild_id'  => request()->guild_id,
        ]);

        try {
            file_get_contents($emoji->link);
        } catch (\Exception $e) {
            activity()
                ->causedBy(auth()->user('api'))
                ->withProperties([
                    'level'        => 'warning',
                    'method'       => __METHOD__,
                    'request'      => request()->all(),
                    'attributes'   => array_merge($emoji->toArray(), ['link' => $emoji->link]),
                ])
                ->log('DiscordEmojiNotFound');

            abort(404);
        }

        $emoji = DiscordEmoji::updateOrCreate(['id'=> request()->id], [
            'name'              => request()->name,
            'animated'          => request()->input('animated', false),
            'deleted'           => request()->input('deleted', false),
            'require_colons'    => request()->input('require_colons', true),
            'discord_guild_id'  => request()->guild_id,
        ]);

        Cache::tags('emojis')->forget('user:' . user('api')->id);

        return [
            'success' => true,
        ];
    });
});
