<?php
use yii\helpers\Html;
?>

<style>
    #@import url('https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&family=Sarabun:wght@300;400;500;600;700&display=swap');
    
    .youth-service-report {
       # font-family: 'Prompt', 'Sarabun', 'TH SarabunPSK', sans-serif;
        padding: 20px;
        background: #f0f4f8;
    }
    
    .youth-service-report h2 {
        text-align: center;
        color: #ffffff;
        font-weight: 700;
        margin-bottom: 30px;
        padding: 20px;
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        border-radius: 12px;
        font-size: 26px;
        font-family: 'Prompt', sans-serif;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 15px rgba(79, 172, 254, 0.3);
    }
    
    .table-bordered {
        border: 2px solid #4facfe !important;
        border-radius: 12px;
        overflow: hidden;
        background: white;
        font-family: 'Sarabun', sans-serif;
        box-shadow: 0 4px 12px rgba(79, 172, 254, 0.15);
    }
    
    .table-bordered thead th {
        background: linear-gradient(135deg, #a8d8ff 0%, #d4efff 100%);
        color: #1e3a5f;
        font-weight: 700;
        text-align: center;
        vertical-align: middle;
        padding: 14px 10px;
        border: 1px solid #a8d8ff !important;
        font-size: 16px;
        font-family: 'Prompt', sans-serif;
    }
    
    .table-bordered thead tr:first-child th {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: #ffffff;
        font-weight: 700;
        font-size: 18px;
        font-family: 'Prompt', sans-serif;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }
    
    .table-bordered tbody tr {
        transition: all 0.2s ease;
    }
    
    .table-bordered tbody tr:hover {
        background: #f0f9ff;
        transform: translateY(-1px);
    }
    
    .table-bordered tbody tr:nth-child(odd) {
        background: #ffffff;
    }
    
    .table-bordered tbody tr:nth-child(even) {
        background: #fafcfe;
    }
    
    .table-bordered tbody td {
        text-align: center;
        vertical-align: middle;
        padding: 12px 10px;
        border: 1px solid #e8f4ff !important;
        color: #2c3e50;
        font-size: 15px;
        font-weight: 500;
    }
    
    .table-bordered tbody td:first-child {
        text-align: center;
        font-weight: 600;
        background: #f0f9ff;
        color: #1e3a5f;
        font-size: 15px;
        width: 80px;
    }
    
    .table-bordered tbody td:nth-child(2) {
        text-align: left;
        font-weight: 600;
        background: #f0f9ff;
        color: #1e3a5f;
        padding-left: 18px;
        font-size: 15px;
        font-family: 'Prompt', sans-serif;
    }
    
    .table-bordered tbody td:nth-child(3) {
        background: #f8fbff;
        font-weight: 600;
        color: #2c5282;
        font-size: 15px;
    }
    
    .table-bordered tbody td:last-child {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        font-weight: 700;
        color: #ffffff;
        border-left: 4px solid #5a67d8 !important;
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
    }
    
    .table-bordered tbody td:last-child strong {
        color: #ffffff;
        font-size: 18px;
        font-weight: 800;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        letter-spacing: 0.5px;
    }
    
    .row-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 35px;
        height: 35px;
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
        border-radius: 50%;
        font-weight: 700;
        font-size: 15px;
        box-shadow: 0 2px 6px rgba(79, 172, 254, 0.4);
    }
    
    /* Responsive */
    @media print {
        .youth-service-report {
            background: white;
        }
        .table-bordered tbody td:last-child {
            box-shadow: none;
            text-shadow: none;
        }
    }
    
    @media screen and (max-width: 1200px) {
        .table-bordered {
            font-size: 14px;
        }
        .table-bordered th,
        .table-bordered td {
            padding: 8px 6px;
            font-size: 14px;
        }
        .row-number {
            width: 30px;
            height: 30px;
            font-size: 13px;
        }
    }
</style>

