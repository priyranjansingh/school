<?php

class ReceiptController extends Controller {

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
    public function actionView($id) {
        $this->layout = '//layouts/invoice_column';
        $tran_model = Transactions::model()->find(array("condition" => "id = '$id'"));
        $school_model = Schools::model()->find(array("condition" => "id = '$tran_model->school'"));
        $student_model = Students::model()->find(array("condition" => "id = '$tran_model->student'"));
        $class_model = Classes::model()->find(array("condition" => " id = '$tran_model->class'"));
        $section_model = Sections::model()->find(array("condition" => " id = '$tran_model->section'"));
        $fee_detail = json_decode($tran_model->amount_detail,TRUE);
        //pre($fee_detail,true);



        $this->render('view', array(
            'tran_model' => $tran_model, 'school_model' => $school_model, 'student_model' => $student_model,
            'class_model' => $class_model, 'section_model' => $section_model,'fee_detail' => $fee_detail
        ));
    }

    public function actionSuccess(){
        // pre($_POST);
        $status = $_POST['status'];
        $trackId = $_POST['trackid'];
        $trans_id = $_POST['transactionId'];
        $transaction = Transactions::model()->find(array("condition" => "receipt = '$trackId'"));
        if($status == "SUCCESS"){
            $transaction->transaction_id = $trans_id;
            $transaction->payment_status = 'complete';
        }
        $transaction->date_modified = date("Y-m-d H:i:s");
        $transaction->transaction_details = serialize($_POST);
        $transaction->save();
        $id = $transaction->id;
        $this->layout = '//layouts/invoice_column';
        $tran_model = Transactions::model()->find(array("condition" => "id = '$id'"));
        $school_model = Schools::model()->find(array("condition" => "id = '$tran_model->school'"));
        $student_model = Students::model()->find(array("condition" => "id = '$tran_model->student'"));
        $class_model = Classes::model()->find(array("condition" => " id = '$tran_model->class'"));
        $section_model = Sections::model()->find(array("condition" => " id = '$tran_model->section'"));
        $fee_detail = json_decode($tran_model->amount_detail,TRUE);

        $this->render('response', array(
            'status' => $status,'response' => $_POST,
            'tran_model' => $tran_model, 'school_model' => $school_model, 'student_model' => $student_model,
            'class_model' => $class_model, 'section_model' => $section_model,'fee_detail' => $fee_detail
        ));
        
    }

    public function actionNotify(){
        $post = $_POST;
        $post = serialize($post);
        $myfile = fopen("assets/notify.txt", "w") or die("Unable to open file!");
        // $txt = "John Doe\n";
        fwrite($myfile, $post);
        // $txt = "Jane Doe\n";
        // fwrite($myfile, $txt);
        fclose($myfile);
    }

}
