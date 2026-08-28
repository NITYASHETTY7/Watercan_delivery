<?php

namespace App\Traits;

use App\Mail\SMTPMail;
use App\Mail\BrevoMail;
use App\Models\MailSmsTemplate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\User;

trait CanSendMail
{
    protected ?array $cc = null;
    protected ?array $bcc = null;
    protected ?array $mailAttachments = null;

    public function __construct()
    {
        $this->resetMailAttributes();
    }

    private function resetMailAttributes(): void
    {
        $this->cc = null;
        $this->bcc = null;
        $this->mailAttachments = null;
    }

    /**
     * Sends email using specified template and method.
     *
     * @param array|string               $emails
     * @param MailSmsTemplate|string|int $template
     * @param array                      $replaceable
     * @param string                     $sendVia
     * @return static|bool
     */
    public function sendMailTo(array|string $emails, MailSmsTemplate|string|int $template, array $replaceable, $sendVia = null): static|bool
    {
        $sendVia = getSetting('mail_mailer') ? getSetting('mail_mailer') : 'smtp';

        if (!getSetting('email_notify')) {
            Log::info('Email notifications are disabled.');
            return false; // Email notifications are disabled.
        }
        $mailTemplate = $this->resolveTemplate($template);
        if (!$mailTemplate) {
            return false;
        }

        if (!$mailTemplate) {
            Log::error('Mail template not found.');
            return false; // Template not found.
        }

        [$mailSubject, $mailBody] = $this->prepareMailContent($mailTemplate, $replaceable);

        $recipients = (array)$emails;
        if (count($recipients) > 0) {
            foreach ($recipients as $email) {
                // Checking User Onsite Notification Condition 
                    $is_allowed = 1;
                    $user = User::where('email', $email)
                    ->select('id', 'email', 'setting_payload')
                    ->first();
                    if (!$user) {
                        Log::info("User Not Found for {$email}.");
                        return false; // Template not found.
                    }
            
                    $userRole = UserRole($user->id)['name'];
                    if (in_array($userRole, ['member', 'user'])) {
                        if($user && isset($user->setting_payload['email_notification_alert'])){
                            if($user->setting_payload['email_notification_alert'] == 0){
                                $is_allowed = 0;
                            }
                        }
                    }

                    if (!$is_allowed) {
                        Log::info("Email notifications are disabled for {$email}.");
                        continue;
                    }
                // End Checking User Onsite Notification Condition 

                // Send via SMTP
                if ($sendVia === 'SMTP') {
                    // Ensure Mail::to($email) is properly initialized
                    $mail = Mail::to($email);

                    // Add CC, BCC, and attachments if provided
                    if (!empty($this->cc)) {
                        $mail->cc($this->cc);
                    }

                    if (!empty($this->bcc)) {
                        $mail->bcc($this->bcc);
                    }

                    if ($this->mailAttachments) {
                        foreach ($this->mailAttachments as $attachment) {
                            if (file_exists($attachment)) {
                                $mail->attach($attachment);
                            } else {
                                Log::warning("Attachment not found: $attachment");
                            }
                        }
                    }

                    // Send the email using SMTP
                    $this->sendSMTPMail($mail, $mailSubject, $mailBody);
                }

                // Send via Brevo
                elseif ($sendVia === 'Brevo') {
                    $this->to = $email;
                    $this->sendBrevoMail($mailSubject, $mailBody);
                }
            }
        }

        return $this;
    }


    private function resolveTemplate(MailSmsTemplate|string|int $template): ?MailSmsTemplate
    {
        return match (true) {
            is_int($template) => MailSmsTemplate::find($template),
            is_string($template) => MailSmsTemplate::where('code', $template)->first(),
            $template instanceof MailSmsTemplate => $template,
            default => null,
        };
    }

    private function prepareMailContent(MailSmsTemplate $mailTemplate, array $replaceable): array
    {
        $mailBody = $mailTemplate->content;
        $mailSubject = $mailTemplate->subject;

        foreach ($replaceable as $key => $value) {
            $mailBody = str_replace("{$key}", $value, $mailBody);
            $mailSubject = str_replace("{$key}", $value, $mailSubject);
        }

        $mailBody = str_replace(
            ['{nl}', '{br}', '{app.name}', '{app.url}'],
            ['<br>', '<br>', getSetting('app_name'), url('/')],
            $mailBody
        );

        $mailSubject = str_replace(
            ['{app.name}', '{app.url}'],
            [getSetting('app_name'), url('/')],
            $mailSubject
        );

        return [$mailSubject, $mailBody];
    }

    private function sendSMTPMail($mail, string $subject, string $body): void
    {
        // Ensure that the $mail object is valid
        if (!$mail) {
            Log::error('Mail object is not properly initialized for SMTP.');
            return;
        }

        Log::info('Sending SMTP Mail', ['subject' => $subject, 'body' => $body]);

        // Validate subject and body before sending
        if (empty($subject) || empty($body)) {
            Log::error('Invalid email content. Subject or body is empty.');
            return;
        }

        try {
            // Create an instance of the SMTPMail Mailable
            $smtpMail = new SMTPMail($subject, $body);

            // Send the email using PendingMail's send() method
            $mail->send($smtpMail); // This should work now with a Mailable instance

            Log::info('Mail sent via SMTP Successfully.');
        } catch (\Exception $e) {
            Log::error('SMTP Mail sending failed: ' . $e->getMessage());
        }
    }


    private function sendBrevoMail(string $subject, string $body): void
    {
        try {
            // Create an instance of BrevoMail with appropriate parameters
            $brevoMail = new BrevoMail($subject, $body, getSetting('mail_from_name'), 'dev@dze-labs.com', (array)$this->to, (array)$this->cc, (array)$this->bcc);
            // Send the mail using the Brevo API
            $result = $brevoMail->send();
            if (!$result) {
                Log::error('Failed to send email via Brevo.');
            } else {
                Log::info('Mail sent via Brevo Successfully.');
            }
        } catch (\Exception $e) {
            Log::error('BrevoMail error: ' . $e->getMessage());
        }
    }

    public function cc(array|string $users): static
    {
        $this->cc = (array)$users;
        return $this;
    }

    public function bcc(array|string $users): static
    {
        $this->bcc = (array)$users;
        return $this;
    }

    public function mailAttachments(array $attachments): static
    {
        $this->mailAttachments = $attachments;
        return $this;
    }
}
