<?php
/**
 * Cobub Razor
 *
 * An open source analytics for mobile applications
 *
 * @package		Cobub Razor
 * @author		WBTECH Dev Team
 * @copyright	Copyright (c) 2011 - 2012, NanJing Western Bridge Co.,Ltd.
 * @license		http://www.cobub.com/products/cobub-razor/license
 * @link		http://www.cobub.com/products/cobub-razor/
 * @since		Version 1.0
 * @filesource
 */
class operatormodel extends CI_Model {
	function __construct() {
		parent::__construct ();
	}
	
	//根据时间段获取运营商活跃用户比例
	function getActiveUsersPercentByOperator($fromTime, $toTime, $productId, $count = REPORT_TOP_TEN) {
	
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql="select   s.devicesupplier_name,
				 count(distinct f.deviceidentifier)
					/ (select count(distinct ff.deviceidentifier,ss.devicesupplier_name) percent
						 from  ".$dwdb->dbprefix('fact_clientdata')."   ff,
							 ".$dwdb->dbprefix('dim_date')." dd,
							 ".$dwdb->dbprefix('dim_product')."  pp,
							 ".$dwdb->dbprefix('dim_devicesupplier')."  ss
						 where  ff.date_sk = dd.date_sk
										and ff.product_sk = pp.product_sk
										and ff.devicesupplier_sk = ss.devicesupplier_sk
										and dd.datevalue between '$fromTime' and '$toTime'
										and pp.product_id = $productId and pp.product_active=1 and pp.channel_active=1 and pp.version_active=1) percentage
from   ".$dwdb->dbprefix('fact_clientdata')."  f,
	 ".$dwdb->dbprefix('dim_date')."  d,
	 ".$dwdb->dbprefix('dim_product')."   p,
	 ".$dwdb->dbprefix('dim_devicesupplier')."   s
where    f.date_sk = d.date_sk
				 and f.product_sk = p.product_sk
				 and f.devicesupplier_sk = s.devicesupplier_sk
				 and d.datevalue between '$fromTime' and '$toTime'
				 and p.product_id = $productId and p.product_active=1 and p.channel_active=1 and p.version_active=1
group by s.devicesupplier_name
order by percentage desc limit 0,$count;
		";
		
		$query = $dwdb->query ( $sql );
		return $query;
	}
	
	//根据时间段获取运营商新增用户比例
	function getNewUsersPercentByOperator($fromTime, $toTime, $productId, $count = REPORT_TOP_TEN) {
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql="select   s.devicesupplier_name,
				 count(distinct f.deviceidentifier)
					/ (select count(distinct ff.deviceidentifier,ss.devicesupplier_name) percent
						 from  ".$dwdb->dbprefix('fact_clientdata')."    ff,
							   ".$dwdb->dbprefix('dim_date')."  			 dd,
								 ".$dwdb->dbprefix('dim_product')."  		 pp,
								 ".$dwdb->dbprefix('dim_devicesupplier')."  		 ss
						 where  ff.date_sk = dd.date_sk
										and ff.product_sk = pp.product_sk
										and ff.devicesupplier_sk = ss.devicesupplier_sk
										and dd.datevalue between '$fromTime' and '$toTime'
										and pp.product_id = $productId and pp.product_active=1 and pp.channel_active=1 and pp.version_active=1 and ff.isnew=1) percentage
from   ".$dwdb->dbprefix('fact_clientdata')."    f,
	 ".$dwdb->dbprefix('dim_date')."    d,
	 ".$dwdb->dbprefix('dim_product')."    p,
	 ".$dwdb->dbprefix('dim_devicesupplier')."    s
where    f.date_sk = d.date_sk
				 and f.product_sk = p.product_sk
				 and f.devicesupplier_sk = s.devicesupplier_sk
				 and d.datevalue between '$fromTime' and '$toTime'
				 and p.product_id = $productId and p.product_active=1 and p.channel_active=1 and p.version_active=1 and f.isnew=1
group by s.devicesupplier_name
order by percentage desc limit 0,$count;
		";
		$query = $dwdb->query ( $sql );
		return $query;
	}
	
