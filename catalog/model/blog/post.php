<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2018 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
 */

class ModelBlogPost extends Model
{

    public function updateViewed($post_id)
    {
        $this->db->query("UPDATE " . DB_PREFIX . "blog_post SET viewed = (viewed + 1) WHERE post_id = '" . (int) $post_id . "'");
    }

    public function getPost($post_id)
    {
        $query = $this->db->query("SELECT DISTINCT p.*, pd.*, (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "blog_comment c WHERE c.post_id = p.post_id AND c.status = '1' GROUP BY c.post_id) AS comments, p.sort_order FROM " . DB_PREFIX . "blog_post p LEFT JOIN " . DB_PREFIX . "blog_post_description pd ON (p.post_id = pd.post_id) LEFT JOIN " . DB_PREFIX . "blog_post_to_store p2s ON (p.post_id = p2s.post_id) WHERE p.post_id = '" . (int)$post_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");

        if ($query->num_rows) {
            return array(
                'post_id'          => $query->row['post_id'],
                'name'             => $query->row['name'],
                'description'      => $query->row['description'],
                'meta_title'       => $query->row['meta_title'],
                'meta_description' => $query->row['meta_description'],
                'meta_keyword'     => $query->row['meta_keyword'],
                'tag'              => $query->row['tag'],
                'image'            => $query->row['image'],
                'author'           => $query->row['author'],
                'featured'         => $query->row['featured'],
                'date_available'   => $query->row['date_available'],
                'comments'         => $query->row['comments'] ? $query->row['comments'] : 0,
                'sort_order'       => $query->row['sort_order'],
                'status'           => $query->row['status'],
                'date_added'       => $query->row['date_added'],
                'date_modified'    => $query->row['date_modified'],
                'viewed'           => $query->row['viewed']
            );
        } else {
            return false;
        }
    }

    public function getPosts($data, $start = 0, $limit = 30)
    {
        $sql = "SELECT p.post_id";

        if (!empty($data['filter_category_id'])) {
            if (!empty($data['filter_sub_category'])) {
                $sql .= " FROM " . DB_PREFIX . "blog_category_path cp LEFT JOIN " . DB_PREFIX . "blog_post_to_category p2c ON (cp.category_id = p2c.category_id)";
            } else {
                $sql .= " FROM " . DB_PREFIX . "blog_post_to_category p2c";
            }

            $sql .= " LEFT JOIN " . DB_PREFIX . "blog_post p ON (p2c.post_id = p.post_id)";
        } else {
            $sql .= " FROM " . DB_PREFIX . "blog_post p";
        }

        $sql .= " LEFT JOIN " . DB_PREFIX . "blog_post_description pd ON (p.post_id = pd.post_id) LEFT JOIN " . DB_PREFIX . "blog_post_to_store p2s ON (p.post_id = p2s.post_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

        if (isset($data['filter_featured'])) {
            $sql .= " AND p.featured= '" . (int)$data['filter_featured'] . "'";
        }

        if (!empty($data['filter_category_id'])) {
            if (!empty($data['filter_sub_category'])) {
                $sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
            } else {
                $sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
            }
        }

        if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
            $sql .= " AND (";

            if (!empty($data['filter_name'])) {
                $implode = array();

                $words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));

                foreach ($words as $word) {
                    $implode[] = "pd.name LIKE '%" . $this->db->escape($word) . "%'";
                }

                if ($implode) {
                    $sql .= " " . implode(" AND ", $implode) . "";
                }

