<?php

/**
 * @link http://www.shenl.com/
 * @copyright Copyright (c) 2012 - 2015 SHENL.COM
 * @license http://www.shenl.com/license/
 */

namespace hustshenl\cropper;

use Yii;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\base\InvalidConfigException;
use yii\web\JsExpression;

/**
 * Cropper widget is a Yii2 wrapper for the Cropper jQuery plugin. This
 * input widget is a jQuery based replacement for select boxes. It supports
 * searching, remote data sets, and infinite scrolling of results. The widget
 * is specially styled for Bootstrap 3.
 *
 * @author Shen Lei <shen@shenl.com>
 * @since 1.0
 * @see https://github.com/fengyuanchen/cropper
 */
class Cropper extends \kartik\base\InputWidget
{
    const LARGE = 'lg';
    const MEDIUM = 'md';
    const SMALL = 'sm';

    const THEME_DEFAULT = 'default';
    const THEME_CLASSIC = 'classic';
    const THEME_BOOTSTRAP = 'bootstrap';

    /**
     * @var array $data the option data items. The array keys are option values, and the array values
     * are the corresponding option labels. The array can also be nested (i.e. some array values are arrays too).
     * For each sub-array, an option group will be generated whose label is the key associated with the sub-array.
     * If you have a list of data models, you may convert them into the format described above using
     * [[\yii\helpers\ArrayHelper::map()]].
     */
    public $data;
    public $label;

    /**
     * @var string the locale ID (e.g. 'fr', 'de') for the language to be used by the Cropper Widget.
     * If this property not set, then the current application language will be used.
     */
    public $language;

    /**
     * @var string the theme name to be used for styling the Cropper
     */
    public $theme = self::THEME_DEFAULT;

    /**
     * @var string|array, the displayed text in the dropdown for the initial
     * value when you do not set or provide `data` (e.g. using with ajax).
     * If options['multiple'] is set to `true`, you can set this as an array of
     * text descriptions for each item in the dropdown `value`.
     */
    public $initValueText;

    /**
     * @var array addon to prepend or append to the Cropper widget
     * - prepend: array the prepend addon configuration
     *     - content: string the prepend addon content
     *     - asButton: boolean whether the addon is a button or button group. Defaults to false.
     *     - append: array the append addon configuration
     *     - content: string the append addon content
     *     - asButton: boolean whether the addon is a button or button group. Defaults to false.
     * - groupOptions: array HTML options for the input group
     * - contentBefore: string content placed before addon
     * - contentAfter: string content placed after addon
     */
    public $addon = [];

    /**
     * @var string Size of the Cropper input, must be one of the
     * [[LARGE]], [[MEDIUM]] or [[SMALL]]. Defaults to [[MEDIUM]]
     */
    public $size = self::MEDIUM;

    /**
     * @var array the HTML attributes for the input tag. The following options are important:
     * multiple: boolean whether multiple or single item should be selected. Defaults to false.
     * placeholder: string placeholder for the select item.
     */
    public $options = [];

