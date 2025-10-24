<?php

namespace liwenyu\swiftmailer;

use Yii;
use yii\mail\BaseMessage;

/**
 * Microsoft Mail Message
 * 
 * 微软邮件消息类
 * 
 * @author liwenyu <liwenyu66@gmail.com>
 * @since 1.0.0
 */
class Message extends BaseMessage
{
    /**
     * @var string 邮件主题
     */
    private $_subject;
    
    /**
     * @var string 邮件内容
     */
    private $_textBody;
    
    /**
     * @var string HTML内容
     */
    private $_htmlBody;
    
    /**
     * @var string 字符编码
     */
    private $_charset = 'UTF-8';
    
    /**
     * @var array 发件人
     */
    private $_from;
    
    /**
     * @var array 收件人
     */
    private $_to = [];
    
    /**
     * @var array 抄送
     */
    private $_cc = [];
    
    /**
     * @var array 密送
     */
    private $_bcc = [];
    
    /**
     * @var array 回复地址
     */
    private $_replyTo = [];
    
    /**
     * @var array 附件
     */
    private $_attachments = [];
    
    /**
     * @var int 邮件优先级
     */
    private $_priority;
    
    /**
     * @var string 邮件重要性
     */
    private $_importance;
    
    /**
     * @var string 邮件敏感度
     */
    private $_sensitivity;

    /**
     * @inheritdoc
     */
    public function getCharset()
    {
        return $this->_charset;
    }

