<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

use ICanBoogie\Inflector;

class EventAppApi extends Event
{

    public function postAppEcommerce()
    {
        // api/categories or api/categories/1
        $path = $this->getPath();

        if (empty($path) || ($path[0] != 'api') || (count($path) < 2)) {
            return;
        }

        // Don't break old API calls @BC
        if (Inflector::get()->singularize($path[1]) == $path[1]) {
            return;
        }

        // Basic authentication using API credentials
        if (!$this->authenticate()) {
            return;
        }

        // Get request method
        $method = $this->getMethod();

        // Get arguments
        $args = $this->getArguments($path, $method);

        // api/orders
        $route = $this->getRoute($path, $method);

        // Action
        $this->load->controller($route, $args);

        // Echo
        $this->response->output();

        die();
    }

    private function getPath()
    {
        // http://localhost/arastta/index.php/api/categories

        $parts = array();

        $query_string = $this->uri->getQuery();

        $path = str_replace($this->url->getFullUrl(), '', rawurldecode($this->uri->toString()));
        $path = str_replace('?'.$query_string, '', $path);

        if (empty($path)) {
            return $parts;
        }

        // May not use htaccess
        $path = str_replace('index.php', '', $path);
        $path = ltrim($path, '/');

        $parts = explode('/', $path);

        return $parts;
    }

    private function authenticate()
    {
        $username = $password = '';

        if (!empty($this->request->server['PHP_AUTH_USER']) && !empty($this->request->server['PHP_AUTH_PW'])) {
            // mod_php servers
            $username = $this->request->server['PHP_AUTH_USER'];
            $password = $this->request->server['PHP_AUTH_PW'];
        } elseif (!empty($this->request->server['HTTP_AUTHORIZATION']) && (strpos(strtolower($this->request->server['HTTP_AUTHORIZATION']), 'basic') === 0)) {
            // most other servers
            list($username, $password) = explode(':', base64_decode(substr($this->request->server['HTTP_AUTHORIZATION'], 6)));
        }

        if (empty($username) || empty($password)) {
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode(array('error' => 'Empty username or password')));
            $this->response->output();

            die();
        }

        // Set username/password
        $this->request->post['username'] = $username;
        $this->request->post['password'] = $password;

        // Authorize
        $this->load->controller('api/login');

        if (!isset($this->session->data['api_id'])) {
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode(array('error' => 'Login failed')));
            $this->response->output();

            die();
        }

        // Reset output
        $this->response->setOutput('');

        unset($this->request->post['username']);
        unset($this->request->post['password']);

        return true;
    }

    private function getMethod()
    {
        $method = isset($this->request->server['REQUEST_METHOD']) ? $this->request->server['REQUEST_METHOD'] : 'GET';

        return strtolower($method);
    }

    private function getArguments($path, $method)
    {
        $args = array();

        switch ($method) {
            case 'get':
                $args = $this->uri->getQuery(true);

                // Resource ID
                if (!empty($path[2]) && is_numeric($path[2])) {
                    $args['id'] = $path[2];
                }

                break;
            case 'post':
                $args = $this->request->post;
                break;
            case 'put':
                parse_str(file_get_contents('php://input'), $args);

                // Resource ID
                $args['id'] = $path[2];

                $args = $this->request->clean($args);

                break;
            case 'delete':
                // Resource ID
                $args['id'] = $path[2];

                break;
        }

        return $args;
    }

    private function getRoute($path, $method)
    {
        $folder = $path[0];
        $file = $path[1];
        $function = $this->getFunction($path, $method);

        $route = $folder . '/' . $file . '/' . $function;

        return $route;
    }

    private function getFunction($path, $method)
    {
        $methods = array('get' => 'get', 'post' => 'add', 'put' => 'edit', 'delete' => 'delete');
        
        $all_singular = false;

        if (!empty($path[3])) {
            // link: api/orders/1/products
            // function: getProducts, addProduct
            $name = $path[3];
        } elseif (!empty($path[2])) {
            if (is_numeric($path[2])) {
                // link: api/orders/1
                // function: getOrder, editOrder, deleteOrder
                $name = $path[1];
                $all_singular = true;
            } else {
                // link: api/orders/totals
                // function: getTotals, addTotal
                $name = $path[2];
            }
        } else {
            // link: api/orders
            // function: getOrders, addOrder
            $name = $path[1];
        }

        if (!$all_singular && ($method == 'get')) {
            $function = $methods[$method] . ucfirst($name);
        } else {
            $singular = Inflector::get()->singularize($name);

            $function = $methods[$method] . ucfirst($singular);
        }

        return $function;
    }
}
