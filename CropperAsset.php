<?php

/**
 * @link http://www.shenl.com/
 * @copyright Copyright (c) 2012 - 2015 SHENL.COM
 * @license http://www.shenl.com/license/
 */

namespace hustshenl\cropper;

use Yii;

/**
 * Asset bundle for Cropper Widget
 *
 * @author Shen Lei <shen@shenl.com>
 * @since 1.0
 */
class CropperAsset extends \kartik\base\AssetBundle
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setSourcePath(__DIR__ . '/assets');
        $this->setupAssets('css', ['css/select2']);
        $this->setupAssets('js', ['js/select2.full', 'js/select2-krajee']);
        parent::init();
    }
}