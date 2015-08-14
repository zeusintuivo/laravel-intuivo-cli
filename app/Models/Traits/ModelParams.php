<?php namespace PHIWeb\Models\Traits;

# use  PHIWeb\Models\Traits\ModelParams;
use DB;
use PDO;

use Illuminate\Database\Eloquent\Collection;

trait ModelParams {


    /**
     * Sample Use
     * use PHIWeb\Models\Param\Param; 
     * Param::byType();
     * Param::byType('report_dental_name_types');
     * Param::byType('type', 'type');
     * Param::byType('procedures');
     * Param::byType('procedures', 'type');     
	 * 
     * use PHIWeb\Models\Param\Location; 
     * Location::byType();
     * Location::byType('countries');
     * Location::byType('cities');
     * Location::byType('states');
     * 
     *  -- get params by called type
     *
     *    -- one liner --
     *
     *  select a.id, a.display_name, b.display_name as type from params a inner join params b on a.param_type_id=b.id where b.display_name='categories' && a.active='1' order by a.param_type_id, a.display_order;
     *   
     *   -- broken down -- 
     *  
     *  select a.id, a.display_name, b.display_name as type 
     *  from params a 
     *  inner join params b 
     *  on a.param_type_id=b.id 
     *  where b.display_name='categories' && a.active='1' 
     *  order by a.param_type_id, a.display_order;
     *  
     * Produced SQL by Laravel 
     *  $typeName='categories'; DB::table(DB::RAW('params a'))->select(['a.id', 'a.display_name', 'b.display_name as type'])->join(DB::RAW('params b'),'a.param_type_id', '=', 'b.id')->where('b.display_name', [$typeName])->where('a.active', ['1'])->orderBy('a.param_type_id')->orderBy('a.display_order')->toSQL();
     *
     * "select `a`.`id`, `a`.`display_name`, `b`.`display_name` as `type` from params a inner join params b on `a`.`param_type_id` = `b`.`id` where `b`.`display_name` = ? and `a`.`active` = ? order by `a`.`param_type_id` asc, `a`.`display_order` asc"
     *
     * @author Jesus Alcaraz <jesus@gammapartners.com>
     * @version 1
     */
    public static function byType($typeName='type', $display_name_name = 'type'){
 
        //tester oneliner
        //$typeName='categories'; DB::table(DB::RAW('params a'))->select(['a.id', 'a.display_name', 'b.display_name as type'])->join(DB::RAW('params b'),'a.param_type_id', '=', 'b.id')->where('b.display_name', [$typeName])->where('a.active', ['1'])->orderBy('a.param_type_id')->orderBy('a.display_order')->toSQL();
    	$thistable = static::getClassCallerName();

        return  DB::table(DB::RAW($thistable . ' a'))
                ->select(['a.id', 'a.display_name',  'a.longer_name', 'a.numeric_value', 'b.display_name as '.$display_name_name])
                ->join(DB::RAW($thistable . ' b'),'a.param_type_id', '=', 'b.id')
                ->where('b.display_name', [$typeName])
                ->where('a.active', ['1'])
                ->orderBy('a.param_type_id')
                ->orderBy('a.display_order')
                ->get();
       
    } 

    /**
     * Returns caller class base tables name according to Laravel rules, as in Laravel's Model->getTable()  
     * Sample Use
     * ./tinker
     * use PHIWeb\Models\Param\Param; 
     * Param::getClassCallerName();
     *
     * @author Jesus Alcaraz <jesus@gammapartners.com>
     * @version 1
     */
    public static function getClassCallerName() {

    	#echo "\nLINE__ . ". __LINE__ . " " . "\nFILE__ . ". __FILE__ . " " . "\nFUNCTION ". __FUNCTION__ . " " . "\nCLASS__  ". __CLASS__ . " " . "\nMETHOD__ ". __METHOD__;
    	#result:"\nLINE__ . 67 \nFILE__ . /home/jesusalc/Sites/ph20/app/Models/Traits/ModelParams.php \nFUNCTION getClass \nCLASS__  PHIWeb\\Models\\Param\\Param \nMETHOD__ PHIWeb\\Models\\Traits\\ModelParams::getClass"
 
 		#vendor/laravel/framework/src/Illuminate/Support/helpers.php
 		#Get the class "basename" of the given object / class.
		#class_basename
 		$g_arr =  explode("\\",__CLASS__) ;
 		$g = array_pop($g_arr);
 		// return $g;	
 		return snake_case( str_plural( $g ) );
    } 


