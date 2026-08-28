<?php

namespace App\Mail;

use Illuminate\Support\Facades\Log;
use Exception;

class BrevoMail
{
    protected string $subject;
    protected string $body;
    protected string $fromName;
    protected string $fromEmail;
    protected array $to;
    protected ?array $cc;
    protected ?array $bcc;

    /**
     * Constructor for the BrevoMail class.
     *
     * @param string $subject
     * @param string $body
     * @param string $fromName
     * @param string $fromEmail
     * @param array  $to
     * @param array|null $cc
     * @param array|null $bcc
     */
    public function __construct(string $subject, string $body, string $fromName, string $fromEmail, array $to, ?array $cc = null, ?array $bcc = null)
    {
        $this->subject = $subject;
        $this->body = $body;
        $this->fromName = $fromName;
        $this->fromEmail = $fromEmail;
        $this->to = $to;
        $this->cc = $cc;
        $this->bcc = $bcc;
    }

    /**
     * Sends the email using Brevo's API via cURL.
     *
     * @return bool
     */
    public function send(): bool
    {
        $curl = curl_init();
        $html_file = view('mail.default.index', [
            'subject' => $this->subject,
            'mail_content' => $this->body,
        ])->render();        
        // Prepare the email payload
        $emailData = [
            'sender' => [
                'name' => $this->fromName,
                'email' => $this->fromEmail,
            ],
            'to' => array_map(fn($email) => ['email' => $email], $this->to),
            'subject' => $this->subject,
            'htmlContent' => $html_file,
        ];

        // Optionally add CC and BCC
        if ($this->cc) {
            $emailData['cc'] = array_map(fn($email) => ['email' => $email], $this->cc);
        }
        if ($this->bcc) {
            $emailData['bcc'] = array_map(fn($email) => ['email' => $email], $this->bcc);
        }
        // Set cURL options for the request
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.brevo.com/v3/smtp/email',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($emailData),
            CURLOPT_HTTPHEADER => [
                'accept: application/json',
                'api-key: ' . env('BREVO_API_KEY'), // Using the API key from .env
                'content-type: application/json',
            ],
        ]);

        // Execute the cURL request
        $response = curl_exec($curl);

        // Check for errors in the request
        if(curl_errno($curl)) {
            Log::error('BrevoMail cURL error: ' . curl_error($curl));
            curl_close($curl);
            return false;
        }

        // Close the cURL session
        curl_close($curl);

        // Log the response (for debugging purposes)
        Log::info('BrevoMail response: ' . $response);

        // Return true if the email was sent successfully (check if response contains an 'id' key)
        $responseData = json_decode($response, true);
        if (isset($responseData['id'])) {
            return true;
        }

        return false;
    }
}
