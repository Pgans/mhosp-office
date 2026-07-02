<?php
/**
 * controllers/HealthIdController.php
 *
 * OAuth2 Flow:
 * 1. Redirect ไป Health ID (moph.id.th) → ได้ code
 * 2. แลก code → Health ID Access Token
 * 3. ใช้ Health ID Token เรียก Provider ID API → ได้ Provider Token
 * 4. Auto-create / Login dektrium User
 *    - บันทึก CID ลงตาราง user (column cid หรือ id_card)
 *    - ไม่เขียนทับ CID ที่มีอยู่แล้ว
 *
 * Compatible: PHP 5.6 / 7.0+  (ไม่ใช้ nullable type ?string / ?array / ?? )
 *
 * FIX v4:
 *   - getProviderIdToken() เพิ่ม debug log ละเอียดทุก step
 *   - วิเคราะห์ HTTP code แยก 400 / 401 / 404 / อื่น ๆ
 *   - ตรวจ json_decode error
 *   - log data.data keys ทั้งหมดที่ได้รับจาก Provider ID
 *   - createRawUser($username, $idCard) INSERT CID ลง user table ทันที
 *   - updateUserCid() ไม่เขียนทับ CID ที่มีแล้ว
 *   - findOrCreateUser() ส่ง $idCard ทุก branch
 *   - session->open() / close() กัน state mismatch บน FastCGI
 *   - ตรวจ hospcode ก่อน login
 *   - ไม่เขียน NULL ทับ id_card / hospcode ที่เคยมีค่าแล้ว
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
    //
    // FIX: session->open() + close() บังคับให้ PHP write session file
    //      ลง disk ก่อน redirect ออกไป moph.id.th
    //      แก้ปัญหา state mismatch บน FastCGI / Shared Hosting
    // ============================================================
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $cfg   = Yii::$app->params['healthId'];
        $state = Yii::$app->security->generateRandomString(16);

        Yii::$app->session->open();
        Yii::$app->session->set('healthid_state', $state);
        Yii::$app->session->close(); // ← flush to disk ก่อน redirect

        Yii::info('[actionLogin] state saved: ' . $state . ' session_id: ' . session_id(), 'health-id');

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
        // FIX: บังคับ open session ก่อนอ่านค่า healthid_state
        Yii::$app->session->open();

        $code  = Yii::$app->request->get('code');
        $state = Yii::$app->request->get('state');
        $error = Yii::$app->request->get('error');

        if ($error) {
            Yii::$app->session->setFlash('danger', 'ยกเลิกการเข้าสู่ระบบ: ' . $error);
            return $this->redirect(['/user/security/login']);
        }

        // ---- ตรวจ state ----
        $savedState = Yii::$app->session->get('healthid_state');
        Yii::$app->session->remove('healthid_state');

        Yii::info(
            '[actionCallback] session_id=' . session_id()
            . ' state_in_session=' . ($savedState ? $savedState : 'NOT_FOUND')
            . ' state_from_url='   . ($state ? $state : 'NULL'),
            'health-id'
        );

        if (!$state || $state !== $savedState) {
            Yii::$app->session->setFlash('danger', 'การเข้าสู่ระบบไม่ถูกต้อง (state mismatch) กรุณาลองใหม่');
            return $this->redirect(['/user/security/login']);
        }

        if (!$code) {
            Yii::$app->session->setFlash('danger', 'ไม่ได้รับ code จากระบบ กรุณาลองใหม่');
            return $this->redirect(['/user/security/login']);
        }

        // ---- ทดสอบ connectivity ก่อน ----
        $cfg    = Yii::$app->params['healthId'];
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

        // ================================================================
        // Step 4.5: ตรวจ hospcode ก่อน login
        // ปฏิเสธทันทีถ้า hospcode ไม่ตรงกับ params['allowedHospcode']
        // ================================================================
        $allowedHospcode = isset(Yii::$app->params['allowedHospcode'])
            ? Yii::$app->params['allowedHospcode']
            : null;

        if ($allowedHospcode) {
            $userHospcode = isset($providerData['hospcode']) ? $providerData['hospcode'] : null;

            Yii::info(
                '[actionCallback] ตรวจ hospcode: ได้ "' . ($userHospcode ? $userHospcode : 'NULL')
                . '" ต้องการ "' . $allowedHospcode . '"',
                'health-id'
            );

            if ($userHospcode !== $allowedHospcode) {
                Yii::warning(
                    '[actionCallback] hospcode ไม่ตรง: ได้ "' . ($userHospcode ? $userHospcode : 'NULL')
                    . '" ต้องการ "' . $allowedHospcode . '"'
                    . ' username=' . (isset($providerData['username']) ? $providerData['username'] : '-'),
                    'health-id'
                );
                Yii::$app->session->setFlash('danger',
                    'บัญชีนี้ไม่มีสิทธิ์เข้าสู่ระบบ ' .
                    'ระบบนี้เฉพาะบุคลากรโรงพยาบาลรหัส ' . $allowedHospcode . ' เท่านั้น'
                );
                return $this->redirect(['/user/security/login']);
            }
        }

        // ---- Step 5: Auto-create / Login dektrium User ----
        $user = $this->findOrCreateUser($providerData);
        if (!$user) {
            Yii::$app->session->setFlash('danger', 'ไม่สามารถสร้างบัญชีได้ กรุณาติดต่อผู้ดูแลระบบ');
            return $this->redirect(['/user/security/login']);
        }

        Yii::$app->user->login($user, 3600 * 24);

        $welcomeName = isset($providerData['username']) ? $providerData['username'] : '';
        Yii::$app->session->setFlash('success', 'เข้าสู่ระบบสำเร็จ ยินดีต้อนรับ ' . $welcomeName);

        return $this->goBack();
    }

    // ============================================================
    // Step 3: แลก Authorization Code → Health ID Access Token
    // POST {HealthID-URL}/api/v1/token
    // ============================================================
    protected function exchangeCodeForToken($code)
    {
        $cfg = Yii::$app->params['healthId'];

        $postData = http_build_query([
            'grant_type'    => 'authorization_code',
            'code'          => $code,
            'redirect_uri'  => $cfg['redirectUri'],
            'client_id'     => $cfg['clientId'],
            'client_secret' => $cfg['clientSecret'],
        ]);

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
                CURLOPT_HTTPHEADER     => ['Content-Type: application/x-www-form-urlencoded'],
                CURLOPT_POSTFIELDS     => $postData,
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

            if ($httpCode === 200 && isset($data['data']['access_token'])) {
                $accountId = isset($data['data']['account_id']) ? $data['data']['account_id'] : '-';
                Yii::info('Health ID Token OK, account_id: ' . $accountId, 'health-id');
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
    //
    // v4: เพิ่ม debug log ละเอียดทุก step
    // ============================================================
    protected function getProviderIdToken($healthIdToken)
    {
        $providerCfg = Yii::$app->params['providerId'];
        $apiUrl      = rtrim($providerCfg['baseUrl'], '/') . '/api/v1/services/token';

        // ---- LOG: config ที่ใช้จริง ----
        Yii::info(
            '[getProviderIdToken] ===== START =====' .
            ' URL=' . $apiUrl .
            ' client_id=' . $providerCfg['clientId'] .
            ' secret_key=***' . substr($providerCfg['secretKey'], -4),
            'provider-id'
        );

        // ---- LOG: Health ID Token ที่รับมา ----
        Yii::info(
            '[getProviderIdToken] healthIdToken length=' . strlen($healthIdToken) .
            ' preview=' . substr($healthIdToken, 0, 30) . '...',
            'provider-id'
        );

        $postBody = json_encode([
            'client_id'  => $providerCfg['clientId'],
            'secret_key' => $providerCfg['secretKey'],
            'token_by'   => 'Health ID',
            'token'      => $healthIdToken,
        ]);

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
                CURLOPT_POSTFIELDS => $postBody,
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlErr  = curl_error($ch);
            curl_close($ch);

            // ---- LOG: raw response ทั้งหมด ----
            Yii::info(
                '[getProviderIdToken] HTTP=' . $httpCode .
                ' curlErr="' . $curlErr . '"' .
                ' response=' . $response,
                'provider-id'
            );

            // ---- ตรวจ cURL error ----
            if ($curlErr) {
                Yii::error('[getProviderIdToken] cURL Error: ' . $curlErr, 'provider-id');
                return null;
            }

            // ---- ตรวจ response ว่างเปล่า ----
            if (!$response) {
                Yii::error('[getProviderIdToken] response ว่างเปล่า HTTP=' . $httpCode, 'provider-id');
                return null;
            }

            // ---- ตรวจ JSON parse ----
            $data = json_decode($response, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                Yii::error(
                    '[getProviderIdToken] json_decode ล้มเหลว: ' . json_last_error_msg() .
                    ' raw=' . $response,
                    'provider-id'
                );
                return null;
            }

            // ---- LOG: top-level keys ----
            Yii::info(
                '[getProviderIdToken] top-level keys=' . implode(', ', array_keys($data ?: [])),
                'provider-id'
            );

            // ---- กรณีสำเร็จ HTTP 200 ----
            if ($httpCode === 200 && isset($data['data'])) {

                // ---- LOG: data.data keys ทั้งหมด ----
                Yii::info(
                    '[getProviderIdToken] data.data keys=' . implode(', ', array_keys($data['data'])),
                    'provider-id'
                );

                $jwtPayload = $this->decodeJwtPayload($healthIdToken);
                Yii::info(
                    '[getProviderIdToken] JWT full payload: ' . json_encode($jwtPayload, JSON_UNESCAPED_UNICODE),
                    'provider-id'
                );

                // ---- ดึง id_card ----
                $idCard = $this->extractIdCardFromJwt($jwtPayload);
                if (!$idCard && isset($data['data']['id_card'])) $idCard = $data['data']['id_card'];
                if (!$idCard && isset($data['data']['pid']))     $idCard = $data['data']['pid'];

                // ---- ดึง hospcode ----
                $hospcode     = $this->extractHospcodeFromJwt($jwtPayload);
                $hospcodeKeys = ['hospcode', 'hospital_code', 'hcode', 'hosp_id', 'hospital_id'];
                if (!$hospcode) {
                    foreach ($hospcodeKeys as $key) {
                        if (!empty($data['data'][$key])) {
                            $hospcode = $data['data'][$key];
                            Yii::info('[getProviderIdToken] hospcode found via key "' . $key . '": ' . $hospcode, 'provider-id');
                            break;
                        }
                    }
                }
                // fallback: ดึงจาก params['allowedHospcode']
                if (!$hospcode && isset(Yii::$app->params['allowedHospcode'])) {
                    $hospcode = Yii::$app->params['allowedHospcode'];
                    Yii::info('[getProviderIdToken] hospcode fallback from params[allowedHospcode]: ' . $hospcode, 'provider-id');
                }

                // ---- ดึง username พร้อม fallback ----
                $username     = null;
                $usernameKeys = ['username', 'user_name', 'name', 'account_name', 'login_name'];
                foreach ($usernameKeys as $key) {
                    if (!empty($data['data'][$key])) {
                        $username = $data['data'][$key];
                        Yii::info('[getProviderIdToken] username found via key "' . $key . '": ' . $username, 'provider-id');
                        break;
                    }
                }
                if (!$username && !empty($data['data']['account_id'])) {
                    $username = $data['data']['account_id'];
                    Yii::warning('[getProviderIdToken] username fallback → account_id: ' . $username, 'provider-id');
                }

                // ---- LOG: สรุปผลที่ได้ ----
                Yii::info(
                    '[getProviderIdToken] ===== RESULT =====' .
                    ' username='  . ($username ? $username : 'NULL ⚠️') .
                    ' id_card='   . ($idCard   ? $idCard   : 'NULL') .
                    ' hospcode='  . ($hospcode ? $hospcode : 'NULL'),
                    'provider-id'
                );

                // ---- username ต้องมีเสมอ ----
                if (!$username) {
                    Yii::error(
                        '[getProviderIdToken] ไม่พบ username ใน response!' .
                        ' ลอง keys: ' . implode(', ', $usernameKeys) .
                        ' data.data ทั้งหมด: ' . json_encode($data['data'], JSON_UNESCAPED_UNICODE),
                        'provider-id'
                    );
                    return null;
                }

                return [
                    'access_token'    => isset($data['data']['access_token'])    ? $data['data']['access_token']    : null,
                    'account_id'      => isset($data['data']['account_id'])      ? $data['data']['account_id']      : null,
                    'username'        => $username,
                    'id_card'         => $idCard,
                    'hospcode'        => $hospcode,
                    'login_by'        => isset($data['data']['login_by'])        ? $data['data']['login_by']        : null,
                    'expiration_date' => isset($data['data']['expiration_date']) ? $data['data']['expiration_date'] : null,
                ];
            }

            // ---- วิเคราะห์ error แต่ละ HTTP code ----
            if ($httpCode === 400) {
                Yii::error(
                    '[getProviderIdToken] 400 Bad Request' .
                    ' — ผู้ใช้ไม่มี Provider ID หรือ client_id/secret_key ผิด' .
                    ' response=' . $response,
                    'provider-id'
                );
                return null;
            }

            if ($httpCode === 401) {
                Yii::error(
                    '[getProviderIdToken] 401 Unauthorized' .
                    ' — client_id/secret_key ผิด หรือ IP ไม่ได้ whitelist' .
                    ' response=' . $response,
                    'provider-id'
                );
                return null;
            }

            if ($httpCode === 404) {
                Yii::error(
                    '[getProviderIdToken] 404 Not Found — URL ผิด: ' . $apiUrl,
                    'provider-id'
                );
                return null;
            }

            Yii::error(
                '[getProviderIdToken] Unexpected HTTP=' . $httpCode .
                ' response=' . $response,
                'provider-id'
            );
            return null;

        } catch (\Exception $e) {
            Yii::error('[getProviderIdToken] Exception: ' . $e->getMessage(), 'provider-id');
            return null;
        }
    }

    // ============================================================
    // Helper: ดึง id_card จาก JWT payload (รองรับหลาย structure)
    // ============================================================
    protected function extractIdCardFromJwt($jwtPayload)
    {
        if (empty($jwtPayload)) {
            return null;
        }

        $idCardKeys   = ['id_card', 'pid', 'citizen_id', 'cid', 'national_id', 'personalId', 'personal_id'];
        $scopesDetail = isset($jwtPayload['scopes_detail']) ? $jwtPayload['scopes_detail'] : null;

        if ($scopesDetail !== null) {
            if (is_string($scopesDetail)) {
                $decoded      = json_decode($scopesDetail, true);
                $scopesDetail = is_array($decoded) ? $decoded : [];
            }
            Yii::info(
                '[extractIdCardFromJwt] scopes_detail: ' . json_encode($scopesDetail, JSON_UNESCAPED_UNICODE),
                'provider-id'
            );
            foreach ($idCardKeys as $key) {
                if (!empty($scopesDetail[$key])) {
                    return $scopesDetail[$key];
                }
            }
        }

        foreach ($idCardKeys as $key) {
            if (!empty($jwtPayload[$key])) {
                return $jwtPayload[$key];
            }
        }

        if (isset($jwtPayload['data']) && is_array($jwtPayload['data'])) {
            foreach ($idCardKeys as $key) {
                if (!empty($jwtPayload['data'][$key])) {
                    return $jwtPayload['data'][$key];
                }
            }
        }

        Yii::warning('[extractIdCardFromJwt] ไม่พบ id_card ใน JWT payload', 'provider-id');
        return null;
    }

    // ============================================================
    // Helper: ดึง hospcode จาก JWT payload (รองรับหลาย structure)
    // ============================================================
    protected function extractHospcodeFromJwt($jwtPayload)
    {
        if (empty($jwtPayload)) {
            return null;
        }

        $hospcodeKeys = ['hospcode', 'hospital_code', 'hcode', 'hosp_id', 'hospital_id'];
        $scopesDetail = isset($jwtPayload['scopes_detail']) ? $jwtPayload['scopes_detail'] : null;

        if ($scopesDetail !== null) {
            if (is_string($scopesDetail)) {
                $decoded      = json_decode($scopesDetail, true);
                $scopesDetail = is_array($decoded) ? $decoded : [];
            }
            foreach ($hospcodeKeys as $key) {
                if (!empty($scopesDetail[$key])) {
                    return $scopesDetail[$key];
                }
            }
        }

        foreach ($hospcodeKeys as $key) {
            if (!empty($jwtPayload[$key])) {
                return $jwtPayload[$key];
            }
        }

        if (isset($jwtPayload['data']) && is_array($jwtPayload['data'])) {
            foreach ($hospcodeKeys as $key) {
                if (!empty($jwtPayload['data'][$key])) {
                    return $jwtPayload['data'][$key];
                }
            }
        }

        Yii::warning('[extractHospcodeFromJwt] ไม่พบ hospcode ใน JWT payload', 'provider-id');
        return null;
    }

    // ============================================================
    // Helper: Decode JWT Payload (ไม่ verify signature)
    // ============================================================
    protected function decodeJwtPayload($token)
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            Yii::warning('[decodeJwtPayload] token ไม่ใช่รูปแบบ JWT', 'provider-id');
            return [];
        }

        $base64 = strtr($parts[1], '-_', '+/');
        $pad    = strlen($base64) % 4;
        if ($pad) {
            $base64 .= str_repeat('=', 4 - $pad);
        }

        $payload = base64_decode($base64);
        if ($payload === false) {
            Yii::warning('[decodeJwtPayload] base64_decode ล้มเหลว', 'provider-id');
            return [];
        }

        $decoded = json_decode($payload, true);
        return is_array($decoded) ? $decoded : [];
    }

    // ============================================================
    // Step 5: Auto-create / Login โดยใช้ตาราง provider_id_users
    //
    // Flow:
    //   A)  มีใน provider_id_users และ user ยังอยู่
    //       → updateUserCid()        : เพิ่ม CID ลง user table (ถ้ายังไม่มี)
    //       → updateProviderRecord() : อัปเดต provider_id_users
    //   A1) มีใน provider_id_users แต่ user ถูกลบ
    //       → createRawUser()        : INSERT user (พร้อม CID)
    //       → updateProviderRecord() : อัปเดต provider_id_users
    //   B)  ไม่มีใน provider_id_users แต่มี user อยู่แล้ว
    //       → updateUserCid()        : เพิ่ม CID ลง user table (ถ้ายังไม่มี)
    //       → insertProviderRecord() : สร้าง mapping ใหม่
    //   C)  ใหม่ทั้งคู่
    //       → createRawUser()        : INSERT user (พร้อม CID)
    //       → insertProviderRecord() : สร้าง mapping ใหม่
    // ============================================================
    protected function findOrCreateUser($providerData)
    {
        $providerUsername = isset($providerData['username'])   ? $providerData['username']   : null;
        $accountId        = isset($providerData['account_id']) ? $providerData['account_id'] : null;
        $idCard           = isset($providerData['id_card'])    ? $providerData['id_card']    : null;
        $hospcode         = isset($providerData['hospcode'])   ? $providerData['hospcode']   : null;

        if (!$providerUsername) {
            Yii::error('[findOrCreateUser] ไม่พบ username ใน providerData: ' . json_encode($providerData), 'provider-id');
            return null;
        }

        $db = Yii::$app->db;

        // ---- ค้นหาใน provider_id_users ก่อน ----
        $providerRecord = $db->createCommand(
            'SELECT * FROM provider_id_users WHERE username = :u',
            [':u' => $providerUsername]
        )->queryOne();

        if ($providerRecord) {

            // บัญชีถูกระงับ
            if (!$providerRecord['is_active']) {
                Yii::warning('[findOrCreateUser] บัญชีถูกระงับ: ' . $providerUsername, 'provider-id');
                Yii::$app->session->setFlash('danger', 'บัญชีนี้ถูกระงับการใช้งาน กรุณาติดต่อผู้ดูแลระบบ');
                return null;
            }

            $user = User::findOne($providerRecord['user_id']);

            // --- A1: user_id ชี้ไป user ที่ถูกลบแล้ว → สร้างใหม่ ---
            if (!$user) {
                Yii::warning(
                    '[findOrCreateUser] user_id=' . $providerRecord['user_id'] .
                    ' ไม่มีในตาราง user → สร้างใหม่',
                    'provider-id'
                );
                $user = $this->createRawUser($providerUsername, $idCard);
                if (!$user) {
                    return null;
                }
                $this->updateProviderRecord($db, $providerUsername, $user->id, $idCard, $hospcode);
                return $user;
            }

            // --- A: user มีอยู่แล้ว → อัปเดต CID ถ้ายังไม่มี ---
            if ($idCard) {
                $this->updateUserCid($user->id, $idCard);
            }

            $this->updateProviderRecord($db, $providerUsername, null, $idCard, $hospcode);

            Yii::info(
                '[findOrCreateUser] Login สำเร็จ (existing): ' . $providerUsername .
                ' user_id='  . $user->id .
                ' id_card='  . ($idCard   ? $idCard   : 'kept') .
                ' hospcode=' . ($hospcode ? $hospcode : 'kept'),
                'provider-id'
            );
            return $user;
        }

        // ---- B: ไม่มีใน provider_id_users แต่ username ตรงกับ user ที่มีอยู่ ----
        $existingUser = User::findOne(['username' => $providerUsername]);
        if ($existingUser) {
            if ($idCard) {
                $this->updateUserCid($existingUser->id, $idCard);
            }
            $this->insertProviderRecord($db, $existingUser->id, $providerUsername, $accountId, $idCard, $hospcode);

            Yii::info(
                '[findOrCreateUser] Linked existing user: ' . $providerUsername .
                ' user_id='  . $existingUser->id .
                ' id_card='  . ($idCard   ? $idCard   : 'null') .
                ' hospcode=' . ($hospcode ? $hospcode : 'null'),
                'provider-id'
            );
            return $existingUser;
        }

        // ---- C: ใหม่ทั้งคู่ → สร้าง user พร้อม CID แล้ว map ----
        $user = $this->createRawUser($providerUsername, $idCard);
        if (!$user) {
            return null;
        }

        $this->insertProviderRecord($db, $user->id, $providerUsername, $accountId, $idCard, $hospcode);

        Yii::info(
            '[findOrCreateUser] Auto-created new user: ' . $providerUsername .
            ' user_id='  . $user->id .
            ' id_card='  . ($idCard   ? $idCard   : 'null') .
            ' hospcode=' . ($hospcode ? $hospcode : 'null'),
            'provider-id'
        );
        return $user;
    }

    // ============================================================
    // Helper: สร้าง dektrium User ด้วย Raw SQL
    //
    // v4: รับ $idCard และ INSERT ลง user table ด้วยเลย
    //     รองรับทั้ง column ชื่อ "cid" และ "id_card"
    // ============================================================
    private function createRawUser($username, $idCard = null)
    {
        $db    = Yii::$app->db;
        $email = $username . '@provider.id.th';

        // ถ้า email ซ้ำ → คืน user เดิม (พร้อมอัปเดต CID ถ้ายังไม่มี)
        $existingByEmail = User::findOne(['email' => $email]);
        if ($existingByEmail) {
            if ($idCard) {
                $this->updateUserCid($existingByEmail->id, $idCard);
            }
            return $existingByEmail;
        }

        $passwordHash = Yii::$app->security->generatePasswordHash(
            Yii::$app->security->generateRandomString(20)
        );

        // ดึง column list เพื่อ INSERT เฉพาะ column ที่มีจริง
        $columns  = $db->createCommand('SHOW COLUMNS FROM {{%user}}')->queryAll();
        $colNames = array_map(function ($c) { return $c['Field']; }, $columns);

        $data = [];
        if (in_array('username', $colNames))        $data['username']        = $username;
        if (in_array('email', $colNames))           $data['email']           = $email;
        if (in_array('password_hash', $colNames))   $data['password_hash']   = $passwordHash;
        if (in_array('auth_key', $colNames))        $data['auth_key']        = Yii::$app->security->generateRandomString();
        if (in_array('confirmed_at', $colNames))    $data['confirmed_at']    = time();
        if (in_array('status', $colNames))          $data['status']          = 20;
        if (in_array('created_at', $colNames))      $data['created_at']      = time();
        if (in_array('updated_at', $colNames))      $data['updated_at']      = time();
        if (in_array('registration_ip', $colNames)) $data['registration_ip'] = Yii::$app->request->userIP;
        if (in_array('flags', $colNames))           $data['flags']           = 0;
        if (in_array('blocked_at', $colNames))      $data['blocked_at']      = null;
        if (in_array('last_login_at', $colNames))   $data['last_login_at']   = time();

        // ✅ INSERT CID ลง user table ทันทีตอนสร้าง
        //    รองรับทั้ง column "cid" (ทั่วไป) และ "id_card" (บาง schema)
        if ($idCard) {
            if (in_array('cid', $colNames))     $data['cid']     = $idCard;
            if (in_array('id_card', $colNames)) $data['id_card'] = $idCard;
        }

        try {
            $db->createCommand()->insert('{{%user}}', $data)->execute();
            $user = User::findOne(['username' => $username]);
            if (!$user) {
                Yii::error('[createRawUser] INSERT สำเร็จแต่ findOne ไม่เจอ: ' . $username, 'provider-id');
                return null;
            }
            Yii::info(
                '[createRawUser] สร้างสำเร็จ user_id=' . $user->id .
                ' username=' . $username .
                ' cid=' . ($idCard ? $idCard : 'null'),
                'provider-id'
            );
            return $user;

        } catch (\Exception $e) {
            Yii::error('[createRawUser] Exception: ' . $e->getMessage(), 'provider-id');
            // กรณี race condition → ลอง findOne อีกครั้ง
            $user = User::findOne(['username' => $username]);
            return $user ? $user : null;
        }
    }

    // ============================================================
    // Helper: อัปเดต CID ให้ existing user ใน user table
    //
    // กฎ: ไม่เขียนทับถ้า CID เดิมมีค่าแล้ว
    //     รองรับทั้ง column ชื่อ "cid" และ "id_card"
    // ============================================================
    private function updateUserCid($userId, $idCard)
    {
        if (!$userId || !$idCard) {
            return;
        }

        $db       = Yii::$app->db;
        $columns  = $db->createCommand('SHOW COLUMNS FROM {{%user}}')->queryAll();
        $colNames = array_map(function ($c) { return $c['Field']; }, $columns);

        // เลือก column ที่จะใช้ (ให้ความสำคัญ "cid" ก่อน)
        $cidCol = null;
        if (in_array('cid', $colNames)) {
            $cidCol = 'cid';
        } elseif (in_array('id_card', $colNames)) {
            $cidCol = 'id_card';
        }

        if (!$cidCol) {
            Yii::warning('[updateUserCid] ไม่พบ column cid/id_card ในตาราง user', 'provider-id');
            return;
        }

        // ตรวจว่า user มี CID แล้วหรือยัง → ถ้ามีแล้วไม่เขียนทับ
        $existing = $db->createCommand(
            "SELECT {$cidCol} FROM {{%user}} WHERE id = :uid",
            [':uid' => $userId]
        )->queryScalar();

        if ($existing) {
            Yii::info(
                '[updateUserCid] user_id=' . $userId .
                ' มี ' . $cidCol . ' แล้ว (' . $existing . ') → ไม่อัปเดต',
                'provider-id'
            );
            return;
        }

        // อัปเดต CID พร้อม updated_at
        $sets   = ["{$cidCol} = :cid"];
        $params = [':cid' => $idCard, ':uid' => $userId];

        if (in_array('updated_at', $colNames)) {
            $sets[]         = 'updated_at = :now';
            $params[':now'] = time();
        }

        $db->createCommand(
            'UPDATE {{%user}} SET ' . implode(', ', $sets) . ' WHERE id = :uid',
            $params
        )->execute();

        Yii::info(
            '[updateUserCid] UPDATE ' . $cidCol . ' OK' .
            ' user_id=' . $userId .
            ' cid=' . $idCard,
            'provider-id'
        );
    }

    // ============================================================
    // Helper: UPDATE provider_id_users
    // ไม่เขียน NULL ทับ id_card / hospcode ที่เคยมีค่าแล้ว
    // ============================================================
    private function updateProviderRecord($db, $username, $newUserId, $idCard, $hospcode)
    {
        $sets   = ['last_login = NOW()'];
        $params = [':u' => $username];

        if ($newUserId) {
            $sets[]         = 'user_id = :uid';
            $params[':uid'] = $newUserId;
        }
        if ($idCard) {
            $sets[]        = 'id_card = :ic';
            $params[':ic'] = $idCard;
        }
        if ($hospcode) {
            $sets[]           = 'hospcode = :hcode';
            $params[':hcode'] = $hospcode;
        }

        $db->createCommand(
            'UPDATE provider_id_users SET ' . implode(', ', $sets) . ' WHERE username = :u',
            $params
        )->execute();

        Yii::info(
            '[updateProviderRecord] UPDATE OK: ' . $username .
            ' id_card='  . ($idCard   ? $idCard   : 'kept') .
            ' hospcode=' . ($hospcode ? $hospcode : 'kept'),
            'provider-id'
        );
    }

    // ============================================================
    // Helper: INSERT provider_id_users mapping (record ใหม่)
    // ถ้า username ซ้ำ → ทำ UPDATE แทน (safe upsert)
    // ============================================================
    private function insertProviderRecord($db, $userId, $username, $accountId, $idCard = null, $hospcode = null)
    {
        try {
            $exists = $db->createCommand(
                'SELECT COUNT(*) FROM provider_id_users WHERE username = :u',
                [':u' => $username]
            )->queryScalar();

            if ($exists) {
                // มีแล้ว → UPDATE แทน
                $this->updateProviderRecord($db, $username, $userId, $idCard, $hospcode);
            } else {
                $db->createCommand()->insert('provider_id_users', [
                    'user_id'    => $userId,
                    'username'   => $username,
                    'account_id' => $accountId,
                    'id_card'    => $idCard,
                    'hospcode'   => $hospcode,
                    'is_active'  => 1,
                    'last_login' => date('Y-m-d H:i:s'),
                ])->execute();

                Yii::info(
                    '[insertProviderRecord] INSERT OK: ' . $username .
                    ' user_id='  . $userId .
                    ' id_card='  . ($idCard   ? $idCard   : 'null') .
                    ' hospcode=' . ($hospcode ? $hospcode : 'null'),
                    'provider-id'
                );
            }

        } catch (\Exception $e) {
            Yii::error('[insertProviderRecord] Exception: ' . $e->getMessage(), 'provider-id');
        }
    }

    // ============================================================
    // Static Helper: ตรวจว่า user นี้ login ผ่าน Provider ID หรือไม่
    // ตัวอย่าง: HealthIdController::isProviderIdUser(Yii::$app->user->id)
    // ============================================================
    public static function isProviderIdUser($userId)
    {
        if (!$userId) return false;
        return (int)Yii::$app->db->createCommand(
            'SELECT COUNT(*) FROM provider_id_users WHERE user_id = :uid AND is_active = 1',
            [':uid' => $userId]
        )->queryScalar() > 0;
    }

    // ============================================================
    // Static Helper: ตรวจว่า user นี้มี hospcode ที่กำหนดหรือไม่
    // ตัวอย่าง: HealthIdController::hasHospcode(Yii::$app->user->id, '10953')
    // ============================================================
    public static function hasHospcode($userId, $hospcode)
    {
        if (!$userId || !$hospcode) return false;
        return (int)Yii::$app->db->createCommand(
            'SELECT COUNT(*) FROM provider_id_users
             WHERE user_id = :uid AND is_active = 1 AND hospcode = :hcode',
            [':uid' => $userId, ':hcode' => $hospcode]
        )->queryScalar() > 0;
    }

} // ← ปิด class