    /**
     * Sample Use
     * use PHIWeb\Models\Param\Param; 
     * Param::arrayByType();
     * Param::arrayByType('type', 'type');
     * Param::arrayByType('procedures');
     * Param::arrayByType('procedures', 'type');
     *
     * @author Jesus Alcaraz <jesus@gammapartners.com>
     * @version 1
     */
    public static function arrayByType($typeName='type', $display_name_name = 'type') { 

        DB::setFetchMode(PDO::FETCH_ASSOC); //set results to array

        $r = static::byType($typeName, $display_name_name);
        
        $res = [];
        $keykey = 0;
        $valuevalue = "";
        foreach($r as $L) {
            foreach($L as $k => $v) {
                if ($k=='id') $keykey = $v;
                if ($k=='display_name') $valuevalue = $v;
               
            }
             $res[$keykey] = $valuevalue;
        }
        DB::setFetchMode(PDO::FETCH_CLASS); //set results to array off , objects on
        return $res;
    }     
    /**
     * Sample Use
     * use PHIWeb\Models\Param\Param; 
     * Param::byTypeLongerName();
     * Param::byTypeLongerName('type', 'type');
     * Param::byTypeLongerName('procedure_id');
     * Param::byTypeLongerName('procedure_id', 'type');
     *
     * Produced SQL by Laravel 
     *
     * $longerName='category_id'; DB::table(DB::RAW('params a'))->select(['a.id', 'a.display_name', 'b.display_name as type'])->join(DB::RAW('params b'),'a.param_type_id', '=', 'b.id')->where('b.longer_name', [$longerName])->where('a.active', ['1'])->orderBy('a.param_type_id')->orderBy('a.display_order')->toSQL();
     * select `a`.`id`, `a`.`display_name`, `b`.`display_name` as `type` from params a inner join params b on `a`.`param_type_id` = `b`.`id` where `b`.`longer_name` = ? and `a`.`active` = ? order by `a`.`param_type_id` asc, `a`.`display_order` asc
     *
     * @author Jesus Alcaraz <jesus@gammapartners.com>
     * @version 1
     */
    public static function byTypeLongerName($longerName='type', $display_name_name = 'type', $longer_name_or_display_name = 'display_name'){
 
        //tester oneliner
    	$thistable = static::getClassCallerName();

        return  DB::table(DB::RAW($thistable . ' a'))
                ->select(['a.id', 'a.'.$longer_name_or_display_name, 'b.display_name as '.$display_name_name])
                ->join(DB::RAW($thistable . ' b'),'a.param_type_id', '=', 'b.id')
                ->where('b.longer_name', [$longerName])
                ->where('a.active', ['1'])
                ->orderBy('a.param_type_id')
                ->orderBy('a.display_order')
                ->get();
       
    }  

    /**
     * Sample use: 
     * use PHIWeb\Models\Param\Param; 
     * Param::arrayByTypeLongerName();
     * Param::arrayByTypeLongerName('type', 'type');
     * Param::arrayByTypeLongerName('procedure_id');
     * Param::arrayByTypeLongerName('procedure_id', 'type');
     *
     * @author Jesus Alcaraz <jesus@gammapartners.com>
     * @version 1
     */
    public static function arrayByTypeLongerName($longerName='type', $display_name_name = 'type', $longer_name_or_display_name = 'display_name') { 
        DB::setFetchMode(PDO::FETCH_ASSOC); //set results to array

        $r = static::byTypeLongerName($longerName, $display_name_name, $longer_name_or_display_name);
        
        $res = [];
        $keykey = 0;
        $valuevalue = "";
        foreach($r as $L) {
            foreach($L as $k => $v) {
                if ($k=='id') $keykey = $v;
                if ($k==$longer_name_or_display_name) $valuevalue = $v;
               
            }
             $res[$keykey] = $valuevalue;
        }
        DB::setFetchMode(PDO::FETCH_CLASS); //set results to array off , objects on
        return $res;
    }  

