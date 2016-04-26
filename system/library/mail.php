<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class Mail {

    protected $to;
    protected $cc;
    protected $bcc;
    protected $from;
    protected $return_path;
    protected $read_receipt_to;
    protected $sender;
    protected $reply_to;
    protected $subject;
    protected $text;
    protected $html;
    protected $charset = null;
    protected $attachments = array();
    protected $attachments_inline = array();
    protected $priority;

    public $config_mail_protocol = 'phpmail';
    public $config_mail_sendmail_path = '/usr/sbin/sendmail -bs';
    public $config_mail_smtp_hostname;
    public $config_mail_smtp_username;
    public $config_mail_smtp_password;
    public $config_mail_smtp_port = 25;
    public $config_mail_smtp_encryption = 'none';

    // Old variables, keeping for B/C
    public $protocol = 'phpmail';
    public $parameter = '';
    public $sendmail_path = '/usr/sbin/sendmail -bs';
    public $smtp_hostname;
    public $smtp_username;
    public $smtp_password;
    public $smtp_port = 25;
    public $smtp_timeout = 0;
    public $smtp_encryption = 'none';

    public function __construct($config = array()) {
        if (!class_exists('Swift_Message')) {
            require_once(DIR_SYSTEM.'vendor/swiftmailer/swiftmailer/lib/swift_required.php');
        }

        foreach ($config as $key => $value) {
            if (!strpos($key, 'config_mail_')) {
                $this->set('config_mail_' . $key, $value);
            }
            else {
                $this->set($key, $value);
            }
        }
    }

    public function get($name) {
        return $this->$name;
    }

    public function set($name, $value, $decode = true, $is_array = false) {
        if ($is_array == true) {
            $array = $this->$name;
            $array[] = $value;

            $this->$name = $array;
        }
        else {
            if ($decode == true) {
                $this->$name = html_entity_decode($value, ENT_QUOTES, 'UTF-8');
            }
            else {
                $this->$name = $value;
            }
        }
    }

    public function setTo($to, $decode = true) {
        $this->set('to', $to, $decode);
    }

    public function setCc($cc, $decode = true) {
        $this->set('cc', $cc, $decode);
    }

    public function setBcc($bcc, $decode = true) {
        $this->set('bcc', $bcc, $decode);
    }

    public function setFrom($from, $decode = true) {
        $this->set('from', $from, $decode);
    }

    public function setReturnPath($return_path, $decode = true) {
        $this->set('return_path', $return_path, $decode);
    }

    public function setReadReceiptTo($read_receipt_to, $decode = true) {
        $this->set('read_receipt_to', $read_receipt_to, $decode);
    }

    public function setSender($sender, $decode = true) {
        $this->set('sender', $sender, $decode);
    }

    public function setReplyTo($reply_to, $decode = true) {
        $this->set('reply_to', $reply_to, $decode);
    }

    public function setSubject($subject, $decode = true) {
        $this->set('subject', $subject, $decode);
    }

    public function setText($text, $decode = true) {
        $this->set('text', $text, $decode);
    }

    public function setHtml($html, $decode = true) {
        $this->set('html', $html, $decode);
    }

    public function setCharset($charset, $decode = false) {
        $this->set('charset', $charset, $decode);
    }

    public function addAttachment($filename, $decode = false) {
        $this->set('attachments', $filename, $decode, true);
    }

    public function addAttachmentInline($filename, $decode = false) {
        $this->set('attachments_inline', $filename, $decode, true);
    }

    public function setPriority($priority, $decode = false) {
        $this->set('priority', $priority, $decode);
    }

    public function send() {
        // Check First
        if (!$this->to) {
            trigger_error('Error: E-Mail to required!');
            exit();
        }

        if (!$this->from) {
            trigger_error('Error: E-Mail from required!');
            exit();
        }

        if (!$this->sender) {
            trigger_error('Error: E-Mail sender required!');
            exit();
        }

        if (!$this->subject) {
            trigger_error('Error: E-Mail subject required!');
            exit();
        }

        if ((!$this->text) && (!$this->html)) {
            trigger_error('Error: E-Mail message required!');
            exit();
        }

        if (!$this->reply_to) {
            $this->setReplyTo(array($this->from => $this->sender), false);
        }

        // Create the message object
        $message = Swift_Message::newInstance();

        if (is_array($this->to)) {
            $message->setTo($this->to);
        }
        else {
            $message->addTo($this->to);
        }

        if (!empty($this->cc)) {
            if (is_array($this->cc)) {
                $message->setCc($this->cc);
            }
            else {
                $message->addCc($this->cc);
            }
        }

        if (!empty($this->bcc)) {
            if (is_array($this->bcc)) {
                $message->setBcc($this->bcc);
            }
            else {
                $message->addBcc($this->bcc);
            }
        }

        if (is_array($this->from)) {
            $message->setFrom($this->from);
        }
        else {
            $message->addFrom($this->from, $this->sender);
        }

        if (!empty($this->return_path)) {
            $message->setReturnPath($this->return_path);
        }

        if (!empty($this->read_receipt_to)) {
            $message->setReadReceiptTo($this->read_receipt_to);
        }

        if (is_array($this->reply_to)) {
            $message->setReplyTo($this->reply_to);
        }
        else {
            $message->addReplyTo($this->reply_to);
        }

        $message->setSubject($this->subject);

        if (!empty($this->html)) {
            $message->setBody($this->html, 'text/html', $this->charset);
        }

        if (!empty($this->text)) {
            $message->addPart($this->text, 'text/plain', $this->charset);
        }

        foreach ($this->attachments as $attachment) {
            $message->attach(Swift_Attachment::fromPath($attachment));
        }

        foreach ($this->attachments_inline as $attachment_inline) {
            $message->attach(Swift_Attachment::fromPath($attachment_inline)->setDisposition('inline'));
        }

        if (!empty($this->priority)) {
            $message->setPriority($this->priority);
        }

        // Create the transport object
        if ($this->config_mail_protocol == 'phpmail') {
            $transport = Swift_MailTransport::newInstance();
        }
        else if ($this->config_mail_protocol == 'sendmail') {
            $transport = Swift_SendmailTransport::newInstance($this->config_mail_sendmail_path);
        }
        else {
            $transport = Swift_SmtpTransport::newInstance($this->config_mail_smtp_hostname, $this->config_mail_smtp_port);

            if (!empty($this->config_mail_smtp_username)) {
                $transport->setUsername($this->config_mail_smtp_username);
            }

            if (!empty($this->config_mail_smtp_password)) {
                $transport->setPassword($this->config_mail_smtp_password);
            }

            if ($this->config_mail_smtp_encryption != 'none') {
                $transport->setEncryption($this->config_mail_smtp_encryption);
            }
        }

        // Finally, send the mail
        $mailer = Swift_Mailer::newInstance($transport);

        $result = $mailer->send($message);

        return $result;
    }
}
