<?php namespace PHIWeb\Models\Traits;
use Illuminate\Database\Eloquent\Model;

use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\Output;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Bootstrap\ConfigureLogging;

trait ModelDeletes {

    use SoftDeletes;



    /**
     * Explanation - Display of children Models/relations to cascade for softdeletes
     * @author Jesus Alcaraz <jesus@gammapartners.com>
     */
    protected function getChildrenAttribute()
    {
        return $this->children();
    }
    
    /**
     * Explanation - Display of children Models/relations to cascade for softdeletes
     * @author Jesus Alcaraz <jesus@gammapartners.com>
     */
    protected function getChildrenTablesAttribute()
    {
        return $this->childrenTables();
    }

    /**
     * 
     * 
     * @author Jesus Alcaraz <jesus@gammapartners.com>
     */
    public  function explore()
    {

        $output = new ConsoleOutput();

        $output->writeln("\n\n".'<info>--( ' . get_class($this) . ' )--</info>');

        foreach($this->children as $child) //scroll by children
        {
            $obj = $this["$child"];
           
            if (sizeof($obj)<=0) {
                //
                // Delete lonely object
                //
                if ($obj!=null && sizeof($obj->count())>0) {
                    //
                    foreach ($obj as $obj_item) { 
                        echo "\n IF 1 \n";
                        echo "\nChild:$child \n";
                        echo "\nobj_item:$obj_item \n";
                        echo "\nobj_item is ". get_class($obj_item)."\n";
                    } //end foreach 
                    //
                }
                //
            } elseif ( sizeof($obj)==1) {
                //
                echo "\n IF 2 \n";
                $output->writeln("\n\n".'<info>--( '. (is_a($obj, "Illuminate\Database\Eloquent\Collection") ?  get_class($obj)  . ' is a collection ' : '' ).'--</info>');
                $output->writeln("\n\n".'<info>--( '. (is_a($obj, "Illuminate\Database\Eloquent\Model") ?  get_class($obj)  . ' is a Model ' : '' ).'--</info>');
                echo "\nChild:$child \n";
                echo "\nobj_:$obj \n";
                echo "\nobj is ". get_class($obj)."\n";

                //
            } else {    
                //
                // sizeof > 1
                //
                foreach ($obj as $obj_item) { 
                    //
                echo "\n IF 3 \n";
                echo "\nChild:$child \n";
                echo "\nobj_item:$obj_item \n";
                echo "\nobj_item is ". get_class($obj_item)."\n";
                    //
                } //end foreach 
                //
            } //end if
            // 
            if (get_class($obj)=="Illuminate\Database\Eloquent\Collection") {
                foreach ($obj as $itemkey => $itemValue) {
                    //if (isset($itemValue->$child) && $itemValue->$child == $value) {
                        echo "\n IF 5 \n";
                        echo "\nChild:$child \n";
                        $output->writeln("\n\n".'<info>--( '. (is_a($itemValue, "Illuminate\Database\Eloquent\Model") ?  get_class($itemValue)  . ' is a Model ' : '' ).'--</info>');
                        echo "\n\n itemkey: $itemkey \n";
                        echo "get_class is itemkey". (is_object($itemkey) ? get_class($itemkey) : $itemkey) ."\n";
                        echo "\n\n itemValue: ". (is_array($itemValue)==true ? "[".implode(', ', $itemValue) ."]" : $itemValue) ."\n"; 
                        echo "get_class is itemValue". (is_object($itemValue)==true ? get_class($itemValue) : (is_array($itemValue)==true ? "[".implode(', ', $itemValue) ."]" : $itemValue)) ."\n";
                        // echo "key is ". (is_object($key) ? get_class($key) : $key) ."\n";
                        // echo "value is ". (is_object($value) ? get_class($value) : $value) ."\n";
                    //}
                }
            } //end if
        } //end foreach
                    //$this
                    echo "\n IF 4 \n";
                    echo "\n\n This:this \n";
                    echo "this is ". get_class($this)."\n";
                        
                    foreach($this->children as $child) //scroll by children
                    {
                        $obj = $this["$child"];
                        echo "\nChild:$child \n";
                        $output->writeln("\n\n".'<info>--( '. (is_a($obj, "Illuminate\Database\Eloquent\Collection") ?  get_class($obj)  . ' is a collection ' : '' ).'--</info>');                           
                        $output->writeln("\n\n".'<info>--( '. (is_a($obj, "Illuminate\Database\Eloquent\Model") ?  get_class($obj)  . ' is a Model ' : '' ).'--</info>');
                        echo "obj is ". get_class($obj)."\n";
                        echo "obj  ". (method_exists($obj, 'delete')==true ? 'has delete' : '') ."\n";
                        echo "obj  ". (method_exists($obj, 'save')==true ? 'has save' : '') ."\n";
                        foreach ($obj as $obj_item) { 
                            $output->writeln("\n\n".'<info>--( '. (is_a($obj_item, "Illuminate\Database\Eloquent\Model") ?  get_class($obj_item)  . ' is a Model ' : '' ).'--</info>');
                            echo "obj_item: ". (is_array($obj_item)==true ? "[".implode(', ', $obj_item) ."]" : $obj_item) ."\n"; 
                            echo "obj_item is ". (is_object($obj_item)==true ? get_class($obj_item) : (is_array($obj_item)==true ? "[".implode(', ', $obj_item) ."]" : $obj_item)) ."\n";
                            echo "obj_item ". (method_exists($obj_item, 'delete')==true ? ' has delete' : '') ."\n";
                            echo "obj_item ". (method_exists($obj_item, 'save')==true ? ' has save' : '') ."\n";
                            echo "obj_item ". (method_exists($obj_item, 'id')==true ? ' has id' : '') ."\n";

                                if (is_object($obj_item)) {
                                    $childClass = get_class($obj_item);

                                    if (is_a($obj_item, "Illuminate\Database\Eloquent\Model")) {
                                        $tempObj = $obj_item->find($obj_item->id);
                                        echo "tempObj is a ". (is_object($obj_item) ? get_class($obj_item) : $obj_item) ."\n";
                                        echo "tempObj ". (method_exists($tempObj, 'undelete')==true ? ' has undelete' : '') ."\n";
                                        echo "tempObj ". (method_exists($tempObj, 'save')==true ? ' has save' : '') ."\n";
                                        echo "tempObj ". ( isset($tempObj['id']) !=null ? ' has id' : ' ERROR Missing ID') ."\n";
                                        echo "tempObj ". ( isset($tempObj['deleted_at']) !=null ? ' has deleted_at' : ' ERROR Missing deleted_at') ."\n";
                                        echo "tempObj ". ( isset($tempObj['updated_at']) !=null ? ' has updated_at' : ' ERROR Missing updated_at') ."\n";

                                        $tempObj->id = $obj_item->id;
                                        $s = $tempObj->save();
                                        if ($s==true)  echo "tempObj saves"; else echo "tempObj save error";
                                    }                                
                                }


                        } //end foreach
                    } //end foreach 

    } //end function  explore()


