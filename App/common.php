<?php
/**
 * This file is part of EasySwoole.
 *
 * @link    https://www.easyswoole.com
 * @document https://www.easyswoole.com
 * @contact https://www.easyswoole.com/Preface/contact.html
 * @license https://github.com/easy-swoole/easyswoole/blob/3.x/LICENSE
 */
declare(strict_types=1);

if (!function_exists('config')) {
    /**
     * 获取配置
     *
     * @param string $keyPath
     *
     * @return array|mixed|null
     */
    function config(string $keyPath = '')
    {
        return EasySwoole\EasySwoole\Config::getInstance()->getConf($keyPath);
    }
}
