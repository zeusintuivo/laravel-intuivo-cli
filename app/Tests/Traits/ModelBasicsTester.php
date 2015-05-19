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

			$output->writeln("<info>-       class - </info>".get_class($entityClass));
			$output->writeln('<info>- Use Imports - </info>');
			$method_array = array();
			#UseDeletes
			// $method_array[] = "Illuminate\Database\Eloquent\Model";
			// $method_array[] = "PHIWeb\Models\BaseModel";
			// $method_array[] = "PHIWeb\Models\Traits\ModelDeletes";
			// $method_array[] = "PHIWeb\Models\Traits\ModelDeletesInterface";
			// $method_array[] = "Illuminate\Database\Eloquent\SoftDeletes";
			// foreach ($method_array as $key => $attribute_name) {
			// 	$met_exists = class_exists($entityClass, $attribute_name);
			// 	if ($met_exists!=true) $output->writeln("<error>- - - - - - - - - - $attribute_name </error> missing");
			// 	$test->assertEquals(true, $met_exists);	
			// }
			

			$output->writeln('<info>- Model Load - </info>');
			$class_test =  get_class($entityClass) == $class_name;
			if (!$class_test) $output->writeln("<error>- - - - - - - - - - model not loading expected </error> $class_name");
			if (!$class_test) $output->writeln("<error>- - - - - - - - - -                  model got </error> ".get_class($entityClass));
				if ($class_test!=true) {
					dd('FIX ');
					$test->assertEquals(true, $class_test);
				}

			$output->writeln('<info>- is a model - </info>');
			$class_test =  is_a($entityClass, "Illuminate\Database\Eloquent\Model");
			if (!$class_test) $output->writeln("<error>- - - - - - - - - - is not a model </error> $entityClass");
				if ($class_test!=true) {
					dd('FIX ');
					$test->assertEquals(true, $class_test);
				}



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
			#model
			$method_array[] = "save";
			
			$output->writeln('<info>- Childrens Exists - </info>');



			foreach ($method_array as $key => $attribute_name) {
				$met_exists = method_exists($entityClass, $attribute_name);
				if ($met_exists!=true) {
					$output->writeln("<error>- - - - - - - - - - $attribute_name </error> missing");
					dd('FIX '. $attribute_name .' in ' . $entityClass);
					$test->assertEquals(true, $met_exists);			
				}
			}

			#
			# Relationship field needed for softdeleting
			#
			$relation_fields = array();
			$relation_fields[] = "id";
			$relation_fields[] = "deleted_at";
			$relation_fields[] = "updated_at";

			$output->writeln('<info>- SoftDelete Fields Exists - </info>');

			# test for elements of model to exists in relationships

			//
			// Create current Model being tested 
			//
			$t = $entityClass->first(); //instantiate one to test fields
			// dump($entityClass);
			// dump($entityClass->first());
			$t_class = get_class($t); 
			// dump($t_class);
			foreach ($relation_fields as $attribute_name) {
				//
				// Calling $t directly spits out {"id":"1000","date":"2015-02-28","timein   .... etc
				// which is a sample record with fieldnames
				// count the name of the field we are looking for 
				// because calling isset to a null deleted_At will fail but we can count it in the spit
				//
				// dump($t);
				// dump($t->first());
				// dump($t->id);
				// dump($key);
				// dump($attribute_name);
				$model_count = substr_count($t->first(), $attribute_name) > 0;
				
				// 
				// do am isset or a counted it in 
				//
				$rel_exists =  isset($t->first()[$attribute_name]) || $model_count;
				if ($rel_exists!=true) {
					//
					// stop test execution and tip about how to fix it 
					//
					$output->writeln("<error>- - - - - - - - - - $attribute_name </error> missing");
					$output->writeln("\n      ./tinker " );
					$output->writeln("\n      use " . $t_class );
					$output->writeln("      \$t=" . $t_class ."::find(1000) " );
					$output->writeln("      \$t->" . $attribute_name );
					$output->writeln("\n    subl " . str_ireplace('PHIWeb', 'app',$t_class )."\n" );
					dd('FIX '. $t_class . '.' . $attribute_name .' in ' . $t_class);
					$test->assertEquals(true, $rel_exists);	
				}
			}


			$c = $t->find(1000);   //instantiate one of recordss of the child to test fields
			// dump($c);
			# test for elements of model to exists in relationships

			foreach ($t->children as $child) {
				$obj = $t["$child"]; //here we get a model or a collection
			

				
				$c = $obj->find(1000); //instantiate one to test fields
				$c_class = get_class($c); 
				
				$output->writeln("<info>- Relation Fields         Child $t_class -></info> $child <info> \t\tExists - </info>");

				foreach ($method_array as $method_name) {
					// $output->writeln("\n <info>- - - - - - method_name  - - - - -  </info>$method_name");
	
					// dump($obj->first());
					// dump($method_name);
					$met_exists = method_exists($obj->first(), $method_name);
					// $output->writeln("\n <info>- - - - - - method_name  - - - - -  </info>$method_name <info>" . ($met_exists ? "true" : "false"));
					$obj_class = get_class($obj->first());
					
					if ($met_exists!=true) {
						$output->writeln("<error>- - - - - - - - - - $method_name </error> missing");
						dd('FIX ' .  $t_class . ' -> ' . $child . ' ' . get_class($obj) . ' method '. $method_name .' in ' . $obj_class .' for ' . $child);
						$test->assertEquals(true, $met_exists);			
					}
				}

				$output->writeln("<info>- Relation Attributes     Child $t_class -> $child \t\tExists- </info>");
				
				foreach ($relation_fields as $attribute_name) {
					//
					// Calling $t directly spits out {"id":"1000","date":"2015-02-28","timein   .... etc
					// which is a sample record with fieldnames
					// count the name of the field we are looking for 
					// because calling isset to a null deleted_At will fail but we can count it in the spit
					//
					
					// echo($c->first()); //expects raw json text
					// dump($c);
					// dump($c->id);
					// dump($key);
					// dump($attribute_name);
					// dump("c->$attribute_name");
					// dump($attribute_name);
					// $output->writeln("<info>- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -</info>".get_class($obj->first()));
					// $output->writeln("<info>- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -</info>".$obj->first());
					$model_count = substr_count($obj->first(), $attribute_name) > 0;
					// dump($model_count ); //expects true
					
					// 
					// do am isset or a counted it in 
					//
					$rel_exists =  isset($obj->first()[$attribute_name]) || $model_count;  
					if ($rel_exists!=true) {
						//
						// stop test execution and tip about how to fix it 
						//
						$output->writeln("<error>- - - - - - - - - - $attribute_name </error> missing");
						$output->writeln("\n      ./tinker " );
						$output->writeln("\n      use " . $c_class );
						$output->writeln("      \$t=" . $c_class ."::find(1000) " );
						$output->writeln("      \$t->" . $attribute_name );
						$output->writeln("\n    subl " . str_ireplace('PHIWeb', 'app',$c_class )."\n" );
						dd('FIX '. $c_class . '.' . $attribute_name .' in ' . $child);
						$test->assertEquals(true, $rel_exists);	
					}
				}

				// foreach ($relation_fields as $key => $attribute_name) {
				// 	$rel_exists =  isset($obj->first()[$attribute_name]);
				// 	if ($rel_exists!=true) {
				// 		$output->writeln("<error>- - - - - - - - - - $attribute_name </error> missing");
				// 		dd('FIX '. $child . '.'. $attribute_name . ' in ' . $obj);
				// 		$test->assertEquals(true, $rel_exists);	
				// 	}
				// }
				//dump( );
				// dd($obj->first() ); //get the first model as a trick to only test methods
				
			} //end foreach children 
			// dd("END");
	} //end modelBasicsTester	
		

} //end t r a i t 