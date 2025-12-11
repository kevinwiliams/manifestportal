<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class EmailQueueService
{
    /**
     * Queue a simple message using defaults from config/emailqueue.php.
     *
     * @param  string|array|null  $to
     * @param  string             $subject
     * @param  string             $body
     * @param  string|array|null  $cc
     * @param  string|array|null  $bcc
     * @param  string|null        $from
     * @param  array              $extra   Extra key=>value pairs to append to payload
     * @return void
     */
    public static function queueMessage(
        string|array|null $to,
        string $subject,
        string $body,
        string|array|null $cc = null,
        string|array|null $bcc = null,
        ?string $from = null,
        array $extra = []
    ): void {
        // Master toggle
        if (!Config::get('emailqueue.enabled', true)) {
            self::log('EmailQueue disabled - not queuing message', [
                'to'      => $to,
                'subject' => $subject,
            ]);
            return;
        }

        $connection = Config::get('emailqueue.connection', 'adhoc');
        $procedure  = Config::get('emailqueue.procedure', 'dbo.InsertMessageQueue');
        $encoding   = Config::get('emailqueue.encoding', 'UTF-8');

        // Normalize addresses: accept array or string
        $to  = self::normalizeAddressList($to, Config::get('emailqueue.default_to'));
        $cc  = self::normalizeAddressList($cc, Config::get('emailqueue.default_cc'));
        $bcc = self::normalizeAddressList($bcc, Config::get('emailqueue.default_bcc'));

        $from = $from ?: Config::get('emailqueue.default_from');

        // Build payload EXACTLY like your classic ASP:
        // encoding=UTF-8&to=...&bcc=...&cc=...&from=...&subject=...&msgbody=...
        $payloadArray = array_merge([
            'encoding' => $encoding,
            'to'       => $to,
            'bcc'      => $bcc,
            'cc'       => $cc,
            'from'     => $from,
            'subject'  => $subject,
            'msgbody'  => $body,
        ], $extra);

        $payload = http_build_query($payloadArray);

        // Log the payload (without body if you prefer)
        self::log('Queuing email via messagequeue stored procedure', [
            'connection' => $connection,
            'procedure'  => $procedure,
            'to'         => $to,
            'cc'         => $cc,
            'bcc'        => $bcc,
            'from'       => $from,
            'subject'    => $subject,
            // 'payload' => $payload, // uncomment if you want full payload logged
        ]);

        try {
            // EXEC dbo.InsertMessageQueue @mess = ?
            // Your stored procedure should take a single NVARCHAR parameter
            DB::connection($connection)->statement("EXEC {$procedure} ?", [$payload]);
        } catch (Throwable $e) {
            self::log('Failed to queue email via messagequeue', [
                'error' => $e->getMessage(),
            ], 'error');

            // Decide if you want to rethrow:
            // throw $e;
        }
    }

    /**
     * Queue a specialized upload notification.
     *
     * Example: call this after a manifest upload finishes.
     */
    public static function queueUploadNotification($upload): void
    {
        $pubDate = $upload->pub_date?->format('d/m/Y') ?? (string) $upload->pub_date;
        $subject = "Manifest upload for {$pubDate} (Pub Code {$upload->pub_code})";

        $bodyLines = [
            "A manifest upload has completed.",
            "",
            "Upload ID: {$upload->id}",
            "Publication Date: {$pubDate}",
            "Publication Code: {$upload->pub_code}",
            "Imported Rows: {$upload->imported_rows}",
            "Skipped Rows: {$upload->skipped_rows}",
            "Uploaded By (User ID): {$upload->user_id}",
        ];

        $body = implode("\r\n", $bodyLines);

        // Add any extra meta that your Python script might handle later
        $extra = [
            'meta' => json_encode([
                'upload_id' => $upload->id,
                'pub_date'  => $upload->pub_date,
                'pub_code'  => $upload->pub_code,
            ]),
        ];

        self::queueMessage(null, $subject, $body, null, null, null, $extra);
    }

    /**
     * Normalize address inputs: accept array or string; fallback to default.
     */
    protected static function normalizeAddressList(string|array|null $value, ?string $default = null): string
    {
        if (is_array($value)) {
            $value = implode(';', array_filter(array_map('trim', $value)));
        }

        $value = trim((string) $value);

        if ($value === '' && $default !== null) {
            return trim($default);
        }

        return $value;
    }

    /**
     * Simple centralized logging that can respect a custom channel.
     */
    protected static function log(string $message, array $context = [], string $level = 'info'): void
    {
        $channel = Config::get('emailqueue.log_channel');

        if ($channel) {
            Log::channel($channel)->{$level}($message, $context);
        } else {
            Log::{$level}($message, $context);
        }
    }
}
