<?php

namespace Spatie\Backup\Tasks\Monitor;

use Illuminate\Support\Collection;
use Spatie\Backup\BackupDestination\BackupDestination;

class BackupDestinationStatusFactory
{
    public static function createForMonitorConfig(array $monitorConfiguration) : Collection
    {
        return collect($monitorConfiguration)
            ->map(function (array $monitorProperties) {
                return BackupDestinationStatusFactory::createForSingleMonitor($monitorProperties);
            })
            ->flatten()
            ->sortBy(function (BackupDestinationStatus $backupDestinationStatus) {
                return "{$backupDestinationStatus->getBackupName()}-{$backupDestinationStatus->getFilesystemName()}";
            });
    }

    public static function createForSingleMonitor(array $monitorConfig) : Collection
    {
        return collect($monitorConfig['filesystems'])->map(function (string $filesystemName) use ($monitorConfig) {

            $backupDestination = BackupDestination::create($filesystemName, $monitorConfig['name']);

            return (new BackupDestinationStatus($backupDestination, $filesystemName))
                ->setMaximumAgeOfNewestBackupInDays($monitorConfig['newestBackupsShouldNotBeOlderThanDays'])
                ->setMaximumStorageUsageInMegabytes($monitorConfig['storageUsedMayNotBeHigherThanMegabytes']);
        });
    }
}