    /**
     * 
     * 
     * @author Jesus Alcaraz <jesus@gammapartners.com>
     */
    public  function exploreWithTrashed()
    {

        $output = new ConsoleOutput();

        $output->writeln("\n\n".'<info>--( ' . get_class($this) . ' )--</info>');

        foreach($this->children as $child) //scroll by children
        {
            $obj = $this["$child"];
           
            if (sizeof($obj)<=0) {
                //
                // undelete lonely object
                //
                if ($obj!=null && sizeof($obj->count())>0) {
                    //
                    foreach ($obj as $obj_item) { 
                        echo "\n IF 1 \n";
                        echo "\nChild:$child \n";
                        echo "\nobj_item:$obj_item \n";
                        echo "\nobj_item is ". get_class($obj_item)."\n";
                    } //end foreach 
                    //
                }
                //
            } elseif ( sizeof($obj)==1) {
                //
                echo "\n IF 2 \n";
                $output->writeln("\n\n".'<info>--( '. (is_a($obj, "Illuminate\Database\Eloquent\Collection") ?  get_class($obj)  . ' is a collection ' : '' ).'--</info>');
                $output->writeln("\n\n".'<info>--( '. (is_a($obj, "Illuminate\Database\Eloquent\Model") ?  get_class($obj)  . ' is a Model ' : '' ).'--</info>');
                echo "\nChild:$child \n";
                echo "\nobj_:$obj \n";
                echo "\nobj is ". get_class($obj)."\n";

                //
            } else {    
                //
                // sizeof > 1
                //
                foreach ($obj as $obj_item) { 
                    //
                echo "\n IF 3 \n";
                echo "\nChild:$child \n";
                echo "\nobj_item:$obj_item \n";
                echo "\nobj_item is ". get_class($obj_item)."\n";
                    //
                } //end foreach 
                //
            } //end if
            // 
            if (get_class($obj)=="Illuminate\Database\Eloquent\Collection") {
                foreach ($obj as $itemkey => $itemValue) {
                    //if (isset($itemValue->$child) && $itemValue->$child == $value) {
                        echo "\n IF 5 \n";
                        echo "\nChild:$child \n";
                        $output->writeln("\n\n".'<info>--( '. (is_a($itemValue, "Illuminate\Database\Eloquent\Model") ?  get_class($itemValue)  . ' is a Model ' : '' ).'--</info>');
                        echo "\n\n itemkey: $itemkey \n";
                        echo "get_class is itemkey". (is_object($itemkey) ? get_class($itemkey) : $itemkey) ."\n";
                        echo "\n\n itemValue: ". (is_array($itemValue)==true ? "[".implode(', ', $itemValue) ."]" : $itemValue) ."\n"; 
                        echo "get_class is itemValue". (is_object($itemValue)==true ? get_class($itemValue) : (is_array($itemValue)==true ? "[".implode(', ', $itemValue) ."]" : $itemValue)) ."\n";
                        // echo "key is ". (is_object($key) ? get_class($key) : $key) ."\n";
                        // echo "value is ". (is_object($value) ? get_class($value) : $value) ."\n";
                    //}
                }
            } //end if
        } //end foreach
                    //$this
                    echo "\n IF 4 \n";
                    echo "\n\n This:this \n";
                    echo "this is ". get_class($this)."\n";
                        
                    foreach($this->children as $child) //scroll by children
                    {
                        $obj = $this["$child"];
                       echo "\nChild:$child \n";
                        $is_a_collection = (is_a($obj, "Illuminate\Database\Eloquent\Collection") ?  'collection' : '');
                        $is_a_model = (is_a($obj, "Illuminate\Database\Eloquent\Model") ?  'model' : '');

                        $what_is_it =  ($is_a_collection ? $is_a_collection  : ( $is_a_model ? $is_a_model : get_class($obj) ) );

                        $output->writeln("\n\n <info>--( $child ". get_class($obj)  . ' is a '.$what_is_it .'--</info>');                           
                        echo "obj is ". get_class($obj)."\n";
                        // echo "obj  ". (method_exists($obj, 'undelete')==true ? 'has undelete' : '') ."\n";
                        // echo "obj  ". (method_exists($obj, 'save')==true ? 'has save' : '') ."\n";
                        
                        if ($what_is_it == 'collection' ) {
                              dump(sizeof($obj));
                            foreach ($obj as $obj_item) { 
                                $output->writeln("\n\n".'<info>--( '. (is_a($obj_item, "Illuminate\Database\Eloquent\Model") ?  get_class($obj_item)  . ' is a Model ' : '' ).'--</info>');

                                echo "obj_item: ". (is_array($obj_item)==true ? "[".implode(', ', $obj_item) ."]" : $obj_item) ."\n"; 
                                echo "obj_item is ". (is_object($obj_item)==true ? get_class($obj_item) : (is_array($obj_item)==true ? "[".implode(', ', $obj_item) ."]" : $obj_item)) ."\n";
                                echo "obj_item ". (method_exists($obj_item, 'undelete')==true ? ' has undelete' : '') ."\n";
                                echo "obj_item ". (method_exists($obj_item, 'save')==true ? ' has save'  : ' ERROR Missing save') ."\n";
                                //
                                // This is the only way to check if a 'null' deleted_at exists
                                //                                       
                                $model_count = substr_count($obj_item, 'deleted_at') > 0;
                                echo "obj_item ". ( $model_count ? ' has deleted_at' : ' ERROR Missing deleted_at') ."\n";
                                echo "obj_item ". (method_exists($obj_item, 'id')==true ? ' has id' : ' ERROR Missing save') ."\n";


                                if (is_object($obj_item)) {
                                    $childClass = get_class($obj_item);

                                    if (is_a($obj_item, "Illuminate\Database\Eloquent\Model")) {
                                        //
                                        //
                                        //
                                        $tempObj = $obj_item->find($obj_item->id);
                                        echo "tempObj is a ". (is_object($obj_item) ? get_class($obj_item) : $obj_item) ."\n";
                                        echo "tempObj ". (method_exists($tempObj, 'undelete')==true ? ' has undelete' : '') ."\n";
                                        echo "tempObj ". (method_exists($tempObj, 'save')==true ? ' has save' : '') ."\n";


                                        //
                                        // This is the only way to check if a 'null' deleted_at exists
                                        //
                                        $model_count = substr_count($tempObj, 'deleted_at') > 0;

                                        echo "tempObj ". ( isset($tempObj['id']) !=null ? ' has id' : ' ERROR Missing ID') ."\n";
                                        //echo "tempObj ". ( isset($tempObj['deleted_at']) !=null ? ' has deleted_at' : ' ERROR Missing deleted_at') ."\n";
                                        echo "tempObj ". ( $model_count ? ' has deleted_at' : ' ERROR Missing deleted_at') ."\n";
                                        echo "tempObj ". ( isset($tempObj['updated_at']) !=null ? ' has updated_at' : ' ERROR Missing updated_at') ."\n";
                                       
                                        $tempObj->id = $obj_item->id;
                                        $s = $tempObj->save();
                                        if ($s==true)  echo "tempObj saves"; else echo "tempObj save error";
                                    }                                
                                }


                            } //end foreach
                        } //end if 
                    } //end foreach 

    } //end function exploreWithTrashed

