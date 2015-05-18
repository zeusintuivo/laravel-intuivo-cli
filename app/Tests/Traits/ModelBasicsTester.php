<?php namespace PHIWeb\Tests\Traits;

#use PHIWeb\Tests\Traits\ModelBasicsTester;


trait ModelBasicsTester {
	
	/**
	 * Performs existance and Method checking testing to the passed model and string 
	 *
	 * @author Jesus Alcaraz <jesus@gammapartners.com>
	 * @revision 7
	 */
	public function modelBasicsTester($test, $entityClass, $class_name, \Symfony\Component\Console\Output\ConsoleOutput $output ) {

			echo "\n".get_class($entityClass);
			$output->writeln('<info>- Use Imports - </info>');
			$method_array = array();
			#UseDeletes
			// $method_array[] = "Illuminate\Database\Eloquent\Model";
			// $method_array[] = "PHIWeb\Models\BaseModel";
			// $method_array[] = "PHIWeb\Models\Traits\ModelDeletes";
			// $method_array[] = "PHIWeb\Models\Traits\ModelDeletesInterface";
			// $method_array[] = "Illuminate\Database\Eloquent\SoftDeletes";
			// foreach ($method_array as $key => $value) {
			// 	$met_exists = class_exists($entityClass, $value);
			// 	if ($met_exists!=true) $output->writeln("<error>- - - - - - - - - - $value </error> missing");
			// 	$test->assertEquals(true, $met_exists);	
			// }
			

			$output->writeln('<info>- Model Load - </info>');
			$test->assertEquals(get_class($entityClass), $class_name);



			$output->writeln('<info>- Methods Exists - </info>');

			
			#ModelDeletes
			$method_array[] = "getChildrenAttribute";
			$method_array[] = "getChildrenTablesAttribute";
			$method_array[] = "delete";
			$method_array[] = "getDeleteAttribute";
			$method_array[] = "unDelete";
			$method_array[] = "getUnDeleteAttribute";
			$method_array[] = "explain";
			$method_array[] = "getDropboxesAttribute";
			$method_array[] = "getRelationshipsAttribute";
			$method_array[] = "getForeignkeysAttribute";
			$method_array[] = "getExplainAttribute";
			#ModelDeletesInterface
			$method_array[] = "children";
			$method_array[] = "childrenTables";
			$method_array[] = "delete";
			$method_array[] = "undelete";
			$method_array[] = "explain";
			$method_array[] = "relationships";
			$method_array[] = "foreignkeys";
			$method_array[] = "dropboxes";

			foreach ($method_array as $key => $value) {
				$met_exists = method_exists($entityClass, $value);
				if ($met_exists!=true) $output->writeln("<error>- - - - - - - - - - $value </error> missing");
				$test->assertEquals(true, $met_exists);	
			}

			$output->writeln('<info>- Childrens Exists - </info>');

			foreach ($entityClass->children as $key => $value) {
				$met_exists = method_exists($entityClass, $value);
				if ($met_exists!=true) $output->writeln("<error>- - - - - - - - - - $value </error> missing");
				$test->assertEquals(true, $met_exists);			
			}
	} //end modelBasicsTester	
		

}