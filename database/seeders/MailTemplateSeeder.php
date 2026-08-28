<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MailSmsTemplate;

class MailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // Admin account create mail
        MailSmsTemplate::firstOrCreate(
            [
                "subject" => "Admin Account Created on {app.name}",
                "purpose" => "To notify the admin that their account has been successfully created by another user.",
                "code" => "admin-account-created-mail",
                "type" => 1,
                "content" => "## Admin Account Created on {app.name}
                    Hello {admin_name}, you have been successfully added as an admin on {app.name} by {user_name}.
                    Please log in to your admin dashboard to start managing the platform.
                    {login_url}",
            ]
        );
        
        // user account create mail
        MailSmsTemplate::firstOrCreate(
            [
                "subject" => "User Account Created on {app.name}",
                "purpose" => "To inform a user that their account was created by an admin and provide login details.",
                "code" => "user-account-created-mail",
                "type" => 1,
                "content" => "## User Account Created on {app.name}
                    Hello {user_name}, your account has been created by {admin_name} on {app.name}.
                    Here are your login details:
                    - Email: {email}
                    - Password: {password}
                    Please change your password after logging in.
                    {login_url}",
            ]
        );
        
        MailSmsTemplate::firstOrCreate(
            [
            "subject" => "Welcome to {app.name}.",
            "purpose" => "To be sent when user registers and verification is disabled.",
            "code" => "registration-welcome-mail",
            "type" => 1,
            "content" => "## Welcome to {app.name}
                You are successfully registered with us,
                please login to start using our platform.
                ### Registered account details
                Name: {name} {br}
                Username: {username} {br}
                Email: **{email}** {br}
                Password: `your selected password` {br}",
            ]
        );
        
        MailSmsTemplate::firstOrCreate(
            [
                "subject" => "User Verification Documents Uploaded",
                "purpose" => "To notify the admin when a user uploads verification documents.",
                "code" => "user-verification-uploaded-admin-mail",
                "type" => 1,
                "content" => "## User Verification Documents Uploaded
                    Hello {admin_name}, {user_name} has uploaded their verification documents. Please review and approve them.
                    {verification_url}",
            ]
        );
        
        MailSmsTemplate::firstOrCreate(
            [
                "subject" => "Verification Documents Submitted",
                "purpose" => "To inform the user that their verification documents are under review.",
                "code" => "user-verification-uploaded-user-mail",
                "type" => 1,
                "content" => "## Verification Documents Submitted
                    Hello {user_name}, your verification documents have been successfully uploaded. They are now pending review by the admin.
                    We will notify you once your documents are reviewed.",
            ]
        );
        
        MailSmsTemplate::firstOrCreate(
            [
                "subject" => "Verification Documents Accepted",
                "purpose" => "To notify the user that their verification documents have been accepted.",
                "code" => "verification-accepted-mail",
                "type" => 1,
                "content" => "## Verification Documents Accepted
                    Hello {user_name}, your verification documents have been successfully accepted.
                    You can now access all services on {app.name}.",
            ]
        );
        
        MailSmsTemplate::firstOrCreate(
            [
                "subject" => "Verification Documents Rejected",
                "purpose" => "To inform the user that their verification documents were rejected and the reason for it.",
                "code" => "verification-rejected-mail",
                "type" => 1,
                "content" => "## Verification Documents Rejected
                    Hello {user_name}, unfortunately, your verification documents have been rejected due to the following reason: {reason}.
                    Please review the requirements and submit the correct documents.",
            ]
        );
        
        
        MailSmsTemplate::firstOrCreate(
            [
                "subject" => "Your password updated successfully for {email}!",
                "purpose" => "To be sent when password updated.",
                "code" => "password-updated-mail",
                "type" => 1,
                "content" => "## Password updated successfully
                    Congratulations! Your password has been reset successfully for your account
                    associated with email **{email}** now you can login to your account.
                    [Login Now]({login_link})",
            ]
        );
        
        MailSmsTemplate::firstOrCreate(
            [
                "subject" => "Password Reset Request",
                "purpose" => "To provide the user with a link to reset their password.",
                "code" => "password-reset-request-mail",
                "type" => 1,
                "content" => "## Password Reset Request
                    Hello {user_name}, you have requested a password reset. Please click the following link to reset your password: {reset_link}",
            ]
        );
        
        MailSmsTemplate::firstOrCreate(
            [
                "subject" => "Two-Factor Authentication Enabled",
                "purpose" => "To notify the user that Two-Factor Authentication has been enabled on their account.",
                "code" => "2fa-enabled-mail",
                "type" => 1,
                "content" => "## Two-Factor Authentication Enabled
                    Hello {user_name}, Two-Factor Authentication (2FA) has been successfully enabled for your account to enhance security.",
            ]
        );
        
        MailSmsTemplate::firstOrCreate(
            [
                "subject" => "2FA Settings Reset",
                "purpose" => "To provide the user with a link to reset their 2FA settings.",
                "code" => "2fa-reset-mail",
                "type" => 1,
                "content" => "## 2FA Settings Reset
                    Hello {user_name}, please use the following link to reset your 2FA settings: {reset_link}",
            ]
        );
        
        MailSmsTemplate::firstOrCreate(
            [
                "subject" => "Two-Factor Authentication Disabled",
                "purpose" => "To inform the user that Two-Factor Authentication has been disabled for their account.",
                "code" => "2fa-disabled-mail",
                "type" => 1,
                "content" => "## Two-Factor Authentication Disabled
                    Hello {user_name}, Two-Factor Authentication (2FA) has been disabled for your account.",
            ]
        );
        
        MailSmsTemplate::firstOrCreate(
            [
                "subject" => "Wallet Recharge Request",
                "purpose" => "To inform the admin that a user has requested a wallet recharge.",
                "code" => "wallet-recharge-request-admin-mail",
                "type" => 1,
                "content" => "## Wallet Recharge Request
                    Hello {admin_name}, {user_name} has requested a wallet recharge. Please review the request and take action.",
            ]
        );
        
        MailSmsTemplate::firstOrCreate(
            [
                "subject" => "Wallet Recharge Request Status",
                "purpose" => "To inform the user of the status of their wallet recharge request.",
                "code" => "wallet-recharge-request-status-mail",
                "type" => 1,
                "content" => "## Wallet Recharge Request Status
                    Hello {user_name}, your wallet recharge request has been {status}.
                    Thank you for your patience.",
            ]
        );
        
        MailSmsTemplate::firstOrCreate(
            [
                "subject" => "Payout Request",
                "purpose" => "To notify the admin that a user has requested a payout.",
                "code" => "payout-request-admin-mail",
                "type" => 1,
                "content" => "## Payout Request
                    Hello {admin_name}, {user_name} has requested a payout. Please review and process the request.",
            ]
        );
        
        MailSmsTemplate::firstOrCreate(
            [
                "subject" => "Payout Request Status",
                "purpose" => "To inform the user about the acceptance or rejection of their payout request.",
                "code" => "payout-request-status-mail",
                "type" => 1,
                "content" => "## Payout Request Status
                    Hello {user_name}, your payout request has been {status}. Thank you for using {app.name}.",
            ]
        );
        
        MailSmsTemplate::firstOrCreate(
            [
                "subject" => "Support Ticket Created",
                "purpose" => "To notify the user that their support ticket has been created and is being reviewed.",
                "code" => "support-ticket-created-mail",
                "type" => 1,
                "content" => "## Support Ticket Created
                    Hello {user_name}, your support ticket has been successfully created. Our support team will review it and get back to you shortly.",
            ]
        );
        
        MailSmsTemplate::firstOrCreate(
            [
                "subject" => "Support Ticket Status Update",
                "purpose" => "To notify the user of the status update on their support ticket.",
                "code" => "support-ticket-status-update-mail",
                "type" => 1,
                "content" => "## Support Ticket Status Update
                    Hello {user_name}, the status of your support ticket has been updated to {status}.
                    You can track the progress of your ticket in your support dashboard.",
            ]
        );

        MailSmsTemplate::firstOrCreate(
            [
                "subject" => "Response to Your Support Ticket",
                "purpose" => "To notify the user that they have received a response to their support ticket.",
                "code" => "support-ticket-reply",
                "type" => 1, 
                "content" => "Hello [User],\n\nYou have received a response to your support ticket: {message}\n\nRegards,\n{company}",
            ]
        );

        MailSmsTemplate::firstOrCreate(
            [
                "subject" => "Subscription Purchase By {user_name}",
                "purpose" => "To notify the admin about a successful subscription purchase by a user.",
                "code" => "subscription-purchase-admin-mail",
                "type" => 1, 
                "content" => "Hello {admin_name},\n\n{user_name} has purchased a subscription. Plan details: {plan_details}\n\nRegards,\n{company}",
            ]
        );

        MailSmsTemplate::firstOrCreate(
            [
                "subject" => "Support Ticket Marked as Resolved",
                "purpose" => "To notify the user that their support ticket has been marked as resolved.",
                "code" => "support-ticket-resolved-notification",
                "type" => 1, 
                "content" => "Hello {user_name},\n\nYour support ticket has been marked as resolved. Thank you for reaching out.\n\nRegards,\n{company}",
            ]
        );
        

        MailSmsTemplate::firstOrCreate(
            [
                "subject" => "New Order Received - Proceed to Checkout",
                "purpose" => "To notify the admin about a new order that needs to be processed.",
                "code" => "new-order-proceed-to-checkout",
                "type" => 1, 
                "content" => "Hello {admin_user},\n\nYou have received a new order ({order_id}) from {user_name}.\n\nRegards,\n{company}",
            ]
        );

        MailSmsTemplate::firstOrCreate(
            [
                "subject" => "Order Placed Successfully - Proceed to Checkout",
                "purpose" => "To notify the user that their order has been successfully placed.",
                "code" => "order-placed-proceed-to-checkout",
                "type" => 2, 
                "content" => "Hello {user_name},\n\nYour order ({order_id}) has been successfully placed.\n\nRegards,\n{company}",
            ]
        );

        MailSmsTemplate::firstOrCreate(
            [
                "subject" => "Order Completed",
                "purpose" => "To notify the user that their order has been completed successfully.",
                "code" => "order-completed-user-mail",
                "type" => 1, 
                "content" => "Hello {user_name},\n\nYour order ({order_id}) has been completed successfully.\n\nRegards,\n{company}",
            ]
        );

        MailSmsTemplate::firstOrCreate(
            [
                "subject" => "Order Canceled by User [#USERXX]",
                "purpose" => "To notify the admin about an order cancellation by a user.",
                "code" => "order-canceled-admin-mail",
                "type" => 1, 
                "content" => "Hello {admin_name},\n\n {user_name} has canceled order {order_id}. Reason: {reason}\n\nRegards,\n{company}",
            ]
        );
        
        
        
        
        MailSmsTemplate::firstOrCreate(
            [
                "subject" => "Subscription Renewal Reminder",
                "purpose" => "To notify the user about the upcoming renewal of their subscription.",
                "code" => "subscription-renewal-reminder-mail-user",
                "type" => 1,
                "content" => "## Subscription Renewal Reminder
                    Hello {user_name}, your subscription is about to expire on {expiry_date}.
                    Please renew it to continue enjoying our services.",
            ]
        );
        
        MailSmsTemplate::firstOrCreate(
            [
                "subject" => "Subscription Plan Purchase Successfully",
                "purpose" => "To notify the user that their subscription plan has been purchased successfully.",
                "code" => "subscription-plan-purchase-mail-user",
                "type" => 1,
                "content" => "## Subscription Plan Purchase Successfully
                    Hello {user_name}, Congratulation, we have successfully purchased your subscription plan. Thank you for your continued support. Here are the plan details: {plan_details}",
            ]
        );
        
        MailSmsTemplate::firstOrCreate(
            [
                "subject" => "Subscription Expired",
                "purpose" => "To notify the user that their subscription has expired and is no longer active.",
                "code" => "subscription-plan-expired-mail-user",
                "type" => 1,
                "content" => "## Subscription Expired
                    Hello {user_name}, your subscription has expired. To continue using our services, please renew your subscription.",
            ]
        );
        
        MailSmsTemplate::firstOrCreate(
            [
                "subject" => "Verify your email.",
                "purpose" => "To be sent when user request for email verification manually.",
                "code" => "email-verification-mail",
                "type" => 1,
                "content" => "## Verify your email: {email}
                    We received a verification request for the account associated with
                    email- **{email}** please click the link below to verify your account.
                    Verification Link: [Verify now]({verify_link}) {br}
                    Or
                    You can click below link to verify your account.
                    <{verify_link}>
                    ### If this was not you who requested the verification please ignore this mail.
                    ",
            ]
        );
        
        MailSmsTemplate::firstOrCreate(
            [
                "subject" => "Verify your email.",
                "purpose" => "To be sent when user request for email verification manually with OTP method.",
                "code" => "email-verification-otp-mail",
                "type" => 1,
                "content" => "## Verify your email: {email}
                    We received a verification request for the account associated with
                    email- **{email}** please use verification given below to verify your account.
                    Verification Code: **{otp}**
                    ### If this was not you who requested the verification please ignore this mail.
                    ",
            ]
        ); 
        
        MailSmsTemplate::firstOrCreate(
            [
                "subject" => "Email verified successfully.",
                "purpose" => "To be sent when email verification completed successfully.",
                "code" => "email-verification-success-mail",
                "type" => 1,
                "content" => "## Email verified successfully
                    Congratulations your account verified successfully
                    now you can login and use our platform.
                    [Login Now]({login_link})",
            ]
        );
    }

}
