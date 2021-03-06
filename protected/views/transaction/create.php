<?php
$this->menu = array(
        //array('label'=>'List Config','url'=>array('index')),
        //array('label'=>'View Config','url'=>array('view','id'=>$model->id)),
);
$this->beginWidget('MiniForm', array('header' => Yii::t("app", "Create Manual Transaction")));
?>
<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'transaction-form',
    'enableAjaxValidation' => true,
        ));
?>


<div class="col-md-3">
    <?php //echo $form->labelEx($model, 'details'); ?>
    <?php echo $form->textFieldRow($model, 'details'); ?>
    <?php //echo $form->error($model, 'details'); ?>

    <?php echo $form->labelEx($model, 'valuedate'); ?>
    <?php
    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
        'attribute' => 'valuedate',
        'model'=>$model,
        'language' => substr(Yii::app()->language, 0, 2),
        'options' => array(
            'dateFormat' => Yii::app()->locale->getDateFormat('short'),
        )
            )
    );
    ?>
    <?php echo $form->error($model, 'valuedate'); ?>
</div>

<div class="col-md-3">   

    <div>
        <?php
        $this->widget('widgetRefnum', array(
            'model' => $model, //Model object
            'attribute' => 'refnum1', //attribute name
        )); //*/
        ?>
        
    </div>
    <?php //echo $form->labelEx($model, 'refnum1'); ?>
    <?php //echo $form->textField($model, 'refnum1'); ?>
    <?php //echo $form->error($model, 'refnum1'); ?>

    <?php //echo $form->labelEx($model, 'refnum2'); ?>
    <?php echo $form->textFieldRow($model, 'refnum2'); ?>
    <?php //echo $form->error($model, 'refnum2'); ?>

    <?php //echo $form->labelEx($model, 'currency_id'); ?>
    <?php echo $form->dropDownListRow($model, 'currency_id', CHtml::listData(Currates::model()->GetRateList(), 'currency_id', 'name'));  ?>
    <?php //echo $form->error($model, 'currency_id'); ?>