                if (!empty($data['filter_description'])) {
                    $sql .= " OR pd.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
                }
            }

            if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
                $sql .= " OR ";
            }

            if (!empty($data['filter_tag'])) {
                $sql .= "pd.tag LIKE '%" . $this->db->escape($data['filter_tag']) . "%'";
            }

            $sql .= ")";
        }

        $sql .= " GROUP BY p.post_id";

        $sort_data = array(
            'pd.name',
            'p.viewed',
            'p.author',
            'p.sort_order',
            'p.date_added',
            'p.date_modified'
        );

        $default_post_sort_order = explode('-', $this->config->get('config_blog_post_list_sort_order'));

        if (count($default_post_sort_order) > 1) {
            $sort  = $default_post_sort_order[0];
            $order = $default_post_sort_order[1];
        } else {
            $sort  = $default_post_sort_order[0];
            $order = 'ASC';
        }

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.author') {
                $sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
            } else {
                $sql .= " ORDER BY " . $data['sort'];
            }
        } else {
            if ($sort == 'pd.name' || $sort == 'p.author') {
                $sql .= " ORDER BY LCASE(" . $sort . ")";
            } else {
                $sql .= " ORDER BY " . $sort;
            }
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC, LCASE(pd.name) DESC";
        } else {
            $sql .= " " . $order . ", LCASE(pd.name) " . $order;
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

        $post_data = array();

        $query = $this->db->query($sql);

        foreach ($query->rows as $result) {
            $post_data[$result['post_id']] = $this->getPost($result['post_id']);
        }

        return $post_data;
    }

    public function getTotalPosts($data)
    {
        $sql = "SELECT COUNT(DISTINCT p.post_id) AS total";

        if (!empty($data['filter_category_id'])) {
            if (!empty($data['filter_sub_category'])) {
                $sql .= " FROM " . DB_PREFIX . "blog_category_path cp LEFT JOIN " . DB_PREFIX . "blog_post_to_category p2c ON (cp.category_id = p2c.category_id)";
            } else {
                $sql .= " FROM " . DB_PREFIX . "blog_post_to_category p2c";
            }

            $sql .= " LEFT JOIN " . DB_PREFIX . "blog_post p ON (p2c.post_id = p.post_id)";
        } else {
            $sql .= " FROM " . DB_PREFIX . "blog_post p";
        }

        $sql .= " LEFT JOIN " . DB_PREFIX . "blog_post_description pd ON (p.post_id = pd.post_id) LEFT JOIN " . DB_PREFIX . "blog_post_to_store p2s ON (p.post_id = p2s.post_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

        if (!empty($data['filter_category_id'])) {
            if (!empty($data['filter_sub_category'])) {
                $sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
            } else {
                $sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
            }
        }

        if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
            $sql .= " AND (";

            if (!empty($data['filter_name'])) {
                $implode = array();

                $words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));

                foreach ($words as $word) {
                    $implode[] = "pd.name LIKE '%" . $this->db->escape($word) . "%'";
                }

                if ($implode) {
                    $sql .= " " . implode(" AND ", $implode) . "";
                }

                if (!empty($data['filter_description'])) {
                    $sql .= " OR pd.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
                }
            }

            if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
                $sql .= " OR ";
            }

            if (!empty($data['filter_tag'])) {
                $sql .= "pd.tag LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_tag'])) . "%'";
            }

            $sql .= ")";
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getFeaturedPosts()
    {
        $sql = "SELECT * FROM " . DB_PREFIX . "blog_post p LEFT JOIN " . DB_PREFIX . "blog_post_description pd ON (p.post_id = pd.post_id) LEFT JOIN " . DB_PREFIX . "blog_post_to_store p2s ON (p.post_id = p2s.post_id) WHERE p.featured = 1 AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY p.date_added DESC";

        $post_data = array();

        $query = $this->db->query($sql);

        foreach ($query->rows as $result) {
            $post_data[$result['post_id']] = $this->getPost($result['post_id']);
        }

        return $post_data;
    }

    public function getLatestPosts($data = array())
    {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog i LEFT JOIN " . DB_PREFIX . "blog_description id ON (i.post_id = id.post_id) LEFT JOIN " . DB_PREFIX . "blog_to_store i2s ON (i.post_id = i2s.post_id) WHERE id.language_id = '" . (int) $this->config->get('config_language_id') . "' AND i2s.store_id = '" . (int) $this->config->get('config_store_id') . "' AND i.status = '1' AND i.sort_order <> '-1' ORDER BY i.sort_order, i.post_id DESC LIMIT " . (int) $data['start'] . "," . (int) $data['limit'] . "");

        return $query->rows;
    }

    public function getCategories($post_id)
    {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog_post_to_category WHERE post_id = '" . (int)$post_id . "'");

        return $query->rows;
    }

    public function getPostCategory($post_id)
    {
        $categories = $this->getCategories($post_id);

        if (!$categories) {
            return false;
        }

        $category = end($categories);

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog_category_description WHERE category_id = '" . (int)$category['category_id'] . "' AND language_id = '" . (int) $this->config->get('config_language_id') . "'");

        if (!$query->num_rows) {
            return false;
        }

        return $query->row;
    }

    public function getPostsByBlogCategoryId($blog_category_id, $start = 0, $limit = 40)
    {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog n LEFT JOIN " . DB_PREFIX . "blog_description nd ON (n.post_id = nd.post_id) LEFT JOIN " . DB_PREFIX . "blog_to_store n2s ON (n.post_id = n2s.post_id) LEFT JOIN " . DB_PREFIX . "blog_to_category n2c ON (n.post_id = n2c.post_id) WHERE nd.language_id = '" . (int) $this->config->get('config_language_id') . "' AND n2s.store_id = '" . (int) $this->config->get('config_store_id') . "' AND n2c.blog_category_id = '" . (int) $blog_category_id . "' AND n.status = '1' AND n.sort_order <> '-1' ORDER BY n.sort_order, n.post_id DESC LIMIT " . (int) $start . "," . (int) $limit);

        return $query->rows;
    }

    public function getTotalPostsByPostCategoryId($blog_category_id = 0)
    {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "blog WHERE status = '1'");
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "blog_to_category n2c LEFT JOIN " . DB_PREFIX . "blog n ON (n2c.post_id = n.post_id) LEFT JOIN " . DB_PREFIX . "blog_to_store n2s ON (n.post_id = n2s.post_id) WHERE n.status = '1' AND n2s.store_id = '" . (int) $this->config->get('config_store_id') . "' AND n2c.blog_category_id = '" . (int) $blog_category_id . "'");

        return $query->row['total'];
    }

    public function getTotalPostsPerCategory($data = array())
    {
        $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "blog n LEFT JOIN " . DB_PREFIX . "blog_to_store n2s ON (n.post_id = n2s.post_id) WHERE n.status = '1' AND n2s.store_id = '" . (int) $this->config->get('config_store_id') . "'";

        if (isset($data['filter_blog_category_id']) && $data['filter_blog_category_id']) {
            if (isset($data['filter_sub_blog_category']) && $data['filter_sub_blog_category']) {
                $implode_data = array();

                $this->load->model('blog/blog_category');

                $blog_categories = $this->model_blog_blog_category->getBlogCategoriesByParentId($data['filter_blog_category_id']);

                foreach ($blog_categories as $blog_category_id) {
                    $implode_data[] = "n2c.blog_category_id = '" . (int) $blog_category_id . "'";
                }

                $sql .= " AND n.post_id IN (SELECT n2c.post_id FROM " . DB_PREFIX . "blog_to_category n2c WHERE " . implode(' OR ', $implode_data) . ")";
            } else {
                $sql .= " AND n.post_id IN (SELECT n2c.post_id FROM " . DB_PREFIX . "blog_to_category n2c WHERE n2c.blog_category_id = '" . (int) $data['filter_blog_category_id'] . "')";
            }
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function updatePostReadCounter($post_id, $new_read_counter_value)
    {
        $this->db->query("UPDATE " . DB_PREFIX . "blog SET viewed = '" . (int) $new_read_counter_value . "' WHERE post_id = '" . (int) $post_id . "'");
    }

    public function getRelatedPost($post_id)
    {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog n LEFT JOIN " . DB_PREFIX . "blog_description nd ON (n.post_id = nd.post_id) LEFT JOIN " . DB_PREFIX . "blog_to_store n2s ON (n.post_id = n2s.post_id) LEFT JOIN " . DB_PREFIX . "blog_related nr ON (n.post_id = nr.child_post_id) WHERE nd.language_id = '" . (int) $this->config->get('config_language_id') . "' AND n2s.store_id = '" . (int) $this->config->get('config_store_id') . "' AND n.status = '1' AND n.sort_order <> '-1' AND nr.parent_post_id = '" . (int) $post_id . "' ORDER BY n.sort_order, n.post_id DESC");

        return $query->rows;
    }

    public function addComment($post_id, $data)
    {
        if ($this->config->get('blogsetting_comment_approve')) {
            $comment_status = '0';
        } else {
            $comment_status = '1';
        }

        $this->event->trigger('pre.comment.add', $data);

        $this->db->query("INSERT INTO " . DB_PREFIX . "blog_comment SET name = '" . $this->db->escape($data['name']) . "', email = '" . $this->db->escape($data['email']) . "', post_id = '" . (int) $post_id . "', comment = '" . $this->db->escape($data['comment']) . "', status = '" . $comment_status . "', date_added = NOW()");

        $blog_comment_id = $this->db->getLastId();

        $this->event->trigger('post.comment.add', $blog_comment_id);
    }

    public function checkMenu($type, $link = null)
    {
        $sql = "SELECT * FROM " . DB_PREFIX . "menu m LEFT JOIN " . DB_PREFIX . "menu_description md ON (m.menu_id = md.menu_id) WHERE m.menu_type = 'blog_" . $this->db->escape($type) . "' AND md.language_id = '" . (int)$this->config->get('config_language_id') . "'";

        if ($link) {
            $sql .= " AND md.link = " . $this->db->escape($link);
        }

        $query = $this->db->query($sql);

        if ($query->num_rows) {
            return $query->row['name'];
        }

        $sql = "SELECT * FROM " . DB_PREFIX . "menu_child mc LEFT JOIN " . DB_PREFIX . "menu_child_description mcd ON (mc.menu_child_id = mcd.menu_id) WHERE mc.menu_type = 'blog_" . $this->db->escape($type) . "' AND mcd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

        if ($link) {
            $sql .= " AND mcd.link = " . $this->db->escape($link);
        }

        $query = $this->db->query($sql);

        if ($query->num_rows) {
            return $query->row['name'];
        }

        return false;
    }
}
