<?php

/**
 * @link http://www.shenl.com/
 * @copyright Copyright (c) 2012 - 2015 SHENL.COM
 * @license http://www.shenl.com/license/
 */

namespace hustshenl\cropper;

use Yii;

/**
 * Classic Cropper theme
 *
 * @author Shen Lei <shen@shenl.com>
 * @since 1.0
 */
class ThemeClassicAsset extends \kartik\base\AssetBundle
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setSourcePath(__DIR__ . '/assets');
        $this->setupAssets('css', ['css/cropper-classic']);
        parent::init();
    }
}