	//获取时间段内累计用户各运营商所占比例
	function getTotalUsersPercentByOperator($fromTime, $toTime, $productId) {
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql="select   s.devicesupplier_name,
				 count(distinct f.deviceidentifier)
					/ (select count(distinct ff.deviceidentifier,ss.devicesupplier_name) percent
						 from  ".$dwdb->dbprefix('fact_clientdata')."   ff,
							 ".$dwdb->dbprefix('dim_product')." 			 pp,
							 ".$dwdb->dbprefix('dim_devicesupplier')." 			 ss
						 where  ff.product_sk = pp.product_sk
										and ff.devicesupplier_sk = ss.devicesupplier_sk
										and pp.product_id = $productId and pp.product_active=1 and pp.channel_active=1 and pp.version_active=1) percentage
from   ".$dwdb->dbprefix('fact_clientdata')."     f,
		 ".$dwdb->dbprefix('dim_product')." 		  p,
		 ".$dwdb->dbprefix('dim_devicesupplier')." 		  s
where    f.product_sk = p.product_sk
				 and f.devicesupplier_sk = s.devicesupplier_sk
				 and p.product_id = $productId and p.product_active=1 and p.channel_active=1 and p.version_active=1
group by s.devicesupplier_name
order by percentage desc;
		";
		$query = $dwdb->query ( $sql );	  
		return $query;
	}
	
	//根据时间段获取运营商活跃用户比例
	function getActiveUsersPercentByOperatorJSON($fromTime, $toTime, $productId, $count = REPORT_TOP_TEN) {
	
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql="select   s.devicesupplier_name,
		count(distinct f.deviceidentifier)
		/ (select count(distinct ff.deviceidentifier,ss.devicesupplier_name) percent
		from  ".$dwdb->dbprefix('fact_clientdata')."   ff,
		".$dwdb->dbprefix('dim_date')." dd,
		".$dwdb->dbprefix('dim_product')."  pp,
		".$dwdb->dbprefix('dim_devicesupplier')."  ss
		where  ff.date_sk = dd.date_sk
		and ff.product_sk = pp.product_sk
		and ff.devicesupplier_sk = ss.devicesupplier_sk
		and dd.datevalue between '$fromTime' and '$toTime'
		and pp.product_id = $productId and pp.product_active=1 and pp.channel_active=1 and pp.version_active=1) percentage
		from   ".$dwdb->dbprefix('fact_clientdata')."  f,
		".$dwdb->dbprefix('dim_date')."  d,
		".$dwdb->dbprefix('dim_product')."   p,
		".$dwdb->dbprefix('dim_devicesupplier')."   s
		where    f.date_sk = d.date_sk
		and f.product_sk = p.product_sk
		and f.devicesupplier_sk = s.devicesupplier_sk
		and d.datevalue between '$fromTime' and '$toTime'
		and p.product_id = $productId and p.product_active=1 and p.channel_active=1 and p.version_active=1
		group by s.devicesupplier_name
		order by percentage desc limit 0,$count;
		";
	
		$query = $dwdb->query ( $sql );
		$ret=array();
		if ($query != null && $query->num_rows > 0) {
		
			$arr = $query->result_array ();
		
			$content_arr = array ();
			for($i = 0; $i < count ( $arr ); $i ++) {
				$row = $arr [$i];
		
				$tmp = array ();
				$tmp['devicesupplier_name']=$row['devicesupplier_name'];
				$tmp['percentage']=$row['percentage'];
				
		
				array_push ( $content_arr , $tmp );
		
			}
			$all_version_name = array_keys($content_arr);
			return $content_arr;
		
		}
		
		
		
		return $query;
	}
	
