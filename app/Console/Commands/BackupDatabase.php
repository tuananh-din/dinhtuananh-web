<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class BackupDatabase extends Command
{
    protected $signature = 'backup:db';

    protected $description = 'Sao luu database MySQL vao storage/app/backups';

    public function handle(): int
    {
        $connection = config('database.connections.mysql');
        $mysqldumpPath = config('database.backup.mysqldump_path', '/bin/mysqldump');

        if (!$connection['host'] || !$connection['port'] || !$connection['database'] || !$connection['username']) {
            Log::error('Database backup failed: incomplete MySQL configuration.');
            $this->error('Khong the sao luu: thieu cau hinh ket noi MySQL.');

            return self::FAILURE;
        }

        $process = new Process([
            $mysqldumpPath,
            '--single-transaction',
            '--no-tablespaces',
            '-h', $connection['host'],
            '-P', (string) $connection['port'],
            '-u', $connection['username'],
            $connection['database'],
        ], null, [
            'MYSQL_PWD' => (string) ($connection['password'] ?? ''),
        ]);

        try {
            $process->mustRun();
        } catch (\Throwable $exception) {
            Log::error('Database backup failed.', [
                'message' => $exception->getMessage(),
            ]);
            $this->error('Khong the sao luu database. Kiem tra mysqldump va cau hinh MySQL.');

            return self::FAILURE;
        }

        $contents = gzencode($process->getOutput());

        if ($contents === false) {
            Log::error('Database backup failed: could not compress dump output.');
            $this->error('Khong the nen file sao luu database.');

            return self::FAILURE;
        }

        $backupDirectory = storage_path('app/backups');
        $backupPath = $backupDirectory.DIRECTORY_SEPARATOR.'db-'.now()->format('Y-m-d-His').'.sql.gz';

        try {
            File::ensureDirectoryExists($backupDirectory);

            if (file_put_contents($backupPath, $contents) === false) {
                throw new \RuntimeException('Could not write backup file.');
            }
        } catch (\Throwable $exception) {
            Log::error('Database backup failed: could not write backup file.', [
                'message' => $exception->getMessage(),
            ]);
            $this->error('Khong the ghi file sao luu database.');

            return self::FAILURE;
        }

        $cutoff = now()->subDays(14)->getTimestamp();

        foreach (File::glob($backupDirectory.DIRECTORY_SEPARATOR.'*.sql.gz') as $file) {
            if (File::lastModified($file) < $cutoff) {
                File::delete($file);
            }
        }

        $this->info('Da tao backup database: '.basename($backupPath));

        return self::SUCCESS;
    }
}
