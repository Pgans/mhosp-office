<?php
use yii\helpers\Html;
$this->title = $name;
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;600&display=swap" rel="stylesheet">
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body {
    min-height: 100vh;
    display: flex; align-items: center; justify-content: center;
    background: linear-gradient(135deg, #0f1b35 0%, #1a2a50 60%, #0d1829 100%);
    font-family: 'Sarabun', sans-serif;
}
.card {
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 20px;
    padding: 48px 44px;
    max-width: 480px; width: 90%;
    text-align: center;
    backdrop-filter: blur(10px);
}
.icon-wrap {
    width: 90px; height: 90px;
    background: rgba(239,68,68,0.12);
    border: 2px solid rgba(239,68,68,0.35);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 28px;
}
.icon-wrap svg { width: 44px; height: 44px; }
.code-badge {
    display: inline-block;
    background: rgba(239,68,68,0.18);
    color: #f87171;
    border: 1px solid rgba(239,68,68,0.35);
    border-radius: 8px;
    padding: 3px 12px;
    font-size: 12px; font-weight: 600; letter-spacing: 1.5px;
    margin-bottom: 16px;
}
h1 { color: #f1f5f9; font-size: 26px; font-weight: 600; margin-bottom: 12px; }
.subtitle { color: rgba(241,245,249,0.5); font-size: 15px; line-height: 1.7; margin-bottom: 28px; }
hr { border: none; border-top: 1px solid rgba(255,255,255,0.07); margin-bottom: 20px; }
.desc { color: rgba(241,245,249,0.4); font-size: 13px; line-height: 1.8; margin-bottom: 28px; }
.search-row { display: flex; gap: 8px; margin-bottom: 24px; }
.search-row input {
    flex: 1; background: rgba(255,255,255,0.06);
    border: 1px solid rgba(255,255,255,0.12);
    border-radius: 10px; padding: 9px 14px;
    color: #f1f5f9; font-family: 'Sarabun',sans-serif; font-size: 14px;
}
.search-row input::placeholder { color: rgba(241,245,249,0.3); }
.search-row button {
    background: rgba(255,255,255,0.08);
    border: 1px solid rgba(255,255,255,0.12);
    border-radius: 10px; padding: 9px 14px; color: rgba(241,245,249,0.7); cursor: pointer;
}
.btns { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
.btn-home {
    background: #4f46e5; color: #fff; border: none;
    border-radius: 10px; padding: 10px 22px;
    font-family: 'Sarabun',sans-serif; font-size: 14px; cursor: pointer;
    display: flex; align-items: center; gap: 6px; text-decoration: none;
}
.btn-back {
    background: transparent; color: rgba(241,245,249,0.6);
    border: 1px solid rgba(255,255,255,0.15);
    border-radius: 10px; padding: 10px 22px;
    font-family: 'Sarabun',sans-serif; font-size: 14px; cursor: pointer;
    text-decoration: none;
}
</style>
</head>
<body>
<div class="card">
    <div class="icon-wrap">
        <svg viewBox="0 0 24 24" fill="none" stroke="#f87171" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/>
            <path d="M15 9l-6 6M9 9l6 6"/>
        </svg>
    </div>
    <div class="code-badge">
        <?= isset($exception) ? 'ERROR ' . $exception->statusCode : 'ERROR' ?>
    </div>
    <h1>ไม่มีสิทธิ์เข้าถึง</h1>
    <p class="subtitle"><?= Html::encode($message) ?></p>
    <hr>
    <p class="desc">
        เกิดข้อผิดพลาดขณะที่เว็บเซิร์ฟเวอร์กำลังประมวลผลคำขอของคุณ<br>
        กรุณาติดต่อเราหากคุณคิดว่านี่เป็นข้อผิดพลาดของระบบ
    </p>
    <form class="search-row" action="/search" method="get">
        <input type="text" name="q" placeholder="ค้นหา...">
        <button type="submit">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
            </svg>
        </button>
    </form>
    <div class="btns">
        <?= Html::a('🏠 กลับหน้าหลัก', ['/site/index'], ['class' => 'btn-home']) ?>
        <a href="javascript:history.back()" class="btn-back">← ย้อนกลับ</a>
    </div>
</div>
</body>
</html>