<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2017 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
 */

class ModelBlogPost extends Model
{

    public function addPost($data)
    {
        $this->trigger->fire('pre.admin.blog.post.add', array(&$data));

        $this->db->query("INSERT INTO " . DB_PREFIX . "blog_post SET allow_comment = '" . (int) $data['allow_comment'] . "', featured = '" . (int) $data['featured'] . "', image = '" . $this->db->escape($data['image']) . "', author = '" . $this->db->escape($data['author']) . "', date_available = '" . $this->db->escape($data['date_available']) . "', sort_order = '" . (int) $this->request->post['sort_order'] . "', status = '" . (int) $data['status'] . "', date_added = NOW()");

        $post_id = $this->db->getLastId();

        foreach ($data['post_description'] as $language_id => $value) {
            $value['meta_title'] = empty($value['meta_title']) ? $value['name'] : $value['meta_title'];
            $value['tag'] = !empty($data['post_tag'][$language_id]) ? implode(',', $data['post_tag'][$language_id]) : '';

            $this->db->query("INSERT INTO " . DB_PREFIX . "blog_post_description SET post_id = '" . (int) $post_id . "', language_id = '" . (int) $language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', tag = '" . $this->db->escape($value['tag']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
        }

        if (isset($data['post_store'])) {
            foreach ($data['post_store'] as $store_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "blog_post_to_store SET post_id = '" . (int) $post_id . "', store_id = '" . (int) $store_id . "'");
            }
        }

        if (isset($data['post_category'])) {
            foreach ($data['post_category'] as $post_category_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "blog_post_to_category SET post_id = '" . (int) $post_id . "', category_id = '" . (int) $post_category_id . "'");
            }
        }

        if (isset($data['post_layout'])) {
            foreach ($data['post_layout'] as $store_id => $layout_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "blog_post_to_layout SET post_id = '" . (int) $post_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
            }
        }

        if (isset($data['seo_url'])) {
            $this->load->model('catalog/url_alias');

            foreach ($data['seo_url'] as $language_id => $value) {
                $alias = empty($value) ? $data['post_description'][$language_id]['name'] : $value;

                $this->model_catalog_url_alias->addAlias('blog_post', $post_id, $alias, $language_id);
            }
        }

        $this->cache->delete('blog.post');

        $this->trigger->fire('post.admin.blog.post.add', array(&$post_id));