    private $softdeleting = false;
    private $softrestoring = false;
    /**
     * SoftDelete override to this->delete without parenthesis to work 
     * used instead of the "public static function boot, static::deleted" override found online
     * 
     * @author Jesus Alcaraz <jesus@gammapartners.com>
     */
    private  function traverse()
    {
     
        $output = new ConsoleOutput();

        // $output->writeln("\n\n".'<info>--( ' . get_class($this) . ' )--</info>');

        if ($this->softdeleting==true) { 
            $r = parent::delete();
        $date_deleted = $this->deleted_at;

        } elseif ($this->softrestoring==true) { 
            $r = parent::restore();
            $tempObj = $this->withTrashed()->find($this->id);       //get a ref to save
            $tempObj->deleted_at=null;                              //set the delete to the same 
            $date_deleted = null;
            $a = $tempObj->save();                                  //save the record to preserve
            $r = $a && $r;
        }

        // $output->writeln("\n\n".'<info>--( date_deleted )--</info>' . ( !is_null($date_deleted) ? $date_deleted : "null") ) ;
        foreach($this->children as $child) //scroll by children
        {
            $obj = $this["$child"];

            if (is_object($obj)) {

                //
                // Just do the one object
                //
                $childClass = get_class($obj);
                //
                // $output->writeln("\n\n"."<info>--( Obj Model Traversing $child )--</info>");

                $a = $this->traverseWorker($childClass, $obj, $date_deleted);
                $r = $a && $r;
                //
                //

                if ( is_a($obj, "Illuminate\Database\Eloquent\Collection") ) {
                    //
                    // Then Loop through collection (of models)
                    //
                    // $output->writeln("\n\n"."<info>--( Collection Traversing $child )--</info>");

                    foreach ($obj as $obj_item) {
                        // 
                        if (is_object($obj_item)) {
                            //
                            // Just do the object
                            // 
                            // $output->writeln("\n\n"."<info>--( Model Item Traversing $child )--</info>");

                            $childClassItem = get_class($obj_item);
                            $a = $this->traverseWorker($childClassItem, $obj_item, $date_deleted);
                            $r = $a && $r;
                        }
                        //
                    } //end foreach
                    //
                } //end if Collection
                //
            } //end if is_object
            // 
        } //end foreach foreach
        //
        return $r;
    } //end delete

