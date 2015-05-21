<?php namespace PHIWeb\Tests\Traits;
use Illuminate\Database\Eloquent\Model;

use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\Output;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Bootstrap\ConfigureLogging;

#use PHIWeb\Tests\Traits\ModelBasicsTester;


trait ModelBasicsTester {

	private $child;
	
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

			# Uses Deletes
			$use_array[] = "use Illuminate\Database\Eloquent\Model;";
			$use_array[] = "use PHIWeb\Models\BaseModel;";
			$use_array[] = "use PHIWeb\Models\Traits\ModelDeletes;";
			$use_array[] = "use PHIWeb\Models\Traits\ModelDeletesInterface;";
			$use_array[] = "use Illuminate\Database\Eloquent\SoftDeletes;";
			$use_array[] = "use ModelDeletes;";
			$use_array[] = " extends BaseModel";
			$use_array[] = " implements ModelDeletesInterface";
			$use_array[] = "protected \$dates = ['deleted_at'];";
			$use_array[] = "protected \$fillable ";

			$file_name = str_ireplace('PHIWeb', 'app', $class_name);	
			$file_name = str_ireplace("\\", "/", $file_name ) . ".php";
		

			$use_not_found = array();
			# read file to seek for the use cases 
			$file_class_array = explode("\n", file_get_contents($file_name));
			
				
			#
			# Loop uses array methods
			#
			foreach ($use_array as $use_name ) {

				# 
				# Loop through the file and get comparing with use_array. Mark inside array use not found if not found.  
				#
				$found_use = 0;
				foreach ($file_class_array as $file_line) {
					#
					# Add padding word to count as fix it really counts it
					#
					$use_exists = substr_count(".padding.".$file_line, $use_name);
					if ( $use_exists ) {
						$found_use = 1;
						break;	
					}  
				} // end foreach
				#
				# Fail if one use was not found 
				#
				if ( $found_use == 0 ) {
					$use_not_found[] = $use_name;
					
				} //end if 

			} //end foreach

			#
			# Tell the user if not found was populated
			#
			if ( sizeof($use_not_found) > 0 ) {
				foreach ($use_not_found as $value) {
					$output->writeln("<error>- - - - - - - - - -  $value </error> missing");

				}
				$output->writeln("\n\n    subl $file_name    \n\n");
				dd('FIX ');
				$test->assertEquals(1,  $found_use);
			} //end if 
			// dump($entityClass);
			$output->writeln('<info>- Model Load - </info>');
			$class_test =  get_class($entityClass) == $class_name;
			if (!$class_test) $output->writeln("<error>- - - - - - - - - - model not loading expected </error> $class_name");
			if (!$class_test) $output->writeln("<error>- - - - - - - - - -                  model got </error> ".get_class($entityClass));
			if ($class_test!=true) {
				dd('FIX ');
				$test->assertEquals(true, $class_test);
			}

			$output->writeln('<info>       is a model  </info>');
			$class_test =  is_a($entityClass, "Illuminate\Database\Eloquent\Model");
			if (!$class_test) $output->writeln("<error>- - - - - - - - - - is not a model </error> $entityClass");
			if ($class_test!=true) {
				dd('FIX ');
				$test->assertEquals(true, $class_test);
			}

			//
			// Read all field names from table                          ++++ READ TABLE FIELDS
			// 

			$file_plural_snake = $entityClass->getTable();
			$field_list=@shell_exec('./describelist '.$file_plural_snake);
			$field_array = array();
			foreach ( explode("\n", $field_list) as $value) {
				$trim_value = trim($value);
				if (in_array(substr($trim_value , 0,8 ), ["","~", "column_n", "Warning:"])) {
					//
					// ignore list this
					//
				} else {
					$field_array[] = $trim_value;
				}
			}

			//
			// Test for Foreign and params if migration file exists         ++++ READ MIGRATION 
			//

