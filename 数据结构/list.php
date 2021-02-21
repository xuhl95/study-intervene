<?php

/**
 * 节点类
 * Class Node
 */
class Node {
    public $val;
    public $next;

    public function __construct($val = null, $next = null) {
        $this->val  = $val;
        $this->next = $next;
    }

}

/**
 * 链表
 * Class Linklist
 *
 * @package app\models
 */
class Linklist {
    public $head;           //头节点(默认一个虚拟头节点)
    public $size;           //长度

    public function __construct() {
        $this->head = new Node();
        $this->size = 0;
    }

    /**
     * 头插法
     *
     * @param $value
     *
     * @throws Exception
     */
    public function addFirst($value) {
        $this->add(0, $value);
    }

    /**
     * 指定索引位置插入
     *
     * @param $index
     * @param $value
     *
     * @throws Exception
     */
    public function add($index, $value) {

        if ($index > $this->size) {
            throw new Exception('超过链表范围');
        }
        $prev = $this->head;
        for ($i = 0; $i < $index; $i++) {
            $prev = $prev->next;
        }

        $prev->next = new Node($value, $prev->next);

        $this->size++;
    }

    /**
     * 尾插法
     *
     * @param $value
     *
     * @throws Exception
     */
    public function addLast($value) {

        $this->add($this->size, $value);
    }

    /**
     * 编辑
     *
     * @param $index
     * @param $value
     *
     * @throws Exception
     */
    public function edit($index, $value) {
        if ($index > $this->size - 1)
            throw new Exception('超过链表范围');

        $prev = $this->head->next;
        for ($i = 0; $i <= $index; $i++) {
            if ($i == $index)
                $prev->val = $value;
            $prev = $prev->next;
        }
    }

    /**
     * 查询
     *
     * @param $index
     *
     * @return null
     * @throws Exception
     */
    public function select($index) {
        if ($index > $this->size - 1)
            throw new Exception('超过链表范围');

        $prev = $this->head->next;
        for ($i = 0; $i <= $index; $i++) {
            if ($i == $index)
                return $prev;
            $prev = $prev->next;
        }

        return null;
    }

    /**
     * 删除
     *
     * @param $index
     *
     * @throws Exception
     */
    public function delete($index) {
        if ($index > $this->size - 1)
            throw new Exception('超过链表范围');

        $prev = $this->head;
        for ($i = 0; $i <= $index; $i++) {
            if ($i == $index)
                $prev->next = $prev->next->next;
            $prev = $prev->next;
        }
        $this->size--;
    }

    /**
     * 检索值是否存在
     *
     * @param $value
     *
     * @return bool
     */
    public function iscontain($value) {
        $prev = $this->head->next;
        while ($prev) {

            if ($prev->val == $value) {
                return true;
            }
            $prev = $prev->next;
        }

        return false;
    }

    /**
     * 转换为字符串
     *
     * @return string
     */
    public function tostring() {

        $prev = $this->head->next;

        $r = [];
        while ($prev) {
            $r[]  = $prev->val;
            $prev = $prev->next;
        }
        return implode('->', $r);
    }

    /**
     * 删除指定的节点值
     *
     * @param $value
     */
    public function removeFileds($value) {
        $prev = $this->head;
        while ($prev->next) {
            if ($prev->val == $value) {
                $prev->val  = $prev->next->val;
                $prev->next = $prev->next->next;
            } else {
                $prev = $prev->next;
            }
        }
    }

    /**
     * 通过递归方式删除指定的节点值
     *
     * @param $value
     */
    public function removeFiledsByRecursion($value) {
        $this->head = $this->removeByRecursion($this->head, $value);
        return $this->head;
    }

    public function removeByRecursion($node, $value, $level = 0) {
        if ($node->next == null) {
            $this->showDeeep($level, $node->val);
            return $node->val == $value ? $node->next : $node;
        }

        $this->showDeeep($level, $node->val);
        $node->next = $this->removeByRecursion($node->next, $value, ++$level);
        $this->showDeeep($level, $node->val);
        return $node->val == $value ? $node->next : $node;
    }

    /**
     * 显示深度
     * 帮助理解递归执行过程，回调函数执行层序遵循系统栈
     *
     * @param int $level 深度层级
     * @param     $val
     *
     * @return bool
     */
    public function showDeeep($level = 1, $val) {
        if ($level < 1) {
            return false;
        }

        while ($level--) {
            echo '-';
        }
        echo "$val\n";
    }

}

$node = new Linklist();
$node->addFirst(1);

//$node->add(1, 7);
//$node->add(2, 10);
//$node->edit(1, 8);
/*var_dump($node->select(1)) ;
$node->delete(1);
$node->addLast(99);*/
//var_dump($node->iscontain(2));
var_export($node);
var_export($node->tostring());