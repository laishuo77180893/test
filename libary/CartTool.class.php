<?php 
//购物车类
/*
	分析购买车
	1.你无论在本网站上刷新了多少次页面，或者新增了多少个商品，
	都要求你查看购物车时，看到的都是同一结果

	即你打开A页面或者B页面，首页，查看到的购物车是一样的
	或者说：整站范围内，购物车----全局有效
	解决：把购物车的信息放在数据库里，也可以放在session,cookie里面

	2.既然是全局有效，暗示，购物车的实例只有1个
	不能说在3个页面，买了3个商品，就形成了3个购物车实例，者显然不合理
	解决：单例模式

	技术选型：session+单例

	功能分析：
	判断某个商品是否存在
	添加商品
	删除商品
	修改商品数量

	某商品数量加1
	某商品数量减1

	查询购物车的商品种类
	查询购物车的商品数量
	查询购物车里的商品总金额
	返回购物车的所有商品

	清空购物车

*/

defined('ACC')||exit('ACC Denied');


class CartTool{
	private static $ins = null;
	private $items = array();

	final protected function __construct(){

	}

	final protected function __clone(){

	}
	
	//获取单例
	protected static function getIns(){
		if(!(self::$ins instanceof self)){
			self::$ins = new self();
		}
		return self::$ins;
	}
	//把购物车的单例对象放在session里
	public static function getCart(){
		if(!isset($_SESSION['cart'])||!($_SESSION['cart'] instanceof self)){//如果没有设置$_SESSION['cart']或者
			$_SESSION['cart'] = self::getIns();                             //$_SESSION['cart']不是自身类的实例
		}
		return $_SESSION['cart'];
	}
	//添加商品
	public function addItem($id,$name,$price,$num){
		//如果该商品已经存在，直接加数量
		if($this->hasItem($id)){
			$this->incNum($id, $num);
			return;
		}
		$item = array();
		$item['name'] = $name;
		$item['price'] = $price;
		$item['num'] = $num;

		$this->items[$id] = $item;
		return $items;
	}
	//清空购物车
	public function clear(){
		 return $this->items = array();
	}
	//判断某商品存在
	public function hasItem($id){
		return array_key_exists($id, $this->items);
	}
	/*
		修改购物车中的商品数量(在网站上的数量栏直接输入数量修改)
		param int $id 商品主键
		param int $num 某个商品修改后的数量，即直接把某商品的数量改为$num
	*/
	public function modNum($id,$num=1){
		if(!$this->hasItem($id)){
			return false;
		}

		return $this->items[$id]['num'] = $num; 
	}
	//商品数量增加1
	public function incNum($id,$num=1){
		if($this->hasItem($id)){
			return $this->items[$id]['num'] += $num; 
		}
	}
	//商品数量减少1
	public function delNum($id,$num=1){
		if($this->hasItem($id)){
			return $this->items[$id]['num'] -= $num;
		}

		if($this->items[$id]['num']<1){
			return $this->delItem($id);
		}

	}
	//删除商品方法
	public function delItem($id){
		unset($this->items[$id]);
	}
	//查询购物车中商品的种类
	public function getCnt(){
		return count($this->items);
	}
	//查询购物车商品的个数
	public function getNum(){
		if($this->getCnt()==0){
			return 0;
		}

		$sum = 0;

		foreach ($this->items as $item) {
			$sum += $item['num'];
		}

		return $sum;
	}
	//查询购物车中的总金额
	public function getPrice(){
		if($this->getCnt()==0){
			return 0;
		}

		$price = 0.0;

		foreach($this->items as $item){
			$price += $item['num'] * $item['price']; 
		} 
		return $price;
	}
	//返回购物车所有商品
	public function all(){
		return $this->items;
	}
}

$cart = CartTool::getCart();

if(!isset($_GET['test'])){
	$_GET['test'] = '';	
}

if($_GET['test']=='add'){
	$cart->addItem(2,'面包',2.4,3);
	echo 'ok';
}else if($_GET['test']=='clear'){
	$cart->clear();
}else if($_GET['test']=='show'){
	print_r($cart->all());
	echo '共',$cart->getPrice(),'钱<br/>';
	echo '共',$cart->getNum(),'个商品<br/>';
}else{
	print_r($cart);
}



























 ?>