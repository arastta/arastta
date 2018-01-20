<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2018 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
 */

class ModelBlogComment extends Model
{

    public function addComment($data)
    {
        $this->trigger->fire('pre.admin.blog.comment.add', array(&$data));

        $this->db->query("INSERT INTO " . DB_PREFIX . "blog_comment SET email = '" . $this->db->escape($data['email']) . "', author = '" . $this->db->escape($data['author']) . "', post_id = '" . (int) $data['post_id'] . "', text = '" . $this->db->escape(strip_tags($data['text'])) . "', status = '" . (int) $data['status'] . "', date_added = NOW()");

        $comment_id = $this->db->getLastId();

        $this->cache->delete('blog.post');

        $this->trigger->fire('post.admin.blog.comment.add', array(&$comment_id));

        return $comment_id;
    }

    public function editComment($comment_id, $data)
    {
        $this->trigger->fire('pre.admin.blog.comment.edit', array(&$data));

        $this->db->query("UPDATE " . DB_PREFIX . "blog_comment SET email = '" . $this->db->escape($data['email']) . "', author = '" . $this->db->escape($data['author']) . "', post_id = '" . (int) $data['post_id'] . "', text = '" . $this->db->escape(strip_tags($data['text'])) . "', status = '" . (int) $data['status'] . "', date_modified = NOW() WHERE comment_id = '" . (int) $comment_id . "'");

        $this->cache->delete('blog.post');

        $this->trigger->fire('post.admin.blog.comment.edit', array(&$comment_id));
    }

    public function deleteComment($comment_id)
    {
        $this->trigger->fire('pre.admin.blog.comment.delete', array(&$comment_id));

        $this->db->query("DELETE FROM " . DB_PREFIX . "blog_comment WHERE comment_id = '" . (int) $comment_id . "'");

        $this->cache->delete('blog.post');

        $this->trigger->fire('post.admin.blog.comment.delete', array(&$comment_id));
    }

    public function updateComment($comment_id, $key, $value)
    {
        $this->trigger->fire('pre.admin.blog.comment.update', array(&$comment_id, &$key, &$value));

        $this->db->query("UPDATE " . DB_PREFIX . "blog_comment SET " . $key . " = '" . $this->db->escape($value) . "', date_modified = NOW() WHERE comment_id = '" . (int) $comment_id . "'");

        $this->trigger->fire('post.admin.blog.comment.update', array(&$comment_id, &$key, &$value));
    }

    public function getComment($comment_id)
    {
        $query = $this->db->query("SELECT DISTINCT *, (SELECT pd.name FROM " . DB_PREFIX . "blog_post_description pd WHERE pd.post_id = c.post_id AND pd.language_id = '" . (int) $this->config->get('config_language_id') . "') AS post FROM " . DB_PREFIX . "blog_comment c WHERE c.comment_id = '" . (int) $comment_id . "'");

        return $query->row;
    }

    public function getComments($data = array())
    {
        $sql = "SELECT c.*, pd.name as name FROM " . DB_PREFIX . "blog_comment c LEFT JOIN " . DB_PREFIX . "blog_post_description pd ON (c.post_id = pd.post_id) WHERE pd.language_id = '" . (int) $this->config->get('config_language_id') . "'";

        if (!empty($data['filter_post'])) {
            $sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_post']) . "%'";
        }

        if (!empty($data['filter_email'])) {
            $sql .= " AND c.email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
        }

        if (!empty($data['filter_author'])) {
            $sql .= " AND c.author LIKE '" . $this->db->escape($data['filter_author']) . "%'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $sql .= " AND c.status = '" . (int) $data['filter_status'] . "'";
        }

        if (!empty($data['filter_date_added'])) {
            $sql .= " AND DATE(c.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        $sort_data = array(
            'pd.name',
            'c.email',
            'c.author',
            'c.rating',
            'c.status',
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

            $sql .= " LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalComments()
    {
        $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "blog_comment c LEFT JOIN " . DB_PREFIX . "blog_post_description pd ON (c.post_id = pd.post_id) WHERE pd.language_id = '" . (int) $this->config->get('config_language_id') . "'";

        if (!empty($data['filter_product'])) {
            $sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_product']) . "%'";
        }

        if (!empty($data['filter_email'])) {
            $sql .= " AND c.email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
        }

        if (!empty($data['filter_author'])) {
            $sql .= " AND c.author LIKE '" . $this->db->escape($data['filter_author']) . "%'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $sql .= " AND c.status = '" . (int) $data['filter_status'] . "'";
        }

        if (!empty($data['filter_date_added'])) {
            $sql .= " AND DATE(c.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getTotalCommentsAwaitingApproval()
    {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "blog_comment WHERE status = '0'");

        return $query->row['total'];
    }
}
