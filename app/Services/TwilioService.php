<?php

namespace App\Services;

// تأكدي أن هذا السطر موجود ويشير إلى كلاس Twilio الصحيح
use Twilio\Rest\Client;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class TwilioService
{
    // 🚨🚨 التصحيح الأول: يجب تعريف الخصائص هنا 🚨🚨
    protected $twilio;
    protected $fromNumber;

    public function __construct()
    {
        // استخدام config() بدلاً من env() (يجب أن يتم تحديث config)
        $sid    = config('services.twilio.sid') ?? env('TWILIO_SID');
        $token  = config('services.twilio.token') ?? env('TWILIO_TOKEN');
        
        // جلب رقم Twilio الذي تم تصحيحه في .env
        $this->fromNumber = env('TWILIO_FROM'); 

        // Crée un client Twilio
        // يتم إنشاء كائن Client بشكل صحيح هنا
        $this->twilio = new Client($sid, $token);
    }

    /**
     * Génère et envoie le code de vérification par SMS
     */
    public function sendTwoFactorCode(User $user)
    {
        // 1. Génère un code aléatoire de 6 chiffres
        $code = rand(100000, 999999);

        // 2. Met à jour les données de l'utilisateur
        $user->update([
            'code' => $code, 
            'two_factor_expires_at' => now()->addMinutes(10), // Le code est valide pour 10 minutes
        ]);

        $to_number = $user->tele;
        
        // 3. Envoie le message texte
        try {
            // 🚨🚨 التصحيح الثاني: يجب استخدام $this->twilio بدلاً من $twilio
            $this->twilio->messages->create(
                $to_number,
                [
                    'from' => $this->fromNumber,
                    'body' => "Votre code de vérification est : {$code}. Le code est valable 10 minutes."
                ]
            );
            return true;
        } catch (\Exception $e) {
            // تسجيل الخطأ كاملاً للتحقق
            Log::error("Twilio SMS failed for user {$user->id} ({$user->tele}): " . $e->getMessage());

            // إظهار رسالة الخطأ الفعلية في بيئة التطوير (local)
            if (env('APP_ENV') == 'local' || env('APP_DEBUG') == true) {
                throw $e; // هذا سيعرض صفحة الخطأ الصفراء (Ignition) بتفاصيل Twilio
            }
            
            return false;
        }
    }
}