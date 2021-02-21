<?php
/**
 * 快速排序
 *
 * 步骤为：
 *
 * 从数列中挑出一个元素，称为"基准"（pivot），
 * 重新排序数列，所有比基准值小的元素摆放在基准前面，所有比基准值大的元素摆在基准后面（相同的数可* 以到任何一边）。在这个分区结束之后，该基准就处于数列的中间位置。这个称为分区（partition）操作。
 * 递归地（recursively）把小于基准值元素的子数列和大于基准值元素的子数列排序。
 * 递归到最底部时，数列的大小是零或一，也就是已经排序好了。这个算法一定会结束，因为在每次的迭代（iteration）中，它至少会把一个元素摆到它最后的位置去
 */

class QuickSort {

    public function qsort(array $arr) {
        $len = count($arr);
        if ($len < 2) {
            return $arr;
        }

        $left   = $right = [];
        $middle = $arr[0];

        for ($i = 1; $i < $len; $i++) {
            // 小于基准值的放左边
            if ($arr[$i] < $middle) {
                $left[] = $arr[$i];
            } else {
                $right[] = $arr[$i];
            }
        }
        $left  = $this->qsort($left);
        $right = $this->qsort($right);

        return array_merge($left, [$middle], $right);
    }
}

$arr = [33, 24, 8, 21, 2, 23, 3, 32, 16];
$obj = new QuickSort();
print_r($obj->qsort($arr));