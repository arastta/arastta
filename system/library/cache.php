<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class Cache {

    protected $adapter;

    public function __construct($storage, $lifetime = 86400, $config = null)
    {
        $class = 'Joomla\Cache\\' . ucfirst($storage);

        if (class_exists($class)) {
            $this->adapter = new $class($this->getAdapterOptions($storage, $lifetime, $config));
        }
        else {
            exit('Error: Could not load cache storage: ' . $storage);
        }
    }

    public function get($key)
    {
        $item = $this->adapter->get($key);

        return $item->getValue();
    }

    public function getMultiple($keys)
    {
        return $this->adapter->getMultiple($keys);
    }

    public function getOption($key)
    {
        return $this->adapter->getOption($key);
    }

    public function set($key, $value, $ttl = null)
    {
        return $this->adapter->set($key, $value, $ttl = null);
    }

    public function setMultiple($keys, $ttl = null)
    {
        return $this->adapter->setMultiple($keys, $ttl = null);
    }

    public function setOption($key)
    {
        return $this->adapter->setOption($key);
    }

    public function remove($key)
    {
        if ($key != 'language') {
            $languages = $this->get('language');

            if (!empty($languages)) {
                foreach ($languages as $language) {
                    $this->adapter->remove($key . '.' . $language['language_id']);
                }
            }
        }
        return $this->adapter->remove($key);
    }

    public function removeMultiple($keys)
    {
        return $this->adapter->removeMultiple($keys);
    }

    // Clear the whole cache
    public function clear()
    {
        return $this->adapter->clear();
    }

    // Check if key exists
    public function exists($key)
    {
        return $this->adapter->exists($key);
    }

    // Depreciated, use remove instead (B/C)
    public function delete($key)
    {
        return $this->remove($key);
    }

    public function getAdapterOptions($storage, $lifetime, $config)
    {
        $options = array(
            'file.path' => DIR_CACHE,
            'ttl' => $lifetime,
        );

        if (!is_object($config)) {
            return $options;
        }

        switch ($storage) {
            case 'redis':
                if (!$config->get('config_cache_redis_server')) {
                    break;
                }

                // 127.0.0.1:80
                $server_parts = explode(':', $config->get('config_cache_redis_server'));

                if (!empty($server_parts[0]) && !empty($server_parts[1])) {
                    $options['redis.host'] = $server_parts[0];
                    $options['redis.port'] = $server_parts[1];
                }

                break;
            case 'memcached':
                if (!$config->get('config_cache_memcache_servers')) {
                    break;
                }

                $options['memcache.servers'] = array();

                foreach (explode(",", $config->get('config_cache_memcache_servers')) as $server) {
                    $server = trim($server);

                    if (!$server) {
                        continue;
                    }

                    // 127.0.0.1:80
                    $server_parts = explode(':', $server);

                    if (empty($server_parts[0]) || empty($server_parts[1])) {
                        continue;
                    }

                    // This is how our current cache lib (Joomla) wants it
                    $s = new stdClass();
                    $s->host = $server_parts[0];
                    $s->port = $server_parts[1];

                    $options['memcache.servers'][] = $s;
                }

                break;
        }

        return $options;
    }
}
