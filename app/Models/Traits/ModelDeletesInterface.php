<?php namespace PHIWeb\Models\Traits;



interface ModelDeletesInterface
{
    /**
     * Explanation - Display of children Models/relations under MedLog Model
     * @author Jesus Alcaraz <jesus@gammapartners.com>
     */
    public static function children();
    /**
     * Explanation - Display of children Models/relations under MedLog Model
     * @author Jesus Alcaraz <jesus@gammapartners.com>
     */
    public static function childrenTables();

    
    /**
     * SoftDelete override to this->delete without parenthesis to work 
     * used instead of the "public static function boot, static::deleted" override found online
     * 
     * @author Jesus Alcaraz <jesus@gammapartners.com>
     */
    public  function delete();
    public  function unDelete();
    /**
     * SoftDelete override to this->delete without parenthesis to work 
     * @author Jesus Alcaraz <jesus@gammapartners.com>
     */
    //protected function getDeleteAttribute();


    /**
     * Explanation - Display of all the explanations in the class
     */
    public static function explain();
    // Relationship Relations
    /**
     * Explanation - Display of children Models/relations under MedLog Model
     * @author Jesus Alcaraz <jesus@gammapartners.com>
     */
    public static function relationships();
    /**
     * Explanation - Display of fields used to link other foreign tables
     * @author Jesus Alcaraz <jesus@gammapartners.com>
     */
    public static function foreignkeys();

    /**
     * Explanation - Display of dropbox content names from params
     * @author Jesus Alcaraz <jesus@gammapartners.com>
     */
    public static function dropboxes();
    

}