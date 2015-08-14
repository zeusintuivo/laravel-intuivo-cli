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

			$file_name = str_ireplace('PHIWeb', 'app', $class_name);	
			$file_name = str_ireplace("\\", "/", $file_name ) . ".php";
			$ignore_table_fields_array = ['id','updated_at','deleted_at','created_at'];
			$output->writeln("<info>-       class - </info>".get_class($entityClass));
			$output->writeln("<info>-       table - </info>".($entityClass->getTable()));
			$output->writeln("<info>-        file - </info>".($file_name));
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
			$copy_to_clipboard = array();
			if ( sizeof($use_not_found) > 0 ) {
				foreach ($use_not_found as $value) {
					$output->writeln("<error>- - - - - - - - - -  $value </error> missing");
					$copy_to_clipboard[] = $value;

				}
				//prepare to copy to clipboard
				$said=@file_put_contents('testerrors', implode("\n", $copy_to_clipboard)."\n");
				$said=@system("./testerrorstoclipboardcall");	
				$said=@exec("subl $file_name &");
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

			$migration_file=@exec('ls database/migrations/*_create_'.$file_plural_snake.'_table.php');
			if ( file_exists($migration_file) ) {
				$params_array  = array();
				$foreign_array = array();

				#
				# Obtain a param list then make it an array based on the text found inside the migration
				# 
				// ack -s params /home/jesusalc/Sites/ph20/database/migrations/2015_04_27_002_create_med_log_progresses_table.php  | cut -d"'" -f2
				$output->writeln('<info>- Params Ref - </info>');
				$command = ' ack -s params '.$migration_file.'  | cut -d"'."'" .'" -f2 ';
				//$output->writeln(" $command ");
				$params_list=@shell_exec( $command );
				foreach (explode("\n", $params_list) as $value) {
					$params_array[] = trim($value);
				}

				# 
				# Compare and fail if not found                                ++++ PARAMS CHECK  
				#
				//recycled $use_not_found array, $ound_use from the top
				$use_not_found = null;
				$use_not_found = array();
				$use_not_found[] = 'params';

				/**
				 * used to store all fields stored in class to later compare
				 * if they really exists in the db or if they are extra
				 */
				$all_fields_in_class = array(); 
				//
				// dd($entityClass->foreignkeys());
				$all_fields_in_class[] = "#params";
				foreach ($params_array as $param) {   //Looking for 
					$found_use = 0;
					if (!is_null($param)) { //avoid blank lines that trigger blank array values
						foreach ($entityClass->foreignkeys() as $k => $fk_array) { //Looking inside here
							if ($k == "foreign - params") { 
								// dd($k);
								// dd($fk_array);
								if (is_array($fk_array)) {
									foreach ($fk_array as $comparing) {
										// add it to the array of fields in this calls comparison for latter process
										if (!in_array($comparing, $all_fields_in_class)) $all_fields_in_class[] = $comparing;
										$use_exists = $param == $comparing;
										if ( $use_exists ) {
											$found_use = 1;
											break;	
										} //end if 		
									} // foreach
								} else  { //
			                    	//
			                    	// I asume this is happening "foreign - params" => "relationship_type_id"
			                    	// that I get one string value 
			                    	 	//dd($fk_array);
			                    	    $comparing = $fk_array[0];
										// add it to the array of fields in this calls comparison for latter process
										if (!in_array($comparing, $all_fields_in_class)) $all_fields_in_class[] = $comparing;
										$use_exists = $param == $comparing;
										if ( $use_exists ) {
											$found_use = 1;
											break;	
										} //end if 		
			                    } //end if array

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
				// dd($use_not_found);

				#
				# Obtain a foreign list then make it an array based on the text found inside the migration
				# 
				// ack -s foreign /home/jesusalc/Sites/ph20/database/migrations/2015_04_27_002_create_med_log_progresses_table.php  | cut -d"'" -f2
				$output->writeln('<info>- Foreigns Ref - </info>');
				$command = ' ack -s foreign '.$migration_file.'  | cut -d"'."'" .'" -f2 ';
				// $output->writeln(" $command ");
				$foreign_list=@shell_exec( $command );
				foreach (explode("\n", $foreign_list) as $value) {
					$foreign_array[] = $value;
				}


			
				# 
				# Compare and fail if not found                                     ++++ FOREIGNS MISSING CHECK  
				#
				//recycled $use_not_found array, $ound_use from the top
				$use_not_found[] = 'foreign';
				$all_fields_in_class[] = "#foreign";
				// dd($foreign_array);
				foreach ($foreign_array as $foreign) {   //Looking for 
					$found_use = 0;
					if (!is_null($foreign)) { //avoid blank lines that trigger blank array values
						foreach ($entityClass->foreignkeys() as $k => $fk_array) { //Looking inside here
							if ($k == "foreign - all") { 
								if ( is_array($fk_array) ) {
									foreach ($fk_array as $comparing) {
										// $output->writeln("<info>- $foreign == $comparing; - </info>");
										// add it to the array of fields in this calls comparison for latter process
										if (!in_array($comparing, $all_fields_in_class)) $all_fields_in_class[] = $comparing;
										$use_exists = $foreign == $comparing;
										// $output->writeln("<comment>- - $foreign == $comparing- - - </comment> ".($use_exists ? "<info> true </info>\n" : "<error> false </error>" ) );
										if ( $use_exists ) {
											$found_use = 1;
											break;	
										} //end if 		
									} // foreach
								} else { //
			                    	//
			                    	// I asume this is happening "foreign - params" => "relationship_type_id"
			                    	// that I get one string value 
			                    	$comparing = $fk_array[0];
			                		// add it to the array of fields in this calls comparison for latter process
									if (!in_array($comparing, $all_fields_in_class)) $all_fields_in_class[] = $comparing;
									// $output->writeln("<comment>- - $foreign == $comparing- - - </comment> ".($use_exists ? "<info> true </info>\n" : "<error> false </error>" ) );
									$use_exists = $foreign == $comparing;
									if ( $use_exists ) {
										$found_use = 1;
										break;	
									} //end if 		
			                    } //end if array
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
 				// dd($use_not_found);

				$output->writeln('<info>- dropboxes Ref - </info>');                    
				# 
				# Compare and fail if not found                                        ++++ DROPBOXES MISSING CHECK  
				#
				//recycled $use_not_found array, $ound_use from the top
				$use_not_found[] = 'dropboxes';
				$all_fields_in_class[] = '#dropboxes';
				// dd($entityClass->dropboxes());
				foreach ($params_array as $param) {   //Looking for 
					$found_use = 0;
					if (!is_null($param)) { //avoid blank lines that trigger blank array values
						if ( is_array($entityClass->dropboxes()) ) {
							foreach ($entityClass->dropboxes() as $comparing) {
								$use_exists = $param == $comparing;
								// add it to the array of fields in this calls comparison for latter process
								if (!in_array($comparing, $all_fields_in_class)) $all_fields_in_class[] = $comparing;
								if ( $use_exists ) {
									$found_use = 1;
									break;	
								} //end if 		
							} // foreach
	                	} else { //
                        	//
                        	// I asume this is happening $entityClass->foreignkeys() = "relationship_type_id"
                        	// that I get one string value 
                        	$comparing = $entityClass->dropboxes();
                        	// add it to the array of fields in this calls comparison for latter process
							if (!in_array($comparing, $all_fields_in_class)) $all_fields_in_class[] = $comparing;
							if ( $use_exists ) {
								$found_use = 1;
								break;	
							} //end if 		
                        } //end if array
	
					} //en if is null
					#
					# Register if not found 
					#
					if ( $found_use == 0 && $param <> "") {
						$use_not_found[] = '        $p[] = "'.$param.'";';
						
					} //end if 
				} // foreach
				# 
				# Compare and fail if not found                                       ++++ DROPBOXES EXTRA FIELDS CHECK  
				#
				//recycled $use_not_found array, $ound_use from the top
				$dont_exist = array();
				//dd($entityClass->getFillable());
				foreach ($entityClass->dropboxes() as $dropbox_entry_in_class) {   //Looking for 
					if (!is_null($dropbox_entry_in_class)) { //avoid blank lines that trigger blank array values
						$found_use = 0;
						// dd($dropbox_entry_in_class);
						foreach ($field_array as $field_in_table) {
							if (!in_array($field_in_table, $ignore_table_fields_array)) {  //ignore list for of tables
									if (!in_array($dropbox_entry_in_class, $all_fields_in_class)) $all_fields_in_class[] = $dropbox_entry_in_class;
							} //end if ignore_table_fields_array
						} // foreach
					} //end if not null dropbox_entry_in_class
				} // foreach
				// dd($all_fields_in_class);



				$output->writeln('<info>- fillable test - </info>');                  
				# 
				# Compare and fail if not found                                 ++++ FILLABLE MISSING CHECK                     
				#
				//recycled $use_not_found array, $ound_use from the top
				$use_not_found[] = 'fillable';
				$all_fields_in_class[] = '#fillable';
				//dd($entityClass->getFillable());
				foreach ($field_array as $param) {   //Looking for 
					if (!in_array($param, $ignore_table_fields_array)) {  //ignore list for fillables
						$found_use = 0;
						if (!is_null($param)) { //avoid blank lines that trigger blank array values
							foreach ($entityClass->getFillable() as $comparing) {
								if (!in_array($comparing, $all_fields_in_class)) $all_fields_in_class[] = $comparing;
								$use_exists = $param == $comparing;
								// $output->writeln("<comment>- - $param vs $comparing  - </comment> ". $use_exists . ' ' . ($use_exists ? "<info> exists </info>\n" : "<error> exists </error>" ));
									
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
							$use_not_found[] = "        '$param',";
							
						} //end if
					}


				} // foreach
				# 
				# Compare and fail if not found                                       ++++ FILLABLE EXTRA FIELDS CHECK  
				#
				//recycled $use_not_found array, $ound_use from the top
				$dont_exist = array();
				//dd($entityClass->getFillable());
				foreach ($entityClass->getFillable() as $fillable_entry_in_class) {   //Looking for 
					if (!is_null($fillable_entry_in_class)) { //avoid blank lines that trigger blank array values
						$found_use = 0;
						// dd($fillable_entry_in_class);
						foreach ($field_array as $field_in_table) {
							if (!in_array($field_in_table, $ignore_table_fields_array)) {  //ignore list for of tables
									if (!in_array($fillable_entry_in_class, $all_fields_in_class)) $all_fields_in_class[] = $fillable_entry_in_class;
							} //end if ignore_table_fields_array
						} // foreach
					} //end if not null fillable_entry_in_class
				} // foreach
				// dd($all_fields_in_class);



				// dd("stop");

	
				


				#
				# Tell the user if not found was populated
				#
				// dump($entityClass->foreignkeys());
				if ( sizeof($use_not_found) > 4 ) {     //    <---- TOPIF 
					$output->writeln("<error>- - - - please</error> add <error>the following inside the class</error> <info>$file_name </info> ");							
					$output->writeln("<comment>- - - - or </comment> remove <comment>its references from the migration file </comment> <info>$migration_file   </info> ");							
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
								$copy_to_clipboard[$k] = "        #$value";							
							} elseif (in_array($value, ["fillable"])) {
								$copy_to_clipboard[$k] = "        #$value";							
							}
							if (in_array($value, [ "params","foreign","dropboxes","fillable"])) {
								$output->writeln("<error>- - - - - </error>      in <error>- - - - -  #$value </error>");
							} else {
								$output->writeln("<error>- - - - - </error> missing <error>- - - - -  $value </error>");
								$copy_to_clipboard[$k] = $value;								
							}
						}

					}
					//prepare to copy to clipboard
					$said=@file_put_contents('testerrors', implode("\n", $copy_to_clipboard)."\n");
					$said=@system("./testerrorstoclipboardcall");	 //using xsel, xclip blocked execution					
					$said=@exec("subl $file_name &");
					$output->writeln("\n\n    subl $file_name    ");
					$output->writeln("\n\n    subl $migration_file    \n\n");
					dd("FIX ");
					$test->assertEquals(1,  $found_use);
				} //end if 

			







				$output->writeln('<info>- remove extra fields test compared to those in the Table * if asked to remove do a describe - </info>');   
				// dd($all_fields_in_class);   
				# 
				# Compare and fail if not found                                        ++++ REMOVE EXTRA FIELDS 
				#
				///New array for clarity of reading it
				$dont_exist[] = '#remove';
				// dd($field_array);
				// dd($all_fields_in_class);
				foreach ($all_fields_in_class as $real_field) {   //Looking for 
					if (!in_array($real_field, $ignore_table_fields_array)) {  //ignore list for fillables
						$found_use = 0;
						if (!is_null($real_field)) { //avoid blank lines that trigger blank array values
		
							foreach ($field_array as $comparing) {
								$use_exists = $real_field == $comparing;
								// $output->writeln("<comment>- - $real_field == $comparing- - - </comment> ".($use_exists ? "<info> true </info>\n" : "<error> false </error>" ) );
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
							if (in_array($real_field, [ "#params","#foreign","#dropboxes","#fillable"])) {
								$dont_exist[] = $real_field;
							} else {
								$dont_exist[] = "	  '$real_field',";
							}
							
						} //end if
					} //end if ignore list


				} // foreach
				// dump($field_array );
				// dd($all_fields_in_class);


				#
				# Tell the user if not found was populated
				#
				// dump($entityClass->foreignkeys());
				// dd($dont_exist);
				if ( sizeof($dont_exist) > 5 ) {     //    <---- TOPIF 
					$output->writeln("<error>- - - - - </error> remove <error>the following in side the class</error> <info>$file_name </info> ");		
					// dd($dont_exist);					
					foreach ($dont_exist as $k => $value) {
						if ($value=="#remove" && $dont_exist[$k+1]=="#params") {
							//
							//skip this if empty params to avoid saying "missing params" with nothign in it
							//
						} elseif ($value=="#params" && $dont_exist[$k+1]=="#foreign") {
							//
							// skip this if empty params to avoid saying "missing foreign" with nothign in it
							// 						
						} elseif ($value=="#foreign" && $dont_exist[$k+1]=="#dropboxes") {
							//
							// skip this if empty params to avoid saying "missing foreign" with nothign in it
							// 
						} elseif ($value=="#dropboxes" && $dont_exist[$k+1]=="#fillable") {
							//
							// skip empty fillable - 
							// 							
							// fillable empty will be avoided from TOPIF ^  when if the array is only 3 long
							// and if we are here \/  is because is at least 4 long and this is the last one
						} elseif ($value=="#fillable" && sizeof($dont_exist)==$k+1) {
							//
							// skip title if empty
							// 							
						} else {
							if (in_array($value, [ "#params","#foreign","#dropboxes","#fillable"])) {
								$output->writeln("<error>- - - - - </error> action <error>- - - - -  $value </error>");
								$copy_to_clipboard[$k] = "	  $value";	
							} else {
								$output->writeln("<error>- - - - - </error> remove <error>- - - - -  $value </error>");	
								$copy_to_clipboard[$k] = $value;						
							} //end if group 
				
						} //end if group 
					} //end foreach 				
					
					//prepare to copy to clipboard
					$said=@file_put_contents('testerrors', implode("\n", $copy_to_clipboard)."\n");
					$said=@system("./testerrorstoclipboardcall");		
					$said=@exec("subl $file_name &");
					$output->writeln("\n\n    subl $file_name    ");
					$output->writeln("\n\n    subl $migration_file    \n\n");
					dd('FIX ');
					$test->assertEquals(1,  $found_use);
				} //end if 


				# 
				# Compare and fail if not found                                   ++++ READ FUNCTIONS
				#
				//ack -s function /app/Models/Organization/School.php | cut -d'(' -f1
				$command = ' ack -s function '.$file_name."  | cut -d'(' -f1 ";
				//$output->writeln(" $command ");
				$cascade_list=@shell_exec( $command );
				$cascade_array = array();
				$cascade_type_array = array();
				$cascade_name_array = array();
				foreach (explode("\n", $cascade_list) as $value) {
					$value = preg_replace('/  /i', ' ', $value); //remove double spaces
					$function_name_array = explode(' ', $value);
					$function_name = array_pop($function_name_array);
 					$cascade_name_array[] = trim($function_name);
					$cascade_type_array[trim($function_name)] = trim(implode(' ', $function_name_array));
				}
		        $cascade_array['type'] = $cascade_type_array;
		        $cascade_array['name'] = $cascade_name_array;
				
				// dd($cascade_array );


	
				# 
				# Compare and fail if not found                                     ++++ CASCADES SHOULD EXISTS
				#
				$output->writeln('<info>- RELATIONSHIP Cascade Existance- </info>');
				//recycled $use_not_found array, $ound_use from the top
				$use_not_found = null; //reset
				$use_not_found[] = 'cascade';
				foreach ($entityClass->children() as $children_cascade_relationship_name) {   //Looking for 
					$found_use = 0;
					if (!is_null($children_cascade_relationship_name)) { //avoid blank lines that trigger blank array values
						foreach ($cascade_array as $k => $name) { //Looking inside here
							if ($k == "name") { 
								if ( is_array($name) ) {
									foreach ($name as $comparing) {
										// $output->writeln("<info>- $children_cascade_relationship_name == $comparing; - </info>");
										$use_exists = $children_cascade_relationship_name == $comparing;
										if ( $use_exists ) {
											$found_use = 1;
											break;	
										} //end if 		
									} // foreach
								} else { //
			                    	//
			                    	// I asume this is happening "children_cascade_relationship_name - params" => "relationship_type_id"
			                    	// that I get one string value 
			                    	$comparing = $name[0];
			                		// add it to the array of fields in this calls comparison for latter process
									$use_exists = $children_cascade_relationship_name == $comparing;
									if ( $use_exists ) {
										$found_use = 1;
										break;	
									} //end if 		
			                    } //end if array
	                        } // if
						} // foreach
					}
					#
					# Register if not found 
					#
					if ( $found_use == 0 && $children_cascade_relationship_name <> "") {
						$use_not_found[] = '    protected function '.$children_cascade_relationship_name.'()';
						
					} //end if 
				} // foreach


				#
				# Tell the user if not found was populated
				#
				// dump($entityClass->foreignkeys());
				if ( sizeof($use_not_found) > 1 ) {     //    <---- TOPIF 
					$output->writeln("<comment>- - - - please</comment> add <comment>the following relationships in </comment> <info>$file_name </info> ");							
					foreach ($use_not_found as $k => $value) {
						if ($value=="cascade" && sizeof($use_not_found)==$k+1) {
							//
							// skip title if empty
							// 							
						} else {
							if (in_array($value, [ "cascade"])) {
								$output->writeln("<error>- - - - - </error>     for <error>- - - - -  #$value </error>");
								$copy_to_clipboard[$k] = "	  #$value";	
							} else {
								$output->writeln("<error>- - - - - </error>     add <error>- - - - -  $value </error>");	
								$copy_to_clipboard[$k] = $value;						
							}
						}

					}
					//prepare to copy to clipboard
					$said=@file_put_contents('testerrors', implode("\n", $copy_to_clipboard)."\n");
					$said=@system("./testerrorstoclipboardcall");		
					$said=@exec("subl $file_name &");
					$output->writeln("\n\n    subl $file_name    ");
					$output->writeln("\n\n    subl $migration_file    \n\n");
					dd('FIX ');
					$test->assertEquals(1,  $found_use);
				} //end if 

				// dd("STOP");


				# 
				# Compare and fail if not found                                  ++++ CASCADES MUST BE PROTECTED
				#
				$output->writeln('<info>- RELATIONSHIP has to be of protected- </info>');
				//recycled $use_not_found array, $ound_use from the top
				$use_not_found = null; //reset
				$use_not_found[] = 'protected';
				foreach ($entityClass->children() as $children_cascade_relationship_name) {   //Looking for 
					$found_use = 0;
					if (!is_null($children_cascade_relationship_name)) { //avoid blank lines that trigger blank array values
						foreach ($cascade_array as $k => $name) { //Looking inside here
							if ($k == "type") { 
								if ( is_array($name) ) {
									foreach ($name as $comparing_name => $comparing_type) {
										$use_exists = $children_cascade_relationship_name == $comparing_name;
										$is_protected = $comparing_type == 'protected function';
										// $output->writeln("<comment>- - $children_cascade_relationship_name == $comparing- - - </comment> ".($use_exists ? "<info> exists </info>" : "<error> not exists </error>" ) . " $comparing_type == 'protected' ".($is_protected ? "<info> protected </info>" : "<error> not proteted </error>" ) );
										if ( $use_exists && $is_protected ) {
											$found_use = 1;
											break;	
										} //end if 		
									} // foreach
								} else { //
			                    	//
			                    	// I asume this is happening "children_cascade_relationship_name - params" => "relationship_type_id"
			                    	// that I get one string value 
			                    	$comparing_name = $name[0];
			                		// add it to the array of fields in this calls comparison for latter process
									if (!in_array($comparing_name, $all_fields_in_class)) $all_fields_in_class[] = $comparing_name;
									$use_exists = $children_cascade_relationship_name == $comparing_name;
									$is_protected = $comparing_type == 'protected function';
									// $output->writeln("<question>- - $children_cascade_relationship_name == $comparing- - - </question> ".($use_exists ? "<info> exists </info>\n" : "<error> not exists </error>" ). ' '.($is_protected ? "<info> protected </info>\n" : "<error> not proteted </error>" ) );
									if ( $use_exists && $is_protected ) {
										$found_use = 1;
										break;	
									} //end if 		
			                    } //end if array
	                        } // if
						} // foreach
					}
					#
					# Register if not found 
					#
					if ( $found_use == 0 && $children_cascade_relationship_name <> "") {
						$use_not_found[] = 'FROM:    '. $cascade_array['type'][$children_cascade_relationship_name].' '.$children_cascade_relationship_name.'()';
						$use_not_found[] = '  TO:    protected function '.$children_cascade_relationship_name.'()';
						
					} //end if 
				} // foreach

				// dd($use_not_found);

				#
				# Tell the user if not found was populated
				#
				// dump($entityClass->foreignkeys());
				if ( sizeof($use_not_found) > 1 ) {     //    <---- TOPIF 
					$output->writeln("<comment>- - - - please</comment> change <comment>the following relationships in </comment> <info>$file_name </info> ");							
					foreach ($use_not_found as $k => $value) {
						if ($value=="protected" && sizeof($use_not_found)==$k+1) {
							//
							// skip title if empty
							// 							
						} else {
							if (in_array($value, [ "protected"])) {
								$output->writeln("<error>- - - - - </error>     for <error>- - - - -  #$value </error>");
								$copy_to_clipboard[$k] = "	  #$value";	
							} else {
								$output->writeln("<error>- - - - - </error>     add <error>- - - - -  $value </error>");	
								$copy_to_clipboard[$k] = $value;						
							}
						}

					}
					//prepare to copy to clipboard
					$said=@file_put_contents('testerrors', implode("\n", $copy_to_clipboard)."\n");
					$said=@system("./testerrorstoclipboardcall");		
					$said=@exec("subl $file_name &");
					$output->writeln("\n\n    subl $file_name    ");
					$output->writeln("\n\n    subl $migration_file    \n\n");
					dd('FIX ');
					$test->assertEquals(1,  $found_use);
				} //end if 

				// dd("STOP");


				# 
				# Compare and fail if not found                                  ++++ CHILDREN CASCADE MUST BE RELATION OBJECT
				#
				$output->writeln('<info>- RELATIONSHIP must return Relation- </info>');
				//recycled $use_not_found array, $ound_use from the top
				$use_not_found = null; //reset
				$use_not_found[] = 'relation';
				// dd($entityClass->children());
				foreach ($entityClass->children() as $children_cascade_relationship_name) {   //Looking for 
					$found_use = 0;

					// dump( $children_cascade_relationship_name );

					// 
					// Calling a relation that is no there wil throw an error
					//
					try {

							//
							// PHPUnit should catch this error
							//
							// dump( $entityClass->$children_cascade_relationship_name );
							// $test_class = ($entityClass->$children_cascade_relationship_name);

							if (!is_null($children_cascade_relationship_name)) { //avoid blank lines that trigger blank array values
								$test_class = ($entityClass->$children_cascade_relationship_name);
								// dump( $test_class );
								#
								# Register errors
								#
								if (is_null($test_class)) {
									$found = 1;
									$use_not_found[] = '    protected function '.$children_cascade_relationship_name.'()  <error> returns null.  </error> ';
									
								} elseif (!is_object($test_class)) {
									$found = 1;
									$use_not_found[] = '    protected function '.$children_cascade_relationship_name.'()  <error> is not an object. </error> ';
									
								} elseif (is_object($test_class)) {
									#
									#
									# Test that we get a Model or a Collection
									#
									#
									$test_is_a_relation = is_a($test_class, 'Illuminate\Database\Eloquent\Relations\Relation');
									$test_is_a_model = is_a($test_class, 'Illuminate\Database\Eloquent\Model');
									$test_is_a_collection = is_a($test_class, 'Illuminate\Database\Eloquent\Collection');
									$test_get_class = get_class($test_class);
									if ($test_is_a_collection || $test_is_a_model || $test_is_a_relation) {
										#
										# Pass Object
										#

									} else {
										$output->writeln("<comment>- - $children_cascade_relationship_name - </comment> ". $test_get_class . ' ' . ($test_is_a_relation ? "<info> is a Relation </info>\n" : "<error> is something else </error>" ));
										$found = 1;
										$use_not_found[] = '    protected function '.$children_cascade_relationship_name.'()  <error> is not an object. </error> ';
											
									}
												
									
								} //end ifss
							} //end if not null
						} catch (Exception $e) {
						    				
							$found = 1;
							$use_not_found[] = '    protected function '.$children_cascade_relationship_name.'()  <error>throws error when calling it. </error> ';
								
						} finally {
						    // echo "Second finally.\n";
						}					
				} // foreach

				// dd($use_not_found);

				#
				# Tell the user if not found was populated
				#
				// dump($entityClass->foreignkeys());
				if ( sizeof($use_not_found) > 1 ) {     //    <---- TOPIF 
					$output->writeln("<comment>- - - - please</comment> change <comment>the following relationships in </comment> <info>$file_name </info> ");							
					foreach ($use_not_found as $k => $value) {
						if ($value=="relation" && sizeof($use_not_found)==$k+1) {
							//
							// skip title if empty
							// 							
						} else {
							if (in_array($value, [ "relation"])) {
								$output->writeln("<error>- - - - - </error>     for <error>- - - - -  #$value </error>");
								$copy_to_clipboard[$k] = "	  #$value";	
							} else {
								$output->writeln("<error>- - - - - </error>     add <error>- - - - -  $value </error>");	
								$copy_to_clipboard[$k] = $value;						
							}
						}

					}
					//prepare to copy to clipboard
					$said=@file_put_contents('testerrors', implode("\n", $copy_to_clipboard)."\n");
					$said=@system("./testerrorstoclipboardcall");		
					$said=@exec("subl $file_name &");
					$output->writeln("\n\n    subl $file_name    ");
					$output->writeln("\n\n    subl $migration_file    \n\n");
					dd('FIX ');
					$test->assertEquals(1,  $found_use);
				} //end if 

				// dd("STOP");


			} //end if  file_exists($migration_file)


			$output->writeln('<info>- Required SoftDelete Methods Exists - </info>');

			
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
        	// $this->$child = $child; //HEREONE
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
				$p_model_name = explode("\\", $class_name);
				$p_model_name = array_pop($p_model_name);
                    foreach($entityClass->children as $child) //scroll by children
                    {
                    	// $this->$child = $child ; //HEREONE
                        $obj = $entityClass["$child"];
                    	// $output->writeln("<info>- Relation Attributes     Child $t_class -> ".$this->$child ." \t\tExists- </info>"); //HEREONE
                        // $output->writeln( "\nChild:$child \n" );
                    	$output->writeln("<info>- Relation Attributes    testing relation - </info>$p_model_name->".$child );
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
                                        // Instanciate an Object to able to scroll its properties
                                        //
                                        $obj_item_class = get_class($obj_item);
                                        $tempObj = $obj_item->find($obj_item->id);  ///It has to be an object from the object a real relationship
                                        // $tempObj = $obj_item::withTrashed()->find($obj_item->id);  ///It has to be an object from the object a real relationship
                                        if (is_null($tempObj)) {
                                        	//$output->writeln("<error>- - - - - - - - - - $attribute_name </error> can't test empty object $child");
                                        	//continue;
                                        }
                                        // $tempObj = $obj_item->first(); //This causes errors
                                         $tempObj_item_class = get_class($tempObj);
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
	                                        # This is the only way to check if a 'null' deleted_at exists. putting $tempObj here will "printf the object" into the function
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
												
												// 
												// Display error
												//
												$output->writeln("<error>- - - - - - - - - - $attribute_name </error> access missing");

												//
												// Display help about the error -- insight from logic
												//
												//
												$output->writeln("\n    <info>$file_name</info> has an entry in <info>$p_model_name ->children()</info>=['<info>$child</info>'] ");
												$output->writeln("    is returning an <error>$c_file_name </error> object for model <error>$c_model_name</error> ");
												$output->writeln("                   which is <error>missing</error>/or missing access to '<error>$attribute_name</error>'.");
												dump($tempObj);
												// dump($obj_item);
												$output->writeln("  Thsi could also mean that:");
												$output->writeln("<error>- - - - - - - - - - $attribute_name </error> access missing in <error>$p_model_name ->$child </error>response ");
												$output->writeln("\n    SOUTIONS ( OPEN $file_name ): ");
												$output->writeln("      1. Remove entry '$child' from $p_model_name"."->children() method.");
												$output->writeln("      2. Replace entry  '$child' from $p_model_name"."->children() with an alternative relationship if you need it.");
												$output->writeln("      3. Check relationship method Person->$child(). to Ensure it correctly links to the model needed.");
												$output->writeln("\n   if you don't want it in the list. Solutions are to remove '$child' from $p_model_name"."->children().");
												$output->writeln("\n   or to create an alternative table access for softdeleting to occur.");
										
												
											
											 	if ( !is_null($tempObj) )  {    ///don't do test for table is object if null
	                                        	
													$c_file_plural_snake = $tempObj->getTable();
													$c_field_list=@shell_exec('./describelist '.$c_file_plural_snake);
													$c_field_array = array();
													$c_longest_name = 0;
													foreach ( explode("\n", $c_field_list) as $value) {
														$trim_value = trim($value);
														if (in_array(substr($trim_value , 0,8 ), ["","~", "column_n", "Warning:"])) {
															//
															// ignore list this
															//
														} else {
															$c_longest_name = $c_longest_name < strlen( $trim_value ) ?  strlen( $trim_value ) : $c_longest_name ;
															$c_field_array[] = $trim_value;
														}
													}
													$found=0;
													$output->writeln("\n          +".str_repeat("-",$c_longest_name+2) . "+ table - ".$tempObj->getTable());
													foreach ($c_field_array as $value) {
														$output->writeln("          | ".sprintf("%-".$c_longest_name."s",   $value). ' | ');
														if ($value==$attribute_name) {
															$found=1;
														}
													}
													if ($found==0) {
														$output->writeln("         <error> + ".sprintf("%-".$c_longest_name."s",   $attribute_name). ' | missing </error>');
													}
													$output->writeln("          +".str_repeat("-",$c_longest_name+2) . '+ ');
													$output->writeln("\n      ./describe ".$tempObj->getTable() );
												} else {
													$output->writeln("<error>- - - - - - - - - - $attribute_name </error> can't test empty null object $child");
												} //end if null
												//
												// Copy Paste Gooddies to ease programming debugging
												//
												$output->writeln("\n    <info>#</info> look for <info>$p_model_name->$child </info> response and see if '$attribute_name' is in the list ");
												$output->writeln("\n      ./tinker " );
												$output->writeln("\n      use " . $class_name );
												$output->writeln("      \$p=" . $p_model_name  ."::first() " );
												$output->writeln("      \$p->children" );	
												$output->writeln("      \$p->$child"   );	
												$output->writeln("      \$p->$child->$attribute_name"   );	
												$said=@exec("subl $file_name &");
												$output->writeln("\n    subl $file_name \n" );
												if ( !is_null($tempObj) )  {

													$output->writeln("\n    <info>#</info> look for <info>$c_model_name->$attribute_name </info> response for not null or invalid");
													$output->writeln("\n      ./tinker " );
													$output->writeln("\n      use " . $c_class );
													$output->writeln("      \$t=" . $c_model_name  ."::first() " );
													$output->writeln("      \$t->" . $attribute_name );
													$said=@exec("subl $c_file_name &");
												 	$output->writeln("\n    subl $c_file_name \n" );
												} //end if null
												// dd('FIX '. $c_class . '. is missing attribute ' . $attribute_name .' called from ' . $this->$child ); //HEREONE
												dd(" FIX $class_name is missing attribute $child->$attribute_name ");
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