    /**
     * SoftDelete worker to trigger deletes
     * 
     * 
     * @author Jesus Alcaraz <jesus@gammapartners.com>
     */
    private  function traverseWorker($childClassItem , $obj, $date_deleted) {
        $r = true;
        if ( is_a($obj, "Illuminate\Database\Eloquent\Model") ) {
            //
            // Delete or restore
            //

            //
            //  trigger delete event for the child's children
            //
            if ($this->softdeleting==true) {
                $t = $obj->delete;
                //
                // Save
                //
                $tempObj = $obj->withTrashed()->find($obj->id);                 //get a ref to save
                $tempObj->deleted_at=$date_deleted;             //set the delete to the same 
                $a = $tempObj->save();                    //save the record to preserve
                $r = $a && $r;
            }  
            if ($this->softrestoring==true) {
               $r = $r && $obj->restore;
                //
                // Save
                // $d = $d->withTrashed()->find($savedId);
                $tempObj = $obj->withTrashed()->find($obj->id);                //get a ref to save
                $tempObj->deleted_at=null;             //set the delete to the same 
                $a = $tempObj->save();                    //save the record to preserve
                $r = $a && $r;
            }  
            
            // $obj->deleted_at=$date_deleted;             //set the delete to the same 
            // $r = $r && $obj->save();                    //save the record to preserve
            //
        }
        return $r;

    }
    /**
     * SoftDelete override to this->delete without parenthesis to work 
     * used instead of the "public static function boot, static::deleted" override found online
     * 
     * @author Jesus Alcaraz <jesus@gammapartners.com>
     */
    public  function delete() {
        $this->softdeleting = true;
        $this->softrestoring = false;
        return $this->traverse();
    }
    /**
     * SoftDelete override to this->delete without parenthesis to work 
     * @author Jesus Alcaraz <jesus@gammapartners.com>
     */
    protected function getDeleteAttribute()
    {
        return $this->delete();
    }  


