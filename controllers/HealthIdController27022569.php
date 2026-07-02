<?php
/**
 * controllers/HealthIdController.php
 *
 * OAuth2 Flow:
 * 1. Redirect ไป Health ID (moph.id.th) → ได้ code
 * 2. แลก code → Health ID Access Token
 * 3. ใช้ Health ID Token เรียก Provider ID API → ได้ Provider Token
 * 4. Auto-create / Login dektrium User
 */

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use dektrium\user\models\User;

class HealthIdController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'login'    => ['get'],
                    'callback' => ['get'],
                ],
            ],
        ];
    }

    // ============================================================
    // Step 1: Redirect ไป Health ID
    // URL: index.php?r=health-id/login
    // ============================================================
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $cfg = Yii::$app->params['healthId'];

        $state = Yii::$app->security->generateRandomString(16);
        Yii::$app->session->set('healthid_state', $state);

        $redirectUrl = $cfg['baseUrl'] . '/oauth/redirect?' . http_build_query([
            'client_id'     => $cfg['clientId'],
            'redirect_uri'  => $cfg['redirectUri'],
            'response_type' => 'code',
            'state'         => $state,
        ]);

        return $this->redirect($redirectUrl);
    }

    // ============================================================
    // Step 2: รับ code จาก Health ID
    // URL: index.php?r=health-id/callback?code=xxx&state=xxx
    // ============================================================
    public function actionCallback()
    {
        $code  = Yii::$app->request->get('code');
        $state = Yii::$app->request->get('state');
        $error = Yii::$app->request->get('error');

        if ($error) {
            Yii::$app->session->setFlash('danger', 'ยกเลิกการเข้าสู่ระบบ: ' . $error);
            return $this->redirect(['/user/security/login']);
        }

        // ตรวจ state
        $savedState = Yii::$app->session->get('healthid_state');
        Yii::$app->session->remove('healthid_state');

        if (!$state || $state !== $savedState) {
            Yii::$app->session->setFlash('danger', 'การเข้าสู่ระบบไม่ถูกต้อง (state mismatch) กรุณาลองใหม่');
            return $this->redirect(['/user/security/login']);
        }

        if (!$code) {
            Yii::$app->session->setFlash('danger', 'ไม่ได้รับ code จากระบบ กรุณาลองใหม่');
            return $this->redirect(['/user/security/login']);
        }

        // ---- ทดสอบ connectivity ก่อน ----
        $cfg = Yii::$app->params['healthId'];
        $testCh = curl_init();
        curl_setopt_array($testCh, [
            CURLOPT_URL            => rtrim($cfg['baseUrl'], '/') . '/api/v1/token',
            CURLOPT_NOBODY         => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 5,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);
        curl_exec($testCh);
        $connectError = curl_error($testCh);
        curl_close($testCh);

        if ($connectError) {
            Yii::error('ไม่สามารถเชื่อมต่อ ' . $cfg['baseUrl'] . ' ได้: ' . $connectError, 'health-id');
            Yii::$app->session->setFlash('danger',
                'Server ไม่สามารถเชื่อมต่อ moph.id.th ได้ ' .
                'กรุณาติดต่อ IT ให้เปิด Firewall: ' . $cfg['baseUrl'] . ' port 443'
            );
            return $this->redirect(['/user/security/login']);
        }

        // ---- Step 3: แลก code → Health ID Access Token ----
        $healthToken = $this->exchangeCodeForToken($code);
        if (!$healthToken) {
            Yii::$app->session->setFlash('danger', 'ไม่สามารถรับ Health ID Token ได้ กรุณาดู log ที่ runtime/logs/app.log');
            return $this->redirect(['/user/security/login']);
        }

        // ---- Step 4: เรียก Provider ID API ด้วย Health ID Token ----
        $providerData = $this->getProviderIdToken($healthToken);

        if (!$providerData) {
            Yii::$app->session->setFlash('danger', 'บัญชีนี้ไม่มี Provider ID กรุณาติดต่อผู้ดูแลระบบ');
            return $this->redirect(['/user/security/login']);
        }

        // ---- Step 5: Auto-create / Login dektrium User ----
        $user = $this->findOrCreateUser($providerData);
        if (!$user) {
            Yii::$app->session->setFlash('danger', 'ไม่สามารถสร้างบัญชีได้ กรุณาติดต่อผู้ดูแลระบบ');
            return $this->redirect(['/user/security/login']);
        }

        Yii::$app->user->login($user, 3600 * 24);
        Yii::$app->session->setFlash('success', 'เข้าสู่ระบบสำเร็จ ยินดีต้อนรับ ' . ($providerData['username'] ?? ''));

        return $this->goBack();
    }

    // ============================================================
    // Step 3: แลก Authorization Code → Health ID Access Token
    // POST {HealthID-URL}/api/v1/token
    // ============================================================
    protected function exchangeCodeForToken(string $code): ?string
    {
        $cfg = Yii::$app->params['healthId'];

        $postData = http_build_query([
            'grant_type'    => 'authorization_code',
            'code'          => $code,
            'redirect_uri'  => $cfg['redirectUri'],
            'client_id'     => $cfg['clientId'],
            'client_secret' => $cfg['clientSecret'],
        ]);

        // *** endpoint จาก API Spec: /api/v1/token ***
        $url = rtrim($cfg['baseUrl'], '/') . '/api/v1/token';

        Yii::info('Token request to: ' . $url, 'health-id');
        Yii::info('redirect_uri: ' . $cfg['redirectUri'], 'health-id');

        try {
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL            => $url,
                CURLOPT_POST           => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT        => 15,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_HTTPHEADER     => [
                    'Content-Type: application/x-www-form-urlencoded',
                ],
                CURLOPT_POSTFIELDS => $postData,
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlErr  = curl_error($ch);
            curl_close($ch);

            Yii::info('Token response [' . $httpCode . ']: ' . $response, 'health-id');

            if ($curlErr) {
                Yii::error('Token cURL error: ' . $curlErr, 'health-id');
                return null;
            }

            $data = json_decode($response, true);

            // Response: { "status":"success", "data": { "access_token":"...", "account_id":"..." } }
            if ($httpCode === 200 && isset($data['data']['access_token'])) {
                Yii::info('Health ID Token OK, account_id: ' . ($data['data']['account_id'] ?? '-'), 'health-id');
                return $data['data']['access_token'];
            }

            Yii::error('Token failed [' . $httpCode . ']: ' . $response, 'health-id');
            return null;

        } catch (\Exception $e) {
            Yii::error('Token exception: ' . $e->getMessage(), 'health-id');
            return null;
        }
    }

    // ============================================================
    // Step 4: เรียก Provider ID API ด้วย Health ID Token
    // POST {Provider-API}/api/v1/services/token
    // ============================================================
    protected function getProviderIdToken(string $healthIdToken): ?array
    {
        $cfg         = Yii::$app->params['healthId'];
        $providerCfg = Yii::$app->params['providerId'];

        $apiUrl = $providerCfg['baseUrl'] . '/api/v1/services/token';

        try {
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL            => $apiUrl,
                CURLOPT_POST           => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT        => 15,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_HTTPHEADER     => [
                    'Content-Type: application/json',
                    'Accept: application/json',
                ],
                CURLOPT_POSTFIELDS => json_encode([
                    'client_id'  => $providerCfg['clientId'],
                    'secret_key' => $providerCfg['secretKey'],
                    'token_by'   => 'Health ID',         // ตามเอกสาร API
                    'token'      => $healthIdToken,       // Health ID access_token
                ]),
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlErr  = curl_error($ch);
            curl_close($ch);

            Yii::info('Provider ID Token [' . $httpCode . ']: ' . $response, 'provider-id');

            if ($curlErr) {
                Yii::error('Provider ID cURL error: ' . $curlErr, 'provider-id');
                return null;
            }

            $data = json_decode($response, true);

            // 200 = มี Provider ID
            if ($httpCode === 200 && isset($data['data'])) {
                return [
                    'access_token'    => $data['data']['access_token']    ?? null,
                    'account_id'      => $data['data']['account_id']      ?? null,
                    'username'        => $data['data']['username']         ?? null,
                    'login_by'        => $data['data']['login_by']         ?? null,
                    'expiration_date' => $data['data']['expiration_date']  ?? null,
                ];
            }

            // 400 = ไม่มี Provider ID
            if ($httpCode === 400) {
                Yii::warning('ผู้ใช้ไม่มี Provider ID: ' . $response, 'provider-id');
                return null;
            }

            Yii::error('Provider ID unexpected response [' . $httpCode . ']: ' . $response, 'provider-id');
            return null;

        } catch (\Exception $e) {
            Yii::error('Provider ID exception: ' . $e->getMessage(), 'provider-id');
            return null;
        }
    }

    // ============================================================
    // Step 5: Auto-create โดยใช้ตาราง provider_id_users
    // ============================================================
    protected function findOrCreateUser(array $providerData): ?User
    {
        $providerUsername = $providerData['username']   ?? null;
        $accountId        = $providerData['account_id'] ?? null;

        if (!$providerUsername) {
            Yii::error('ไม่พบ username จาก Provider ID: ' . json_encode($providerData), 'provider-id');
            return null;
        }

        $db = Yii::$app->db;

        // ---- ค้นหาใน provider_id_users ----
        $providerRecord = $db->createCommand(
            'SELECT * FROM provider_id_users WHERE username = :u',
            [':u' => $providerUsername]
        )->queryOne();

        if ($providerRecord) {
            // ---- มีแล้ว ตรวจสถานะ ----
            if (!$providerRecord['is_active']) {
                Yii::warning('Provider user ถูกระงับ: ' . $providerUsername, 'provider-id');
                Yii::$app->session->setFlash('danger', 'บัญชีนี้ถูกระงับการใช้งาน กรุณาติดต่อผู้ดูแลระบบ');
                return null;
            }

            // อัปเดต last_login
            $db->createCommand('UPDATE provider_id_users SET last_login = NOW() WHERE username = :u', [':u' => $providerUsername])->execute();

            // ดึง dektrium user
            $user = User::findOne($providerRecord['user_id']);
            if (!$user) {
                Yii::error('ไม่พบ user_id: ' . $providerRecord['user_id'], 'provider-id');
                return null;
            }

            Yii::info('Login สำเร็จ (existing): ' . $providerUsername, 'provider-id');
            return $user;
        }

        // ---- ไม่มีในตาราง → Auto-create ----
        Yii::info('Auto-create user ใหม่: ' . $providerUsername, 'provider-id');

        // สร้าง dektrium user
        $user           = new User();
        $user->username = $providerUsername;
        $user->email    = $providerUsername . '@provider.id.th';
        $user->password = Yii::$app->security->generateRandomString(20);

        if (property_exists($user, 'confirmed_at')) {
            $user->confirmed_at = time();
        }

        try {
            $user->save(false); // false = ข้าม validation ป้องกัน duplicate check
        } catch (\yii\db\IntegrityException $e) {
            // FK profile → userx error — user อาจถูกสร้างแล้ว ค้นหาใหม่
            Yii::warning('IntegrityException (profile FK): ' . $e->getMessage(), 'provider-id');
        }

        // ค้นหา user ที่สร้างหรือมีอยู่แล้ว
        $user = User::findOne(['username' => $providerUsername]);
        if (!$user) {
            Yii::error('ไม่พบ user หลัง save: ' . $providerUsername, 'provider-id');
            return null;
        }

        // บันทึกลง provider_id_users
        $db->createCommand()->insert('provider_id_users', [
            'user_id'    => $user->id,
            'username'   => $providerUsername,
            'account_id' => $accountId,
            'is_active'  => 1,
            'last_login' => date('Y-m-d H:i:s'),
        ])->execute();

        Yii::info('Auto-created สำเร็จ: ' . $providerUsername . ' (user_id=' . $user->id . ')', 'provider-id');
        return $user;
    }
}