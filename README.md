yii2-widget-cropper
===================

[![Latest Stable Version](https://poser.pugx.org/hustshenl/yii2-widget-cropper/v/stable)](https://packagist.org/packages/hustshenl/yii2-widget-cropper)
[![License](https://poser.pugx.org/hustshenl/yii2-widget-cropper/license)](https://packagist.org/packages/hustshenl/yii2-widget-cropper)
[![Total Downloads](https://poser.pugx.org/hustshenl/yii2-widget-cropper/downloads)](https://packagist.org/packages/hustshenl/yii2-widget-cropper)
[![Monthly Downloads](https://poser.pugx.org/hustshenl/yii2-widget-cropper/d/monthly)](https://packagist.org/packages/hustshenl/yii2-widget-cropper)
[![Daily Downloads](https://poser.pugx.org/hustshenl/yii2-widget-cropper/d/daily)](https://packagist.org/packages/hustshenl/yii2-widget-cropper)


## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/). Check the [composer.json](https://github.com/hustshenl/yii2-widget-cropper/blob/master/composer.json) for this extension's requirements and dependencies. Read this [web tip /wiki](http://webtips.krajee.com/setting-composer-minimum-stability-application/) on setting the `minimum-stability` settings for your application's composer.json.

To install, either run

```
$ php composer.phar require hustshenl/yii2-widget-cropper "@dev"
```

or add

```
"hustshenl/yii2-widget-cropper": "@dev"
```

to the ```require``` section of your `composer.json` file.


## Usage


In View
```
echo Form::widget([ // continuation fields to row above without labels
    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [
        'cover' => [
            'type' => Form::INPUT_WIDGET,
            'widgetClass' => '\hustshenl\cropper\Cropper',
            'options' => [
                'data' => '',
                'pluginOptions' => [
                    'aspectRatio' => 240 / 320,
                    'autoCropArea' => 1,
                    'preview' => '.img-preview',
                    'strict' => true,
                    'guides' => false,
                    'highlight' => true,
                    'dragCrop' => true,
                    'cropBoxMovable' => true,
                    'cropBoxResizable' => true,
                ],
            ]
        ],
    ]
]);
```

In Model
```
$image = UploadedFile::getInstance($this, 'cover');
$cropper = $this->cover_crop
```



## License

**yii2-widget-cropper** is released under the BSD 3-Clause License. See the bundled `LICENSE.md` for details.