<div class="youth-service-report">
    <h2>แบบฟอร์มเก็บข้อมูลบริการวัยรุ่นและเยาวชน ปีงบประมาณ <?= $fiscalYear ?></h2>
    
    <table class="table table-bordered">
        <thead>
            <tr>
                <th rowspan="2">ลำดับ</th>
                <th rowspan="2">หน่วยงาน / รายการข้อมูล</th>
                <th rowspan="2">กลุ่มอายุ</th>
                <th colspan="12">เดือน</th>
                <th rowspan="2">รวมทั้งปี</th>
            </tr>
            <tr>
                <th>ต.ค.</th>
                <th>พ.ย.</th>
                <th>ธ.ค.</th>
                <th>ม.ค.</th>
                <th>ก.พ.</th>
                <th>มี.ค.</th>
                <th>เม.ย.</th>
                <th>พ.ค.</th>
                <th>มิ.ย.</th>
                <th>ก.ค.</th>
                <th>ส.ค.</th>
                <th>ก.ย.</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $rowNumber = 1;
            foreach ($data as $ageGroup => $genderData): 
                $maleTotal = array_sum($genderData['male']);
                $femaleTotal = array_sum($genderData['female']);
            ?>
                
                <!-- แถวชาย -->
                <tr>
                    <td>
                        <span class="row-number"><?= $rowNumber++ ?></span>
                    </td>
                    <td>จำนวนผู้รับบริการทั้งหมด (ชาย)</td>
                    <td><?= $ageGroup ?> ปี</td>
                    <?php for ($m = 10; $m <= 12; $m++): ?>
                        <td><?= $genderData['male'][$m] ?? 0 ?></td>
                    <?php endfor; ?>
                    <?php for ($m = 1; $m <= 9; $m++): ?>
                        <td><?= $genderData['male'][$m] ?? 0 ?></td>
                    <?php endfor; ?>
                    <td><strong><?= $maleTotal ?></strong></td>
                </tr>
                
                <!-- แถวหญิง -->
                <tr>
                    <td>
                        <span class="row-number"><?= $rowNumber++ ?></span>
                    </td>
                    <td>จำนวนผู้รับบริการทั้งหมด (หญิง)</td>
                    <td><?= $ageGroup ?> ปี</td>
                    <?php for ($m = 10; $m <= 12; $m++): ?>
                        <td><?= $genderData['female'][$m] ?? 0 ?></td>
                    <?php endfor; ?>
                    <?php for ($m = 1; $m <= 9; $m++): ?>
                        <td><?= $genderData['female'][$m] ?? 0 ?></td>
                    <?php endfor; ?>
                    <td><strong><?= $femaleTotal ?></strong></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<!-- ตารางที่สอง: ยาเสพติด -->
<div class="drug-service-report">
    <h3>ยาเสพติด - บำบัดและ-ฟื้นฟูผู้ใช้สารเสพติด</h3>
    
    <table class="table table-drug table-bordered">
        <thead>
            <tr>
                <th rowspan="2">ลำดับ</th>
                <th rowspan="2">รายการ</th>
                <th rowspan="2">กลุ่มอายุ</th>
                <th rowspan="2">รหัส ICD-10 ที่เกี่ยวข้อง</th>
                
                <th colspan="12">เดือน</th>
                <th rowspan="2" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%) !important; color: #ffffff;">รวมทั้งปี</th>
            </tr>
            <tr>
                <th>ต.ค.</th>
                <th>พ.ย.</th>
                <th>ธ.ค.</th>
                <th>ม.ค.</th>
                <th>ก.พ.</th>
                <th>มี.ค.</th>
                <th>เม.ย.</th>
                <th>พ.ค.</th>
                <th>มิ.ย.</th>
                <th>ก.ค.</th>
                <th>ส.ค.</th>
                <th>ก.ย.</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $rowNum = 1;
            foreach ($drugData as $row): 
            ?>
            <tr>
                <td>
                    <span class="row-number-drug"><?= $rowNum++ ?></span>
                </td>
                <td><?= Html::encode($row['service_name']) ?></td>
                <td><?= Html::encode($row['age_group']) ?></td>
                <td><?= Html::encode($row['icd_code']) ?></td>
               
                <td><?= $row['oct'] ?></td>
                <td><?= $row['nov'] ?></td>
                <td><?= $row['dec'] ?></td>
                <td><?= $row['jan'] ?></td>
                <td><?= $row['feb'] ?></td>
                <td><?= $row['mar'] ?></td>
                <td><?= $row['apr'] ?></td>
                <td><?= $row['may'] ?></td>
                <td><?= $row['jun'] ?></td>
                <td><?= $row['jul'] ?></td>
                <td><?= $row['aug'] ?></td>
                <td><?= $row['sep'] ?></td>
                <td><strong><?= $row['total'] ?></strong></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>