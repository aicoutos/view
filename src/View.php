<?php
/**
* Basic
* Micro framework em PHP
*/
namespace Basic;

/**
* Classe View
*/
class View
{
    public function firstWord(string $word)
    {
        return strtok($word, " ");
    }
    public function i18n(string $key, bool $print=true)
    {
        $language='pt';
        $filename=ROOT.'app/view/i18n.php';
        if (file_exists($filename)) {
            $i18n=require $filename;
            if (isset($i18n[$key][$language])) {
                $output=$i18n[$key][$language];
            } elseif (isset($i18n[$key][DEFAULT_LANGUAGE])) {
                $output=$i18n[$key][DEFAULT_LANGUAGE];
            } else {
                $output=$key;
            }
        } else {
            $output=$key;
        }
        if ($print) {
            print $output;
        } else {
            return $output;
        }
    }
    public function isAjax()
    {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            return true;
        } else {
            return false;
        }
    }
    public function isDev():bool
    {
        $end=@end(explode('.', $_SERVER['SERVER_NAME']));
        if ($end==='dev') {
            return true;
        } else {
            return false;
        }
    }
    public function json($data)
    {
        header("Content-type:application/json");
        print json_encode($data, JSON_PRETTY_PRINT);
    }
    public function method()
    {
        return @$_SERVER['REQUEST_METHOD'];
    }
    public function redirect($url)
    {
        header('Location: '.$url);
    }
    public function segment($key = null)
    {
        $uri = @explode('?', $_SERVER ['REQUEST_URI'])[0];
        $uri = @explode('/', $uri);
        $uri = @array_values(array_filter($uri));
        if (is_null($key)) {
            if (count($uri)==0) {
                $uri[0]='/';
            }
            return $uri;
        } else {
            if (isset($uri [$key])) {
                return $uri [$key];
            } else {
                return false;
            }
        }
    }
    public function slug(string $text, $set=true)
    {
        if ($set) {
            return str_replace(' ', '_', $text);
        } else {
            return str_replace('_', ' ', $text);
        }
    }
    public function view($name, $data=null, $print=true)
    {
        if ($name=='404') {
            header('HTTP/1.0 404 Not Found');
        }
        $filename=ROOT.'app/view/'.$name.'.php';
        $data['data']=$data;
        $data['viewName']=$name;
        if (file_exists($filename)) {
            extract($data);
            ob_start();
            require_once($filename);
            $output = ob_get_contents();
            ob_end_clean();
            if ($print) {
                print $output;
            } else {
                return $output;
            }
        } else {
            die('view <b>'.$filename.'</b> not found');
        }
    }
}
