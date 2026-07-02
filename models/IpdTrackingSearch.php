<?php
namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ArrayDataProvider;

class IpdTrackingSearch extends Model
{
    public $adm_id;     // ค้นหาจาก AN
   #public $hn;         // ค้นหาจาก HN
    public $date_from;  // วันจำหน่าย เริ่มต้น
    public $date_to;    // วันจำหน่าย สิ้นสุด

    public function rules()
    {
        return [
            [['adm_id', 'hn', 'date_from', 'date_to'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'adm_id'    => 'AN (ADM_ID)',
            'hn'        => 'HN',
            'date_from' => 'วันจำหน่าย (เริ่ม)',
            'date_to'   => 'วันจำหน่าย (สิ้นสุด)',
        ];
    }

    public function search(array $params): ArrayDataProvider
    {
        $this->load($params);

        // ค่าเริ่มต้น: วันนี้
        if (empty($this->date_from)) $this->date_from = date('Y-m-d');
        if (empty($this->date_to))   $this->date_to   = date('Y-m-d');

        $sql    = IpdTracking::getBaseQuery();
        $wheres = ['a.IS_CANCEL=0'];
        $binds  = [];

        // ค้นหาจาก ADM_ID (AN)
        if (!empty($this->adm_id)) {
            $wheres[] = "TRIM(a.ADM_ID) = :adm_id";
            $binds[':adm_id'] = trim($this->adm_id);
        }

        // ค้นหาจาก HN
        if (!empty($this->hn)) {
            $wheres[] = "TRIM(b.HN) = :hn";
            $binds[':hn'] = trim($this->hn);
        }

        // กรองวันจำหน่าย เมื่อไม่ได้ระบุ adm_id หรือ hn
        if (empty($this->adm_id) && empty($this->hn)) {
            $wheres[] = "a.DSC_DT BETWEEN :date_from AND :date_to";
            $binds[':date_from'] = $this->date_from . ' 00:00:00';
            $binds[':date_to']   = $this->date_to   . ' 23:59:59';
        }

        // แทน WHERE clause ใน base query
        $sql = str_replace(
            'WHERE a.IS_CANCEL=0',
            'WHERE ' . implode(' AND ', $wheres),
            $sql
        );
        $sql .= " GROUP BY a.ADM_ID ORDER BY a.WARD_NO, a.DSC_DT";

        $rows = Yii::$app->db2->createCommand($sql, $binds)->queryAll();

        return new ArrayDataProvider([
            'allModels'  => $rows,
            'pagination' => ['pageSize' => 30],
            'sort'       => [
                'attributes' => ['ward_name', 'adm_dt', 'dsc_dt', 'patient_name'],
            ],
        ]);
    }
}