    protected static $_inbuiltThemes = [
        self::THEME_DEFAULT,
        self::THEME_CLASSIC,
        self::THEME_BOOTSTRAP,
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        //$this->pluginOptions = $this->theme;
        if (!empty($this->addon) || empty($this->pluginOptions['width'])) {
            $this->pluginOptions['width'] = '100%';
            //$this->pluginOptions['crop'] = 'function(e) {console.log(e);}';
        }
        $this->pluginOptions['aspectRatio'] = empty($this->pluginOptions['aspectRatio']) ? 1 : $this->pluginOptions['aspectRatio'];
        $this->pluginOptions['autoCropArea'] = empty($this->pluginOptions['autoCropArea']) ? 1 : $this->pluginOptions['autoCropArea'];
        $this->pluginOptions['preview'] = empty($this->pluginOptions['preview']) ? '.img-preview' : $this->pluginOptions['preview'];
        $this->pluginOptions['strict'] = empty($this->pluginOptions['strict']) ? true : $this->pluginOptions['strict'];
        $this->pluginOptions['guides'] = empty($this->pluginOptions['guides']) ? false : $this->pluginOptions['guides'];
        $this->pluginOptions['highlight'] = empty($this->pluginOptions['highlight']) ? true : $this->pluginOptions['highlight'];
        $this->pluginOptions['dragCrop'] = empty($this->pluginOptions['dragCrop']) ? true : $this->pluginOptions['dragCrop'];
        $this->pluginOptions['cropBoxMovable'] = empty($this->pluginOptions['cropBoxMovable']) ? true : $this->pluginOptions['cropBoxMovable'];
        $this->pluginOptions['cropBoxResizable'] = empty($this->pluginOptions['cropBoxResizable']) ? true : $this->pluginOptions['cropBoxResizable'];


        //$this->initPlaceholder();
        if (!isset($this->data)) {
            if (!isset($this->value) && !isset($this->initValueText)) {
                $this->data = [];
            } else {
                $key = isset($this->value) ? $this->value : '';
                $val = isset($this->initValueText) ? $this->initValueText : $key;
                $this->data = [$key => $val];
            }
        }
        Html::addCssClass($this->options, 'form-control');
        $this->initLanguage('language', true);
        $this->registerAssets();
        $this->renderInput();
        $this->renderCropper();
    }

    /**
     * Initializes the placeholder for Cropper
     */
    protected function initPlaceholder()
    {
        $isMultiple = ArrayHelper::getValue($this->options, 'multiple', false);
        if (isset($this->options['prompt']) && !isset($this->pluginOptions['placeholder'])) {
            $this->pluginOptions['placeholder'] = $this->options['prompt'];
            if ($isMultiple) {
                unset($this->options['prompt']);
            }
            return;
        }
        if (isset($this->options['placeholder'])) {
            $this->pluginOptions['placeholder'] = $this->options['placeholder'];
            unset($this->options['placeholder']);
        }
        if (isset($this->pluginOptions['placeholder']) && is_string($this->pluginOptions['placeholder']) && !$isMultiple) {
            $this->options['prompt'] = $this->pluginOptions['placeholder'];
        }
    }

    /**
     * Renders the source Input for the Cropper plugin.
     * Graceful fallback to a normal HTML select dropdown
     * or text input - in case JQuery is not supported by
     * the browser
     */
    protected function renderInput()
    {
        if ($this->pluginLoading) {
            $this->_loadIndicator = '<div class="kv-plugin-loading loading-' . $this->options['id'] . '">&nbsp;</div>';
            Html::addCssStyle($this->options, 'display:none');
        }
        //$input = $this->getInput('hiddenInput', false);
        $cropperName = Html::getInputName($this->model, $this->attribute . '_crop');
        echo Html::hiddenInput($cropperName . '[x]', '', ['id' => 'data-x-' . $this->options['id']]);
        echo Html::hiddenInput($cropperName . '[y]', '', ['id' => 'data-y-' . $this->options['id']]);
        echo Html::hiddenInput($cropperName . '[height]', '', ['id' => 'data-height-' . $this->options['id']]);
        echo Html::hiddenInput($cropperName . '[width]', '', ['id' => 'data-width-' . $this->options['id']]);
        echo Html::hiddenInput($cropperName . '[rotate]', '', ['id' => 'data-rotate-' . $this->options['id']]);
        echo Html::hiddenInput($cropperName . '[scale_x]', '', ['id' => 'data-scale-x-' . $this->options['id']]);
        echo Html::hiddenInput($cropperName . '[scale_y]', '', ['id' => 'data-scale-y-' . $this->options['id']]);
        //echo $this->_loadIndicator . $this->embedAddon($input);
        //echo $input;


    }

