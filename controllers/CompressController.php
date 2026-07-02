<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;

class CompressController extends Controller
{
    public $enableCsrfValidation = false;

    // แสดง process gzip/pigz จาก server ต่าง ๆ
    public function actionIndex()
    {
        $servers = [
            ['host' => '192.168.200.7', 'user' => 'root'],
			['host' => '192.168.200.77', 'user' => 'm30'],
        ];

        $results = [];

        foreach ($servers as $server) {
            $host = $server['host'];
            $user = $server['user'];

            $cmd = "ssh {$user}@{$host} \"ps -eo pid,etime,comm | grep -E 'gzip|pigz' | grep -v grep\"";

            $output = shell_exec($cmd);
            if ($output) {
                $lines = explode("\n", trim($output));
                foreach ($lines as $line) {
                    if (trim($line) === '') continue;

                    $parts = preg_split('/\s+/', $line, 3);
                    if (count($parts) >= 3) {
                        list($pid, $etime, $cmd) = $parts;
                        $results[] = [
                            'host' => $host,
                            'user' => $user,
                            'pid' => $pid,
                            'etime' => $etime,
                            'cmd' => $cmd,
                        ];
                    }
                }
            }
        }

        return $this->render('index', [
            'processes' => $results,
        ]);
    }

    // คำสั่ง kill gzip/pigz
    public function actionKill()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $host = Yii::$app->request->post('host');
        $user = Yii::$app->request->post('user');
        $pid = Yii::$app->request->post('pid');

        if ($host && $user && $pid && is_numeric($pid)) {
            $cmd = "ssh {$user}@{$host} \"kill -9 {$pid}\"";
            $result = shell_exec($cmd);
            return ['status' => 'success', 'message' => "Killed PID {$pid} on {$host}"];
        }

        return ['status' => 'error', 'message' => 'Invalid parameters'];
    }
}
