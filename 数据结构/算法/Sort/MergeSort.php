<?php
/**
 * 归并排序
 *
 * 利用递归，先拆分、后合并、再排序。
 *
 * 步骤:
 *
 * 均分数列为两个子数列
 * 递归重复上一步骤，直到子数列只有一个元素
 * 父数列合并两个子数列并排序，递归返回数列
 */
$arr = [1, 43, 54, 62, 21, 66, 32, 78, 36, 76, 39, 2];

// 归并排序主程序
function mergeSort($arr) {
    $len = count($arr);

    // 递归结束条件, 到达这步的时候, 数组就只剩下一个元素了, 也就是分离了数组
    if ($len <= 1) {
        return $arr;
    }

    $mid   = intval($len / 2);             // 取数组中间
    $left  = array_slice($arr, 0, $mid); // 拆分数组0-mid这部分给左边left
    $right = array_slice($arr, $mid);          // 拆分数组mid-末尾这部分给右边right
    $left  = mergeSort($left);                 // 左边拆分完后开始递归合并往上走
    $right = mergeSort($right);                // 右边拆分完毕开始递归往上走
    $arr   = merge($left, $right);             // 合并两个数组,继续递归

    return $arr;
}

// merge函数将指定的两个有序数组(arrA, arr)合并并且排序
function merge($arrA, $arrB) {
    $arrC = [];
    while (count($arrA) && count($arrB)) {
        // 这里不断的判断哪个值小, 就将小的值给到arrC, 但是到最后肯定要剩下几个值,
        // 不是剩下arrA里面的就是剩下arrB里面的而且这几个有序的值, 肯定比arrC里面所有的值都大所以使用
        //从小到大 < || 从大到小 >
        $arrC[] = $arrA[0] < $arrB[0] ? array_shift($arrA) : array_shift($arrB);
    }

    return array_merge($arrC, $arrA, $arrB);
}

$arr = mergeSort($arr);
print_r($arr);
