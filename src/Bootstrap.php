<?php

namespace liwenyu\swiftmailer;

use Yii;
use yii\base\BootstrapInterface;
use yii\base\Module;

/**
 * Microsoft Mail Extension Bootstrap
 * 
 * @author liwenyu <liwenyu66@gmail.com>
 * @since 1.0.0
 */
class Bootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        // 注册邮件组件
        if (!isset($app->getComponents()['microsoftMail'])) {
            $app->setComponents([
                'microsoftMail' => [
                    'class' => 'liwenyu\swiftmailer\Mailer',
                ],
            ]);
        }
    }
}
