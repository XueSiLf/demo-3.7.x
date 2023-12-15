<?php
/**
 * This file is part of EasySwoole.
 *
 * @link    https://www.easyswoole.com
 * @document https://www.easyswoole.com
 * @contact https://www.easyswoole.com/Preface/contact.html
 * @license https://github.com/easy-swoole/easyswoole/blob/3.x/LICENSE
 */

namespace EasySwoole\EasySwoole;

use EasySwoole\Component\Di;
use EasySwoole\FastDb\FastDb;
use EasySwoole\EasySwoole\AbstractInterface\Event;
use EasySwoole\EasySwoole\Swoole\EventRegister;
use EasySwoole\FileWatcher\FileWatcher;
use EasySwoole\FileWatcher\WatchRule;
use EasySwoole\Http\Message\Status;

class EasySwooleEvent implements Event
{
    public static function initialize()
    {
        date_default_timezone_set('Asia/Shanghai');

        // 加载配置文件
        self::loadExtraConfig();

        ###### 注册数据库连接池【使用FastDb组件】 ######
        self::initFastDbORM();
    }

    public static function mainServerCreate(EventRegister $register)
    {
        // 初始化热重载
        self::initHotReload();
    }

    public static function loadExtraConfig()
    {
        Config::getInstance()->loadDir(EASYSWOOLE_ROOT . '/Config');
    }

    public static function initFastDbORM()
    {
        $mysqlConfigs = config('DATABASE.MYSQL');
        foreach ($mysqlConfigs as $mysqlConfig) {
            $configObj = new \EasySwoole\FastDb\Config($mysqlConfig);
            FastDb::getInstance()->addDb($configObj);
        }
    }

    public static function initHotReload()
    {
        $watcher = new FileWatcher();
        $rule = new WatchRule(EASYSWOOLE_ROOT . "/App");
        $watcher->addRule($rule);
        $watcher->setOnChange(function () {
            Logger::getInstance()->info('file change ,reload!!!');
            ServerManager::getInstance()->getSwooleServer()->reload();
        });
        $watcher->attachServer(ServerManager::getInstance()->getSwooleServer());
    }
}
