<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/** @var array $arResult
 * @var array $arParams
 * @global CMain $APPLICATION
 */
$formId = 'form_'.randString(5);
CJSCore::Init(['masked_input']);
?>
<div class="content__block">
    <div class="from__block">
        <form action="" id="<?=$formId?>">
            <div class="error">
            </div>
            <div class="notice">
            </div>
            <div class="form_fields">
                <label for="name" class="text-field__label"><?=GetMessage('FORM_FIELD_NAME')?></label>
                    <input type="text" id="name" class="text-field__input" placeholder="<?=GetMessage('FORM_PLACEHOLDER_NAME')?>">
            </div>
            <div class="form_fields">
                <label for="phone" class="text-field__label"><?=GetMessage('FORM_FIELD_PHONE')?></label>
                <input type="text" id="phone" class="text-field__input" placeholder="<?=GetMessage('FORM_PLACEHOLDER_PHONE')?>">
            </div>
            <div class="form_fields">
                <label for="email" class="text-field__label"><?=GetMessage('FORM_FIELD_EMAIL')?></label>
                <input type="text" id="email" class="text-field__input" placeholder="<?=GetMessage('FORM_PLACEHOLDER_EMAIL')?>">
            </div>
            <div class="form__buttons">
                <input type="submit" class="submit-button" value="<?=GetMessage('FORM_BUTTON')?>">
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    const testForm = new TestForm(
        <?=CUtil::PhpToJSObject([
                'formId' => $formId,
                'componentName' => $this->getComponent()->getName(),
                'blockId' => $arParams['BLOCK_ID']
        ])?>
    );
    testForm.init();
</script>