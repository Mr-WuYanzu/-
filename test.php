<?php //
//  // function a($n,$b=1){
//  // 	static $a;
//  // 		if($n-$b==1||$n==1){
//  // 			if($b==1){
//  // 				$a=$n;
//  // 			}
//  // 			$a=$a*1;
//  // 		}else{
//  // 			if($b==1){
//  // 				$a=$n;
//  // 			}
//  // 			$a=$a*($n-$b);
//  // 			a($n,$b+1);
//  // 		}
//  // 		return $a;
//  // }
//  // echo a(6);
//  // function a($n){
//  // 	$a=$n;
//  // 	for($i=1;$i<=$n;$i++){
//  // 		if($n-$i==1||$n==1){
//  // 			$a=$a*1;
//  // 			return $a;
//  // 		}else{
//  // 			$a=$a*($n-$i);
//  // 		}
//  // 	}
//
//  // }
//   // echo a(4);
//
//   // 计算字符串长度
//   // function num($n){
//   // 		static $arr=['1'=>9];
//   // 		foreach ($arr as $k=>$v){
//   // 			if($n<=$v){
//   // 				echo $k;
//   // 			}else{
//   // 				$arr=[$k+1=>$v.'9'];
//   // 				// print_r($arr);
//   // 				num($n);
//   // 			}
//   // 		}
//   // }
//   // num(33);
//   // function a($n){
//   // 		if($n==1){
//   // 			return 1;
//   // 		}
//
//   // 		return $n*a($n-1);
//   // }
//   // echo a(10);
//   // function num($n){
//   // 		$num=0;
//   // 		while($n){
//   // 			// $n=$n%10;
//   // 			$num++;
//   // 			$n=floor($n/10);
//   // 		}
//   // 		return $num;
//   // }
//   // echo num(1000);
//   function a($n){
//   		$num=1;
//   		for($i=2;$i<=$n;$i++){
//   			$sum=0;
//   			for($j=0;$j<$i;$j++){
//   				$sum=$sum+$num;
//   			}
//   			$num=$sum;
//   		}
//   		echo $num;
//   }
//   a(10);
// ?>
