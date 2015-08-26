<?php

/**
 * Pico Plug-in which adds the must-have social meta tags.
 *
 * @author David Beermann
 * @link https://github.com/davidbeermann/pico-plugins
 * @license http://opensource.org/licenses/MIT
 * @version 0.1.0
 */
class pico_socialize
{
    private $file_meta = array(
        'twitter_author' => 'Twitter_Author'
    );
    private $meta_data;
    private $skip_output;

    public function __construct()
    {
        $this->meta_data['twitter']['card'] = 'summary';
        $this->skip_output                  = false;
    }

    public function config_loaded(&$settings)
    {
        if ($this->key_exists($settings, 'site_title')) {
            $this->meta_data['og']['site_name'] = $settings['site_title'];
        }

        if ($this->key_exists($settings, 'socialize')) {
            $socialize = $settings['socialize'];

            if ($this->key_exists($socialize, 'twitter')) {
                $this->meta_data['twitter']['site']    = $this->sanitize_twitter_id($socialize['twitter']);
                $this->meta_data['twitter']['creator'] = $this->sanitize_twitter_id($socialize['twitter']);
            }
        }
    }

    public function request_url(&$url)
    {
        if ($url == '') {
            $this->meta_data['og']['type'] = 'website';
        } else {
            $this->meta_data['og']['type'] = 'article';
        }
    }

    public function after_404_load_content(&$file, &$content)
    {
        $this->skip_output = true;
    }

    public function before_read_file_meta(&$headers)
    {
        foreach ($this->file_meta as $key => $value) {
            $headers[$key] = $value;
        }
    }

    public function file_meta(&$meta)
    {
        if ($this->key_exists($meta, 'title')) {
            $this->meta_data['og']['title']      = $meta['title'];
            $this->meta_data['twitter']['title'] = $meta['title'];
        }
        if ($this->key_exists($meta, 'description')) {
            $this->meta_data['og']['description']      = $meta['description'];
            $this->meta_data['twitter']['description'] = $meta['description'];
        }
        if ($this->key_exists($meta, 'twitter_author')) {
            $this->meta_data['twitter']['creator'] = $this->sanitize_twitter_id($meta['twitter_author']);
        }
    }

    public function after_render(&$output)
    {
        if (!$this->skip_output) {
            $meta_output = '';

            $twitter = $this->meta_data['twitter'];
            foreach ($twitter as $key => $value) {
                $meta_output .= '<meta name="twitter:' . $key . '" content="' . $twitter[$key] . '">';
            }

            $og = $this->meta_data['og'];
            foreach ($og as $key => $value) {
                $meta_output .= '<meta property="og:' . $key . '" content="' . $og[$key] . '">';
            }

            $output = str_replace('</head>', PHP_EOL . $meta_output . '</head>', $output);
        }
    }

    private function key_exists(&$arr, $key)
    {
        return isset($arr[$key]) && $arr[$key] != '';
    }

    private function sanitize_twitter_id($id)
    {
        if (substr($id, 0, 1) != '@') {
            $id = '@' . $id;
        }
        return $id;
    }
}