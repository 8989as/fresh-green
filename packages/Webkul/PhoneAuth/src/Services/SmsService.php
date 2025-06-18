<?php

namespace Webkul\PhoneAuth\Services;

use Exception;
use Illuminate\Support\Facades\Log;

class SmsService
{
    public function send($phone, $message)
    {
        // For now, just log the message for local/testing
        if (config('phoneauth.sms_provider') === 'log') {
            Log::info("SMS to $phone: $message");
            return true;
        }
        
        // Production: Twilio integration
        if (config('phoneauth.sms_provider') === 'twilio') {
            // Check if Twilio is installed
            if (!class_exists('\Twilio\Rest\Client')) {
                Log::error('Twilio SDK not installed. Run: composer require twilio/sdk');
                throw new Exception('Twilio SDK not installed. Please run: composer require twilio/sdk');
            }
            
            $sid = config('phoneauth.twilio.sid');
            $token = config('phoneauth.twilio.token');
            $from = config('phoneauth.twilio.from');
            
            if (empty($sid) || empty($token) || empty($from)) {
                Log::error('Twilio configuration missing');
                throw new Exception('Twilio configuration missing. Check phoneauth.twilio config values.');
            }
            
            try {
                // Log Twilio credentials (without the actual token for security)
                Log::info("Twilio Config: Using SID=" . substr($sid, 0, 8) . "..., From=" . $from);
                Log::info("Sending SMS to $phone with message: $message");
                
                $twilio = new \Twilio\Rest\Client($sid, $token);
                $result = $twilio->messages->create($phone, [
                    'from' => $from,
                    'body' => $message
                ]);
                
                // Log success information
                Log::info("Twilio SMS sent successfully. SID: " . $result->sid);
                return true;
            } catch (\Twilio\Exceptions\RestException $e) {
                Log::error('Twilio Error: ' . $e->getMessage() . ' (Code: ' . $e->getCode() . ')');
                
                // For trial accounts, provide helpful message about verification
                if (strpos($e->getMessage(), 'unverified') !== false) {
                    Log::warning("You're using a Twilio trial account. The phone number $phone needs to be verified at twilio.com/console/phone-numbers/verified");
                    // Continue execution but log the error
                    return true;
                }
                
                throw $e;
            } catch (\Exception $e) {
                Log::error('General error sending SMS: ' . $e->getMessage());
                throw $e;
            }
        }
        
        throw new Exception('SMS provider not properly configured.');
    }
}
