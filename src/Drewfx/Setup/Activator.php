<?php

namespace Drewfx\Salesforce\Setup;

class Activator
{
    public const TOKEN_TABLE_NAME = 'salesforce_integration_token';
    public const QUEUE_TABLE_NAME = 'salesforce_integration_queue';
    public const LOG_TABLE_NAME = 'salesforce_integration_lead';

    public static function run() : void
    {
        require(ABSPATH . 'wp-admin/includes/upgrade.php');

        global $wpdb;

        $log_table = sprintf(
            "create table if not exists %s (
                id int not null auto_increment primary key,
                url varchar(255),
                request text,
                response text,
                code int default null,
                message text,
                created_at timestamp) %s;",
            self::LOG_TABLE_NAME,
            $wpdb->get_charset_collate()
        );

        $queue_table = sprintf(
            "create table if not exists %s (
                id int not null auto_increment primary key,
                fields text,
                attempts int default 0,
                message varchar(255),
                created_at timestamp) %s;",
            self::QUEUE_TABLE_NAME,
            $wpdb->get_charset_collate()
        );

        $token_table = sprintf(
            "create table if not exists %s (
                id int not null auto_increment primary key,
                instance_url varchar(255),
                token_type varchar(255),
                access_token varchar(255),
                active tinyint,
                signature varchar(255),
                issued_at timestamp,
                created_at timestamp) %s;",
            self::TOKEN_TABLE_NAME,
            $wpdb->get_charset_collate()
        );

        $wpdb->query($log_table);
        $wpdb->query($queue_table);
        $wpdb->query($token_table);
    }
}