			$migration_file=@exec('ls database/migrations/*_'.$file_plural_snake.'_table.php');
			if ( file_exists($migration_file)) {
				$params_array  = array();
				$foreign_array = array();

				#
				# Obtain a param list then make it an array based on the text found inside the migration
				# 
				// ack -s params /home/jesusalc/Sites/ph20/database/migrations/2015_04_27_002_create_med_log_progresses_table.php  | cut -d"'" -f2
				$output->writeln('<info>- Params Ref - </info>');
				$command = ' ack -s params '.$migration_file.'  | cut -d"'."'" .'" -f2 ';
				$output->writeln(" $command ");
				$params_list=@shell_exec( $command );
				foreach (explode("\n", $params_list) as $value) {
					$params_array[] = trim($value);
				}

				# 
				# Compare and fail if not found                                ++++ PARAMS CHECK  
				#
				//recycled $use_not_found array, $ound_use from the top
				$use_not_found[] = 'params';

				/**
				 * used to store all fields stored in class to later compare
				 * if they really exists in the db or if they are extra
				 */
				$all_fields_in_class = array(); 
				//
				foreach ($params_array as $param) {   //Looking for 
					$found_use = 0;
					if (!is_null($param)) { //avoid blank lines that trigger blank array values
						foreach ($entityClass->foreignkeys() as $k => $fk_array) { //Looking inside here
							if ($k == "foreign - params") { 
								foreach ($fk_array as $comparing) {
									if (!in_array($comparing, $all_fields_in_class)) $all_fields_in_class[] = $comparing;
									$use_exists = $param == $comparing;
									if ( $use_exists ) {
										$found_use = 1;
										break;	
									} //end if 		
								} // foreach
	                        
							} // if

						} // foreach
					}
					#
					# Register if not found 
					#
					if ( $found_use == 0 && $param <> "") {
						$use_not_found[] = '        $r[] = "'.$param.'";';
						
					} //end if 
				} // foreach


				#
				# Obtain a foreign list then make it an array based on the text found inside the migration
				# 
				// ack -s foreign /home/jesusalc/Sites/ph20/database/migrations/2015_04_27_002_create_med_log_progresses_table.php  | cut -d"'" -f2
				$output->writeln('<info>- Foreigns Ref - </info>');
				$command = ' ack -s foreign '.$migration_file.'  | cut -d"'."'" .'" -f2 ';
				$output->writeln(" $command ");
				$foreign_list=@shell_exec( $command );
				foreach (explode("\n", $foreign_list) as $value) {
					$foreign_array[] = $value;
				}


			
				# 
				# Compare and fail if not found                                     ++++ FOREIGNS CHECK  
				#
				//recycled $use_not_found array, $ound_use from the top
				$use_not_found[] = 'foreign';
				foreach ($foreign_array as $foreign) {   //Looking for 
					$found_use = 0;
					if (!is_null($foreign)) { //avoid blank lines that trigger blank array values
						foreach ($entityClass->foreignkeys() as $k => $fk_array) { //Looking inside here
							if ($k == "foreign - all") { 
							
								foreach ($fk_array as $comparing) {
									// $output->writeln("<info>- $foreign == $comparing; - </info>");
									if (!in_array($comparing, $all_fields_in_class)) $all_fields_in_class[] = $comparing;
									$use_exists = $foreign == $comparing;
									if ( $use_exists ) {
										$found_use = 1;
										break;	
									} //end if 		
								} // foreach
	                        
							} // if

						} // foreach
					}
					#
					# Register if not found 
					#
					if ( $found_use == 0 && $foreign <> "") {
						$use_not_found[] = '        $p[] = "'.$foreign.'";';
						
					} //end if 
				} // foreach


				$output->writeln('<info>- dropboxes Ref - </info>');                    
				# 
				# Compare and fail if not found                                        ++++ DROPBOXES CHECK  
				#
				//recycled $use_not_found array, $ound_use from the top
				$use_not_found[] = 'dropboxes';
				foreach ($params_array as $param) {   //Looking for 
					$found_use = 0;
					if (!is_null($param)) { //avoid blank lines that trigger blank array values
						foreach ($entityClass->dropboxes() as $comparing) {
							$use_exists = $param == $comparing;
							if (!in_array($comparing, $all_fields_in_class)) $all_fields_in_class[] = $comparing;
							if ( $use_exists ) {
								$found_use = 1;
								break;	
							} //end if 		
						} // foreach
	                
					}
					#
					# Register if not found 
					#
					if ( $found_use == 0 && $param <> "") {
						$use_not_found[] = '        $r[] = "'.$param.'";';
						
					} //end if 
				} // foreach




				$output->writeln('<info>- fillable test - </info>');                  
				# 
				# Compare and fail if not found                                        ++++ FILLABLE CHECK  
				#
				//recycled $use_not_found array, $ound_use from the top
				$use_not_found[] = 'fillable';
				foreach ($field_array as $param) {   //Looking for 
					if (!in_array($param, ['id','created_at','updated_at','deleted_at'])) {  //ignore list for fillables
						$found_use = 0;
						if (!is_null($param)) { //avoid blank lines that trigger blank array values
							foreach ($entityClass->getFillable() as $comparing) {
								if (!in_array($comparing, $all_fields_in_class)) $all_fields_in_class[] = $comparing;
								$use_exists = $param == $comparing;
								if ( $use_exists ) {
									$found_use = 1;
									break;	
								} //end if 		
							} // foreach
		                
						}
						#
						# Register if not found 
						#
						if ( $found_use == 0 && $param <> "") {
							$use_not_found[] = "	  '$param',";
							
						} //end if
					}


				} // foreach






				#
				# Tell the user if not found was populated
				#
				// dump($entityClass->foreignkeys());
				$copy_to_clipboard = array();
				if ( sizeof($use_not_found) > 4 ) {     //    <---- TOPIF 
					$output->writeln("<error>- - - - please</error> add <error>the following in side the class</error> <info>$file_name </info> ");							
					foreach ($use_not_found as $k => $value) {
						if ($value=="params" && $use_not_found[$k+1]=="foreign") {
							//
							//skip this if empty params to avoid saying "missing params" with nothign in it
							//
						} elseif ($value=="foreign" && $use_not_found[$k+1]=="dropboxes") {
							//
							// skip this if empty params to avoid saying "missing foreign" with nothign in it
							// 						
						} elseif ($value=="dropboxes" && $use_not_found[$k+1]=="fillable") {
							//
							// skip this if empty params to avoid saying "missing foreign" with nothign in it
							// 
						} elseif ($value=="fillable" && sizeof($use_not_found)==$k+1) {
							//
							// skip empty fillable - 
							// 							
							// fillable empty will be avoided from TOPIF ^  when if the array is only 3 long
							// and if we are here \/  is because is at least 4 long and this is the last one
						} else {
							if (in_array($value, [ "params","foreign","dropboxes"])) {
								$copy_to_clipboard[$k] = "        $value";							
							} elseif (in_array($value, ["fillable"])) {
								$copy_to_clipboard[$k] = "	  #$value";							
							} elseif (in_array($value, [ "params","foreign","dropboxes","fillable"])) {
								$output->writeln("<error>- - - - - </error>      in <error>- - - - -  #$value </error>");
							} else {
								$output->writeln("<error>- - - - - </error> missing <error>- - - - -  $value </error>");
								$copy_to_clipboard[$k] = $value;								
							}
						}

					}
					//prepare to copy to clipboard
					$said=@file_put_contents('testerrors', implode("\n", $copy_to_clipboard)."\n");
					$said=@system("./testerrorstoclipboardcall & ");	 //using xsel, xclip blocked execution					
					$said=@exec("subl $file_name &");
					$output->writeln("\n\n    subl $file_name    ");
					$output->writeln("\n\n    subl $migration_file    \n\n");
					dd('FIX ');
					$test->assertEquals(1,  $found_use);
				} //end if 





				$output->writeln('<info>- remove extra fields test - </info>');                  
				# 
				# Compare and fail if not found                                        ++++ REMOVE EXTRA FIELDS 
				#
				///New array for clarity of reading it
				$dont_exist[] = 'remove';
				foreach ($field_array as $real_field) {   //Looking for 
					if (!in_array($real_field, ['id','created_at','updated_at','deleted_at'])) {  //ignore list for fillables
						$found_use = 0;
						if (!is_null($real_field)) { //avoid blank lines that trigger blank array values
							foreach ($all_fields_in_class as $comparing) {
								$use_exists = $real_field == $comparing;
								if ( $use_exists ) {
									$found_use = 1;
									break;	
								} //end if 		
							} // foreach
		                
						}
						#
						# Register if not found 
						#
						if ( $found_use == 0 && $real_field <> "") {
							$dont_exist[] = "	  '$real_field',";
							
						} //end if
					} //end if ignore list


				} // foreach




				#
				# Tell the user if not found was populated
				#
				// dump($entityClass->foreignkeys());
				if ( sizeof($dont_exist) > 1 ) {     //    <---- TOPIF 
					$output->writeln("<error>- - - - - </error> remove <error>the following in side the class</error> <info>$file_name </info> ");							
					foreach ($dont_exist as $k => $value) {
						if ($value=="remove" && sizeof($dont_exist)==$k+1) {
							//
							// skip title if empty
							// 							
						} else {
							if (in_array($value, [ "remove"])) {
								$output->writeln("<error>- - - - - </error> action <error>- - - - -  #$value </error>");
								$copy_to_clipboard[$k] = "	  #$value";	
							} else {
								$output->writeln("<error>- - - - - </error> remove <error>- - - - -  $value </error>");	
								$copy_to_clipboard[$k] = $value;						
							}
						}

					}
					//prepare to copy to clipboard
					$said=@file_put_contents('testerrors', implode("\n", $copy_to_clipboard)."\n");
					$said=@system("./testerrorstoclipboardcall & ");	 //using xsel, xclip blocked execution			
					$said=@exec("subl $file_name &");
					$output->writeln("\n\n    subl $file_name    ");
					$output->writeln("\n\n    subl $migration_file    \n\n");
					dd('FIX ');
					$test->assertEquals(1,  $found_use);
				} //end if 







			} //end if  file_exists($migration_file)


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

/*
			$c = $t->find(1000);   //instantiate one of recordss of the child to test fields
			// dump($c);
			# test for elements of model to exists in relationships

			foreach ($t->children as $child) {
				$obj = $t["$child"]; //here we get a model or a collection
			

				
				$c = $obj->find(1000); //instantiate one to test fields
				$c_class = get_class($c); 
				
				$output->writeln("<info>- Relation Fields         Child $t_class -></info> $child <info> \t\tExists - </info>");

				foreach ($method_array as $method_name) {
					$output->writeln("\n <info>- - - - - - method_name  - - - - -  </info>$method_name");
	
					dump($obj->first());
					dump($method_name);
					$met_exists = method_exists($obj->first(), $method_name);
					$output->writeln("\n <info>- - - - - - method_name  - - - - -  </info>$method_name <info>" . ($met_exists ? "true" : "false"));
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



			#
			#
			#
			#
			#

*/
        foreach($entityClass->children as $child) //scroll by children
        {
        	$this->$child = $child;
            $obj = $entityClass["$child"];
           
            if (sizeof($obj)<=0) {
                //
                // undelete lonely object
                //
                if ($obj!=null && sizeof($obj->count())>0) {
                    //
                    foreach ($obj as $obj_item) { 
                        // echo "\n IF 1 \n";
                        // echo "\nChild:$child \n";
                        // echo "\nobj_item:$obj_item \n";
                        // echo "\nobj_item is ". get_class($obj_item)."\n";
                    } //end foreach 
                    //
                }
                //
            } elseif ( sizeof($obj)==1) {
                //
                // echo "\n IF 2 \n";
                // $output->writeln("\n\n".'<info>--( '. (is_a($obj, "Illuminate\Database\Eloquent\Collection") ?  get_class($obj)  . ' is a collection ' : '' ).'--</info>');
                // $output->writeln("\n\n".'<info>--( '. (is_a($obj, "Illuminate\Database\Eloquent\Model") ?  get_class($obj)  . ' is a Model ' : '' ).'--</info>');
                // echo "\nChild:$child \n";
                // echo "\nobj_:$obj \n";
                // echo "\nobj is ". get_class($obj)."\n";

                //
            } else {    
                //
                // sizeof > 1
                //
                foreach ($obj as $obj_item) { 
                    //
                // echo "\n IF 3 \n";
                // echo "\nChild:$child \n";
                // echo "\nobj_item:$obj_item \n";
                // echo "\nobj_item is ". get_class($obj_item)."\n";
                    //
                } //end foreach 
                //
            } //end if
            // 
            if (get_class($obj)=="Illuminate\Database\Eloquent\Collection") {
                foreach ($obj as $itemkey => $itemValue) {
                    //if (isset($itemValue->$child) && $itemValue->$child == $value) {
                        // echo "\n IF 5 \n";
                        // echo "\nChild:$child \n";
                        // $output->writeln("\n\n".'<info>--( '. (is_a($itemValue, "Illuminate\Database\Eloquent\Model") ?  get_class($itemValue)  . ' is a Model ' : '' ).'--</info>');
                        // echo "\n\n itemkey: $itemkey \n";
                        // echo "get_class is itemkey". (is_object($itemkey) ? get_class($itemkey) : $itemkey) ."\n";
                        // echo "\n\n itemValue: ". (is_array($itemValue)==true ? "[".implode(', ', $itemValue) ."]" : $itemValue) ."\n"; 
                        // echo "get_class is itemValue". (is_object($itemValue)==true ? get_class($itemValue) : (is_array($itemValue)==true ? "[".implode(', ', $itemValue) ."]" : $itemValue)) ."\n";
                        // echo "key is ". (is_object($key) ? get_class($key) : $key) ."\n";
                        // echo "value is ". (is_object($value) ? get_class($value) : $value) ."\n";
                    //}
                }
            } //end if
        } //end foreach
                    //$entityClass
                    // echo "\n IF 4 \n";
                    // echo "\n\n This:this \n";
                    // $output->writeln( "this is ". get_class($entityClass)."\n" );
				if (sizeof($entityClass->children)==0) {
					$output->writeln("<info>-.".get_class($entityClass) ." has no children - </info>");
				} 
                    foreach($entityClass->children as $child) //scroll by children
                    {
                    	$this->$child = $child ;
                    	$output->writeln("<info>- Relation Attributes     Child $t_class -> ".$this->$child ." \t\tExists- </info>");
                        $obj = $entityClass["$child"];
                        // $output->writeln( "\nChild:$child \n" );
                        $is_a_collection = (is_a($obj, "Illuminate\Database\Eloquent\Collection") ?  'collection' : '');
                        $is_a_model = (is_a($obj, "Illuminate\Database\Eloquent\Model") ?  'model' : '');

                        $what_is_it =  ($is_a_collection ? $is_a_collection  : ( $is_a_model ? $is_a_model : get_class($obj) ) );

                        // $output->writeln("\n\n <info>--( $child ". get_class($obj)  . ' is a '.$what_is_it .'--</info>');                           
                        // $output->writeln( "obj is ". get_class($obj)."\n" );
                        // echo "obj  ". (method_exists($obj, 'undelete')==true ? 'has undelete' : '') ."\n";
                        // echo "obj  ". (method_exists($obj, 'save')==true ? 'has save' : '') ."\n";
                        
                        if ($what_is_it == 'collection' ) {
                             // dump(sizeof($obj));	
                            foreach ($obj as $obj_item) { 

                                if (is_object($obj_item)) {
                                    $childClass = get_class($obj_item);

                                    if (is_a($obj_item, "Illuminate\Database\Eloquent\Model")) {
                                        //
                                        //
                                        //
                                        $tempObj = $obj_item->find($obj_item->id);
                                		// $output->writeln( "tempObj: ". (is_array($tempObj)==true ? "[".implode(', ', $tempObj) ."]" : $tempObj) ."\n"); 
                                        // $output->writeln( "tempObj is a ". (is_object($tempObj) ? get_class($tempObj) : $tempObj) ."\n");
                                        // $output->writeln( "tempObj ". (method_exists($tempObj, 'undelete')==true ? ' has undelete' : '') ."\n");
                                        // $output->writeln( "tempObj ". (method_exists($tempObj, 'save')==true ? ' has save' : '') ."\n");
										
										/**
										 *
										 * Sample Manual Test
										 * 
										 *
                                         * //
                                         * // This is the only way to check if a 'null' deleted_at exists
                                         * //
                                         * $model_count = substr_count($tempObj, 'deleted_at') > 0;
 										 * 
                                         * $output->writeln( "tempObj ". ( isset($tempObj['id']) !=null ? ' has id' : ' ERROR Missing ID') ."\n");
                                         * echo "tempObj ". ( isset($tempObj['deleted_at']) !=null ? ' has deleted_at' : ' ERROR Missing deleted_at') ."\n");
                                         * $output->writeln( "tempObj ". ( $model_count ? ' has deleted_at' : ' ERROR Missing deleted_at') ."\n");
                                         * $output->writeln( "tempObj ". ( isset($tempObj['updated_at']) !=null ? ' has updated_at' : ' ERROR Missing updated_at') ."\n");
                                         * 
                                         * $tempObj->id = $obj_item->id;
                                         * $s = $tempObj->save();
                                         * if ($s==true)  $output->writeln( "tempObj saves"); else $output->writeln( "tempObj save error");
                                         * 
                                         *
                                         */

                                        #
                                        # Test looping the array 
                                        #
										foreach ($relation_fields as $attribute_name) {

	                                        #
	                                        # This is the only way to check if a 'null' deleted_at exists
	                                        #
	                                        $model_count = substr_count($tempObj, $attribute_name) > 0;

											# 
											# Test the attribute by calling it or checking if it printed in the list  
											#

											$rel_exists =  isset($tempObj[$attribute_name]) || $model_count;  
											if ($rel_exists!=true) {
												//
												// stop test execution and tip about how to fix it 
												//
												$c_class = get_class($tempObj);
												$c_file_name = str_ireplace('PHIWeb', 'app', $c_class);	
												$c_file_name = str_ireplace("\\", "/", $c_file_name ) . ".php";
												$c_model_name = explode("\\", $c_class);
												$c_model_name = array_pop($c_model_name);
												$p_model_name = explode("\\", $class_name);
												$p_model_name = array_pop($p_model_name);
												$output->writeln("<error>- - - - - - - - - - $attribute_name </error> access missing");
												$output->writeln("\n    $file_name has an entry in $p_model_name->children()=['$child'] ");
												$output->writeln("\n    that points to $c_model_name which is missing/ or missing access to '$attribute_name' ");
												$output->writeln("\n      ./describe ".$tempObj->getTable() );
												$output->writeln("\n      ./tinker " );
												$output->writeln("\n      use " . $c_class );
												$output->writeln("      \$t=" . $c_model_name  ."::first() " );
												$output->writeln("      \$t->" . $attribute_name );
												$said=@exec("subl $c_file_name &");
												$output->writeln("\n    subl $c_file_name \n" );
												dd('FIX '. $c_class . '.' . $attribute_name .' in ' . $this->$child );
												$test->assertEquals(true, $rel_exists);	
											}
	                                         
	                                      
	                                     
                                    	} //end foreach
                                	  	// $output->writeln("<info>- Models Saves  ?   Child $t_class -> $child \t\tExists- </info>");
                                        $tempObj->id = $obj_item->id;

                                        $s = $tempObj->save();
                                        
                                        if ($s==true) {
                                        	// $output->writeln( "tempObj saves"); 	
                                        
                                        } else {
                                        	$output->writeln( "tempObj save error");
                                        	//
											// stop test execution and tip about how to fix it 
											//
											$output->writeln("<error>- - - - - - - - - - save </error> does not save");
											$output->writeln("\n      ./tinker " );
											$output->writeln("\n      use " . $c_class );
											$output->writeln("      \$t=" . $c_class ."::find(1000) " );
											$output->writeln("      \$t->" . $attribute_name );
											$output->writeln("\n    subl " . str_ireplace('PHIWeb', 'app',$c_class )."\n" );
											dd('FIX '. $c_class . '.' . $attribute_name .' in ' . $child);
											$test->assertEquals(true, $s);		                                        
                                        }
                                    } //end if                               
                                } //end if


                            } //end foreach
                        } //end if 
                    } //end foreach 


                    #
                    #
                    #


			 // dd("END");

// $entityClass->exploreWithTrashed;


	} //end modelBasicsTester	
		

} //end t r a i t 