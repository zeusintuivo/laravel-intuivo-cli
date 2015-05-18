<?php namespace PHIWeb\Models\Traits;


static class ModelDeletesTester {

	
		public static function modelDeletesTesterWorker(ConsoleOutput $output, Model $classInstance) {

			//test delete command to children
			foreach($d->children as $child) //scroll by children
			{

				$obj = $d["$child"];
				//test it got deleted
				$output->writeln("\n".'<info> -  - - - child ('.sizeof($obj).'):</info>'.$child);
	            
	            if (sizeof($obj)<=0) {
	                //
	                //
	            } elseif ( sizeof($obj)==1) {
	                //
	                //test it got deleted
					$output->writeln('<info> -  - - - has deleted_at </info>'.$obj->deleted_at);
	            	$this->assertEquals($date_deleted,$obj->deleted_at);  
	                //
	            } else {
	                //
	                // sizeof > 1
	                //
	                foreach ($obj as $objItem) { 
	                    
	                    //test it got deleted
						$output->writeln('<info> -  - - - has deleted_at </info>'.$objItem->deleted_at);
		            	$this->assertEquals($date_deleted,$objItem->deleted_at); 

	                } //end foreach 
	                
	            } //end if 



			} //end foreach 

		} ///end modelDeletesTesterWorker()

}