<section class="content-header">
    <h1>
        Manage
        <small>Parents</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url() . '/school'; ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="<?php echo base_url() . '/school/parents'; ?>"><i class="fa fa-dashboard"></i> Parents</a></li>
        <li class="active">Manage</li>
    </ol>
</section>
<section class="content">

    <div class="row">
        <div class="col-xs-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Search</h3>
                </div>
                <?php
                Yii::app()->clientScript->registerScript('search', "
						$('form.search-form').submit(function(){
							$('#parents-grid').yiiGridView('update', {
								data: $(this).serialize()
							});
							return false;
						});
						");
                $this->renderPartial('_search', array(
                    'model' => $model,
                    'childs'=>$childs
                ));
                ?>
            </div>
            <div class="box">
                <div class="box-header">
                    <div class="row">
                        <div class="col-sm-8">
                            <h3 class="box-title">Parents List</h3>
                        </div>
                        <div class="col-sm-4">
                            <a href="<?php echo base_url() . '/school/parents/create'; ?>">
                                <button class="btn btn-block btn-primary">Add Parent</button>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="box-body">
                    <div class="dataTables_wrapper form-inline dt-bootstrap">

                        <div class="row">
                            <div class="col-sm-12 table-responsive">
                                <?php
                                $this->widget('zii.widgets.grid.CGridView', array(
                                    'id' => 'parents-grid',
                                    'itemsCssClass' => 'table table-bordered table-hover dataTable',
                                    'dataProvider' => $model->search(),
                                    'enablePagination' => true,
                                    // 'filter'=>$model,
                                    'columns' => array(
                                        array(
                                            'name' => 'child',
                                            'value' => array($this, 'studentName')
                                        ),
                                        'firstname',
                                        'lastname',
                                        array(
                                            'name' => 'parent_type',
                                            'value' => array($this, 'parentType')
                                        ),
										array(
                                            'class'=>'CButtonColumn',
                                            'template'=>'{v} {u} {d}', // <-- TEMPLATE WITH THE TWO STATES
                                            'htmlOptions'=>array(
                                                    'width'=>80,
                                            ),
                                            'buttons' => array(
                                                'v'=>array(
                                                        'label'=>'<i class="fa fa-search"></i>',
                                                        'url'=>'Yii::app()->createUrl("school/parents/view", array("id"=>$data->id))',
                                                        'options'=>array('class'=>'view','title'=>'View'),
                                                        // 'imageUrl'=>'images/icons/inactive.png',
                                                        // 'visible'=> '$data->active == 0', // <-- SHOW IF ROW INACTIVE
                                                ),
                                                'u'=>array(
                                                        'label'=>'<i class="fa fa-edit"></i>',
                                                        'url'=>'Yii::app()->createUrl("school/parents/update", array("id"=>$data->id))',
                                                        'options'=>array('class'=>'edit','title'=>'Update'),
                                                        // 'imageUrl'=>'images/icons/active.png',
                                                        // 'visible'=> '$data->active == 1', // <-- SHOW IF ROW ACTIVE
                                                ),
                                                'd'=>array(
                                                        'label'=>'<i class="fa fa-trash"></i>',
                                                        'url'=>'Yii::app()->createUrl("school/parents/delete", array("id"=>$data->id))',
                                                        'options'=>array('class'=>'delete','title'=>'Delete'),
                                                        'click'=>'function(){return confirm("are you sure ?");}'
                                                        // 'imageUrl'=>'images/icons/printer.png',
                                                ),
                                            ),
                                        )
                                    ),
                                ));
                                ?>
                            </div>
                        </div>
                        <div class="row"><div class="col-sm-6"></div><div class="col-sm-6"></div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>