	//根据时间段获取运营商新增用户比例
	function getNewUsersPercentByOperatorJSON($fromTime, $toTime, $productId, $count = REPORT_TOP_TEN) {
	$dwdb = $this->load->database ( 'dw', TRUE );
	$sql="select   s.devicesupplier_name,
	count(distinct f.deviceidentifier)
	/ (select count(distinct ff.deviceidentifier,ss.devicesupplier_name) percent
	from  ".$dwdb->dbprefix('fact_clientdata')."    ff,
	".$dwdb->dbprefix('dim_date')."  			 dd,
	".$dwdb->dbprefix('dim_product')."  		 pp,
	".$dwdb->dbprefix('dim_devicesupplier')."  		 ss
	where  ff.date_sk = dd.date_sk
	and ff.product_sk = pp.product_sk
	and ff.devicesupplier_sk = ss.devicesupplier_sk
	and dd.datevalue between '$fromTime' and '$toTime'
	and pp.product_id = $productId and pp.product_active=1 and pp.channel_active=1 and pp.version_active=1 and ff.isnew=1) percentage
	from   ".$dwdb->dbprefix('fact_clientdata')."    f,
	".$dwdb->dbprefix('dim_date')."    d,
	".$dwdb->dbprefix('dim_product')."    p,
	".$dwdb->dbprefix('dim_devicesupplier')."    s
	where    f.date_sk = d.date_sk
	and f.product_sk = p.product_sk
	and f.devicesupplier_sk = s.devicesupplier_sk
	and d.datevalue between '$fromTime' and '$toTime'
	and p.product_id = $productId and p.product_active=1 and p.channel_active=1 and p.version_active=1 and f.isnew=1
	group by s.devicesupplier_name
	order by percentage desc limit 0,$count;
	";
	$query = $dwdb->query ( $sql );
	$ret=array();
	if ($query != null && $query->num_rows > 0) {
	
		$arr = $query->result_array ();
	
		$content_arr = array ();
		for($i = 0; $i < count ( $arr ); $i ++) {
			$row = $arr [$i];
	
			$tmp = array ();
			$tmp['devicesupplier_name']=$row['devicesupplier_name'];
			$tmp['percentage']=$row['percentage'];
	
	
			array_push ( $content_arr , $tmp );
	
		}
		$all_version_name = array_keys($content_arr);
		return $content_arr;
	
	}
	
	
	return $query;
	}
	
	//获取时间段内累计用户各运营商所占比例
	function getTotalUsersPercentByOperatorJSON($fromTime, $toTime, $productId) {
	$dwdb = $this->load->database ( 'dw', TRUE );
	$sql="select   s.devicesupplier_name,
	count(distinct f.deviceidentifier)
	/ (select count(distinct ff.deviceidentifier,ss.devicesupplier_name) percent
	from  ".$dwdb->dbprefix('fact_clientdata')."   ff,
	".$dwdb->dbprefix('dim_product')." 			 pp,
	".$dwdb->dbprefix('dim_devicesupplier')." 			 ss
	where  ff.product_sk = pp.product_sk
	and ff.devicesupplier_sk = ss.devicesupplier_sk
	and pp.product_id = $productId and pp.product_active=1 and pp.channel_active=1 and pp.version_active=1) percentage
	from   ".$dwdb->dbprefix('fact_clientdata')."     f,
	".$dwdb->dbprefix('dim_product')." 		  p,
	".$dwdb->dbprefix('dim_devicesupplier')." 		  s
			where    f.product_sk = p.product_sk
			and f.devicesupplier_sk = s.devicesupplier_sk
			and p.product_id = $productId and p.product_active=1 and p.channel_active=1 and p.version_active=1
			group by s.devicesupplier_name
			order by percentage desc;
			";
			$query = $dwdb->query ( $sql );
			$ret=array();
			if ($query != null && $query->num_rows > 0) {
			
				$arr = $query->result_array ();
			
				$content_arr = array ();
				for($i = 0; $i < count ( $arr ); $i ++) {
					$row = $arr [$i];
			
					$tmp = array ();
					$tmp['devicesupplier_name']=$row['devicesupplier_name'];
					$tmp['percentage']=$row['percentage'];
			
			
					array_push ( $content_arr , $tmp );
			
				}
				$all_version_name = array_keys($content_arr);
				return $content_arr;
			
			}
			return $query;
	}
	
	
	
}

?>