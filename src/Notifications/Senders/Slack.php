<?php

namespace Spatie\Backup\Notifications\Senders;

use Spatie\Backup\Notifications\BaseSender;

class Slack extends BaseSender
{
    /** @var \Maknz\Slack\Client */
    protected $client;

    /** @var array */
    protected $config;

    /**
     * @param \Maknz\Slack\Client                     $client
     * @param \Illuminate\Contracts\Config\Repository $config
     */
    public function __construct($client, $config)
    {
        $this->config = $config->get('laravel-backup.notifications.slack');

        $client->setDefaultUsername($this->config['username']);
        $client->setDefaultIcon($this->config['icon']);

        $this->client = $client;
    }

    public function send()
    {
        $this->client
            ->to($this->config['channel'])
            ->attach([
                'text' => $this->message,
                'color' => $this->type === static::TYPE_SUCCESS ? 'good' : 'warning',
            ])
            ->send($this->subject);
    }
}
