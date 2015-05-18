<?php namespace PHIWeb\Tests\Traits;

#use PHIWeb\Tests\Traits\ModelExistsTester;


trait ModelExistsTester {
	
	/**
	 * Performs existance and Method checking testing to the passed model and string 
	 *
	 * @author Jesus Alcaraz <jesus@gammapartners.com>
	 * @revision 7
	 */
	public function modelExistsTester($test, $med_log, $class_name, $output ) {

			echo "\n".get_class($med_log);

			$output->writeln('<info>- Model Load - </info>');
			$test->assertEquals(get_class($med_log), $class_name);


		
	} //end modelExistsTester	
		

}