    /**
     * Sample use: 
     * use PHIWeb\Models\Param\Param; 
     * Param::byEntity();
     * Param::byEntity('entity');
     * Param::byEntity('MedLog');
     * Param::byEntity('procedure_id', 'type');
     *     
     * use PHIWeb\Models\Param\Location; 
     * Location::byEntity();
     * Location::byEntity('entity');
     *
     * -- get params by called entity
     *
     *   -- one liner --
     *
     * select a.id, a.display_name, b.display_name as entity from params a inner join params b on a.parent_param_id=b.id where b.display_name='MedLog' && a.active='1' order by a.param_type_id, a.display_order;
     *   -- broken down -- 
     *
     * select a.id, a.display_name, b.display_name as entity 
     * from params a 
     * inner join params b 
     * on a.parent_param_id=b.id 
     * where b.display_name='MedLog' && a.active='1' 
     * order by a.param_type_id, a.display_order;
     *      
     * Produced SQL by Laravel 
     * $entityName='MedLog'; DB::table(DB::RAW('params a'))->select(['a.id', 'a.display_name', 'b.display_name as entity'])->join(DB::RAW('params b'),'a.parent_param_id', '=', 'b.id')->where('b.display_name', [$entityName])->where('a.active', ['1'])->orderBy('a.param_type_id')->orderBy('a.display_order')->get();
     * $entityName='MedLog'; DB::table(DB::RAW('params a'))->select(['a.id', 'a.display_name', 'b.display_name as entity'])->join(DB::RAW('params b'),'a.parent_param_id', '=', 'b.id')->where('b.display_name', [$entityName])->where('a.active', ['1'])->orderBy('a.param_type_id')->orderBy('a.display_order')->toSQL(); 
     * "select `a`.`id`, `a`.`display_name`, `b`.`display_name` as `type` from params a inner join params b on `a`.`param_type_id` = `b`.`id` where `b`.`display_name` = ? and `a`.`active` = ? order by `a`.`param_type_id` asc, `a`.`display_order` asc"
     * 
     * @author Jesus Alcaraz <jesus@gammapartners.com>
     * @version 1
     */
    public static function byEntity($entityName='entity'){

    	$thistable = static::getClassCallerName();
        //tester oneliner
        //$entityName='MedLog'; DB::table(DB::RAW('params a'))->select(['a.id', 'a.display_name', 'b.display_name as entity'])->join(DB::RAW('params b'),'a.parent_param_id', '=', 'b.id')->where('b.display_name', [$entityName])->where('a.active', ['1'])->orderBy('a.param_type_id')->orderBy('a.display_order')->get();
        return  DB::table(DB::RAW($thistable . ' a'))
                ->select(['a.id', 'a.display_name', 'b.display_name as entity'])
                ->join(DB::RAW($thistable . ' b'),'a.parent_param_id', '=', 'b.id')
                ->where('b.display_name', [$entityName])
                ->where('a.active', ['1'])
                ->orderBy('a.param_type_id')
                ->orderBy('a.display_order')
                ->get();
    }  

   /**
     * Sample use: 
     * use PHIWeb\Models\Param\Param; 
     * Param::typesByEntity();
     * Param::typesByEntity('Dental');
     * 
     * @author Jesus Alcaraz <jesus@gammapartners.com>
     * @version 1
     */
    public static function typesByEntity($entityName='entity'){
        DB::setFetchMode(PDO::FETCH_ASSOC); //set results to array

        $res = ((  DB::select("call paramsTypesByEntity('$entityName');") ));
        // $res; = (new Collection(  DB::select("call paramsTypesByEntity('$entityName');") ));
        DB::setFetchMode(PDO::FETCH_CLASS); //set results to array off , objects on
        return $res;
    }