</div>
<div class="row">
    <div class="col-md-12">   
        <table class="formy">
            <tbody>
                <tr>
                    <th class="header"><?php echo Yii::t('app', "Account") ?></th>
                    <th class="header"><?php echo Yii::t('app', "Oppt Account") ?></th>
                </tr>
                <tr>
                    <td style="vertical-align: top;">
                        <table>
                            <thead>
                                <tr>
                                    <td><?php echo Yii::t('app', 'Account id'); ?></td>
                                    <td style=""><?php //echo Yii::t('app', 'Account Name'); ?></td>
                                    <td><?php echo Yii::t('app', 'Credit'); ?></td>
                                    <td><?php echo Yii::t('app', 'Debit'); ?></td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <?php echo $form->dropDownList($model, 'account_id', CHtml::listData(Accounts::model()->findAll(), 'id', 'name')); ?>
                                        <?php
                  
                                        ?>
                                        <?php echo $form->error($model, 'account_id'); ?>
                                    </td>
                                    <td>
                                        <span id="nameTransactions_account_id"></span>
                                    </td>
                                    <td>
                                        <input size="6" id="sourcepos" type="text" class="number" name="FormTransaction[sourcepos]" onchange="CalcSum()" value="0">
                                    </td>
                                    <td>
                                        <input size="6" id="sourceneg" type="text" class="number" name="FormTransaction[sourceneg]" onchange="CalcSum()" value="0">
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </td>
                    <td>
                        <table>
                            <thead>
                                <tr>
                                    <td><?php echo Yii::t('app', 'Account id'); ?></td>

                                    <td><?php echo Yii::t('app', 'Credit'); ?></td>
                                    <td><?php echo Yii::t('app', 'Debit'); ?></td>
                                    <td></td>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <td></td>
                                    <td><?php echo Yii::t('app', 'balance'); ?></td>
                                    <td>
                                        <input size="5" id="balance" type="text" value="0" readonly="">
                                    </td>
                                    <td></td>

                                </tr>
                            </tfoot>
                            <tbody id="det">

                            </tbody>

                        </table>

                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<script type="text/javascript">
    <?php
    Yii::app()->clientScript->registerScript('search', "
$(document).ready(
            function() {
                addItem(0);
                $('#transaction-form').submit(function() {
                    if (Number($('#balance').val()) != 0) {
                        alert('sum is not 0');
                        return false;
                    }

                });

            }

    );

");
    
    
    ?>
    
    function CalcSum() {
        var value = -1 * parseFloat($("#sourceneg").val()).toFixed(2);
        if (value == 0) {
            value = 1 * parseFloat($("#sourcepos").val()).toFixed(2);
            if (value == 0) {
                $("#sourcepos").removeAttr('disabled');//unlock all;
                $("#sourceneg").removeAttr('disabled');
                $("[id^=sumpos]").attr('disabled', true);//lock crap
                $("[id^=sumneg]").attr('disabled', true);
                $("[id^=sumpos]").val(0);
                $("[id^=sumneg]").val(0);
                //return;
            } else {
                $("#sourceneg").attr('disabled', true);//lock pos;
                $("#sourceneg").val(0);
                $("[id^=sumneg]").removeAttr('disabled');//unlock pos;
                $("[id^=sumpos]").attr('disabled', true);//lock neg;
                $("[id^=sumpos]").val(0);
            }
        } else {
            $("#sourcepos").attr('disabled', true);//lock neg
            $("#sourcepos").val(0);
            $("[id^=sumpos]").removeAttr('disabled');//unlock pos;
            $("[id^=sumneg]").attr('disabled', true);//lock neg;
            $("[id^=sumneg]").val(0);
        }
        var elements = $("[id^=sumpos]");
        var multi = (1);
        var credit = false;
        if (value > 0) {
            credit = true;
            elements = $("[id^=sumneg]");
            multi = (-1);
        }
        for (var i = 0; i < elements.length; i++) {
            if ($('#' + elements[i].id).val() != '') {
                if (parseFloat($('#' + elements[i].id).val()) >= 0) {
                    value += (multi * parseFloat($('#' + elements[i].id).val()).toFixed(2));
                    $('#' + elements[i].id).removeClass("error");
                    $("label[for=" + elements[i].id + "]").remove();
                } else {
                    if (!parseFloat($('#' + elements[i].id).val()).NaN)
                        markMyWords(elements[i].id);
                }
            }

        }
        $('#balance').val(value);
        return true;
    }



    function removeElement(divNum) {
        var d = document.getElementById('det');
        var olddiv = document.getElementById(divNum);
        d.removeChild(olddiv);
        CalcSum();
    }
    function addItem(last) {
        //console.log("fire!");
        var ni = document.getElementById('det');
        var num = last + 1;
        var IdName = "My" + num;
        var r = document.createElement('tr');
        var ca = document.createElement('td');
        //var cb = document.createElement('td');
        var cc = document.createElement('td');
        var cd = document.createElement('td');

        var cg = document.createElement('td');
        var accountselect='<?php echo str_replace("\n","",$form->dropDownList($model, 'ops', CHtml::listData(Accounts::model()->findAll(), 'id', 'name'))); ?>'
        
        r.setAttribute("id", 'tr' + IdName);
        cg.setAttribute("id", 'Action' + IdName);//id="FormTransaction_ops"
        ca.innerHTML = accountselect.replace('id="FormTransaction_ops','id="FormTransaction_ops'+num).replace('name="FormTransaction[ops','name="FormTransaction[ops]['+num);
        
 

        cc.innerHTML = "<input type=\"text\" id=\"sumpos" + num + "\" value=\"0\" class=\"number\" name=\"FormTransaction[sumpos][" + num + "]\" size=\"6\" onblur=\"CalcSum(" + name + ")\" />";
        cd.innerHTML = "<input type=\"text\" id=\"sumneg" + num + "\" value=\"0\" class=\"number\" name=\"FormTransaction[sumneg][" + num + "]\" size=\"6\" onblur=\"CalcSum(" + name + ")\" />";

        cg.innerHTML = "<a href=\"javascript:addItem(" + num + ");\" class=\"btnadd\">הוסף</a>";

        if (last != 0) {
            var lastaction = document.getElementById('ActionMy' + last);
            lastaction.innerHTML = "<a href=\"javascript:;\" onclick=\"removeElement(\'trMy" + last + "\')\" class=\"btnremove\">X</a>";
        }
        //replace add button with remove

        r.appendChild(ca);
        //r.appendChild(cb);
        r.appendChild(cc);
        r.appendChild(cd);

        r.appendChild(cg);

        ni.appendChild(r);
        
        
        $("#FormTransaction_ops" + num).select2()
        .on("change", function(e) {
          console.log(e.val);
          $("#FormTransaction_ops" + num).val(e.val);
          $("#FormTransaction_ops" + num+' [value="'+e.val+'"]').attr('selected',true);//ugly fix but it just wont select:(
        });
        /*
        $("#ops" + num).autocomplete({
            source: "<?php echo $this->createUrl('/accounts/autocomplete', array('type' => 'all')); ?>",
            open: function() {
                $(this).autocomplete('widget').css('z-index', 2048);
                return false;
            }
        });*/
    }


    function refNum(doc) {//
        $("#choseFormTransaction_refnum1").dialog("close");
        $('#FormTransaction_refnum1_div').html($('#FormTransaction_refnum1_div').html() + ", " + doc.doctype + " #" + doc.docnum);
        $('#FormTransaction_refnum1_ids').val($('#FormTransaction_refnum1_ids').val() + doc.id + ",");
        return false;
    }

//*/

</script>


<div class="form-actions">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => Yii::t('app', "Save"),
    ));
    ?>
</div>

<?php $this->endWidget(); ?>



<?php
$this->endWidget();
?>