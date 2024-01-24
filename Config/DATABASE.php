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
return [
    'DATABASE' => [
        'MYSQL' => [
            [
                'name'              => 'default', // 数据库连接池名称
                'useMysqli'         => false, // 是否是使用php-mysqli扩展
                'host'              => '127.0.0.1', // 数据库地址
                'port'              => 3306, // 数据库端口
                'user'              => 'easyswoole', // 数据库用户名
                'password'          => 'easyswoole', // 数据库用户密码
                'timeout'           => 45, // 数据库连接超时时间
                'charset'           => 'utf8', // 数据库字符编码
                'database'          => 'easyswoole_demo', // 数据库名
                'autoPing'          => 5, // 自动 ping 客户端链接的间隔
                'strict_type'       => false, // 不开启严格模式
                'fetch_mode'        => false,
                'returnCollection'  => false, // 设置返回结果为 数组
                // 配置 数据库 连接池配置，配置详细说明请看连接池组件 https://www.easyswoole.com/Components/Pool/introduction.html
                'intervalCheckTime' => 15 * 1000, // 设置 连接池定时器执行频率
                'maxIdleTime'       => 10, // 设置 连接池对象最大闲置时间 (秒)
                'maxObjectNum'      => 20, // 设置 连接池最大数量
                'minObjectNum'      => 5, // 设置 连接池最小数量
                'getObjectTimeout'  => 3.0, // 设置 获取连接池的超时时间
                'loadAverageTime'   => 0.001, // 设置负载阈值
            ]
        ]
    ]
];
