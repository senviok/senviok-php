<?php
namespace Senviok;

use Senviok\Services\Emails;
use Senviok\Services\Sms;
use Senviok\Services\Whatsapp;
use Senviok\Services\Otp;
use Senviok\Services\Templates;
use Senviok\Services\Domains;
use Senviok\Services\Webhooks;
use Senviok\Services\Suppressions;

class Senviok
{
    public Emails $emails;
    public Sms $sms;
    public Whatsapp $whatsapp;
    public Otp $otp;
    public Templates $templates;
    public Domains $domains;
    public Webhooks $webhooks;
    public Suppressions $suppressions;

    public function __construct(string $apiKey, array $config = [])
    {
        $baseUrl = $config['base_url'] ?? 'https://api.senviok.live';
        $client = new HttpClient($apiKey, $baseUrl);

        $this->emails = new Emails($client);
        $this->sms = new Sms($client);
        $this->whatsapp = new Whatsapp($client);
        $this->otp = new Otp($client);
        $this->templates = new Templates($client);
        $this->domains = new Domains($client);
        $this->webhooks = new Webhooks($client);
        $this->suppressions = new Suppressions($client);
    }
}