    /**
     * @inheritdoc
     */
    public function setCharset($charset)
    {
        $this->_charset = $charset;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getFrom()
    {
        return $this->_from;
    }

    /**
     * @inheritdoc
     */
    public function setFrom($from)
    {
        $this->_from = $this->normalizeAddresses($from);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getTo()
    {
        return $this->_to;
    }

    /**
     * @inheritdoc
     */
    public function setTo($to)
    {
        $this->_to = $this->normalizeAddresses($to);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCc()
    {
        return $this->_cc;
    }

    /**
     * @inheritdoc
     */
    public function setCc($cc)
    {
        $this->_cc = $this->normalizeAddresses($cc);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getBcc()
    {
        return $this->_bcc;
    }

    /**
     * @inheritdoc
     */
    public function setBcc($bcc)
    {
        $this->_bcc = $this->normalizeAddresses($bcc);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getReplyTo()
    {
        return $this->_replyTo;
    }

    /**
     * @inheritdoc
     */
    public function setReplyTo($replyTo)
    {
        $this->_replyTo = $this->normalizeAddresses($replyTo);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getSubject()
    {
        return $this->_subject;
    }

    /**
     * @inheritdoc
     */
    public function setSubject($subject)
    {
        $this->_subject = $subject;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setTextBody($text)
    {
        $this->_textBody = $text;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setHtmlBody($html)
    {
        $this->_htmlBody = $html;
        return $this;
    }

    /**
     * 添加附件
     * 
     * @param string $fileName 文件名
     * @param string $content 文件内容
     * @param string $contentType 内容类型
     * @return $this
     */
    public function attach($fileName, $content, $contentType = null)
    {
        $this->_attachments[] = [
            'fileName' => $fileName,
            'content' => $content,
            'contentType' => $contentType ?: $this->getContentTypeFromFileName($fileName),
        ];
        
        return $this;
    }

    /**
     * 添加文件附件
     * 
     * @param string $filePath 文件路径
     * @param string $fileName 文件名（可选）
     * @return $this
     */
    public function attachFile($filePath, $fileName = null)
    {
        if (!file_exists($filePath)) {
            throw new \InvalidArgumentException("文件不存在: {$filePath}");
        }
        
        $fileName = $fileName ?: basename($filePath);
        $content = file_get_contents($filePath);
        $contentType = $this->getContentTypeFromFileName($fileName);
        
        return $this->attach($fileName, $content, $contentType);
    }

    /**
     * 获取附件列表
     * 
     * @return array
     */
    public function getAttachments()
    {
        return $this->_attachments;
    }

    /**
     * @inheritdoc
     */
    public function toString()
    {
        // 优先使用HTML内容，如果没有则使用文本内容
        return $this->_htmlBody ?: $this->_textBody ?: '';
    }

    /**
     * 标准化邮件地址
     * 
     * @param string|array $addresses
     * @return array
     */
    protected function normalizeAddresses($addresses)
    {
        if (is_string($addresses)) {
            return [$addresses];
        }
        
        if (is_array($addresses)) {
            $result = [];
            foreach ($addresses as $email => $name) {
                if (is_numeric($email)) {
                    $result[] = $name;
                } else {
                    $result[$email] = $name;
                }
            }
            return $result;
        }
        
        return [];
    }

    /**
     * 根据文件名获取内容类型
     * 
     * @param string $fileName
     * @return string
     */
    protected function getContentTypeFromFileName($fileName)
    {
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        $contentTypes = [
            'txt' => 'text/plain',
            'html' => 'text/html',
            'htm' => 'text/html',
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'ppt' => 'application/vnd.ms-powerpoint',
            'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
        ];
        
        return $contentTypes[$extension] ?? 'application/octet-stream';
    }

    /**
     * 添加嵌入图片
     * 
     * @param string $filePath 图片文件路径
     * @param string $cid 内容ID
     * @return $this
     */
    public function embed($filePath, $cid = null)
    {
        if (!file_exists($filePath)) {
            throw new \InvalidArgumentException("文件不存在: {$filePath}");
        }
        
        $cid = $cid ?: 'cid_' . uniqid();
        $fileName = basename($filePath);
        $content = file_get_contents($filePath);
        $contentType = $this->getContentTypeFromFileName($fileName);
        
        $this->_attachments[] = [
            'fileName' => $fileName,
            'content' => $content,
            'contentType' => $contentType,
            'cid' => $cid,
            'isEmbedded' => true,
        ];
        
        return $this;
    }

    /**
     * 添加嵌入内容
     * 
     * @param string $content 内容
     * @param string $contentType 内容类型
     * @param string $cid 内容ID
     * @return $this
     */
    public function embedContent($content, $contentType, $cid = null)
    {
        $cid = $cid ?: 'cid_' . uniqid();
        
        $this->_attachments[] = [
            'fileName' => 'embedded_' . $cid,
            'content' => $content,
            'contentType' => $contentType,
            'cid' => $cid,
            'isEmbedded' => true,
        ];
        
        return $this;
    }

    /**
     * 获取嵌入的图片CID
     * 
     * @param string $filePath 文件路径
     * @return string|null
     */
    public function getEmbeddedCid($filePath)
    {
        foreach ($this->_attachments as $attachment) {
            if (isset($attachment['isEmbedded']) && $attachment['isEmbedded'] && 
                isset($attachment['fileName']) && $attachment['fileName'] === basename($filePath)) {
                return $attachment['cid'];
            }
        }
        return null;
    }

    /**
     * 设置邮件优先级
     * 
     * @param int $priority 优先级 (1=高, 3=普通, 5=低)
     * @return $this
     */
    public function setPriority($priority)
    {
        // Microsoft Graph API 不直接支持优先级，但可以存储在主题中
        $this->_priority = $priority;
        return $this;
    }

    /**
     * 获取邮件优先级
     * 
     * @return int
     */
    public function getPriority()
    {
        return $this->_priority ?? 3;
    }

    /**
     * 设置邮件重要性
     * 
     * @param string $importance 重要性 (high, normal, low)
     * @return $this
     */
    public function setImportance($importance)
    {
        $this->_importance = $importance;
        return $this;
    }

    /**
     * 获取邮件重要性
     * 
     * @return string
     */
    public function getImportance()
    {
        return $this->_importance ?? 'normal';
    }

    /**
     * 设置邮件敏感度
     * 
     * @param string $sensitivity 敏感度 (normal, personal, private, confidential)
     * @return $this
     */
    public function setSensitivity($sensitivity)
    {
        $this->_sensitivity = $sensitivity;
        return $this;
    }

    /**
     * 获取邮件敏感度
     * 
     * @return string
     */
    public function getSensitivity()
    {
        return $this->_sensitivity ?? 'normal';
    }
}
