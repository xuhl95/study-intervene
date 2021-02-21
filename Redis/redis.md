## redis 篇

扩展阅读[《官方文档》](https://redis.io/documentation)

### redis详解

扩展阅读[《redis详解》](https://www.cnblogs.com/ysocean/category/1221478.html)

- [redis的简介与安装](https://www.cnblogs.com/ysocean/p/9074353.html)
- [redis的配置文件介绍](https://www.cnblogs.com/ysocean/p/9074787.html)
- [redis的五大数据类型详细用法](https://www.cnblogs.com/ysocean/p/9080940.html)
- [redis的底层数据结构](https://www.cnblogs.com/ysocean/p/9080942.html)
- [redis的五大数据类型实现原理](https://www.cnblogs.com/ysocean/p/9102811.html)
- [redis持久化](#redis持久化)
- [主从复制](https://www.cnblogs.com/ysocean/p/9143118.html)
- [缓存穿透，缓存击穿，缓存雪崩解决方案分析](#缓存穿透，缓存击穿，缓存雪崩解决方案分析)
- 如何实现分布式锁
- [常见问题解析](#常见问题解析)
- [redis和memcache和mongodb的区别](#redis和memcache和mongodb的区别)

### redis 70题解析
[redis 70题解析](./redis70题解析.pdf)

### redis和memcache的区别

|对比项|redis|memcache|
|---|---|---|
|数据结构|丰富数据类型|只支持简单 KV 数据类型|
|数据一致性|事务|cas|
|持久性|快照/AOF|不支持|
|网络IO|单线程 IO 复用|多线程、非阻塞 IO 复用|
|内存管理机制|现场申请内存|预分配内存|

### redis和mongodb的区别

1、内存管理机制

Redis 数据全部存在内存，定期写入磁盘，当内存不够时，可以选择指定的 LRU 算法删除数据。

MongoDB 数据存在内存，由 linux系统 mmap 实现，当内存不够时，只将热点数据放入内存，其他数据存在磁盘。

2、支持的数据结构

Redis 支持的数据结构丰富，包括hash、set、list等。

MongoDB 数据结构比较单一，但是支持丰富的数据表达，索引，最类似关系型数据库，支持的查询语言非常丰富。

3、数据量和性能：

当物理内存够用的时候，redis>mongodb>mysql

当物理内存不够用的时候，redis和mongodb都会使用虚拟内存。

实际上如果redis要开始虚拟内存，那很明显要么加内存条，要么你换个数据库了。

但是，mongodb不一样，只要，业务上能保证，冷热数据的读写比，使得热数据在物理内存中，mmap的交换较少。

mongodb还是能够保证性能。

4、性能

mongodb依赖内存，TPS较高；Redis依赖内存，TPS非常高。性能上Redis优于MongoDB。

5、可靠性

mongodb从1.8版本后，采用binlog方式（MySQL同样采用该方式）支持持久化，增加可靠性；

Redis依赖快照进行持久化；AOF增强可靠性；增强可靠性的同时，影响访问性能。

可靠性上MongoDB优于Redis。

6、数据分析

mongodb内置数据分析功能（mapreduce）；而Redis不支持。

7、事务支持情况

Redis 事务支持比较弱，只能保证事务中的每个操作连续执行；mongodb不支持事务。

8、集群

MongoDB 集群技术比较成熟，Redis从3.0开始支持集群。


扩展阅读[《redis和memcache区别，源码怎么说》](https://mp.weixin.qq.com/s?__biz=MjM5ODYxMDA5OQ==&mid=2651961272&idx=1&sn=79ad515b013b0ffc33324db86ba0f834&chksm=bd2d02648a5a8b728db094312f55574ec521b30e3de8aacf1d2d948a3ac24dbf30e835089fa7&scene=21#wechat_redirect)

扩展阅读 [《redis、memcache和mongodb的区别》](https://www.cnblogs.com/tuyile006/p/6382062.html)

### 缓存穿透，缓存击穿，缓存雪崩解决方案分析

扩展阅读[《缓存雪崩、缓存穿透、缓存预热、缓存更新、缓存降级等问题》](https://blog.csdn.net/xlgen157387/article/details/79530877)

扩展阅读[《谈关于缓存穿透+击穿+雪崩，热点数据失效问题的解决方案》](https://www.toutiao.com/i6811443473862885899/?tt_from=weixin&utm_campaign=client_share&wxshare_count=1&timestamp=1585981210&app=news_article&utm_source=weixin&utm_medium=toutiao_android&req_id=20200404142010010014040091362FB821&group_id=6811443473862885899)

二、常见问题

1、缓存穿透

 访问一个不存在的key，缓存不起作用，请求会穿透到DB，流量大时DB会挂掉。

解决方案

（1）采用布隆过滤器，使用一个足够大的bitmap，用于存储可能访问的key，不存在的key直接被过滤；

（2）拦截器，id<=0的直接拦截。

（3）从cache和db都取不到，可以将key-value写为key-null，设置较短过期时间，如30秒（设置太长会导致正常情况也没法使用）。这样可以防止攻击用户反复用同一个id暴力攻击。

2、缓存击穿

一个存在的key，在缓存过期的一刻，同时有大量的请求，这些请求都会击穿到DB，造成瞬时DB请求量大、压力骤增。

解决方案

（1）设置热点数据永远不过期。

（2）加互斥锁。

3、缓存雪崩


大量的key设置了相同的过期时间，导致在缓存在同一时刻全部失效，造成瞬时DB请求量大、压力骤增，引起雪崩。

解决方案

（1）缓存数据的过期时间设置随机，防止同一时间大量数据过期现象发生。

（2）如果缓存数据库是分布式部署，将热点数据均匀分布在不同搞得缓存数据库中。

（3）设置热点数据永远不过期。

### 发布订阅

扩展阅读[《redis发布订阅》](http://doc.redisfans.com/topic/pubsub.html)

### redis持久化

扩展阅读[《redis持久化》](https://segmentfault.com/a/1190000016021217)

扩展阅读[《redis持久化》](https://segmentfault.com/a/1190000015983518)

### redis 事务

扩展阅读[《redis事务》](http://doc.redisfans.com/topic/transaction.html)

```shell
redis> MULTI  #标记事务开始
OK
redis> INCR user_id  #多条命令按顺序入队
QUEUED
redis> INCR user_id
QUEUED
redis> INCR user_id
QUEUED
redis> PING
QUEUED
redis> EXEC  #执行
1) (integer) 1
2) (integer) 2
3) (integer) 3
4) PONG
```

> 在 redis 事务中如果有某一条命令执行失败，其后的命令仍然会被继续执行

> 使用 DISCARD 可以取消事务，放弃执行事务块内的所有命令

### 如何实现分布式锁

### redis 过期策略及内存淘汰机制

#### 过期策略

redis 的过期策略就是指当 redis 中缓存的 Key 过期了，redis 如何处理

- 定时过期：每个设置过期时间的 Key 创建定时器，到过期时间立即清除。内存友好，CPU 不友好

- 惰性过期：访问 Key 时判断是否过期，过期则清除。CPU 友好，内存不友好

- 定期过期：隔一定时间，expires 字典中扫描一定数量的 Key，清除其中已过期的 Key。内存和 CPU 资源达到最优的平衡效果

#### 内存淘汰机制

```shell
[root]# redis-cli config get maxmemory-policy
1) "maxmemory-policy"
2) "noeviction"
```

- noeviction：新写入操作会报错
- allkeys-lru：移除最近最少使用的 key
- allkeys-random：随机移除某些 key
- volatile-lru：在设置了过期时间的键中，移除最近最少使用的 key
- volatile-random：在设置了过期时间的键中，随机移除某些 key
- volatile-ttl：在设置了过期时间的键中，有更早过期时间的 key 优先移除

### 常见问题解析

#### 为什么redis是单线程的

1.官方答案

因为redis是基于内存的操作，CPU不是redis的瓶颈，redis的瓶颈最有可能是机器内存的大小或者网络带宽。既然单线程容易实现，而且CPU不会成为瓶颈，那就顺理成章地采用单线程的方案了。

2.性能指标

关于redis的性能，官方网站也有，普通笔记本轻松处理每秒几十万的请求。

3.详细原因

1）不需要各种锁的性能消耗

redis的数据结构并不全是简单的Key-Value，还有list，hash等复杂的结构，这些结构有可能会进行很细粒度的操作，比如在很长的列表后面添加一个元素，在hash当中添加或者删除

一个对象。这些操作可能就需要加非常多的锁，导致的结果是同步开销大大增加。

总之，在单线程的情况下，就不用去考虑各种锁的问题，不存在加锁释放锁操作，没有因为可能出现死锁而导致的性能消耗。

2）单线程多进程集群方案

单线程的威力实际上非常强大，每核心效率也非常高，多线程自然是可以比单线程有更高的性能上限，但是在今天的计算环境中，即使是单机多线程的上限也往往不能满足需要了，需要进一步摸索的是多服务器集群化的方案，这些方案中多线程的技术照样是用不上的。

所以单线程、多进程的集群不失为一个时髦的解决方案。

3）CPU消耗

采用单线程，避免了不必要的上下文切换和竞争条件，也不存在多进程或者多线程导致的切换而消耗 CPU。

但是如果CPU成为redis瓶颈，或者不想让服务器其他CUP核闲置，那怎么办？

可以考虑多起几个redis进程，redis是key-value数据库，不是关系数据库，数据之间没有约束。只要客户端分清哪些key放在哪个redis进程上就可以了

#### redis单线程的优劣势

##### 单进程单线程优势
- 代码更清晰，处理逻辑更简单

- 不用去考虑各种锁的问题，不存在加锁释放锁操作，没有因为可能出现死锁而导致的性能消耗

- 不存在多进程或者多线程导致的切换而消耗CPU
 
##### 单进程单线程弊端

- 无法发挥多核CPU性能，不过可以通过在单机开多个redis实例来完善

#### redis的高并发和快速原因
1、redis是基于内存的，内存的读写速度非常快；

2、redis是单线程的，省去了很多上下文切换线程的时间；

3、redis使用多路复用技术，可以处理并发的连接。非阻塞IO 内部实现采用epoll，采用了epoll+自己实现的简单的事件框架。epoll中的读、写、关闭、连接都转化成了事件，然后利用epoll的多路复用特性，绝不在io上浪费一点时间

扩展阅读 [《redis高并发快总结》](https://www.cnblogs.com/angelyan/p/10450885.html)

#### 如何利用CPU多核心

在单机单实例下，如果操作都是 O(N)、O(log(N)) 复杂度，对 CPU 消耗不会太高。为了最大利用 CPU，单机可以部署多个实例

#### redis为什么这么快

1、完全基于内存，绝大部分请求是纯粹的内存操作，非常快速。数据存在内存中，类似于HashMap，HashMap的优势就是查找和操作的时间复杂度都是O(1)；

2、数据结构简单，对数据操作也简单，redis中的数据结构是专门进行设计的；

3、采用单线程，避免了不必要的上下文切换和竞争条件，也不存在多进程或者多线程导致的切换而消耗 CPU，不用去考虑各种锁的问题，不存在加锁释放锁操作，没有因为可能出现死锁而导致的性能消耗；

4、使用多路I/O复用模型，非阻塞IO；

5、使用底层模型不同，它们之间底层实现方式以及与客户端之间通信的应用协议不一样，redis直接自己构建了VM 机制 ，因为一般的系统调用系统函数的话，会浪费一定的时间去移动和请求

#### redis如何保证原子性的

redis是单线程的，保证了操作的原子性

对于Redis而言，命令的原子性指的是：一个操作的不可以再分，操作要么执行，要么不执行

#### redis单核CPU占用过高

##### 出现cpu过高的原因有：

1、连接数过多，通过redis-cli info | grep connected_clients查看

2、慢查询，因为redis是单线程，如果有慢查询的话，会阻塞住之后的操作，通过redis日志查 

3、value值过大？比如value几十兆，当然这种情况比较少，其实也可以看做是慢查询的一种

4、aof重写/rdb fork发生？瞬间会堵一下redis服务器

##### 对应解决方案：
1、连接数过多解决：

1.1 关闭僵尸连接
采用redi-cli登录,采用client kill ip:port(redis远程连接的ip和端口)。 
需要采用脚本批量删除多个连接

1.2 修改redis timeout参数
采用redis-cli登录，采用config set timeout xx 设置redis的keepalive时间

1.3 修改redis进程的文件数限制
echo -n "Max open files  3000:3000" >  /proc/PID/limits

1.4 修改系统参数的最大文件数限制
/etc/security/limits.conf

2、对慢查询进行持久化，比如定时存放到mysql之类。（redis的慢查询只是一个list，超过list设置的最大值，会清除掉之前的数据，也就是看不到历史）

3、限制key的长度和value的大小

#### 使用redis的注意事项:
1、Master最好不要做任何持久化工作，包括内存快照和AOF日志文件，特别是不要启用内存快照做持久化。

2、如果数据比较关键，某个Slave开启AOF备份数据，策略为每秒同步一次。

3、为了主从复制的速度和连接的稳定性，Slave和Master最好在同一个局域网内。

4、尽量避免在压力较大的主库上增加从库

5、为了Master的稳定性，主从复制不要用图状结构，用单向链表结构更稳定，即主从关系为：Master<--Slave1<--Slave2<--Slave3.......， 这样的结构也方便解决单点故障问题，实现Slave对Master的替换，也即，如果Master挂了，可以立马启用Slave1做Master，其他不变

6、使用redis负载监控工具：redis-monitor，它是一个Web可视化的 redis 监控程序

### Redis 性能优化的 13 条军规

原文地址 [《Redis 性能优化的 13 条军规》](https://www.toutiao.com/i6807956017473651207/?timestamp=1585222728&app=news_article&group_id=6807956017473651207&req_id=2020032619384801001404116020142494)

### redis 常见面试题

参考 [《Redis常见面试题》](https://www.cnblogs.com/lanqiu5ge/p/9442837.html#_label3)