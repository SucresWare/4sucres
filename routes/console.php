<?php

use App\Models\Post;
use Illuminate\Foundation\Inspiring;
use Spatie\Regex\Regex;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

// Oneshot command to convert all legacy bbcode posts to markdown
Artisan::command('convert_all_posts_from_bbcode_to_markdown', function () {
    $posts = Post::all();
    $bar = $this->output->createProgressBar(count($posts));

    foreach ($posts as $post) {
        $çadégage = ['u', 'email', 'list', '*', 'li', 'quote', 'youtube', 'font', 'size', 'color', 'left', 'right', 'center'];

        foreach ($çadégage as $cassetoi) {
            $post->body = Regex::replace('/\[' . $cassetoi . '(?:(?:=)(.*?)|)\](.*?)\[\/' . $cassetoi . '\]/', '$2', $post->body)->result();
        }

        $post->body = Regex::replace('/\[b(?:(?:=)(.*?)|)\](.*?)\[\/b\]/', '**$2**', $post->body)->result();
        $post->body = Regex::replace('/\[i(?:(?:=)(.*?)|)\](.*?)\[\/i\]/', '*$2*', $post->body)->result();
        $post->body = Regex::replace('/\[s(?:(?:=)(.*?)|)\](.*?)\[\/s\]/', '~~$2~~', $post->body)->result();
        $post->body = Regex::replace('/\[code(?:(?:=)(.*?)|)\](.*?)\[\/code\]/', '````' . "\r\n " . '$2' . "\r\n " . '```', $post->body)->result();
        $post->body = Regex::replace('/\[url(?:(?:=)(.*?)|)\](.*?)\[\/url\]/', '[$1]($2)', $post->body)->result();
        $post->body = Regex::replace('/\[img(?:(?:=)(.*?)|)\](.*?)\[\/img\]/', '![]($2)', $post->body)->result();
        $post->body = Regex::replace('/\[spoiler(?:(?:=)(.*?)|)\](.*?)\[\/spoiler\]/', '||$2||', $post->body)->result();
        $post->body = Regex::replace('/\[mock(?:(?:=)(.*?)|)\](.*?)\[\/mock\]/', '%$2%', $post->body)->result();
        $post->body = Regex::replace('/\[glitch(?:(?:=)(.*?)|)\](.*?)\[\/glitch\]/', '@$2@', $post->body)->result();

        $post->save(['timestamps' => false]);
        $bar->advance();
    }

    $bar->finish();
});
