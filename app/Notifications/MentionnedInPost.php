<?php

namespace App\Notifications;

use App\Models\Post;
use Illuminate\Bus\Queueable;

class MentionnedInPost extends DefaultNotification
{
    use Queueable;

    public $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function toArray($notifiable)
    {
        return array_merge($this->attributes(), [
            'post_id' => $this->post->discussion->id,
        ]);
    }

    protected function attributes()
    {
        $attributes = [
            'title'  => 'Hey! T\'as été mentionné !',
            'target' => $this->post->link,
            'html'   => '<b>' . e($this->post->user->display_name) . '</b> t\'as mentionné dans la discussion <b>' . e($this->post->discussion->title) . '</b>',
            'text'   => $this->post->user->display_name . ' t\'as mentionné dans la discussion : ' . $this->post->discussion->title,
        ];

        return $attributes;
    }
}
