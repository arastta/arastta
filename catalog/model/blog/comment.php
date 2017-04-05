<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2017 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
 */

class ModelBlogComment extends Model
{

    public function addComment($post_id, $data)
    {
        $this->trigger->fire('pre.comment.add', array(&$data));

        $this->db->query("INSERT INTO " . DB_PREFIX . "blog_comment SET author = '" . $this->db->escape($data['name']) . "', email = '" . $this->db->escape($data['email']) . "', customer_id = '" . (int) $this->customer->getId() . "', post_id = '" . (int) $post_id . "', text = '" . $this->db->escape($data['text']) . "', date_added = NOW()");

        $comment_id = $this->db->getLastId();

        if ($this->config->get('config_comment_mail')) {
            $this->load->language('mail/comment');

            $this->load->model('blog/post');

            $post_info = $this->model_blog_post->getPost($post_id);

            $data['post'] = $post_info['name'];

            $subject = $this->emailtemplate->getSubject('Comment', 'comments_1', $data);
            $message = $this->emailtemplate->getMessage('Comment', 'comments_1', $data);

            $mail = new Mail($this->config->get('config_mail'));
            $mail->setTo(html_entity_decode($this->config->get('config_email'), ENT_QUOTES, 'UTF-8'));
            $mail->setFrom(html_entity_decode($this->config->get('config_email'), ENT_QUOTES, 'UTF-8'));
            $mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
            $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
            $mail->setHtml(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
            $mail->send();

            // Send to additional alert emails
            $emails = explode(',', $this->config->get('config_mail_alert'));

            foreach ($emails as $email) {
                if ($email && preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $email)) {
                    $mail->setTo($email);
                    $mail->send();
                }
            }
        }

        $this->trigger->fire('post.comment.add', array(&$comment_id));
    }

    public function getComments($data)
    {
        $sql ="SELECT c.*, pd.name AS name FROM " . DB_PREFIX . "blog_comment c LEFT JOIN " . DB_PREFIX . "blog_post_description pd ON (c.post_id = pd.post_id) WHERE c.status = 1 AND pd.language_id = '" . (int) $this->config->get('config_language_id') . "'";

        $sql .= " GROUP BY c.post_id";

        $sort_data = array(
            'c.post_id',
            'c.customer_id',
            'c.email',
            'c.author',
            'c.sort_order',
            'c.date_added'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY c.date_added";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getCommentsByPostId($post_id, $start = 0, $limit = 20)
    {
        if ($start < 0) {
            $start = 0;
        }

        if ($limit < 1) {
            $limit = 20;
        }

        $query = $this->db->query("SELECT c.*, p.post_id, pd.name, p.image FROM " . DB_PREFIX . "blog_comment c LEFT JOIN " . DB_PREFIX . "blog_post p ON (c.post_id = p.post_id) LEFT JOIN " . DB_PREFIX . "blog_post_description pd ON (p.post_id = pd.post_id) WHERE p.post_id = '" . (int) $post_id . "' AND p.date_available <= NOW() AND p.status = '1' AND c.status = '1' AND pd.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY c.date_added DESC LIMIT " . (int) $start . "," . (int) $limit);

        return $query->rows;
    }

    public function getTotalCommentsByPostId($post_id)
    {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "blog_comment c LEFT JOIN " . DB_PREFIX . "blog_post p ON (c.post_id = p.post_id) LEFT JOIN " . DB_PREFIX . "blog_post_description pd ON (p.post_id = pd.post_id) WHERE p.post_id = '" . (int) $post_id . "' AND p.date_available <= NOW() AND p.status = '1' AND c.status = '1' AND pd.language_id = '" . (int) $this->config->get('config_language_id') . "'");

        return $query->row['total'];
    }
}
