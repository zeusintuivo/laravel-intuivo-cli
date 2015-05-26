<?php namespace PHIWeb\Tests\Traits;

#use PHIWeb\Tests\Traits\ModelDeletesTester;
use DB;
use PDO;

trait ModelDeletesTester {

	
	/**
	 * Performs the test on a softdeleted object with SQL queries
	 *
	 * @author Jesus Alcaraz <jesus@gammapartners.com>
	 * @revision 7
	 */
	public function modelDeletesTesterWorker( $test, \Symfony\Component\Console\Output\ConsoleOutput $output,  $d) {


		
	           	 DB::setFetchMode(PDO::FETCH_ASSOC); //set results to array
		$index = DB::table(DB::RAW('`'.$d->getTable().'` where `deleted_at` is null  '))->orderBy(DB::RAW(' rand()'), '')->take(1)->get()[0]['id']; 
		//$index = DB::table(DB::RAW('`med_logs` where `deleted_at` is not null  '))->orderBy(DB::RAW(' rand()'), '')->take(1)->get()[0]['id']; 
	             DB::setFetchMode(PDO::FETCH_CLASS); //set results to array off , objects on
		
 		
 		// dump($index);
 		// dump(intval($index));
 		//
 		// Find a valid id before finding with Laravel
 		// because it gets stuck with ::find(:id) 
 		// sample query 
 		//            select * from med_logs where deleted_at is not null order by rand() limit 1;
 		//
 		// 

    	// $d = MedLog::find(($index));
    	$d = $d->find(($index));
	
		// dump($d);        
        //
        // Actual testing here
        //
		
		$className = explode("\\",get_class($d));
		$output->writeln("\n\n".'<info>SofDelete Testing: </info> -( '. array_pop($className) . ' )- ' .$d->getTable());
		$output->writeln('<info>Got record # </info>'. $d->id);
		
		//send delete command
		$d->delete();
		$date_deleted=$d->deleted_at;

		$savedId = $d->id; 

		//Load from DB again.
		// $d = MedLog::withTrashed()->find($savedId);
		$d = $d->withTrashed()->find($savedId);


		//test it got deleted
		$test->assertEquals($date_deleted, $d->deleted_at); 
		// $output->writeln('<info>Test date: </info>'. $date_deleted);
		// $output->writeln('<info> -  vs - </info>');


        //notes this works on tinker 
        // $m->comment()->withTrashed()->get()->first()
		// $m->withTrashed()->get()->first()['comment']

		// $abj = $d->withTrashed()->get()->first(); //this moves the record to the first record. 
		// $abj = $d->withTrashed()->get(); //this get me all the records from MedLog

		// $r= DB::table('med_logs')->where('id',1048)->select('deleted_at')->get(['deleted_at']);

		//test delete command to children
		$id  = $d->id;
		// dump($d->childrenTables);
		// dd($d->childrenTables);
		$parent_field_name=$d->getForeignKey();
		
		$output->writeln(" \ $parent_field_name:$id ");
		           	                        DB::setFetchMode(PDO::FETCH_ASSOC); //set results to array
		foreach($d->childrenTables as $child) //scroll by children
		{
		$output->writeln("    + - - - $child");

        	$obj = $d["$child"];

           	//test it got deleted

            // $child_deleted_at_array=DB::table($child)->where('id',$id)->select('deleted_at')->get(['deleted_at']); 
        	// DB::table('med_log_comments')->where('med_log_id', 1194)->get()
            $child_deleted_at_array=DB::table($child)->where($parent_field_name, $id)->get();   

            $count_results = sizeof($child_deleted_at_array);
            // dump( $count_results);

            // $child_deleted_at=$child_deleted_at_array[0]['deleted_at'];
            foreach($child_deleted_at_array as $k => $v) {
            	
            	$child_deleted_at=$v['deleted_at'];
				$output->writeln("\n".'<info> -  - - - child ('.($k+1).'):</info>'.$child);
				$output->writeln(".");
	            
	            // dump($child_deleted_at_array[0]['deleted_at']);
	            //test it got deleted
				$output->writeln('<info> -  - - - has deleted_at </info>'.($child_deleted_at == null ? "null" : $child_deleted_at));
	        	$test->assertEquals($date_deleted,$child_deleted_at); 

            }

		} //end foreach 
	                                DB::setFetchMode(PDO::FETCH_CLASS); //set results to array off , objects on
	} ///end modelDeletesTesterWorker()

}