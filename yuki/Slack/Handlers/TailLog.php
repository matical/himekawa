<?php

namespace yuki\Slack\Handlers;

use SplFileInfo;
use Spatie\SlashCommand\Request;
use Spatie\SlashCommand\Response;
use Spatie\SlashCommand\Attachment;
use Illuminate\Support\Facades\File;
use Spatie\SlashCommand\Handlers\SignatureHandler;

class TailLog extends SignatureHandler
{
    protected $signature = '* log {--l|lines=50 : Number of lines to display }';

    protected $description = 'Tail the latest logfile';

    /**
     * Handle the given request.
     *
     * @param \Spatie\SlashCommand\Request $request
     * @return \Spatie\SlashCommand\Response
     */
    public function handle(Request $request): Response
    {
        $logDirectory = storage_path('logs');
        if (! $path = $this->findLatestLogFile($logDirectory)) {
            return $this->respondToSlack('')
                        ->withAttachment(Attachment::create()
                                                   ->setColor('warning')
                                                   ->setText("Could not find a log file in `{$logDirectory}`."));
        }

        $lines = $this->getOption('lines');
        $output = $this->tail($path, $lines);

        return $this->respondToSlack("Logs for last $lines lines.\n" . '```' . $output . '```');
    }

    protected function findLatestLogFile(string $directory)
    {
        $logFile = collect(File::allFiles($directory))
            ->sortByDesc(fn (SplFileInfo $file) => $file->getMTime())
            ->first();

        return $logFile
            ? $logFile->getPathname()
            : false;
    }

    protected function tail($filePath, $lines = 1, $adaptive = true)
    {
        $file = @fopen($filePath, 'rb');
        if ($file === false) {
            return false;
        }

        $buffer = ! $adaptive ? 4096 : ($lines < 2 ? 64 : ($lines < 10 ? 512 : 4096));
        fseek($file, -1, SEEK_END);

        if (fread($file, 1) != "\n") {
            $lines -= 1;
        }

        $output = '';

        while (ftell($file) > 0 && $lines >= 0) {
            $seek = min(ftell($file), $buffer);
            fseek($file, -$seek, SEEK_CUR);
            $output = ($chunk = fread($file, $seek)) . $output;
            fseek($file, -mb_strlen($chunk, '8bit'), SEEK_CUR);
            $lines -= substr_count($chunk, "\n");
        }

        while ($lines++ < 0) {
            $output = substr($output, strpos($output, "\n") + 1);
        }

        fclose($file);

        return trim($output);
    }
}