    /**
     * Sample use:
     * use PHIWeb\Models\Param\Param; 
     *  Param::allTypes(); 
     *     
     * use PHIWeb\Models\Param\Location; 
     * Location::allTypes();
     *
     * -- get all type params
     *
     *   -- one liner --
     *
     * select a.id, a.display_name, b.display_name as type from params a inner join params b on a.param_type_id=b.id where b.display_name='type' && a.active='1' order by a.param_type_id, a.display_order;
     *
     *   -- broken down --
     *
     * select a.id, a.display_name, b.display_name as type 
     * from params a 
     * inner join params b 
     * on a.param_type_id=b.id 
     * where b.display_name='type' && a.active='1' 
     * order by a.param_type_id, a.display_order;
     * 
     * Produced SQL by Laravel 
     * DB::table(DB::RAW('params a'))->select(['a.id', 'a.display_name', 'b.display_name as type'])->join(DB::RAW('params b'),'a.param_type_id', '=', 'b.id')->where('b.display_name', ['type'])->where('a.active', ['1'])->orderBy('a.param_type_id')->orderBy('a.display_order')->toSQL();
     * DB::table(DB::RAW('params a'))->select(['a.id', 'a.display_name', 'b.display_name as type'])->join(DB::RAW('params b'),'a.param_type_id', '=', 'b.id')->where('b.display_name', ['type'])->where('a.active', ['1'])->orderBy('a.param_type_id')->orderBy('a.display_order')->get();
     *   
     * "select `a`.`id`, `a`.`display_name`, `b`.`display_name` as `type` from params a inner join params b on `a`.`param_type_id` = `b`.`id` where `b`.`display_name` = ? and `a`.`active` = ? order by `a`.`param_type_id` asc, `a`.`display_order` asc"
     * 
     * @author Jesus Alcaraz <jesus@gammapartners.com>
     * @version 1
     */
    public static function allTypes(){

        return static::byType('type', 'type');
    }    

    /**
     * Sample use:
     * use PHIWeb\Models\Param\Param; 
     *  Param::allEntities();
     *     
     * use PHIWeb\Models\Param\Location; 
     * Location::allEntities();
     *
     *   SQL: 
     *
     *   -- get all entity params --
     *   
     *   -- one liner --
     *   select a.id, a.display_name, b.display_name as entity from params a inner join params b on a.param_type_id=b.id where b.display_name='entity' && a.active='1' order by a.param_type_id, a.display_order;
     *
     *   -- broken down sql parts --
     *
     *   select a.id, a.display_name, b.display_name as entity 
     *   from params a 
     *   inner join params b 
     *   on a.param_type_id=b.id 
     *   where b.display_name='entity' && a.active='1' 
     *   order by a.param_type_id, a.display_order;
     *   
     *   Produced SQL by Laravel 
     *   DB::table(DB::RAW('params a'))->select(['a.id', 'a.display_name', 'b.display_name as entity'])->join(DB::RAW('params b'),'a.param_type_id', '=', 'b.id')->where('b.display_name', ['entity'])->where('a.active', ['1'])->orderBy('a.param_type_id')->orderBy('a.display_order')->toSQL();
     *   DB::table(DB::RAW('params a'))->select(['a.id', 'a.display_name', 'b.display_name as entity'])->join(DB::RAW('params b'),'a.param_type_id', '=', 'b.id')->where('b.display_name', ['entity'])->where('a.active', ['1'])->orderBy('a.param_type_id')->orderBy('a.display_order')->get();
     *   
     *   "select `a`.`id`, `a`.`display_name`, `b`.`display_name` from params a inner join params b on `a`.`param_type_id` = `b`.`id` where `b`.`display_name` = ? and `a`.`active` = ? order by `a`.`param_type_id` asc, `a`.`display_order` asc"  
     *
     * @author Jesus Alcaraz <jesus@gammapartners.com>
     * @version 1
     */
    public static function allEntities() {

        return static::byType('entity', 'entity');
    
    }

} //end ModelParams trait
