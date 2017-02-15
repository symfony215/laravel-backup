<?php

namespace Spatie\Backup\Events;

use Spatie\Backup\Tasks\Monitor\BackupDestinationStatus;

class UnhealthyBackupWasFound
{
    /** @var BackupDestinationStatus */
    public $backupDestinationStatus;

    public function __construct(BackupDestinationStatus $backupDestinationStatus)
    {
        $this->backupDestinationStatus = $backupDestinationStatus;
    }
}