    protected function renderCropper()
    {
        echo <<<HTML
        <div class="row">
        <div class="col-xs-12">
            <label class="btn btn-primary btn-upload" for="input-{$this->options['id']}" title="Upload image file">
                <input type="file" class="sr-only" id="input-{$this->options['id']}" name="{$this->name}" accept="image/*">
                <span class="docs-tooltip" data-toggle="tooltip" title="Import image with Blob URLs">
                  <span class="fa fa-upload"></span>
                      {$this->label}
                    </span>
              </label>
              <div style="margin-bottom: 10px;"></div>

          </div>




      <div class="col-xs-8">
        <!--<h3 class="page-header">Demo:</h3>-->
        <div class="img-container">
        <img src="{$this->data}" alt="Picture" style="display: none;">
        </div>
      </div>
      <div class="col-xs-4">
        <!--<h3 class="page-header">Preview:</h3>-->
        <div class="docs-preview clearfix">
          <div class="img-preview preview-lg"></div>
          <div class="img-preview preview-md"></div>
          <div class="img-preview preview-sm"></div>
          <div class="img-preview preview-xs"></div>
        </div>
      </div>

    </div>

HTML;
    }

    /**
     * Registers the asset bundle and locale
     */
    public function registerAssetBundle()
    {
        $view = $this->getView();
        $lang = isset($this->language) ? $this->language : '';
        CropperAsset::register($view)->addLanguage($lang, '', 'js/i18n');
        if (in_array($this->theme, self::$_inbuiltThemes)) {
            $bundleClass = __NAMESPACE__ . '\Theme' . ucfirst($this->theme) . 'Asset';
            $bundleClass::register($view);
        }
    }

    /**
     * Registers the needed assets
     */
    public function registerAssets()
    {
        $id = $this->options['id'];
        $this->registerAssetBundle();
        $this->pluginOptions['crop'] = new JsExpression(<<<JS
function(e) {
    $("#data-x-{$id}").val(Math.round(e.x));
    $("#data-y-{$id}").val(Math.round(e.y));
    $("#data-height-{$id}").val(Math.round(e.height));
    $("#data-width-{$id}").val(Math.round(e.width));
    $("#data-rotate-{$id}").val(e.rotate);
    $("#data-scale-x-{$id}").val(e.scaleX);
    $("#data-scale-y-{$id}").val(e.scaleY);
  }
JS
        );

        // do not open dropdown when clear icon is pressed to clear value
        //$js = "\$('#{$id}').on('select2:opening', initS2Open).on('select2:unselecting', initS2Unselect);";
        $js = <<<JS
// Import image
    var \$image = \$('.field-{$this->options['id']} .img-container > img')
    var \$inputImage = \$('#input-{$this->options['id']}');
    var URL = window.URL || window.webkitURL;
    var blobURL;

    if (URL) {
      \$inputImage.change(function () {
        var files = this.files;
        var file;
        if (!\$image.data('cropper')) {
          return;
        }
        if (files && files.length) {
          file = files[0];
          if (/^image\/\w+$/.test(file.type)) {
            blobURL = URL.createObjectURL(file);
            \$image.one('built.cropper', function () {
              URL.revokeObjectURL(blobURL); // Revoke when load complete
            }).cropper('reset').cropper('replace', blobURL).show();
            //\$inputImage.val('');
          } else {
            \$body.tooltip('Please choose an image file.', 'warning');
          }
        }
      });
    } else {
      \$inputImage.prop('disabled', true).parent().addClass('disabled');
    }

JS;

        $this->getView()->registerJs($js);
        // register plugin
        if ($this->pluginLoading) {
            $this->registerPlugin(
                'cropper',
                //"jQuery('#{$id} > img')",
                "jQuery('.field-{$id} .img-container > img')"//,
            //"initS2Loading('{$id}', '.select2-container--{$this->theme}')"
            );
        } else {
            $this->registerPlugin('cropper');
        }

    }
}