<?php
/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2017/3/30
 * Time: 16:46
 */

namespace Support;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider as Provider;

class ServiceProvider extends Provider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [];

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfiguration();
        $this->registerProviders();
        $this->registerCommands();
        $this->registerEvents();
    }

    /**
     * Merge config.
     */
    protected function registerConfiguration()
    {
        $items = $this->getConfigurations();

        foreach ( $items as $item ) {
            $this->mergeConfigFrom($item['file'], $item['key']);
        }
    }

    /**
     * Register providers.
     */
    protected function registerProviders()
    {
        $providers = $this->getProviders();

        foreach ( $providers as $provider ) {
            $this->app->register($provider);
        }
    }

    /**
     * Register commands.
     */
    protected function registerCommands()
    {
        $commands = $this->getCommands();
        $this->commands($commands);
    }

    /**
     * Register events.
     */
    protected function registerEvents()
    {
        $events = app('events');

        foreach ($this->listen as $event => $listeners) {
            foreach ($listeners as $listener) {
                $events->listen($event, $listener);
            }
        }

        foreach ($this->subscribe as $subscriber) {
            $events->subscribe($subscriber);
        }
    }

    /**
     * @return Schedule
     */
    protected function getScheduler()
    {
        return app(Schedule::class);
    }

    /**
     * @return array
     */
    protected function getConfigurations()
    {
        return [
            /* configuration items.
            [
                'file' => 'database',
                'key' => 'database.connections',
            ],
            */
        ];
    }

    /**
     * @return array
     */
    protected function getProviders()
    {
        return [
            // Service providers.
        ];
    }

    /**
     * @return array
     */
    protected function getCommands()
    {
        return [
            // Commands.
        ];
    }

    /**
     * Get the events and handlers.
     *
     * @return array
     */
    public function listens()
    {
        return $this->listen;
    }

}