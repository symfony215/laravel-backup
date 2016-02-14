<?php

namespace Spatie\Backup\Commands;

use Spatie\Backup\Events\HealthyBackupWasFound;
use Spatie\Backup\Events\UnHealthyBackupWasFound;
use Spatie\Backup\Tasks\Monitor\BackupDestinationStatus;
use Spatie\Backup\Tasks\Monitor\BackupDestinationStatusFactory;

class MonitorCommand extends BaseCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'backup:monitor';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Monitor the health of all backups.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $statuses = BackupDestinationStatusFactory::createForMonitorConfig(config('laravel-backup.monitorBackups'));

        $statuses->each(function (BackupDestinationStatus $backupDestinationStatus) {

           if ($backupDestinationStatus->isHealthy()) {
               event(new HealthyBackupWasFound($backupDestinationStatus));

               return;
           }

            event(new UnHealthyBackupWasFound($backupDestinationStatus));
        });
    }
}
