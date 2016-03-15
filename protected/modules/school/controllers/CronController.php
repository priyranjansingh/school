<?php

class CronController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/school_column2';

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function actionStart() {
        $schools = BaseModel::getAll('Schools');
        if (!empty($schools)) {
            foreach ($schools as $school) {
                foreach ($school->students_list as $student) {
                    $total_fee_amount = 0;
                    $total_extra_fee_amount = 0;
                    $total_amount = 0;
                    $payment_info_array = array();
                    foreach ($student->fee_options as $fee_option) {
                        $fee_structure = $fee_option->fee_structure;
                        $total_fee_amount = $total_fee_amount + $fee_structure->amount;
                        $payment_info_array[$fee_option->fee_structure->fee_label->fee_label] = $fee_structure->amount;
                    }
                    foreach ($student->extra_fee as $extra_fee) {
                        $total_extra_fee_amount = $total_extra_fee_amount + $extra_fee->amount;
                        $payment_info_array[$extra_fee->label] = $extra_fee->amount;
                    }
                    $total_amount = $total_fee_amount + $total_extra_fee_amount;

                    $transaction = new Transactions();
                    $transaction->school = $student->school;
                    $transaction->student = $student->id;
                    $transaction->class = $student->class;
                    $transaction->section = $student->section;
                    $transaction->receipt = 1;
                    $transaction->transaction_type = 'CREDIT';
                    $transaction->amount = $total_amount;
                    $transaction->amount_detail = json_encode($payment_info_array);
                    $transaction->month = date('m');
                    $transaction->year = date('Y');
                    $transaction->payment_status = 'pending';
                    $transaction->save();
                }
            }
        }

    }

}
