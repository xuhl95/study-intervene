## php进阶

### 目录
- [php7程序执行过程](#php7程序执行过程)
- [Nginx与FPM的工作机制](#Nginx与FPM的工作机制)
- [php进程间通信的通信的几种方式](#php进程间通信的通信的几种方式)
- [php-fpm三种运行方式](#php-fpm三种运行方式)
- [php-fpm配置文件详解](#php-fpm配置文件详解)
- [php-fpm.conf 配置详解](#php-fpm.conf配置详解)
- [php新特性](#php新特性)
- [php底层运行机制与原理](#底层运行机制与原理)
- [为什么PHP7比PHP5性能提升了](#为什么PHP7比PHP5性能提升了)
- [php安全最大化](#php安全最大化)
- [php性能最大化](#php性能最大化)
- [php页面静态化](#php页面静态化)
- [如何解决PHP内存溢出问题]()
- [PSR 介绍，PSR-1, 2, 4, 7]()
- [HashTable]()
- [Xhprof 、Xdebug 性能调试工具使用]()
- [ZVAL]


#### php7程序执行过程

1.PHP代码经过词法分析转换为有意义的Token；

2.Token经过语法分析生成AST（Abstract Synstract Syntax Tree，抽象语法树）；

3.AST生成对应的opcode，被虚拟机执行。

![php7执行过程](./assets/php7-执行过程)

#### Nginx与FPM的工作机制
[Nginx与FPM的工作机制](https://www.jianshu.com/p/da152c6fdfa6)


#### php新特性
[《新特性》](https://www.php.net/manual/zh/migration70.new-features.php)

1、主要分为两种模式，强制性模式和严格模式
```
    declare(strict_types=1)
```

表示严格类型校验模式，作用于函数调用和返回语句；0表示弱类型校验模式。

2、NULL合并运算符
```
$site = isset($_GET['site']) ? $_GET['site'] : 'wo';
#简写成
$site = $_GET['site'] ??'wo';
```

3、组合预算符
```
// 整型比较
print( 1 <=> 1);print(PHP_EOL);
print( 1 <=> 2);print(PHP_EOL);
print( 2 <=> 1);print(PHP_EOL);
print(PHP_EOL); // PHP_EOL 为换行符
//结果：
0
-1
1
```

4、常量数组
```
// 使用 define 函数来定义数组
define('sites', [
   'Google',
   'Jser',
   'Taobao'
]);

print(sites[1]);
```

5、匿名类
```
interface Logger { 
   public function log(string $msg); 
} 

class Application { 
   private $logger; 

   public function getLogger(): Logger { 
      return $this->logger; 
   } 

   public function setLogger(Logger $logger) { 
      $this->logger = $logger; 
   }   
} 

$app = new Application; 
// 使用 new class 创建匿名类 
$app->setLogger(new class implements Logger { 
   public function log(string $msg) { 
      print($msg); 
   } 
}); 

$app->getLogger()->log("我的第一条日志"); 
```

6、Closure::call()方法增加，意思向类绑定个匿名函数
```
<?php 
class A { 
    private $x = 1; 
} 

// PHP 7 之前版本定义闭包函数代码 
$getXCB = function() { 
    return $this->x; 
}; 

// 闭包函数绑定到类 A 上 
$getX = $getXCB->bindTo(new A, 'A');  

echo $getX(); 
print(PHP_EOL); 

// PHP 7+ 代码 
$getX = function() { 
    return $this->x; 
}; 
echo $getX->call(new A); 
?>
```

7、CSPRNG（伪随机数产生器）
PHP 7 通过引入几个 CSPRNG 函数提供一种简单的机制来生成密码学上强壮的随机数。

random_bytes() - 加密生存被保护的伪随机字符串。

random_int() - 加密生存被保护的伪随机整数。

8、异常
PHP 7 异常用于向下兼容及增强旧的assert()函数。

9、use 语句改变
可以导入同一个namespace下的类简写
```
use some\namespace\{ClassA, ClassB, ClassC as C};
```

10、Session 选项
```
11.session_start()可以定义数组
<?php
session_start(&#91;
   'cache_limiter' => 'private',
   'read_and_close' => true,
]);
?>
2.引入了一个新的php.ini设置（session.lazy_write）,默认情况下设置为 true，意味着session数据只在发生变化时才写入。
```


#### php进程间通信的通信的几种方式

- 管道
- FIFO
- 消息队列
- 信号量
- 共享内存 

[《php进程间通信的通信的几种方式》](https://www.cnblogs.com/zgq0/p/8780893.html)

#### php-fpm 三种运行方式

[《php-fpm 三种运行方式》](https://www.cnblogs.com/xuey/p/9573080.html)

#### php-fpm配置文件详解

[《php-fpm配置文件详解》](https://www.cnblogs.com/jonsea/p/5522018.html)

#### 为什么PHP7比PHP5性能提升了

1、变量存储字节减小，减少内存占用，提升变量操作速度

2、改善数组结构，数组元素和hash映射表被分配在同一块内存里，降低了内存占用、提升了 cpu 缓存命中率

3、改进了函数的调用机制，通过优化参数传递的环节，减少了一些指令，提高执行效率

#### php底层运行机制与原理

参考文章 [《php底层运行机制与原理》](https://blog.csdn.net/lili0710432/article/details/47816365?depth_1-utm_source=distribute.pc_relevant.none-task-blog-BlogCommendFromBaidu-4&utm_source=distribute.pc_relevant.none-task-blog-BlogCommendFromBaidu-4)

#### php安全最大化

1、避免sql注入漏洞
~~~~
防范方法
1 使用预编译语句绑定变量(最佳方式)
2 使用安全的存储过程(也可能存在注入问题)
3 检查输入数据的数据类型(可对抗注入)
4 数据库最小权限原则
~~~~

2、防止跨网站脚本攻击(Cross Site Scripting, XSS)

~~~~
防范方法：
1、使用htmlspecialchars函数将特殊字符转换成HTML编码，过滤输出的变量
2、为cookie设置HttpOnly,防止cookie劫持
~~~~

3、防止跨网站请求伪造攻击(Cross Site Request Forgeries, CSRF)

~~~~
防范方法
1 增加验证码(简单有效)
2 检查请求来源是否合法（验证HTTPReferer字段）
3 增加随机 token
~~~~

参考文章 [《PHP安全之Web攻击》](https://www.cnblogs.com/luyucheng/p/6234524.html)


#### php性能最大化

参考文章 [《让PHP7达到最高性能的几个Tips》](https://www.laruence.com/2015/12/04/3086.html)

#### php页面静态化

ob输出缓冲系列函数 [Output Control 函数](#https://www.php.net/manual/zh/ref.outcontrol.php)

主要利用php输出缓冲，涉及到函数
~~~~
ob_start - 打开输出缓冲

ob_get_contents - 返回输出缓冲区的内容

ob_clean — 清空（擦掉）输出缓冲区

清空（擦除）缓冲区并关闭输出缓冲

ob_end_clean — 清空（擦除）缓冲区并关闭输出缓冲
~~~~
参考文章 [实现页面静态化，PHP是如何实现的](https://blog.csdn.net/assasin0308/article/details/90674751)