        return $post_id;
    }

    public function editPost($post_id, $data)
    {
        $this->trigger->fire('pre.admin.blog.post.edit', array(&$data));

        $this->db->query("UPDATE " . DB_PREFIX . "blog_post SET sort_order = '" . (int) $data['sort_order'] . "', image = '" . $this->db->escape($data['image']) . "', allow_comment = '" . (int) $data['allow_comment'] . "', featured = '" . (int) $data['featured'] . "' , author = '" . $this->db->escape($data['author']) . "', date_available = '" . $this->db->escape($data['date_available']) . "', status = '" . (int) $data['status'] . "' WHERE post_id = '" . (int) $post_id . "'");

        $this->db->query("DELETE FROM " . DB_PREFIX . "blog_post_description WHERE post_id = '" . (int) $post_id . "'");

        foreach ($data['post_description'] as $language_id => $value) {
            $value['meta_title'] = empty($value['meta_title']) ? $value['name'] : $value['meta_title'];
            $value['tag'] = !empty($data['post_tag'][$language_id]) ? implode(',', $data['post_tag'][$language_id]) : '';

            $this->db->query("INSERT INTO " . DB_PREFIX . "blog_post_description SET post_id = '" . (int) $post_id . "', language_id = '" . (int) $language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', tag = '" . $this->db->escape($value['tag']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "blog_post_to_store WHERE post_id = '" . (int) $post_id . "'");

        if (isset($data['post_store'])) {
            foreach ($data['post_store'] as $store_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "blog_post_to_store SET post_id = '" . (int) $post_id . "', store_id = '" . (int) $store_id . "'");
            }
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "blog_post_to_category WHERE post_id = '" . (int) $post_id . "'");

        if (isset($data['post_category'])) {
            foreach ($data['post_category'] as $post_category_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "blog_post_to_category SET post_id = '" . (int) $post_id . "', category_id = '" . (int) $post_category_id . "'");
            }
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "blog_post_to_layout WHERE post_id = '" . (int) $post_id . "'");

        if (isset($data['post_layout'])) {
            foreach ($data['post_layout'] as $store_id => $layout_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "blog_post_to_layout SET post_id = '" . (int) $post_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
            }
        }

        $this->load->model('catalog/url_alias');

        $this->model_catalog_url_alias->clearAliases('blog_post', $post_id);

        foreach ($data['seo_url'] as $language_id => $value) {
            $alias = empty($value) ? $data['post_description'][$language_id]['name'] : $value;

            $this->model_catalog_url_alias->addAlias('blog_post', $post_id, $alias, $language_id);
        }

        $this->cache->delete('blog.post');

        $this->trigger->fire('post.admin.blog.post.edit', array(&$post_id));
    }

    public function deletePost($post_id)
    {
        $this->trigger->fire('pre.admin.blog.post.delete', array(&$post_id));

        $this->db->query("DELETE FROM " . DB_PREFIX . "blog_post WHERE post_id = '" . (int) $post_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "blog_post_description WHERE post_id = '" . (int) $post_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "blog_post_to_store WHERE post_id = '" . (int) $post_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "blog_post_to_layout WHERE post_id = '" . (int) $post_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "blog_post_to_category WHERE post_id = '" . (int) $post_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "blog_comment WHERE post_id = '" . (int) $post_id . "'");

        $this->load->model('catalog/url_alias');

        $this->model_catalog_url_alias->clearAliases('blog_post', $post_id);

        // Main Menu Item
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "menu_description` AS md LEFT JOIN `" . DB_PREFIX . "menu` AS m ON m.menu_id = md.menu_id WHERE m.menu_type = 'blog.post' AND md.link = '" . (int)$post_id . "'");

        if (!empty($query->row['menu_id'])) {
            $menu_id = $query->row['menu_id'];

            $this->db->query("DELETE FROM `" . DB_PREFIX . "menu` WHERE menu_id = '" . (int)$menu_id . "'");
            $this->db->query("DELETE FROM `" . DB_PREFIX . "menu_description` WHERE menu_id = '" . (int)$menu_id . "'");
            $this->db->query("DELETE FROM `" . DB_PREFIX . "menu_to_store` WHERE menu_id = '" . (int)$menu_id . "'");
        }

        // Child Menu Item
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "menu_child_description` AS mcd LEFT JOIN `" . DB_PREFIX . "menu_child` AS mc ON mc.menu_child_id = mcd.menu_child_id WHERE mc.menu_type = 'blog.post' AND mcd.link = '" . (int)$post_id . "'");

        if (!empty($query->row['menu_child_id'])) {
            $menu_child_id = $query->row['menu_child_id'];

            $this->db->query("DELETE FROM `" . DB_PREFIX . "menu_child` WHERE menu_child_id = '" . (int)$menu_child_id . "'");
            $this->db->query("DELETE FROM `" . DB_PREFIX . "menu_child_description` WHERE menu_child_id = '" . (int)$menu_child_id . "'");
            $this->db->query("DELETE FROM `" . DB_PREFIX . "menu_child_to_store` WHERE menu_child_id = '" . (int)$menu_child_id . "'");
        }

        $this->cache->delete('blog.post');

        $this->trigger->fire('post.admin.blog.post.delete', array(&$post_id));
    }

    public function updatePost($post_id, $key, $value)
    {
        $this->trigger->fire('pre.admin.blog.post.update', array(&$post_id, $key, $value));

        $this->db->query("UPDATE " . DB_PREFIX . "blog_post SET date_modified = NOW() WHERE post_id = '" . (int) $post_id . "'");

        if ($key == 'name') {
            $this->db->query("UPDATE " . DB_PREFIX . "blog_post_description SET " . $key . " = '" . $this->db->escape($value) . "' WHERE post_id = '" . (int) $post_id . "' AND language_id = '" . (int) $this->config->get('config_language_id') . "'");
        } elseif ($key == 'status') {
            $this->db->query("UPDATE " . DB_PREFIX . "blog_post SET " . $key . " = '" . $this->db->escape($value) . "' WHERE post_id = '" . (int) $post_id . "'");

            // Main menu changed product status
            $product_menus = $this->db->query("SELECT * FROM " . DB_PREFIX . "menu m LEFT JOIN " . DB_PREFIX . "menu_description md ON (m.menu_id = md.menu_id) WHERE m.menu_type = 'blog.post' AND md.link = " . (int) $post_id);

            if ($product_menus->num_rows) {
                foreach ($product_menus->rows as $product_menu) {
                    $this->db->query("UPDATE " . DB_PREFIX . "menu SET status = '" . (int) $value . "' WHERE menu_id = '" . (int) $product_menu['menu_id'] . "'");
                }
            }

            // Child menu changed product status
            $product_child_menus = $this->db->query("SELECT * FROM " . DB_PREFIX . "menu_child mc LEFT JOIN " . DB_PREFIX . "menu_child_description mcd ON (mc.menu_child_id = mcd.menu_child_id) WHERE mc.menu_type = 'blog.post' AND mcd.link = " . (int) $post_id);

            if ($product_child_menus->num_rows) {
                foreach ($product_child_menus->rows as $product_child_menu) {
                    $this->db->query("UPDATE " . DB_PREFIX . "menu_child SET status = '" . (int) $value . "' WHERE menu_child_id = '" . (int) $product_child_menu['menu_child_id'] . "'");
                }
            }
        } else {
            $this->db->query("UPDATE " . DB_PREFIX . "blog_post SET " . $key . " = '" . $this->db->escape($value) . "' WHERE post_id = '" . (int) $post_id . "'");
        }

        $this->trigger->fire('post.admin.blog.post.update', array(&$post_id, $key, $value));
    }

    public function getPost($post_id)
    {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "blog_post p LEFT JOIN " . DB_PREFIX . "blog_post_description pd ON (p.post_id = pd.post_id) WHERE p.post_id = '" . (int)$post_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

        $post = $query->row;

        if ($post) {
            $post['seo_url'] = array();

            $this->load->model('catalog/url_alias');

            $aliases = $this->model_catalog_url_alias->getAliases('blog_post', $post_id);

            if ($aliases) {
                foreach ($aliases as $row) {
                    $post['seo_url'][$row['language_id']] = $row['keyword'];
                }
            }
        }

        return $post;
    }

    public function getPosts($data = array())
    {
        if ($data) {
            $sql = "SELECT * FROM " . DB_PREFIX . "blog_post p LEFT JOIN " . DB_PREFIX . "blog_post_description pd ON (p.post_id = pd.post_id) WHERE pd.language_id = '" . (int) $this->config->get('config_language_id') . "'";

            $sort_data = array(
                'pd.name',
                'p.date_added',
                'p.sort_order'
            );

            if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
                $sql .= " ORDER BY " . $data['sort'];
            } else {
                $sql .= " ORDER BY p.post_id";
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
        } else {
            $blog_data = $this->cache->get('blog.post.' . $this->config->get('config_language_id'));

            if (!$blog_data) {
                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog_post p LEFT JOIN " . DB_PREFIX . "blog_post_description pd ON (p.post_id = pd.post_id) WHERE pd.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY pd.name");

                $blog_data = $query->rows;

                $this->cache->set('blog.post.' . $this->config->get('config_language_id'), $blog_data);
            }

            return $blog_data;
        }
    }

    public function getPostsByCategoryId($category_id)
    {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog_post p LEFT JOIN " . DB_PREFIX . "blog_post_description pd ON (p.post_id = pd.post_id) LEFT JOIN " . DB_PREFIX . "blog_post_to_category p2c ON (p.post_id = p2c.post_id) WHERE pd.language_id = '" . (int) $this->config->get('config_language_id') . "' AND p2c.category_id = '" . (int) $category_id . "' ORDER BY pd.name ASC");

        return $query->rows;
    }

    public function getPostDescriptions($post_id)
    {
        $blog_post_description_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog_post_description WHERE post_id = '" . (int) $post_id . "'");

        foreach ($query->rows as $result) {
            $blog_post_description_data[$result['language_id']] = array(
                'name'             => $result['name'],
                'description'      => $result['description'],
                'meta_title'       => $result['meta_title'],
                'meta_keyword'     => $result['meta_keyword'],
                'meta_description' => $result['meta_description'],
                'tag'              => !empty($result['tag']) ? explode(',', $result['tag']) : $result['tag']
            );
        }

        return $blog_post_description_data;
    }

    public function getPostTags($post_id)
    {
        $post_tag_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog_post_description WHERE post_id = '" . (int) $post_id . "'");

        foreach ($query->rows as $result) {
            $post_tag_data[$result['language_id']] = !empty($result['tag']) ? explode(',', $result['tag']) : $result['tag'];
        }

        return $post_tag_data;
    }

    public function getPostCategories($post_id)
    {
        $post_category_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog_post_to_category WHERE post_id = '" . (int) $post_id . "'");

        foreach ($query->rows as $result) {
            $post_category_data[] = $result['category_id'];
        }

        return $post_category_data;
    }

    public function getPostStores($post_id)
    {
        $post_store_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog_post_to_store WHERE post_id = '" . (int) $post_id . "'");

        foreach ($query->rows as $result) {
            $post_store_data[] = $result['store_id'];
        }

        return $post_store_data;
    }

    public function getPostLayouts($post_id)
    {
        $post_layout_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog_post_to_layout WHERE post_id = '" . (int) $post_id . "'");

        foreach ($query->rows as $result) {
            $post_layout_data[$result['store_id']] = $result['layout_id'];
        }

        return $post_layout_data;
    }

    public function getCommentsByPostId($post_id, $start = 0, $limit = 40)
    {
        $query = $this->db->query("SELECT c.comment_id, c.name, c.email, c.comment, c.status, p.post_id, pd.name, p.image, c.date_added FROM " . DB_PREFIX . "blog_comment c LEFT JOIN " . DB_PREFIX . "blog p ON (c.post_id = p.post_id) LEFT JOIN " . DB_PREFIX . "blog_post_description pd ON (p.post_id = pd.post_id) WHERE p.post_id = '" . (int) $post_id . "' AND pd.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY c.date_added DESC LIMIT " . (int) $start . "," . (int) $limit);

        return $query->rows;
    }

    public function getTotalPosts()
    {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "blog_post");

        return $query->row['total'];
    }

    public function getTotalCommentsByPostId($post_id)
    {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "blog_comment c LEFT JOIN " . DB_PREFIX . "blog_post p ON (c.post_id = p.post_id) LEFT JOIN " . DB_PREFIX . "blog_post_description pd ON (p.post_id = pd.post_id) WHERE p.post_id = '" . (int) $post_id . "' AND pd.language_id = '" . (int) $this->config->get('config_language_id') . "'");

        return $query->row['total'];
    }

    public function getTags($tag_name = null, $filter_tags = null)
    {
        $tags = $tags_filter = array();

        $check_search = true;

        $filter = " AND not(tag = '')";

        if (!empty($filter_tags)) {
            foreach ($filter_tags as $filter_tag) {
                $filter .= " AND not (tag = '" . $this->db->escape($filter_tag['value']) . "')";
                $tags_filter[] = $filter_tag['value'];
            }
        }

        $query = $this->db->query("SELECT DISTINCT(tag) FROM `" . DB_PREFIX . "blog_post_description` WHERE `tag` LIKE '%" . $this->db->escape($tag_name) . "%'" . $filter);

        if ($query->num_rows) {
            foreach ($query->rows as $result) {
                $check = strpos($result['tag'], ',');

                $tag = $result['tag'];

                if ($check !== false) {
                    $tag = explode(',', $result['tag']);
                }

                if (is_array($tag)) {
                    foreach ($tag as $value) {
                        if (!empty($tag_name)) {
                            $check_search = strpos($value, $tag_name);
                        }

                        if (!in_array($value, $tags) && !in_array($value, $tags_filter) && $check_search !== false) {
                            $tags[] = $value;
                        }
                    }
                } else {
                    if (!empty($tag_name)) {
                        $check_search = strpos($tag, $tag_name);
                    }

                    if (!in_array($tag, $tags) && !in_array($tag, $tags_filter) && $check_search !== false) {
                        $tags[] = $tag;
                    }
                }
            }
        }

        return $tags;
    }
}
