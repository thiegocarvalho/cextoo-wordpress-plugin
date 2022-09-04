<?php

class Cextoo_Crons
{
    public array $jobs = [
        'cextoo_manager_subscriptions_job'
    ];

    public function cextoo_manager_subscriptions_job_activation()
    {
        if (!wp_next_scheduled('cextoo_manager_subscriptions_job')) {
            wp_schedule_event(strtotime('today midnight'), 'daily', 'cextoo_manager_subscriptions_job');
        }
    }

    public function cextoo_manager_subscriptions_job_desactivation()
    {
        $timestamp = wp_next_scheduled('cextoo_manager_subscriptions_job');
        wp_unschedule_event($timestamp, 'cextoo_manager_subscriptions_job');
    }

    function cextoo_manager_subscriptions_job()
    {
        $database = new Cextoo_Database();
        $database->desactiveExpiredSubscriptions();
    }
}