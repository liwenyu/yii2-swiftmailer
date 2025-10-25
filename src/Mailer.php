<?php

namespace liwenyu\swiftmailer;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\mail\BaseMailer;
use yii\mail\MessageInterface;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Microsoft Mail Sender Component
 * 
 * 基于 Microsoft Graph API 的邮件发送组件
 * 
 * @author liwenyu <liwenyu66@gmail.com>
 * @since 1.0.0
 */
class Mailer extends BaseMailer
{
    /**
     * @var MicrosoftMailConfig 微软邮件配置
     */
    public $config;
    
    /**
     * @var array SMTP 传输配置（可选）
     */
    public $transport;
    
    /**
     * @var string 默认消息类
     */
    public $messageClass = 'liwenyu\swiftmailer\Message';
    
    /**
     * @var bool 是否启用调试模式
     */
    public $debug = false;
    
    /**
     * @var bool 是否使用 SMTP 传输（而不是 Microsoft Graph API）
     */
    public $useSmtp = false;
    
    /**
     * @var \Swift_Transport|array Swift transport instance or its array configuration.
     */
    private $_transport;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        if ($this->useSmtp) {
            // 使用 SMTP 传输
            if (empty($this->transport)) {
                throw new InvalidConfigException('使用 SMTP 模式时，transport 配置不能为空');
            }
            
            // 设置 SwiftMailer 传输
            $this->setTransport($this->transport);
        } else {
            // 使用 Microsoft Graph API
            if (empty($this->config)) {
                throw new InvalidConfigException('使用 Microsoft Graph API 模式时，config 配置不能为空');
            }
            
            // 如果 config 是数组，自动实例化为 MicrosoftMailConfig 对象
            if (is_array($this->config)) {
                $this->config = Yii::createObject(array_merge([
                    'class' => MicrosoftMailConfig::class,
                ], $this->config));
            }
            
            if (!$this->config instanceof MicrosoftMailConfig) {
                throw new InvalidConfigException('使用 Microsoft Graph API 模式时，config 必须是 MicrosoftMailConfig 的实例');
            }
        }
    }

    /**
     * 设置传输配置
     * 
     * @param array|\Swift_Transport $transport
     * @throws InvalidConfigException on invalid argument.
     */
    public function setTransport($transport)
    {
        if (!is_array($transport) && !is_object($transport)) {
            throw new InvalidConfigException('"' . get_class($this) . '::transport" should be either object or array, "' . gettype($transport) . '" given.');
        }
        $this->_transport = $transport;
    }

    /**
     * 获取传输实例
     * 
     * @return \Swift_Transport
     */
    public function getTransport()
    {
        if (!is_object($this->_transport)) {
            $this->_transport = $this->createTransport($this->_transport);
        }

        return $this->_transport;
    }

    /**
     * 创建传输实例
     * 
     * @param array $config
     * @return \Swift_Transport
     * @throws InvalidConfigException
     */
    protected function createTransport($config)
    {
        if (is_array($config)) {
            if (!isset($config['class'])) {
                throw new InvalidConfigException('The "class" property must be set for transport.');
            }
            
            $class = $config['class'];
            unset($config['class']);
            
            $constructArgs = isset($config['constructArgs']) ? $config['constructArgs'] : [];
            unset($config['constructArgs']);
            
            $transport = new $class(...$constructArgs);
            
            // 设置传输属性
            foreach ($config as $name => $value) {
                if (property_exists($transport, $name)) {
                    $transport->$name = $value;
                } elseif (method_exists($transport, 'set' . ucfirst($name))) {
                    $transport->{'set' . ucfirst($name)}($value);
                }
            }
            
            return $transport;
        }
        
        return $config;
    }

    /**
     * @inheritdoc
     */
    protected function sendMessage($message)
    {
        if ($this->useSmtp) {
            // 使用 SMTP 传输
            return $this->sendViaSmtp($message);
        } else {
            // 使用 Microsoft Graph API
            return $this->sendViaGraphApi($message);
        }
    }

    /**
     * 通过 SMTP 发送邮件
     * 
     * @param MessageInterface $message
     * @return bool
     */
    protected function sendViaSmtp($message)
    {
        try {
            $transport = $this->getTransport();
            $swiftMailer = new \Swift_Mailer($transport);
            
            // 创建 Swift_Message
            $swiftMessage = new \Swift_Message();
            $swiftMessage->setSubject($message->getSubject());
            
            // 设置邮件内容
            if ($message->getHtmlBody()) {
                $swiftMessage->setBody($message->getHtmlBody(), 'text/html');
                if ($message->getTextBody()) {
                    $swiftMessage->addPart($message->getTextBody(), 'text/plain');
                }
            } else {
                $swiftMessage->setBody($message->getTextBody(), 'text/plain');
            }
            
            // 设置发件人 - 必须与SMTP认证用户一致
            $from = $message->getFrom();
            $transportUsername = $transport->getUsername();
            
            if (!empty($from)) {
                // 检查发件人是否与SMTP认证用户一致
                $isAuthorizedSender = false;
                if ($transportUsername) {
                    foreach ($from as $email => $name) {
                        if ($email === $transportUsername) {
                            $isAuthorizedSender = true;
                            break;
                        }
                    }
                }
                
                if ($isAuthorizedSender) {
                    $swiftMessage->setFrom($from);
                } else {
                    // 使用SMTP认证用户作为发件人，但保留原始发件人信息
                    $swiftMessage->setFrom([$transportUsername => '系统邮件']);
                    // 在邮件内容中添加原始发件人信息
                    $originalFrom = is_array($from) ? implode(', ', array_keys($from)) : $from;
                    $body = $swiftMessage->getBody();
                    $swiftMessage->setBody($body . "\n\n原始发件人: " . $originalFrom);
                }
            } else {
                // 使用SMTP认证用户作为默认发件人
                if ($transportUsername) {
                    $swiftMessage->setFrom([$transportUsername => '系统邮件']);
                }
            }
            
            // 设置收件人
            $to = $message->getTo();
            if (!empty($to)) {
                $swiftMessage->setTo($to);
            }
            
            // 设置抄送
            $cc = $message->getCc();
            if (!empty($cc)) {
                $swiftMessage->setCc($cc);
            }
            
            // 设置密送
            $bcc = $message->getBcc();
            if (!empty($bcc)) {
                $swiftMessage->setBcc($bcc);
            }
            
            // 设置回复地址
            $replyTo = $message->getReplyTo();
            if (!empty($replyTo)) {
                $swiftMessage->setReplyTo($replyTo);
            }
            
            // 发送邮件
            $result = $swiftMailer->send($swiftMessage);
            
            if ($result > 0) {
                Yii::info('SMTP邮件发送成功，发送数量: ' . $result, __METHOD__);
                return true;
            } else {
                Yii::error('SMTP邮件发送失败', __METHOD__);
                return false;
            }
            
        } catch (\Exception $e) {
            Yii::error('SMTP邮件发送异常: ' . $e->getMessage(), __METHOD__);
            return false;
        }
    }

    /**
     * 通过 Microsoft Graph API 发送邮件
     * 
     * @param MessageInterface $message
     * @return bool
     */
    protected function sendViaGraphApi($message)
    {
        try {
            $accessToken = $this->config->getAccessToken();
            $httpClient = $this->config->getHttpClient();
            
            $endpoint = $this->config->getGraphApiUrl("users/{$this->config->userEmail}/sendMail");
            
            $mailData = $this->buildMailData($message);
            
            if ($this->debug) {
                Yii::info('发送邮件数据: ' . json_encode($mailData, JSON_UNESCAPED_UNICODE), __METHOD__);
            }
            
            $response = $httpClient->post($endpoint, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => $mailData,
            ]);
            
            if ($response->getStatusCode() === 202) {
                Yii::info('邮件发送成功', __METHOD__);
                return true;
            } else {
                Yii::error('邮件发送失败，状态码: ' . $response->getStatusCode(), __METHOD__);
                return false;
            }
            
        } catch (GuzzleException $e) {
            Yii::error('邮件发送异常: ' . $e->getMessage(), __METHOD__);
            return false;
        } catch (\Exception $e) {
            Yii::error('邮件发送错误: ' . $e->getMessage(), __METHOD__);
            return false;
        }
    }

    /**
     * 构建邮件数据
     * 
     * @param MessageInterface $message
     * @return array
     */
    protected function buildMailData(MessageInterface $message)
    {
        $mailData = [
            'message' => [
                'subject' => $message->getSubject(),
                'body' => [
                    'contentType' => $message->getCharset() === 'text/html' ? 'HTML' : 'Text',
                    'content' => $message->toString(),
                ],
                'toRecipients' => $this->buildRecipients($message->getTo()),
                'ccRecipients' => $this->buildRecipients($message->getCc()),
                'bccRecipients' => $this->buildRecipients($message->getBcc()),
            ],
        ];
        
        // 添加回复地址
        $replyTo = $message->getReplyTo();
        if (!empty($replyTo)) {
            $mailData['message']['replyTo'] = $this->buildRecipients($replyTo);
        }
        
        // 添加重要性
        if (method_exists($message, 'getImportance')) {
            $importance = $message->getImportance();
            if ($importance !== 'normal') {
                $mailData['message']['importance'] = ucfirst($importance);
            }
        }
        
        // 添加敏感度
        if (method_exists($message, 'getSensitivity')) {
            $sensitivity = $message->getSensitivity();
            if ($sensitivity !== 'normal') {
                $mailData['message']['sensitivity'] = ucfirst($sensitivity);
            }
        }
        
        // 添加附件
        $attachments = $message->getAttachments();
        if (!empty($attachments)) {
            $mailData['message']['attachments'] = $this->buildAttachments($attachments);
        }
        
        return $mailData;
    }

    /**
     * 构建收件人列表
     * 
     * @param array $recipients
     * @return array
     */
    protected function buildRecipients($recipients)
    {
        $result = [];
        
        if (is_array($recipients)) {
            foreach ($recipients as $email => $name) {
                $result[] = [
                    'emailAddress' => [
                        'address' => is_numeric($email) ? $name : $email,
                        'name' => is_numeric($email) ? null : $name,
                    ],
                ];
            }
        }
        
        return $result;
    }

    /**
     * 构建附件列表
     * 
     * @param array $attachments
     * @return array
     */
    protected function buildAttachments($attachments)
    {
        $result = [];
        
        foreach ($attachments as $attachment) {
            if (is_array($attachment) && isset($attachment['content'])) {
                $attachmentData = [
                    '@odata.type' => '#microsoft.graph.fileAttachment',
                    'name' => $attachment['fileName'] ?? 'attachment',
                    'contentType' => $attachment['contentType'] ?? 'application/octet-stream',
                    'contentBytes' => base64_encode($attachment['content']),
                ];
                
                // 如果是嵌入附件，添加 contentId
                if (isset($attachment['isEmbedded']) && $attachment['isEmbedded'] && isset($attachment['cid'])) {
                    $attachmentData['contentId'] = $attachment['cid'];
                }
                
                $result[] = $attachmentData;
            }
        }
        
        return $result;
    }

    /**
     * 创建消息实例
     * 
     * @return MessageInterface
     */
    public function compose($view = null, array $params = [])
    {
        $message = parent::compose($view, $params);
        
        if (!$this->useSmtp) {
            // 使用 Microsoft Graph API 时，设置默认发件人
            if (!$message->getFrom()) {
                $message->setFrom($this->config->userEmail);
            }
        }
        
        return $message;
    }

    /**
     * 发送邮件 - 兼容官方 yii2-swiftmailer
     * 
     * @param MessageInterface $message
     * @return bool
     */
    public function send($message)
    {
        return $this->sendMessage($message);
    }

    /**
     * 发送简单邮件
     * 
     * @param string $to 收件人
     * @param string $subject 主题
     * @param string $body 内容
     * @param string $contentType 内容类型 (text/plain 或 text/html)
     * @return bool
     */
    public function sendSimpleMail($to, $subject, $body, $contentType = 'text/html')
    {
        $message = $this->compose()
            ->setTo($to)
            ->setSubject($subject)
            ->setTextBody($contentType === 'text/plain' ? $body : null)
            ->setHtmlBody($contentType === 'text/html' ? $body : null);
        
        return $this->send($message);
    }

    /**
     * 批量发送邮件
     * 
     * @param array $recipients 收件人列表
     * @param string $subject 主题
     * @param string $body 内容
     * @param string $contentType 内容类型
     * @return array 发送结果
     */
    public function sendBulkMail($recipients, $subject, $body, $contentType = 'text/html')
    {
        $results = [];
        
        foreach ($recipients as $recipient) {
            $results[$recipient] = $this->sendSimpleMail($recipient, $subject, $body, $contentType);
        }
        
        return $results;
    }

    /**
     * 获取邮件发送器信息
     * 
     * @return array
     */
    public function getMailerInfo()
    {
        if ($this->useSmtp) {
            return [
                'name' => 'SMTP Mailer',
                'version' => '1.0.0',
                'transport' => 'SMTP',
                'debug' => $this->debug,
            ];
        } else {
            return [
                'name' => 'Microsoft Graph API Mailer',
                'version' => '1.0.0',
                'transport' => 'Microsoft Graph API',
                'userEmail' => $this->config->userEmail,
                'debug' => $this->debug,
            ];
        }
    }

    /**
     * 检查邮件发送器是否可用
     * 
     * @return bool
     */
    public function isAvailable()
    {
        if ($this->useSmtp) {
            // SMTP 模式下检查传输是否可用
            return $this->getTransport() !== null;
        } else {
            // Microsoft Graph API 模式下验证配置
            try {
                return $this->config->validateConfig();
            } catch (\Exception $e) {
                return false;
            }
        }
    }

    /**
     * 获取发送统计信息
     * 
     * @return array
     */
    public function getStats()
    {
        // 这里可以添加发送统计逻辑
        return [
            'totalSent' => 0,
            'totalFailed' => 0,
            'lastSent' => null,
        ];
    }
}