    /**
     * SoftRestore override
     * @author Jesus Alcaraz <jesus@gammapartners.com>
     */
    public  function restore() 
    {
        $this->softdeleting = false;
        $this->softrestoring = true;
        return $this->traverse();        
    }
    protected function getRestoreAttribute()
    {
        return $this->restore();
    }


    /**
     * SoftRestore alias 
     * @author Jesus Alcaraz <jesus@gammapartners.com>
     */
    public  function undelete() 
    {
        return $this->restore();      
    }
    protected function getUndeleteAttribute()
    {
        return $this->restore();    
    }
 





    protected function getExploreAttribute()
    {
        return $this->explore();
    }
    protected function getExploreWithTrashedAttribute()
    {
        return $this->exploreWithTrashed();
    }

 





    /**
     * Explanation - Display of all the explanations in the class
     */
    public static function explain()
    {
        $r = array();
        $r['relationships']=MedLog::relationships();
        $r['children']=MedLog::children();
        $r['foreignkeys']=MedLog::foreignkeys();
        $r['dropboxes']=MedLog::dropboxes();
        return $r;
    }




    /**
     * Explanation - Display of dropbox content names from params
     * @author Jesus Alcaraz <jesus@gammapartners.com>
     */
    protected function getDropboxesAttribute()
    {
        return $this->dropboxes();
    }
    // Relationship Relations
    /**
     * Explanation - Display of children Models/relations to cascade for softdeletes
     * @author Jesus Alcaraz <jesus@gammapartners.com>
     */
    protected function getRelationshipsAttribute()
    {
        return $this->relationships();
    }

    /**
     * Explanation - Display of fields used to link other foreign tables
     * @author Jesus Alcaraz <jesus@gammapartners.com>
     */
    protected static function getForeignkeysAttribute()
    {
        return $this->foreignkeys();    
    }

    /**
     * Explanation - Display of all the explanations in the class
     * @author Jesus Alcaraz <jesus@gammapartners.com>
     */
    public function getExplainAttribute()
    {
        $this->explain();   
    }

}