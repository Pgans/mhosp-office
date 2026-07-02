<?php
namespace app\models;

use yii\db\ActiveRecord;

class IpdTracking extends ActiveRecord
{
    public static function tableName()
    {
        return 'ipd_reg';
    }

    /**
     * Base SQL query — WHERE a.IS_CANCEL=0 ถูกแทนที่ใน SearchModel
     */
    public static function getBaseQuery(): string
    {
        return "
            SELECT
                CASE
                    WHEN a.WARD_NO='38' THEN 'IPD2'
                    WHEN a.WARD_NO='39' THEN 'IPD1'
                    WHEN a.WARD_NO='22' THEN 'LR'
                    WHEN a.WARD_NO='50' THEN 'HomeWard'
                    WHEN a.WARD_NO='61' THEN 'Ward5ER'
                    WHEN a.WARD_NO='55' THEN 'Ward4'
                    ELSE a.WARD_NO
                END AS ward_name,
                TRIM(b.HN)     AS hn,
                TRIM(a.ADM_ID) AS adm_id,
                a.ADM_DT       AS adm_dt,
                a.DSC_DT       AS dsc_dt,
                CONCAT(
                    CASE
                        WHEN p.PRENAME NOT IN ('') THEN TRIM(p.PRENAME)
                        WHEN TIMESTAMPDIFF(YEAR,p.BIRTHDATE,NOW())<20  AND p.sex='1' AND p.MARRIAGE='4' THEN 'สามเณร'
                        WHEN TIMESTAMPDIFF(YEAR,p.BIRTHDATE,NOW())>=20 AND p.sex='1' AND p.MARRIAGE='4' THEN 'พระภิกษุ'
                        WHEN TIMESTAMPDIFF(YEAR,p.BIRTHDATE,NOW())<15  AND p.sex='1' THEN 'เด็กชาย'
                        WHEN TIMESTAMPDIFF(YEAR,p.BIRTHDATE,NOW())>=15 AND p.sex='1' THEN 'นาย'
                        WHEN TIMESTAMPDIFF(YEAR,p.BIRTHDATE,NOW())<15  AND p.sex='2' THEN 'เด็กหญิง'
                        WHEN TIMESTAMPDIFF(YEAR,p.BIRTHDATE,NOW())>=15 AND p.sex='2' AND p.MARRIAGE='1' THEN 'นางสาว'
                        ELSE 'นาง'
                    END,
                    TRIM(p.FNAME),'  ',TRIM(p.LNAME),
                    ' --อายุ ',FLOOR(DATEDIFF(NOW(),p.BIRTHDATE)/365.25),' ปี'
                ) AS patient_name,
                TRIM(i10.NICKNAME)         AS diagnosis,
                TRIM(p.TELEPHONE)          AS patient_tel,
                TRIM(p.RL_PHONE)           AS relative_tel,
                TRIM(y.caretaker_name)     AS caretaker_name,
                TRIM(y.caretaker_relation) AS caretaker_relation,
                TRIM(y.caretaker_tel)      AS caretaker_tel,
                CASE
                    WHEN a.dsc_status=1 THEN 'Complete Recovery'
                    WHEN a.dsc_status=2 THEN 'Improved'
                    WHEN a.dsc_status=3 THEN 'Not Improved'
                    WHEN a.dsc_status=4 THEN 'Delivered'
                    WHEN a.dsc_status=5 THEN 'Undelivered'
                    WHEN a.dsc_status=6 THEN 'Normal child discharge with mother'
                    WHEN a.dsc_status=7 THEN 'Normal child discharge separately'
                    WHEN a.dsc_status=8 THEN 'Stillbirth'
                    WHEN a.dsc_status=9 THEN 'Dead'
                    ELSE 'Error'
                END AS dsc_status_text,
				CASE
				WHEN b.INSCL in (03,04) AND g.HOSPMAIN ='10953' THEN CONCAT(f.INSCL_NAME,'-ในเขต') 
				WHEN b.INSCL in (03,04) AND g.HOSPMAIN !='10953' THEN CONCAT(f.INSCL_NAME, '-นอกเขต') 
				ELSE f.INSCL_NAME 
				END as 'inscl_name',
                CASE
                    WHEN a.dsc_type=1 THEN 'With Approval'
                    WHEN a.dsc_type=2 THEN 'Against Advice'
                    WHEN a.dsc_type=3 THEN 'Escape'
                    WHEN a.dsc_type=4 THEN 'Transfer'
                    WHEN a.dsc_type=5 THEN 'Other'
                    WHEN a.dsc_type=8 THEN 'Dead, Autopsy'
                    WHEN a.dsc_type=9 THEN 'Dead, No Autopsy'
                    ELSE 'Error'
                END AS dsc_type_text
            FROM ipd_reg a
            LEFT  JOIN opd_visits b        ON a.VISIT_ID=b.VISIT_ID AND b.IS_CANCEL='0'
            INNER JOIN cid_hn c            ON b.HN=c.HN
            INNER JOIN population p        ON c.CID=p.CID
            LEFT  JOIN opd_diagnosis od    ON a.VISIT_ID=od.VISIT_ID AND od.DXT_ID=1
            LEFT  JOIN icd10new i10        ON i10.ICD10=od.ICD10
            LEFT  JOIN admission_surveys y ON a.VISIT_ID=y.visit_id AND y.is_cancel=0
			LEFT JOIN main_inscls f ON b.INSCL = f.INSCL
		    LEFT JOIN uc_inscl g ON c.CID= g.CID AND (g.date_abort > date(b.REG_DATETIME) OR DAY(g.DATE_ABORT)=0)  and trim(g.hospmain) <>'' 
            WHERE a.IS_CANCEL=0
        ";
    }
}